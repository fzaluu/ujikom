<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanPenjualanService
{
    public function ringkasanHariIni(): array
    {
        $data = DB::table('penjualan')
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'COMPLETED')
            ->selectRaw('
                COUNT(*) as total_transaksi,
                SUM(total_pembayaran) as total_penjualan,
                SUM(CASE WHEN metode_pembayaran = "CASH" THEN total_pembayaran ELSE 0 END) as total_cash,
                SUM(CASE WHEN metode_pembayaran != "CASH" THEN total_pembayaran ELSE 0 END) as total_non_tunai
            ')
            ->first();

        return [
            'total_transaksi' => $data->total_transaksi ?? 0,
            'total_penjualan' => $data->total_penjualan ?? 0,
            'total_cash' => $data->total_cash ?? 0,
            'total_non_tunai' => $data->total_non_tunai ?? 0,
        ];
    }

    public function produkTerlarisHariIni(int $limit = 5)
    {
        return DB::table('item_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'item_penjualan.penjualan_id')
            ->join('produk', 'produk.id', '=', 'item_penjualan.produk_id')
            ->whereDate('penjualan.created_at', Carbon::today())
            ->where('penjualan.status', 'COMPLETED')
            ->groupBy('produk.id', 'produk.nama')
            ->select(
                'produk.nama',
                'produk.foto',
                'produk.stok',
                DB::raw('SUM(item_penjualan.kuantitas) as total_terjual')
            )
            ->orderByDesc('total_terjual')
            ->limit($limit)
            ->get();
    }

    public function produkTerlarisKeseluruhan(int $limit = 6)
    {
        return DB::table('item_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'item_penjualan.penjualan_id')
            ->join('produk', 'produk.id', '=', 'item_penjualan.produk_id')
            ->where('penjualan.status', 'COMPLETED')
            ->groupBy('produk.id', 'produk.nama', 'produk.foto', 'produk.harga_jual')
            ->select(
                'produk.id',
                'produk.nama',
                'produk.foto',
                'produk.harga_jual',
                'produk.stok',
                DB::raw('SUM(item_penjualan.kuantitas) as total_terjual')
            )
            ->having('total_terjual', '>=', 27) // mengatur best seller
            ->orderByDesc('total_terjual')
            ->limit($limit)
            ->get();
    }

    public function rekapBerdasarkanTanggal($startDate, $endDate, $metode = 'ALL', $searchLunas = null, $searchPiutang = null)
    {
        $cleanMetode = strtoupper(trim($metode));

        // 1. Query Ringkasan Utama (Omset Lunas)
        $queryRingkasan = DB::table('penjualan')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'COMPLETED');

        if ($cleanMetode && $cleanMetode !== 'ALL') {
            $queryRingkasan->where('metode_pembayaran', $cleanMetode);
        }

        $ringkasan = $queryRingkasan->selectRaw('
            COUNT(*) as total_transaksi,
            SUM(total_pembayaran) as total_omset,
            SUM(CASE WHEN metode_pembayaran = "CASH" THEN total_pembayaran ELSE 0 END) as total_cash,
            SUM(CASE WHEN metode_pembayaran != "CASH" THEN total_pembayaran ELSE 0 END) as total_non_tunai
        ')->first();

        // 2. Query Tabel Produk Terjual Lunas (COMPLETED) + Search + Pagination
        $queryProduk = DB::table('item_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'item_penjualan.penjualan_id')
            ->join('produk', 'produk.id', '=', 'item_penjualan.produk_id')
            ->whereBetween('penjualan.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('penjualan.status', 'COMPLETED');

        if ($cleanMetode && $cleanMetode !== 'ALL' && $cleanMetode !== 'BAYAR_NANTI') {
            $queryProduk->where('penjualan.metode_pembayaran', $cleanMetode);
        }

        if ($searchLunas) {
            $queryProduk->where('produk.nama', 'like', '%' . $searchLunas . '%');
        }

        $produkTerlaris = (clone $queryProduk)
            ->groupBy('produk.id', 'produk.nama', 'produk.harga_jual', 'penjualan.id', 'penjualan.status', 'penjualan.metode_pembayaran')
            ->select(
                'penjualan.id as penjualan_id',
                'penjualan.status as status_pesanan',
                'penjualan.metode_pembayaran',
                'produk.nama',
                'produk.harga_jual',
                DB::raw('SUM(item_penjualan.kuantitas) as total_terjual'),
                DB::raw('SUM(item_penjualan.subtotal) as total_pendapatan_produk')
            )
            ->orderByRaw("FIELD(penjualan.metode_pembayaran, 'CASH', 'QRIS') ASC")
            ->orderByDesc('total_terjual')
            ->paginate(10, ['*'], 'page_lunas')
            ->withQueryString();

        // 3. Query Tabel Piutang / Bayar Nanti (OPEN) + Search + Pagination
        $queryBayarNanti = DB::table('item_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'item_penjualan.penjualan_id')
            ->join('produk', 'produk.id', '=', 'item_penjualan.produk_id')
            ->whereBetween('penjualan.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('penjualan.status', 'OPEN');

        if ($searchPiutang) {
            $queryBayarNanti->where('produk.nama', 'like', '%' . $searchPiutang . '%');
        }

        $bayarNantiList = $queryBayarNanti
            ->groupBy('produk.id', 'produk.nama', 'produk.harga_jual', 'penjualan.id', 'penjualan.status', 'penjualan.metode_pembayaran')
            ->select(
                'penjualan.id as penjualan_id',
                'penjualan.status as status_pesanan',
                'penjualan.metode_pembayaran',
                'produk.nama',
                'produk.harga_jual',
                DB::raw('SUM(item_penjualan.kuantitas) as total_terjual'),
                DB::raw('SUM(item_penjualan.subtotal) as total_pendapatan_produk')
            )
            ->orderByDesc('total_terjual')
            ->paginate(10, ['*'], 'page_piutang')
            ->withQueryString();

        return [
            'total_transaksi' => $ringkasan->total_transaksi ?? 0,
            'total_omset' => $ringkasan->total_omset ?? 0,
            'total_cash' => $ringkasan->total_cash ?? 0,
            'total_non_tunai' => $ringkasan->total_non_tunai ?? 0,
            'produkTerlaris' => $produkTerlaris,
            'bayarNantiList' => $bayarNantiList,
        ];
    }
}