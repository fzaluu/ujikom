<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Services\LaporanPenjualanService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request, LaporanPenjualanService $laporanService)
    {
        $search = $request->input('search');

        if (!empty($search)) {
            $bestSellers = Produk::where('nama', 'like', '%' . $search . '%')
                ->latest()
                ->get(['id', 'nama', 'foto', 'harga_jual']);
        } else {
            $bestSellers = $laporanService->produkTerlarisKeseluruhan(6);

            if ($bestSellers->isEmpty()) {
                $bestSellers = Produk::latest()
                    ->take(6)
                    ->get(['id', 'nama', 'foto', 'harga_jual']);
            }
        }

        return view('home', compact('bestSellers', 'search'));
    }

    // Method khusus untuk pencarian AJAX tanpa refresh halaman
    public function searchAjax(Request $request, LaporanPenjualanService $laporanService)
    {
        $search = $request->input('search');

        if (!empty($search)) {
            $bestSellers = Produk::where('nama', 'like', '%' . $search . '%')
                ->latest()
                ->get(['id', 'nama', 'foto', 'harga_jual']);
        } else {
            $bestSellers = $laporanService->produkTerlarisKeseluruhan(6);

            if ($bestSellers->isEmpty()) {
                $bestSellers = Produk::latest()
                    ->take(6)
                    ->get(['id', 'nama', 'foto', 'harga_jual']);
            }
        }

        return view('partials.product-grid', compact('bestSellers', 'search'));
    }
}