<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Services\LaporanPenjualanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request, LaporanPenjualanService $laporanService)
    {
        $search = $request->input('search');

        if (!empty($search)) {
            // Cari produk dari database sekaligus cek apakah produk ini punya data total terjual (best seller)
            $bestSellers = DB::table('produk')
                ->leftJoin('item_penjualan', 'produk.id', '=', 'item_penjualan.produk_id')
                ->leftJoin('penjualan', function($join) {
                    $join->on('penjualan.id', '=', 'item_penjualan.penjualan_id')
                         ->where('penjualan.status', '=', 'COMPLETED');
                })
                ->where('produk.nama', 'like', '%' . $search . '%')
                ->groupBy('produk.id', 'produk.nama', 'produk.foto', 'produk.harga_jual', 'produk.stok')
                ->select(
                    'produk.id',
                    'produk.nama',
                    'produk.foto',
                    'produk.harga_jual',
                    'produk.stok',
                    DB::raw('SUM(item_penjualan.kuantitas) as total_terjual')
                )
                ->latest('produk.id')
                ->get();
        } else {
            $bestSellers = $laporanService->produkTerlarisKeseluruhan(6);

            if ($bestSellers->isEmpty()) {
                $bestSellers = Produk::latest()
                    ->take(6)
                    ->get(['id', 'nama', 'foto', 'harga_jual', 'stok']);
            }
        }

        return view('home', compact('bestSellers', 'search'));
    }

    public function searchAjax(Request $request, LaporanPenjualanService $laporanService)
    {
        $search = $request->input('search');

        if (!empty($search)) {
            $bestSellers = DB::table('produk')
                ->leftJoin('item_penjualan', 'produk.id', '=', 'item_penjualan.produk_id')
                ->leftJoin('penjualan', function($join) {
                    $join->on('penjualan.id', '=', 'item_penjualan.penjualan_id')
                         ->where('penjualan.status', '=', 'COMPLETED');
                })
                ->where('produk.nama', 'like', '%' . $search . '%')
                ->groupBy('produk.id', 'produk.nama', 'produk.foto', 'produk.harga_jual', 'produk.stok')
                ->select(
                    'produk.id',
                    'produk.nama',
                    'produk.foto',
                    'produk.harga_jual',
                    'produk.stok',
                    DB::raw('SUM(item_penjualan.kuantitas) as total_terjual')
                )
                ->latest('produk.id')
                ->get();
        } else {
            $bestSellers = $laporanService->produkTerlarisKeseluruhan(6);

            if ($bestSellers->isEmpty()) {
                $bestSellers = Produk::latest()
                    ->take(6)
                    ->get(['id', 'nama', 'foto', 'harga_jual', 'stok']);
            }
        }

        return view('partials.product-grid', compact('bestSellers', 'search'));
    }
}