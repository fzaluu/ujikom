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
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('penjualan.index', compact('sales'));
    }

    public function create(SearchRequest $request)
    {
        // Cari transaksi OPEN terakhir milik user yang login, hindari duplikasi data
        $sale = Penjualan::where('user_id', Auth::id())
            ->where('status', 'OPEN')
            ->latest()
            ->first();

        if (!$sale) {
            $sale = Penjualan::create([
                'user_id' => Auth::id(),
                'status' => 'OPEN',
                'total_pembayaran' => 0,
                'metode_pembayaran' => 'CASH'
            ]);
        }

        $sale->load('itemPenjualan.produk');

        $keyword = $request->input('search');

        $products = Produk::when($keyword, function ($query) use ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        })
        ->orderBy('nama')
        ->get();

        $mode = 'create';

        return view('penjualan.pos', compact('sale', 'products', 'mode'));
    }

    public function edit(Penjualan $penjualan)
    {
        $sale = $penjualan;

        abort_if($sale->status == 'COMPLETED', 403);
        $this->authorize('update', $sale);

        $sale->load('itemPenjualan.produk');
        $products = Produk::orderBy('nama')->get();
        $mode = 'edit';

        return view('penjualan.pos', compact('sale', 'products', 'mode'));
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'payment_method' => 'required|in:CASH,QRIS'
        ]);

        if ($penjualan->status == 'COMPLETED') {
            return back()->with('error', 'Transaksi sudah diproses');
        }

        $this->authorize('update', $penjualan);

        if ($penjualan->itemPenjualan()->count() == 0) {
            return back()->with('error', 'Keranjang masih kosong');
        }

        DB::transaction(function () use ($penjualan, $request) {
            $total = $penjualan->itemPenjualan()->sum('subtotal');

            $penjualan->update([
                'metode_pembayaran' => $request->payment_method,
                'total_pembayaran' => $total,
                'status' => 'COMPLETED'
            ]);
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil diselesaikan');
    }

   public function destroy(Penjualan $penjualan)
    {
        $user = Auth::user();

        // Cek apakah admin (mendukung huruf besar/kecil)
        $isAdmin = strtolower(optional($user->role)->name) === 'admin';
        
        // Cek apakah pemilik transaksi
        $isOwner = $user->id === $penjualan->user_id;

        // UBAH DI SINI: Jika ingin kasir juga bebas menghapus transaksi OPEN milik siapa saja, hapus syarat $isOwner
        // Atau jika hanya pemilik & admin, pastikan Anda login pakai akun admin (ID 1) atau akun owner (ID 4)
        if (!($isAdmin || $isOwner) || $penjualan->status !== 'OPEN') {
            return redirect()->route('penjualan.index')->with('error', 'Aksi tidak diizinkan atau transaksi bukan milik anda.');
        }

        DB::transaction(function () use ($penjualan) {
            foreach ($penjualan->itemPenjualan as $item) {
                $item->produk->increment('stok', $item->kuantitas);
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
}