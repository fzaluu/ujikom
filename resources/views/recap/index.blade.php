@extends('layouts.app')

@section('title', 'Rekapitulasi Penjualan')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-page { animation: fadeInUp 0.4s ease forwards; }

    .table-hover-custom tbody tr {
        transition: all 0.2s ease;
    }
    .table-hover-custom tbody tr:hover {
        background-color: #F8FAFC;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    /* Styling Konsisten untuk Tombol Shortcut Periode */
    .shortcut-btn {
        background-color: #ffffff;
        color: #495057;
        border: 1px solid #ced4da;
        transition: all 0.2s ease;
    }
    .shortcut-btn:hover {
        background-color: #f8f9fa;
        color: #212529;
        border-color: #adb5bd;
    }
    .shortcut-btn.active-shortcut {
        background-color: #212529 !important;
        color: #ffffff !important;
        border-color: #212529 !important;
        font-weight: 600;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    /* Penyesuaian Tabel agar sangat rapi di Mobile */
    @media (max-width: 768px) {
        .table-custom th, .table-custom td {
            white-space: nowrap;
        }
    }

    /* Styling Khusus agar Pagination Responsif dan Bisa Digeser di Mobile */
.recap-pagination-wrapper {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    white-space: nowrap;
    padding-bottom: 4px;
}
.recap-pagination-wrapper nav {
    display: inline-block;
}
.recap-pagination-wrapper .pagination {
    font-size: 0.8rem;
    margin-bottom: 0;
    display: inline-flex;
    gap: 2px;
}
.recap-pagination-wrapper .page-link {
    padding: 0.25rem 0.6rem;
    color: #0d6efd;
    border-radius: 4px;
    border: 1px solid #dee2e6;
}
.recap-pagination-wrapper .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}
</style>

@php
    $activeShortcut = request('shortcut', 'custom');
@endphp

<div class="container-fluid px-0 animate-page">
    
    {{-- Header & Filter Form --}}
    <div class="card shadow-sm border-0 rounded-4 p-3 p-md-4 mb-4 bg-white">
        <div class="mb-3">
            <span class="text-primary fw-semibold small text-uppercase tracking-wider">Laporan Toko</span>
            <h3 class="fw-bold text-dark fs-4 fs-md-3 mb-1">Rekapitulasi Penjualan</h3>
            <p class="text-muted small mb-0">Pilih rentang tanggal untuk melihat rekap omset dan daftar piutang.</p>
        </div>

        <form action="{{ route('recap.index') }}" method="GET" id="rekapForm">
            <input type="hidden" name="shortcut" id="shortcutInput" value="{{ $activeShortcut }}">
            
            <div class="row g-3 align-items-end mb-3">
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-semibold">Dari Tanggal</label>
                    <input type="date" name="start_date" id="startDate" class="form-control rounded-3" value="{{ $startDate }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="endDate" class="form-control rounded-3" value="{{ $endDate }}">
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 pt-2 border-top">
                <div class="d-grid d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm rounded-3 px-3 shortcut-btn flex-fill {{ $activeShortcut == 'hari_ini' ? 'active-shortcut' : '' }}" onclick="setPeriode('hari_ini')">1 Hari</button>
                    <button type="button" class="btn btn-sm rounded-3 px-3 shortcut-btn flex-fill {{ $activeShortcut == '1_minggu' ? 'active-shortcut' : '' }}" onclick="setPeriode('1_minggu')">1 Minggu</button>
                    <button type="button" class="btn btn-sm rounded-3 px-3 shortcut-btn flex-fill {{ $activeShortcut == '1_bulan' ? 'active-shortcut' : '' }}" onclick="setPeriode('1_bulan')">1 Bulan</button>
                    <button type="button" class="btn btn-sm rounded-3 px-3 shortcut-btn flex-fill {{ $activeShortcut == '1_tahun' ? 'active-shortcut' : '' }}" onclick="setPeriode('1_tahun')">1 Tahun</button>
                </div>

                <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm">
                    <i class="bi bi-filter me-1"></i> Tampilkan Rekap
                </button>
            </div>
        </form>
    </div>

    {{-- Ringkasan Statistik Kartu --}}
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card h-100 p-3 p-md-4 border-0 shadow-sm rounded-4 bg-white">
                <span class="text-muted small fw-bold text-uppercase">Total Omset (Lunas)</span>
                <h3 class="fw-bold text-primary fs-4 fs-md-3 mt-2 mb-0">
                    Rp {{ number_format($rekap['total_omset'], 0, ',', '.') }}
                </h3>
                <small class="text-muted mt-1">Periode: {{ $startDate }} s/d {{ $endDate }}</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card h-100 p-3 p-md-4 border-0 shadow-sm rounded-4 bg-white">
                <span class="text-muted small fw-bold text-uppercase">Total Transaksi Lunas</span>
                <h3 class="fw-bold text-success fs-4 fs-md-3 mt-2 mb-0">{{ $rekap['total_transaksi'] }} <span class="fs-6 fw-normal text-muted">Order</span></h3>
                <small class="text-muted mt-1">Status: Selesai (Cash & QRIS)</small>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-xl-4">
            <div class="card h-100 p-3 p-md-4 border-0 shadow-sm rounded-4 bg-white">
                <span class="text-muted small fw-bold text-uppercase">Total Piutang Aktif</span>
                <h3 class="fw-bold text-warning fs-4 fs-md-3 mt-2 mb-0">
                    Rp {{ number_format(collect($rekap['bayarNantiList'] ?? [])->sum('total_pendapatan_produk'), 0, ',', '.') }}
                </h3>
                <small class="text-muted mt-1">Belum Lunas (Bayar Nanti)</small>
            </div>
        </div>
    </div>

    {{-- TABEL 1: TRANSAKSI LUNAS (CASH & QRIS) --}}
    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4">
        <h5 class="fw-bold text-dark mb-3 fs-5">
            <i class="bi bi-box-seam text-primary me-2"></i> Detail Produk Terjual (Lunas: Cash & QRIS)
        </h5>
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary fw-bold">
                    <tr>
                        <th scope="col" class="py-3 ps-3 rounded-start-3 align-middle" style="width: 5%;">No</th>
                        <th scope="col" class="py-3 align-middle" style="min-width: 180px;">Nama Produk</th>
                        <th scope="col" class="py-3 align-middle" style="min-width: 130px;">Harga Satuan</th>
                        <th scope="col" class="py-3 align-middle" style="width: 100px;">Terjual</th>
                        <th scope="col" class="py-3 align-middle" style="min-width: 150px;">Total Pendapatan</th>
                        <th scope="col" class="py-3 align-middle" style="width: 120px;">Metode</th>
                        <th scope="col" class="py-3 text-center pe-3 rounded-end-3 align-middle" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekap['produkTerlaris'] as $item)
                    <tr>
                        <th scope="row" class="ps-3 py-3 text-muted fw-medium align-middle">
                            {{ method_exists($rekap['produkTerlaris'], 'firstItem') ? $rekap['produkTerlaris']->firstItem() + $loop->index : $loop->iteration }}
                        </th>
                        <td class="fw-semibold text-dark align-middle">
                            {{ $item->nama }}
                        </td>
                        <td class="text-secondary small align-middle">
                            Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-2.5 py-1 fw-semibold">
                                {{ $item->total_terjual }} Pcs
                            </span>
                        </td>
                        <td class="fw-bold text-success align-middle">
                            Rp {{ number_format($item->total_pendapatan_produk, 0, ',', '.') }}
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 fw-normal">
                                {{ $item->metode_pembayaran === 'BAYAR_NANTI' ? 'BAYAR NANTI' : ($item->metode_pembayaran ?? 'Belum Dipilih') }}
                            </span>
                        </td>
                        <td class="pe-3 text-center align-middle">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('penjualan.show', $item->penjualan_id) }}" 
                                class="btn btn-light btn-sm border text-info shadow-none px-2" 
                                title="Detail Transaksi">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 opacity-50 d-block mb-1"></i>
                            Tidak ada data transaksi lunas pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($rekap['produkTerlaris'], 'links'))
            <div class="mt-3 px-2 d-flex justify-content-center justify-content-md-end">
                <div class="recap-pagination-wrapper text-center text-md-end">
                    {{ $rekap['produkTerlaris']->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- TABEL 2: KHUSUS PIUTANG / BAYAR NANTI --}}
    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4">
        <h5 class="fw-bold text-warning mb-3 fs-5">
            <i class="bi bi-clock-history me-2"></i> Daftar Piutang (Bayar Nanti / Belum Lunas)
        </h5>
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary fw-bold">
                    <tr>
                        <th scope="col" class="py-3 ps-3 rounded-start-3 align-middle" style="width: 5%;">No</th>
                        <th scope="col" class="py-3 align-middle" style="min-width: 180px;">Nama Produk</th>
                        <th scope="col" class="py-3 align-middle" style="min-width: 130px;">Harga Satuan</th>
                        <th scope="col" class="py-3 align-middle" style="width: 100px;">Jumlah</th>
                        <th scope="col" class="py-3 align-middle" style="min-width: 150px;">Total Piutang</th>
                        <th scope="col" class="py-3 align-middle" style="width: 120px;">Status</th>
                        <th scope="col" class="py-3 text-center pe-3 rounded-end-3 align-middle" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekap['bayarNantiList'] ?? [] as $item)
                    <tr>
                        <th scope="row" class="ps-3 py-3 text-muted fw-medium align-middle">
                            {{ method_exists($rekap['bayarNantiList'], 'firstItem') ? $rekap['bayarNantiList']->firstItem() + $loop->index : $loop->iteration }}
                        </th>
                        <td class="fw-semibold text-dark align-middle">
                            {{ $item->nama }}
                        </td>
                        <td class="text-secondary small align-middle">
                            Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1 fw-semibold">
                                {{ $item->total_terjual }} Pcs
                            </span>
                        </td>
                        <td class="fw-bold text-warning align-middle">
                            Rp {{ number_format($item->total_pendapatan_produk, 0, ',', '.') }}
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 fw-normal">
                                {{ $item->metode_pembayaran === 'BAYAR_NANTI' ? 'BAYAR NANTI' : ($item->metode_pembayaran ?? 'Belum Dipilih') }}
                            </span>
                        </td>
                        <td class="pe-3 text-center align-middle">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('penjualan.show', $item->penjualan_id) }}" 
                                class="btn btn-light btn-sm border text-info shadow-none px-2" 
                                title="Detail Transaksi">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('penjualan.edit', $item->penjualan_id) }}" 
                                class="btn btn-light btn-sm border text-warning shadow-none px-2" 
                                title="Lanjut / Pelunasan Kasir">
                                    <i class="bi bi-cart-plus"></i>
                                </a>
                                <form action="{{ route('penjualan.destroy', $item->penjualan_id) }}" 
                                    method="POST" 
                                    class="d-inline"
                                    id="delete-form-penjualan-{{ $item->penjualan_id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            class="btn btn-light btn-sm border text-danger shadow-none px-2" 
                                            title="Hapus Transaksi"
                                            onclick="openDeleteModal('penjualan-{{ $item->penjualan_id }}', 'Apakah anda yakin akan menghapus piutang ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-check-circle fs-2 text-success opacity-50 d-block mb-1"></i>
                            Tidak ada data piutang (Bayar Nanti) pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(is_object($rekap['bayarNantiList']) && method_exists($rekap['bayarNantiList'], 'links'))
    <div class="mt-3 px-2 d-flex justify-content-center justify-content-md-end">
        <div class="recap-pagination-wrapper text-center text-md-end">
            {{ $rekap['bayarNantiList']->appends(request()->query())->links() }}
        </div>
    </div>
