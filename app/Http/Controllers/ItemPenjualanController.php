<?php

namespace App\Http\Controllers; // Pastikan namespace ini benar

use App\Http\Controllers\Controller; // <-- TAMBAHKAN BARIS INI
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

        DB::transaction(function () use ($request, &$errorMessage) {
            // Cek apakah penjualan_id dikirim dan valid
            if ($request->filled('penjualan_id')) {
                $sale = Penjualan::where('id', $request->penjualan_id)
                    ->where('user_id', Auth::id())
                    ->where('status', 'OPEN')
                    ->first();
            } else {
                $sale = null;
            }

            // Jika belum ada record penjualan (transaksi baru yang belum masuk database), buat sekarang!
            if (!$sale) {
                $sale = Penjualan::create([
                    'user_id' => Auth::id(),
                    'status' => 'OPEN',
                    'total_pembayaran' => 0,
                    'metode_pembayaran' => 'CASH'
                ]);
            }

            $product = Produk::lockForUpdate()->findOrFail($request->product_id);

            if ($product->stok < $request->quantity) {
                $errorMessage = 'Produk stok tidak mencukupi!';
                return;
            }

            $product->decrement('stok', $request->quantity);

            $item = ItemPenjualan::where('penjualan_id', $sale->id)
                ->where('produk_id', $product->id)
                ->lockForUpdate()
                ->first();

            if ($item) {
                $item->kuantitas += $request->quantity;
                $item->subtotal = $item->kuantitas * $item->harga_satuan;
                $item->save();
            } else {
                ItemPenjualan::create([
                    'penjualan_id' => $sale->id,
                    'produk_id' => $product->id,
                    'kuantitas' => $request->quantity,
                    'harga_satuan' => $product->harga_jual,
                    'subtotal' => $request->quantity * $product->harga_jual
                ]);
            }

            $sale->total_pembayaran = $sale->itemPenjualan()->sum('subtotal');
            $sale->save();
            
            // Simpan ID sale ke session/request agar view bisa merefresh dengan ID yang benar
            request()->merge(['active_sale_id' => $sale->id]);
        });

        if ($errorMessage) {
            return redirect()->back()->with('error', $errorMessage);
        }

        // Redirect kembali ke halaman edit POS dengan ID transaksi yang baru dibuat agar keranjang langsung muncul
        $activeSaleId = Penjualan::where('user_id', Auth::id())->where('status', 'OPEN')->latest()->first()->id;

        return redirect()->route('penjualan.edit', $activeSaleId)->with('success', 'Produk ditambahkan ke keranjang.');
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

            $product->increment('stok', $itempenjualan->kuantitas);
            $itempenjualan->delete();

            $sale->update([
                'total_pembayaran' => $sale->itemPenjualan()->sum('subtotal')
            ]);
        });

        return back();
    }
}