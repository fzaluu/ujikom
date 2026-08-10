@extends('layouts.app')

@section('title', 'Detail Produk - Aplikasi POS')

@section('content')
@php
    $isAdmin = auth()->check() && (optional(auth()->user()->role)->name === 'admin' || auth()->user()->role_id == 1);
@endphp
<div class="container-fluid px-0">
    <div class="card shadow-sm border-0 rounded-4 col-lg-10 mx-auto">
        
        {{-- Header --}}
        <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-card-list text-primary me-2"></i> Detail Produk
                </h3>
                <p class="text-muted small mb-0">Informasi lengkap data barang dan inventaris toko.</p>
            </div>
            
            <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali
            </a>
        </div>

        {{-- Body --}}
        <div class="card-body p-4">
            <div class="row g-4 align-items-center">
                
                {{-- Kolom Foto Produk --}}
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-light text-center p-2">
                        @if($produk->foto)
                            <img src="{{ asset('storage/' . $produk->foto) }}" class="img-fluid rounded-3 w-100 shadow-sm" alt="{{ $produk->nama }}" style="height: 320px; object-fit: cover;">
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="height: 320px;">
                                <i class="bi bi-image fs-1 mb-2"></i>
                                <span class="small">Tidak ada foto produk</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Kolom Informasi Rinci --}}
                <div class="col-md-7">
                    <div class="card border-0 bg-light bg-opacity-50 rounded-4 h-100">
                        <div class="card-body p-4">
                            <h3 class="card-title text-dark fw-bold mb-1">{{ $produk->nama }}</h3>
                            <div class="mb-3">
                                @if($produk->jenisProduk)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">{{ $produk->jenisProduk->nama }}</span>
                                @else
                                    <span class="text-muted small">Tanpa jenis</span>
                                @endif
                            </div>
                            <hr class="text-muted opacity-25">
                            
                            <div class="row g-3 mb-3">
                                @if($isAdmin)
                                    <div class="col-6">
                                        <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Harga Beli</div>
                                        <div class="fw-semibold text-secondary">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</div>
                                    </div>
                                @endif
                                <div class="{{ $isAdmin ? 'col-6' : 'col-12' }}">
                                    <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Harga Jual</div>
                                    <div class="fw-bold text-success fs-5">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Stok Tersedia</div>
                                    <div>
                                        <span class="badge {{ $produk->stok > 5 ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }} px-2 py-1">
                                            {{ $produk->stok }} Unit
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Ditambahkan Oleh</div>
                                    <div class="fw-semibold text-dark">
                                        <span class="badge bg-white text-dark border px-2 py-1">
                                            <i class="bi bi-person me-1"></i> {{ $produk->user->name ?? 'Admin' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Dibuat Pada</div>
                                    <div class="small text-muted">{{ $produk->created_at->translatedFormat('d F Y H:i') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Terakhir Diupdate</div>
                                    <div class="small text-muted">{{ $produk->updated_at->translatedFormat('d F Y H:i') }}</div>
                                </div>
                            </div>

                            {{-- Tombol Aksi Bawah (Edit hanya muncul jika Admin) --}}
                            <div class="d-flex flex-column flex-sm-row gap-2 pt-2">
                                @if($isAdmin)
                                    <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning text-white px-4 shadow-sm">
                                        <i class="bi bi-pencil-square me-1"></i> Edit Produk
                                    </a>
                                @endif
                                <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
                                    <i class="bi bi-list-ul me-1"></i> Daftar Produk
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection