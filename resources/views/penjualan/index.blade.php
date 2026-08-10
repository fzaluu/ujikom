@extends('layouts.app')

@section('title', 'Manajemen Penjualan - POS SMART')

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
</style>

<div class="container-fluid px-0 animate-page">
    <div class="card shadow-sm border-0 rounded-4 p-4">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <span class="text-primary fw-semibold small text-uppercase tracking-wider">Transaksi Kasir</span>
                <h3 class="fw-bold text-dark mb-1">
                    Manajemen Penjualan
                </h3>
                <p class="text-muted small mb-0">
                    Kelola riwayat transaksi penjualan, status pesanan, dan pembayaran toko.
                </p>
            </div>

            <a href="{{ route('penjualan.create') }}" class="btn btn-primary shadow-sm rounded-3 py-2 px-3">
                <i class="bi bi-plus-circle me-1"></i> Transaksi Baru
            </a>
        </div>

        {{-- Search Bar --}}
        <div class="row mb-4">
            <div class="col-md-5">
                <form action="{{ route('penjualan.index') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request()->search }}" class="form-control bg-light border-start-0 ps-0" placeholder="Cari transaksi...">
                        <button class="btn btn-primary px-3" type="submit">Cari</button>
                        @if(request('search'))
                            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Penjualan --}}
        <div class="table-responsive">
            <table class="table table-hover-custom align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-muted">
                    <tr>
                        <th scope="col" width="5%" class="py-3 ps-3 rounded-start-3">#</th>
                        <th scope="col" class="py-3">Tanggal Transaksi</th>
                        <th scope="col" class="py-3">Kasir</th>
                        <th scope="col" class="py-3">Total Pembayaran</th>
                        <th scope="col" class="py-3">Metode</th>
                        <th scope="col" class="py-3">Status</th>
                        <th scope="col" width="18%" class="py-3 text-center pe-3 rounded-end-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <th scope="row" class="ps-3 py-3 text-muted">
                            {{ $sales->firstItem() + $loop->index }}
                        </th>
                        <td class="text-secondary small">
                            <i class="bi bi-calendar-event me-1 text-primary"></i> {{ $sale->created_at->translatedFormat('d-m-Y H:i:s') }}
                        </td>
                        <td class="fw-semibold text-dark">
                            <span class="badge bg-light text-dark border px-2 py-1">
                                <i class="bi bi-person me-1"></i> {{ $sale->user->name }}
                            </span>
                        </td>
                        <td class="fw-bold text-success">
                            Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                {{ $sale->metode_pembayaran ?? 'Belum Dipilih' }}
                            </span>
                        </td>
                        <td>
                            @if($sale->status === 'COMPLETED')
                                <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1">Completed</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1">Open</span>
                            @endif
                        </td>
                        <td class="pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('penjualan.show', $sale) }}" class="btn btn-info btn-sm text-white shadow-sm rounded-2" title="Detail Transaksi">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @can('update', $sale)
                                    @if($sale->status === 'OPEN')
                                        <a href="{{ route('penjualan.edit', $sale) }}" class="btn btn-warning btn-sm text-white shadow-sm rounded-2" title="Lanjut / Edit Kasir">
                                            <i class="bi bi-cart-plus"></i>
                                        </a>
                                        
                                        <form action="{{ route('penjualan.destroy', $sale) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah anda yakin akan menghapus penjualan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm shadow-sm rounded-2" title="Hapus Transaksi">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-bag-x fs-1 text-muted opacity-50"></i>
                            <h6 class="mt-3 text-muted">Belum ada data transaksi penjualan.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-4 mt-3">
            <small class="text-muted mb-2 mb-md-0">
                Menampilkan total <strong>{{ $sales->total() }}</strong> riwayat transaksi
            </small>
            <div>
                {{ $sales->links() }}
            </div>
        </div>

    </div>
</div>
@endsection