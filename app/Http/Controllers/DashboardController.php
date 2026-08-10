<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\User;
use App\Services\LaporanPenjualanService;
use App\Services\MonitoringStokService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function __construct(
        protected LaporanPenjualanService $laporanService,
        protected MonitoringStokService $stokService
    ) {}

    public function index()
    {
        $ringkasan = $this->laporanService->ringkasanHariIni();
        $produkStokRendah = $this->stokService->produkStokRendah();

        return view('dashboard', [
            'tanggalHariIni' => Carbon::now(),
            'ringkasan' => $ringkasan,
            'produkTerlaris' => $this->laporanService->produkTerlarisHariIni(),
            'produkStokRendah' => $produkStokRendah,
            'produkStokHabis' => $this->stokService->produkStokHabis(),
            'totalProduk' => Produk::count(),
            'stokMenipis' => $produkStokRendah->total(),
            // Menggunakan DashboardPolicy::viewAny() yang sudah dibuat sejak awal
            // untuk menentukan card mana yang boleh dilihat non-admin.
            'isAdmin' => Gate::allows('viewAny', User::class),
        ]);
    }
}