@endif
    </div>

</div>

{{-- Modal Konfirmasi Hapus di Tengah --}}
<div class="modal fade" id="customDeleteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg animate-page">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-trash text-danger display-4 mb-3"></i>
                <p id="deleteModalMessage" class="text-dark fs-6 mb-0">Apakah Anda yakin ingin menghapus data ini?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4 gap-2">
                <button type="button" class="btn btn-light px-4 rounded-3 shadow-none border" data-bs-dismiss="modal" id="cancelDeleteBtn">Batal</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger px-4 rounded-3 shadow-sm">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    function setPeriode(tipe) {
        const today = new Date();
        let start = new Date();
        let end = new Date();

        if (tipe === 'hari_ini') {
            start = today;
            end = today;
        } else if (tipe === '1_minggu') {
            start.setDate(today.getDate() - 7);
            end = today;
        } else if (tipe === '1_bulan') {
            start.setMonth(today.getMonth() - 1);
            end = today;
        } else if (tipe === '1_tahun') {
            start.setFullYear(today.getFullYear() - 1);
            end = today;
        }

        document.getElementById('startDate').value = formatDate(start);
        document.getElementById('endDate').value = formatDate(end);
        document.getElementById('shortcutInput').value = tipe;

        document.getElementById('rekapForm').submit();
    }

    function formatDate(date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    let activeDeleteFormId = null;

    function openDeleteModal(identifier, message) {
        activeDeleteFormId = 'delete-form-' + identifier;
        document.getElementById('deleteModalMessage').innerText = message;
        
        let btn = document.getElementById('confirmDeleteBtn');
        btn.disabled = false;
        btn.innerHTML = 'Ya, Hapus';

        let cancelBtn = document.getElementById('cancelDeleteBtn');
        if (cancelBtn) cancelBtn.disabled = false;

        var myModal = new bootstrap.Modal(document.getElementById('customDeleteModal'));
        myModal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (activeDeleteFormId) {
            let btn = this;
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...`;
            
            let cancelBtn = document.getElementById('cancelDeleteBtn');
            if (cancelBtn) cancelBtn.disabled = true;

            document.getElementById(activeDeleteFormId).submit();
        }
    });
</script>
@endsection