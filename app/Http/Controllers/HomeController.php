<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Services\LaporanPenjualanService;

class HomeController extends Controller
{
    public function index(LaporanPenjualanService $laporanService)
    {
        $bestSellers = $laporanService->produkTerlarisKeseluruhan(6);

        if ($bestSellers->isEmpty()) {
            $bestSellers = Produk::latest()
                ->take(6)
                ->get(['id', 'nama', 'foto', 'harga_jual']);
        }

        return view('home', compact('bestSellers'));
    }
}