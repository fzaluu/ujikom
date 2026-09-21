<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LaporanPenjualanService;
use Carbon\Carbon;

class RekapController extends Controller
{
    public function index(Request $request, LaporanPenjualanService $service)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $metode = $request->input('metode', 'ALL');
        
        $searchLunas = $request->input('search_lunas');
        $searchPiutang = $request->input('search_piutang');


        $rekap = $service->rekapBerdasarkanTanggal($startDate, $endDate, $metode, $searchLunas, $searchPiutang);

        return view('recap.index', compact('rekap', 'startDate', 'endDate', 'metode'));
    }
}