@extends('layouts.app')

@section('title', 'Detail Penjualan - POS SMART')

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm border-0 rounded-4 col-lg-10 mx-auto p-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-primary fw-semibold small text-uppercase tracking-wider">Riwayat Kasir</span>
                <h3 class="fw-bold text-dark mb-1">Detail Penjualan</h3>
                <p class="text-muted small mb-0">Informasi lengkap transaksi dan item barang yang dibeli.</p>
            </div>
            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary shadow-sm rounded-3 py-2">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 bg-light bg-opacity-50 rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3">Informasi Transaksi</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Tanggal Transaksi</div>
                    <div class="fw-semibold text-dark">{{ $sale->created_at->translatedFormat('d F Y H:i:s') }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Kasir Bertugas</div>
                    <div class="fw-semibold text-dark">
                        <span class="badge bg-white text-dark border px-2 py-1">
                            <i class="bi bi-person me-1"></i> {{ $sale->user->name }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Status Pesanan</div>
                    <div>
                        <span class="badge {{ $sale->status == 'COMPLETED' ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }} px-2.5 py-1">
                            {{ $sale->status }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Metode Pembayaran</div>
                    <div class="fw-semibold text-secondary">{{ $sale->metode_pembayaran ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-0 overflow-hidden">
            <div class="card-header bg-white border-0 p-3 pb-0">
                <h5 class="fw-bold text-dark mb-0">Daftar Item Produk yang Dibeli</h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-muted">
                            <tr>
                                <th width="5%" class="py-3 ps-3 rounded-start">#</th>
                                <th class="py-3">Nama Produk</th>
                                <th class="py-3">Harga Satuan</th>
                                <th class="py-3">Qty</th>
                                <th class="py-3 pe-3 rounded-end text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sale->itemPenjualan as $item)
                            <tr>
                                <td class="ps-3 py-3 text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold text-dark">{{ $item->produk->nama }}</td>
                                <td class="text-muted small">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td>{{ $item->kuantitas }} Unit</td>
                                <td class="pe-3 fw-bold text-success text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada item produk pada transaksi ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end py-3">Total Pembayaran:</th>
                                <th class="text-end py-3 text-success fs-5 pe-3">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection