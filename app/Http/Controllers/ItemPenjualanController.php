<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ItemPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemPenjualanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $errorMessage = null;
        $activeSaleId = null;

        DB::transaction(function () use ($request, &$errorMessage, &$activeSaleId) {
            $user = Auth::user();
            
            // Pengecekan Admin yang KUAT dan AMAN dari nilai null (menggunakan role_id atau string role)
            $isAdmin = ($user->role_id == 1) || 
                       (isset($user->role) && strtolower($user->role->name) === 'admin');

            $saleId = $request->input('penjualan_id');
            $sale = null;

            // 1. Cari berdasarkan penjualan_id dari form jika valid
            if ($saleId) {
                $sale = Penjualan::where('id', $saleId)
                    ->when(!$isAdmin, function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->where('status', 'OPEN')
                    ->first();
            }

            // 2. Jika tidak ada dari form, cari transaksi OPEN milik user yang PALING AKTIF
            if (!$sale) {
                $sale = Penjualan::when(!$isAdmin, function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->where('status', 'OPEN')
                    ->latest()
                    ->first();
            }

            // 3. Jika benar-benar belum ada sama sekali, baru buat baru
            if (!$sale) {
                $sale = Penjualan::create([
                    'user_id' => $user->id,
                    'status' => 'OPEN',
                    'total_pembayaran' => 0,
                    'metode_pembayaran' => 'BAYAR_NANTI'
                ]);
            }

            $product = Produk::lockForUpdate()->findOrFail($request->product_id);

            if ($product->stok < $request->quantity) {
                $errorMessage = 'Stok produk tidak mencukupi!';
                return;
            }

            // Cek apakah produk yang sama sudah ada di keranjang transaksi ini
            $item = ItemPenjualan::where('penjualan_id', $sale->id)
                ->where('produk_id', $product->id)
                ->lockForUpdate()
                ->first();

            // Kurangi stok produk
            $product->decrement('stok', $request->quantity);

            if ($item) {
                $item->kuantitas += $request->quantity;
                $item->subtotal = $item->kuantitas * $item->harga_satuan;
                $item->save();
            } else {
                ItemPenjualan::create([
                    'penjualan_id' => $sale->id,
                    'produk_id' => $product->id,
                    'nama_produk' => $product->nama,
                    'kuantitas' => $request->quantity,
                    'harga_satuan' => $product->harga_jual,
                    'subtotal' => $request->quantity * $product->harga_jual
                ]);
            }

            $sale->total_pembayaran = $sale->itemPenjualan()->sum('subtotal');
            $sale->save();
            
            $activeSaleId = $sale->id;
        });

        if ($errorMessage) {
            return redirect()->back()->with('error', $errorMessage);
        }

        return redirect()->route('penjualan.edit', $activeSaleId)->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, ItemPenjualan $itempenjualan)
    {
        $this->authorize('update', $itempenjualan);

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request, $itempenjualan) {
            $product = $itempenjualan->produk()->lockForUpdate()->first();
            $selisih = $request->quantity - $itempenjualan->kuantitas;

            if ($selisih > 0) {
                if ($product->stok < $selisih) {
                    return redirect()->back()->with('error', 'Stok tidak mencukupi');
                }
                $product->decrement('stok', $selisih);
            } elseif ($selisih < 0) {
                $product->increment('stok', abs($selisih));
            }

            $itempenjualan->update([
                'kuantitas' => $request->quantity,
                'subtotal' => $request->quantity * $itempenjualan->harga_satuan
            ]);

            $itempenjualan->penjualan->update([
                'total_pembayaran' => $itempenjualan->penjualan->itemPenjualan()->sum('subtotal')
            ]);
        });

        return back();
    }

    public function destroy(ItemPenjualan $itempenjualan)
    {
        $this->authorize('delete', $itempenjualan);

        DB::transaction(function () use ($itempenjualan) {
            $product = $itempenjualan->produk;
            $sale = $itempenjualan->penjualan;

            if ($product) {
                $product->increment('stok', $itempenjualan->kuantitas);
            }
            $itempenjualan->delete();

            $sale->update([
                'total_pembayaran' => $sale->itemPenjualan()->sum('subtotal')
            ]);
        });

        return back();
    }
}