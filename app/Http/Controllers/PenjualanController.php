<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(SearchRequest $request)
    {
        $user = Auth::user();
        $keyword = $request->input('search');

        $sales = Penjualan::with('user')
            ->when(strtolower(optional($user->role)->name) === 'kasir', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->orderByRaw("CASE WHEN status = 'OPEN' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('penjualan.partials.table', compact('sales'))->render();
        }

        return view('penjualan.index', compact('sales'));
    }

    public function create(Request $request) 
    {
        $user = Auth::user();

        // === TAMBAHKAN BARIS INI (Pembersih transaksi open kosong) ===
        Penjualan::where('user_id', $user->id)
            ->where('status', 'OPEN')
            ->whereNull('customer_name')
            ->doesntHave('itemPenjualan')
            ->delete();
        // ============================================================

        // Cari transaksi OPEN yang murni keranjang aktif milik user (belum di-checkout/belum ada nama pelanggannya)
        $sale = Penjualan::where('user_id', $user->id)
            ->where('status', 'OPEN')
            ->whereNull('customer_name')
            ->latest()
            ->first();


        // Jika tidak ada keranjang aktif yang kosong, buat transaksi baru yang bersih
        if (!$sale) {
            $sale = Penjualan::create([
                'user_id' => $user->id,
                'status' => 'OPEN',
                'total_pembayaran' => 0,
                'metode_pembayaran' => 'BAYAR_NANTI'
            ]);
        }

        $keyword = $request->input('search');

        $products = Produk::when($keyword, function ($query) use ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        })
        ->orderByRaw('CASE WHEN stok <= 0 THEN 1 ELSE 0 END')
        ->orderBy('stok', 'desc')
        ->orderBy('nama')
        ->paginate(5)
        ->appends($request->all());

        $totalProdukCount = Produk::count();
        $mode = 'create';

        $sale->load('itemPenjualan.produk');

        if ($request->ajax()) {
            return response()->json([
                'html' => view('penjualan.partials.product-grid', compact('products', 'sale'))->render()
            ]);
        }

        return view('penjualan.pos', compact('sale', 'products', 'mode', 'totalProdukCount'));
    }

    public function edit($id, Request $request)
    {
        $user = Auth::user();
        $isAdmin = ($user->role_id == 1) || (isset($user->role) && strtolower($user->role->name) === 'admin');

        $sale = Penjualan::where('id', $id)
            ->when(!$isAdmin, function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        // Hanya blokir jika transaksi tidak ada atau statusnya sudah COMPLETED
        if (!$sale || $sale->status == 'COMPLETED') {
            return redirect()->route('penjualan.index')->with('error', 'Transaksi tidak ditemukan atau sudah selesai.');
        }

        // === TAMBAHKAN BARIS INI (Hapus transaksi open jika itemnya kosong) ===
        if ($sale->status == 'OPEN' && $sale->itemPenjualan()->count() == 0 && is_null($sale->customer_name)) {
            $sale->delete();
            return redirect()->route('penjualan.create');
        }

        $this->authorize('update', $sale);

        $sale->load('itemPenjualan.produk');
        
        $keyword = $request->input('search');
        
        $products = Produk::when($keyword, function ($query) use ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        })
        ->orderByRaw('CASE WHEN stok <= 0 THEN 1 ELSE 0 END')
        ->orderBy('stok', 'desc')
        ->orderBy('nama')
        ->paginate(5)
        ->appends($request->all());

        $totalProdukCount = Produk::count();
        $mode = 'edit';

        if ($request->ajax()) {
            return response()->json([
                'html' => view('penjualan.partials.product-grid', compact('products', 'sale'))->render()
            ]);
        }

        return view('penjualan.pos', compact('sale', 'products', 'mode', 'totalProdukCount'));
    }

    public function show(Penjualan $penjualan)
    {
        // Pastikan method view di policy mengizinkan, atau langsung load data tanpa batasan ketat 404
        $sale = $penjualan->load('itemPenjualan.produk', 'user');
        return view('penjualan.show', compact('sale'));
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'payment_method' => 'required|in:CASH,QRIS,BAYAR_NANTI',
            'uang_dibayar' => 'nullable',
            'kembalian' => 'nullable',
            'customer_name' => 'required_if:payment_method,BAYAR_NANTI|nullable|string|max:255',
            'customer_phone' => 'required_if:payment_method,BAYAR_NANTI|nullable|string|max:255',
            'due_date' => 'required_if:payment_method,BAYAR_NANTI|nullable|date',
        ]);

        if ($penjualan->status == 'COMPLETED') {
            return back()->with('error', 'Transaksi sudah diproses');
        }

        $this->authorize('update', $penjualan);

        if ($penjualan->itemPenjualan()->count() == 0) {
            return back()->with('error', 'Keranjang masih kosong');
        }

        $total = $penjualan->itemPenjualan()->sum('subtotal');
        
        $uangDibayar = null;
        $kembalian = null;

        if ($request->payment_method === 'CASH') {
            $uangDibayar = floatval($request->input('uang_dibayar', 0));
            $kembalian = floatval($request->input('kembalian', 0));

            if ($uangDibayar < $total) {
                return back()->withErrors(['uang_dibayar' => 'Uang tunai dari pelanggan kurang dari total pembayaran!'])->withInput();
            }
        }

        $newStatus = ($request->payment_method === 'BAYAR_NANTI') ? 'OPEN' : 'COMPLETED';

        DB::transaction(function () use ($penjualan, $request, $total, $newStatus, $uangDibayar, $kembalian) {
            $penjualan->update([
                'metode_pembayaran' => $request->payment_method,
                'total_pembayaran' => $total,
                'uang_dibayar' => $uangDibayar,
                'kembalian' => $kembalian,
                'status' => $newStatus,
                'customer_name' => $request->payment_method === 'BAYAR_NANTI' ? $request->customer_name : null,
                'customer_phone' => $request->payment_method === 'BAYAR_NANTI' ? $request->customer_phone : null,
                'due_date' => $request->payment_method === 'BAYAR_NANTI' ? $request->due_date : null,
            ]);
        });

        $message = ($newStatus === 'OPEN') 
            ? 'Transaksi Bayar Nanti berhasil disimpan' 
            : 'Transaksi berhasil diselesaikan';

        return redirect()
            ->route('penjualan.index')
            ->with('success', $message);
    }

    public function destroy(Penjualan $penjualan)
    {
        $user = Auth::user();
        $isAdmin = strtolower(optional($user->role)->name) === 'admin';
        $isOwner = $user->id === $penjualan->user_id;

        if (!($isAdmin || $isOwner) || $penjualan->status !== 'OPEN') {
            return redirect()->route('penjualan.index')->with('error', 'Aksi tidak diizinkan.');
        }

        DB::transaction(function () use ($penjualan) {
            foreach ($penjualan->itemPenjualan as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->kuantitas);
                }
            }

            $penjualan->itemPenjualan()->delete();
            $penjualan->delete();
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dibatalkan');
    }

    public function destroyAuto(Penjualan $penjualan)
    {
        if ($penjualan->status === 'OPEN') {
            DB::transaction(function () use ($penjualan) {
                foreach ($penjualan->itemPenjualan as $item) {
                    if ($item->produk) {
                        $item->produk->increment('stok', $item->kuantitas);
                    }
                }
                $penjualan->itemPenjualan()->delete();
                $penjualan->delete();
            });
        }
        return response()->noContent();
    }

    

    public function batalEdit(Penjualan $penjualan)
    {
        $user = Auth::user();
        $isAdmin = strtolower(optional($user->role)->name) === 'admin';
        $isOwner = $user->id === $penjualan->user_id;

        if (!($isAdmin || $isOwner)) {
            return redirect()->route('penjualan.index')->with('error', 'Aksi tidak diizinkan.');
        }

        DB::transaction(function () use ($penjualan) {
            if ($penjualan->status === 'OPEN' && $penjualan->itemPenjualan()->count() === 0) {
                $penjualan->delete();
            } else {
                foreach ($penjualan->itemPenjualan as $item) {
                    if ($item->produk) {
                        $item->produk->increment('stok', $item->kuantitas);
                    }
                }
                $penjualan->itemPenjualan()->delete();
                $penjualan->delete();
            }
        });

        return redirect()->route('penjualan.index');
    }
}