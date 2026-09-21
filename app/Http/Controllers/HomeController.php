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

        // Jika user mengetik minimal 1 huruf atau lebih, cari langsung dari seluruh database
        if (!empty($search)) {
            $bestSellers = Produk::where('nama', 'like', '%' . $search . '%')
                ->latest()
                ->get(['id', 'nama', 'foto', 'harga_jual']);
        } else {
            // Jika kosong, tampilkan produk terlaris (best seller) seperti biasa
            $bestSellers = $laporanService->produkTerlarisKeseluruhan(6);

            if ($bestSellers->isEmpty()) {
                $bestSellers = Produk::latest()
                    ->take(6)
                    ->get(['id', 'nama', 'foto', 'harga_jual']);
            }
        }

        return view('home', compact('bestSellers', 'search'));
    }
}