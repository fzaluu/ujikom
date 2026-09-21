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

    public function export(Request $request, LaporanPenjualanService $service)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $metode = $request->input('metode', 'ALL');

        // Ambil SEMUA data sesuai filter inputan tanpa batasan pagination
        $rekap = $service->rekapSemuaDataTanpaPagination($startDate, $endDate, $metode);

        $fileName = 'Rekap-Penjualan-' . $startDate . '-sampai-' . $endDate . '-' . $metode . '.xls';

        $headers = [
            "Content-type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($rekap, $startDate, $endDate, $metode) {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style>
                table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 11pt; }
                th { background-color: #198754; color: white; font-weight: bold; border: 1px solid #dee2e6; padding: 8px; text-align: center; }
                td { border: 1px solid #dee2e6; padding: 6px; vertical-align: middle; }
                .title { font-size: 14pt; font-weight: bold; margin-bottom: 5px; }
                .section-header { background-color: #f8f9fa; font-weight: bold; font-size: 12pt; color: #333; }
                .total-row { font-weight: bold; background-color: #e9ecef; }
            </style></head>';
            echo '<body>';
            
            echo '<table>';
            echo '<tr><td colspan="6" class="title" style="border:none;">REKAPITULASI PENJUALAN RAJA CELL</td></tr>';
            echo '<tr><td colspan="6" style="border:none;"><strong>Periode Tanggal:</strong> ' . $startDate . ' s/d ' . $endDate . '</td></tr>';
            echo '<tr><td colspan="6" style="border:none;"><strong>Metode Dipilih:</strong> ' . $metode . '</td></tr>';
            echo '<tr><td colspan="6" style="border:none;"></td></tr>';

            // Jika metode bukan BAYAR_NANTI, tampilkan tabel produk lunas
            if ($metode !== 'BAYAR_NANTI') {
                echo '<tr class="section-header"><td colspan="6">DETAIL PRODUK TERJUAL (LUNAS: CASH & QRIS)</td></tr>';
                echo '<tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Terjual</th>
                    <th>Total Pendapatan</th>
                    <th>Metode</th>
                </tr>';
                
                $totalTerjualLunas = 0;
                $totalPendapatanLunas = 0;

                if (count($rekap['produkTerlaris']) > 0) {
                    foreach ($rekap['produkTerlaris'] as $index => $item) {
                        $totalTerjualLunas += $item->total_terjual;
                        $totalPendapatanLunas += $item->total_pendapatan_produk;

                        echo '<tr>';
                        echo '<td align="center">' . ($index + 1) . '</td>';
                        echo '<td>' . htmlspecialchars($item->nama) . '</td>';
                        echo '<td align="right">Rp ' . number_format($item->harga_jual, 0, ',', '.') . '</td>';
                        echo '<td align="center">' . $item->total_terjual . ' Pcs</td>';
                        echo '<td align="right">Rp ' . number_format($item->total_pendapatan_produk, 0, ',', '.') . '</td>';
                        echo '<td align="center">' . $item->metode_pembayaran . '</td>';
                        echo '</tr>';
                    }

                    // Baris Total Lunas
                    echo '<tr class="total-row">';
                    echo '<td colspan="3" align="right"><strong>TOTAL KESELURUHAN:</strong></td>';
                    echo '<td align="center"><strong>' . $totalTerjualLunas . ' Pcs</strong></td>';
                    echo '<td align="right"><strong>Rp ' . number_format($totalPendapatanLunas, 0, ',', '.') . '</strong></td>';
                    echo '<td></td>';
                    echo '</tr>';

                } else {
                    echo '<tr><td colspan="6" align="center">Tidak ada data produk lunas pada filter ini.</td></tr>';
                }
                echo '<tr><td colspan="6" style="border:none;"></td></tr>';
                echo '<tr><td colspan="6" style="border:none;"></td></tr>';
            }

            // Jika metode bukan CASH atau QRIS (artinya ALL atau BAYAR_NANTI), tampilkan tabel piutang
            if ($metode !== 'CASH' && $metode !== 'QRIS') {
                echo '<tr class="section-header"><td colspan="7">DAFTAR PIUTANG (BAYAR NANTI / BELUM LUNAS)</td></tr>';
                echo '<tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Total Piutang</th>
                    <th>Status</th>
                </tr>';
                
                $totalJumlahPiutang = 0;
                $totalNominalPiutang = 0;

                if (count($rekap['bayarNantiList']) > 0) {
                    foreach ($rekap['bayarNantiList'] as $index => $item) {
                        $totalJumlahPiutang += $item->total_terjual;
                        $totalNominalPiutang += $item->total_pendapatan_produk;

                        echo '<tr>';
                        echo '<td align="center">' . ($index + 1) . '</td>';
                        echo '<td>' . htmlspecialchars($item->customer_name ?? '-') . '</td>';
                        echo '<td>' . htmlspecialchars($item->nama) . '</td>';
                        echo '<td align="right">Rp ' . number_format($item->harga_jual, 0, ',', '.') . '</td>';
                        echo '<td align="center">' . $item->total_terjual . ' Pcs</td>';
                        echo '<td align="right">Rp ' . number_format($item->total_pendapatan_produk, 0, ',', '.') . '</td>';
                        echo '<td align="center">BAYAR NANTI</td>';
                        echo '</tr>';
                    }

                    // Baris Total Piutang
                    echo '<tr class="total-row">';
                    echo '<td colspan="4" align="right"><strong>TOTAL KESELURUHAN PIUTANG:</strong></td>';
                    echo '<td align="center"><strong>' . $totalJumlahPiutang . ' Pcs</strong></td>';
                    echo '<td align="right"><strong>Rp ' . number_format($totalNominalPiutang, 0, ',', '.') . '</strong></td>';
                    echo '<td></td>';
                    echo '</tr>';

                } else {
                    echo '<tr><td colspan="7" align="center">Tidak ada data piutang pada filter ini.</td></tr>';
                }
            }

            echo '</table>';
            echo '</body></html>';
        };

        return response()->stream($callback, 200, $headers);
    }
}