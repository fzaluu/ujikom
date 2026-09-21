<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\User;
use App\Models\Setting;
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
        
        $batasStok = Setting::where('key', 'batas_stok_menipis')->value('value') ?? 5;
        $produkStokRendah = $this->stokService->produkStokRendah((int)$batasStok);

        return view('dashboard', [
            'tanggalHariIni' => Carbon::now(),
            'ringkasan' => $ringkasan,
            'produkTerlaris' => $this->laporanService->produkTerlarisHariIni(),
            'produkStokRendah' => $produkStokRendah,
            'produkStokHabis' => $this->stokService->produkStokHabis(),
            'totalProduk' => Produk::count(),
            'stokMenipis' => $produkStokRendah->total(),
            'isAdmin' => Gate::allows('viewAny', User::class),
        ]);
    }

    public function updateTarget(Request $request)
    {
        $request->validate([
            'target_omset' => 'required|numeric|min:0',
            'target_transaksi' => 'required|integer|min:0',
            'target_kapasitas' => 'required|integer|min:0',
            'batas_stok_menipis' => 'required|integer|min:1',
            'min_penjualan_bestseller' => 'required|integer|min:1', // Validasi input baru
        ]);

        Setting::updateOrCreate(['key' => 'target_omset'], ['value' => $request->target_omset]);
        Setting::updateOrCreate(['key' => 'target_transaksi'], ['value' => $request->target_transaksi]);
        Setting::updateOrCreate(['key' => 'target_kapasitas'], ['value' => $request->target_kapasitas]);
        Setting::updateOrCreate(['key' => 'batas_stok_menipis'], ['value' => $request->batas_stok_menipis]);
        Setting::updateOrCreate(['key' => 'min_penjualan_bestseller'], ['value' => $request->min_penjualan_bestseller]);

        return redirect()->back()->with('success', 'Pengaturan dashboard berhasil diperbarui!');
    }
}