@extends('layouts.app')

@section('title', 'Jenis Produk - POS SMART')

@section('content')
@php
    $isAdmin = auth()->check() && (optional(auth()->user()->role)->name === 'admin' || auth()->user()->role_id == 1);
@endphp
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-page { animation: fadeInUp 0.4s ease forwards; }
    .table-hover-custom tbody tr { transition: all 0.2s ease; }
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
                <span class="text-primary fw-semibold small text-uppercase tracking-wider">Inventaris Toko</span>
                <h3 class="fw-bold text-dark mb-1">Jenis Produk</h3>
                <p class="text-muted small mb-0">
                    Kelola kategori produk. Klik salah satu jenis untuk melihat daftar produknya.
                </p>
            </div>

            @if($isAdmin)
                <a href="{{ route('jenis-produk.create') }}" class="btn btn-primary shadow-sm rounded-3 py-2 px-3">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Jenis Produk
                </a>
            @endif
        </div>

        {{-- Table Jenis Produk --}}
        <div class="table-responsive">
            <table class="table table-hover-custom align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-muted">
                    <tr>
                        <th width="5%" class="py-3 ps-3 rounded-start-3">#</th>
                        <th class="py-3">Nama Jenis</th>
                        <th width="15%" class="py-3">Jumlah Produk</th>
                        <th width="18%" class="py-3 text-center pe-3 rounded-end-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($jenisProduk as $index => $jenis)
                    <tr>
                        <td class="ps-3 py-3 text-muted">
                            {{ $jenisProduk->firstItem() + $index }}
                        </td>

                        <td class="fw-semibold text-dark">
                            <a href="{{ route('produk.index', ['jenis_id' => $jenis->id]) }}" class="text-decoration-none text-dark">
                                <i class="bi bi-tag me-1 text-primary"></i> {{ $jenis->nama }}
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('produk.index', ['jenis_id' => $jenis->id]) }}" class="text-decoration-none">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                                    {{ $jenis->produk_count }} Produk
                                </span>
                            </a>
                        </td>

                        <td class="pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                @if($isAdmin)
                                    <a href="{{ route('jenis-produk.edit', $jenis) }}" class="btn btn-warning btn-sm text-white shadow-sm rounded-2" title="Edit Jenis">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('jenis-produk.destroy', $jenis) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus jenis ini? Produk yang memakai jenis ini tidak akan ikut terhapus, hanya jadi tanpa jenis.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm shadow-sm rounded-2" title="Hapus Jenis">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="bi bi-tags fs-1 text-muted opacity-50"></i>
                            <h6 class="mt-3 text-muted">Belum ada jenis produk.</h6>
                            @if($isAdmin)
                                <a href="{{ route('jenis-produk.create') }}" class="btn btn-sm btn-primary rounded-3 mt-2">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Jenis Produk Pertama
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-4 mt-3">
            <small class="text-muted mb-2 mb-md-0">
                Menampilkan total <strong>{{ $jenisProduk->total() }}</strong> jenis produk
            </small>
            <div>
                {{ $jenisProduk->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
