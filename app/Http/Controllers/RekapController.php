<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LaporanPenjualanService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class RekapController extends Controller
{
    public function index(Request $request, LaporanPenjualanService $service)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $metode = $request->input('metode', 'ALL');

        // Ambil data rekap dari service
        $rekap = $service->rekapBerdasarkanTanggal($startDate, $endDate, $metode);

        // --- PAGINASI MANUAL UNTUK PRODUK TERLARIS / LUNAS ---
        $perPage = 10; // Jumlah item per halaman
        $currentPageProduk = LengthAwarePaginator::resolveCurrentPage('produk_page'); // Nama parameter query halaman
        
        $collectionProduk = collect($rekap['produkTerlaris'] ?? []);
        $currentItemsProduk = $collectionProduk->slice(($currentPageProduk - 1) * $perPage, $perPage)->all();
        
        $rekap['produkTerlaris'] = new LengthAwarePaginator(
            $currentItemsProduk,
            $collectionProduk->count(),
            $perPage,
            $currentPageProduk,
            [
                'path' => $request->url(),
                'query' => $request->query(),
                'pageName' => 'produk_page'
            ]
        );

        // --- PAGINASI MANUAL UNTUK DAFTAR PIUTANG (BAYAR NANTI) ---
        $currentPagePiutang = LengthAwarePaginator::resolveCurrentPage('piutang_page');
        
        $collectionPiutang = collect($rekap['bayarNantiList'] ?? []);
        $currentItemsPiutang = $collectionPiutang->slice(($currentPagePiutang - 1) * $perPage, $perPage)->all();
        
        $rekap['bayarNantiList'] = new LengthAwarePaginator(
            $currentItemsPiutang,
            $collectionPiutang->count(),
            $perPage,
            $currentPagePiutang,
            [
                'path' => $request->url(),
                'query' => $request->query(),
                'pageName' => 'piutang_page'
            ]
        );

        return view('recap.index', compact('rekap', 'startDate', 'endDate', 'metode'));
    }
}