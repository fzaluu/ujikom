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
            // Mengatur agar status 'OPEN' berada di paling atas, lalu diurutkan dari yang terbaru
            ->orderByRaw("CASE WHEN status = 'OPEN' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('penjualan.partials.table', compact('sales'))->render();
        }

        return view('penjualan.index', compact('sales'));
    }

    public function create(SearchRequest $request)
    {
        // Jangan langsung buat record di database saat masuk halaman create.
        // Buat objek kosong penampung sementara untuk view POS.
        $sale = new Penjualan([
            'user_id' => Auth::id(),
            'status' => 'OPEN',
            'total_pembayaran' => 0,
            'metode_pembayaran' => 'CASH'
        ]);
        // Belum disimpan ke database ($sale->id masih null sampai ada item yang ditambahkan)

        $keyword = $request->input('search');

        $products = Produk::when($keyword, function ($query) use ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        })
        ->orderByRaw('CASE WHEN stok <= 0 THEN 1 ELSE 0 END')
        ->orderBy('stok', 'desc')
        ->orderBy('nama')
        ->get();

        $totalProdukCount = Produk::count();
        $mode = 'create';

        return view('penjualan.pos', compact('sale', 'products', 'mode', 'totalProdukCount'));
    }

    public function edit(Penjualan $penjualan, Request $request)
    {
        $sale = $penjualan;

        abort_if($sale->status == 'COMPLETED', 403);
        $this->authorize('update', $sale);

        $sale->load('itemPenjualan.produk');
        
        $keyword = $request->input('search');
        $products = Produk::when($keyword, function ($query) use ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        })
        ->orderByRaw('CASE WHEN stok <= 0 THEN 1 ELSE 0 END')
        ->orderBy('stok', 'desc')
        ->orderBy('nama')
        ->get();

        $totalProdukCount = Produk::count();
        $mode = 'edit';

        if ($request->ajax()) {
            return response()->json([
                'html' => view('penjualan.partials.product-grid', compact('products', 'sale'))->render()
            ]);
        }

        return view('penjualan.pos', compact('sale', 'products', 'mode', 'totalProdukCount'));
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'payment_method' => 'required|in:CASH,QRIS,BAYAR_NANTI'
        ]);

        if ($penjualan->status == 'COMPLETED') {
            return back()->with('error', 'Transaksi sudah diproses');
        }

        $this->authorize('update', $penjualan);

        if ($penjualan->itemPenjualan()->count() == 0) {
            return back()->with('error', 'Keranjang masih kosong');
        }

        // Tentukan status berdasarkan metode pembayaran
        // Jika BAYAR_NANTI -> Tetap OPEN agar bisa diedit/dilanjutkan pembayaran nanti
        // Jika CASH / QRIS -> COMPLETED
        $newStatus = ($request->payment_method === 'BAYAR_NANTI') ? 'OPEN' : 'COMPLETED';

        DB::transaction(function () use ($penjualan, $request, $newStatus) {
            $total = $penjualan->itemPenjualan()->sum('subtotal');

            $penjualan->update([
                'metode_pembayaran' => $request->payment_method,
                'total_pembayaran' => $total,
                'status' => $newStatus
            ]);
        });

        $message = ($newStatus === 'OPEN') 
            ? 'Transaksi berhasil disimpan (Bayar Nanti)' 
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

    public function show(Penjualan $penjualan)
    {
        $sale = $penjualan->load('itemPenjualan.produk', 'user');
        return view('penjualan.show', compact('sale'));
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
            // Cek apakah transaksi ini statusnya OPEN dan keranjangnya masih kosong (belum ada item yang dimasukkan)
            if ($penjualan->status === 'OPEN' && $penjualan->itemPenjualan()->count() === 0) {
                // Jika kosong, hapus permanen dari database agar tidak menjadi sampah riwayat
                $penjualan->delete();
            } else {
                // Jika sudah ada item di dalamnya, kembalikan stok produk yang telanjur terpotong (jika ada) lalu hapus
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