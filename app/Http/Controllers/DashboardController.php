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
    
    // Ambil batas stok menipis dari database (default 5 jika belum diatur)
    $batasStok = \App\Models\Setting::where('key', 'batas_stok_menipis')->value('value') ?? 5;

    // Kirim batas stok dinamis ke service monitoring
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
        'batas_stok_menipis' => 'required|integer|min:1', // Validasi input baru
    ]);

    Setting::updateOrCreate(['key' => 'target_omset'], ['value' => $request->target_omset]);
    Setting::updateOrCreate(['key' => 'target_transaksi'], ['value' => $request->target_transaksi]);
    Setting::updateOrCreate(['key' => 'target_kapasitas'], ['value' => $request->target_kapasitas]);
    Setting::updateOrCreate(['key' => 'batas_stok_menipis'], ['value' => $request->batas_stok_menipis]);

    return redirect()->back()->with('success', 'Pengaturan dashboard berhasil diperbarui!');
}
}