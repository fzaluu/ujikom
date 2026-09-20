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

        // Mengambil data rekap (sudah otomatis ada pagination di dalam service)
        $rekap = $service->rekapBerdasarkanTanggal($startDate, $endDate, $metode);

        return view('recap.index', compact('rekap', 'startDate', 'endDate', 'metode'));
    }
}