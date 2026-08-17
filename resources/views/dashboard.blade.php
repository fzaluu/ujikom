@extends('layouts.app')

@section('title', 'Dashboard - POS SMART')

@section('content')
@php
    $hour = date('H');
    $greeting = 'Selamat Pagi';
    if ($hour >= 12 && $hour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat Sore';
    } elseif ($hour >= 18 || $hour < 4) {
        $greeting = 'Selamat Malam';
    }
    $userName = auth()->user()->name ?? 'Admin';
@endphp

<!-- CSS Animasi & Perbaikan Tata Letak -->
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-card-1 { animation: fadeInUp 0.4s ease 0.05s forwards; opacity: 0; }
    .animate-card-2 { animation: fadeInUp 0.4s ease 0.1s forwards; opacity: 0; }
    .animate-card-3 { animation: fadeInUp 0.4s ease 0.15s forwards; opacity: 0; }
    .animate-card-4 { animation: fadeInUp 0.4s ease 0.2s forwards; opacity: 0; }
    .animate-section-bottom { animation: fadeInUp 0.4s ease 0.25s forwards; opacity: 0; }

    /* Hover Interaktif Produk Terlaris */
    .table-hover-custom tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .table-hover-custom tbody tr:hover {
        background-color: #F8FAFC;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .table-hover-custom tbody tr:hover .product-icon {
        background-color: #2563EB !important;
        color: #ffffff !important;
    }
</style>

<!-- Header Sambutan & Utilitas -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2 animate-card-1">
    <div>
        <span class="text-primary fw-semibold small text-uppercase tracking-wider">Dashboard Overview</span>
        <h2 class="fw-bold text-dark mb-1">{{ $greeting }}, {{ $userName }} 👋</h2>
        <p class="text-muted mb-0">Berikut adalah ringkasan performa dan aktivitas toko Anda hari ini.</p>
    </div>
    
    <!-- Bagian Kanan Header: Tanggal & Jam Realtime -->
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Tanggal Hari Ini -->
        <span class="badge bg-white text-dark shadow-sm px-3 py-2.5 rounded-pill border fw-normal d-flex align-items-center gap-2">
            <i class="bi bi-calendar-event text-primary"></i> 
            <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </span>

        <!-- Jam Realtime -->
        <div class="d-flex align-items-center gap-2 text-muted bg-white px-3 py-2 rounded-pill border shadow-sm small">
            <i class="bi bi-clock text-primary"></i>
            <span id="realtime-clock" class="fw-semibold text-dark">00:00:00</span>
        </div>
    </div>
</div>

<!-- Statistik Kartu Ringkasan -->
<div class="row g-4 mb-4">
    @php
        // Hitung persentase real untuk progress bar
        $targetTransaksiHarian = 20; // Kamu bisa ubah angka target harian sesuai kebutuhan toko
        $persenTransaksi = min(100, ($ringkasan['total_transaksi'] / max(1, $targetTransaksiHarian)) * 100);
        
        $persenStokMenipis = $totalProduk > 0 ? min(100, ($stokMenipis / $totalProduk) * 100) : 0;
        
        // Asumsi total produk ideal di toko adalah 50 item untuk progress bar total produk
        $targetKapasitasProduk = 50; 
        $persenTotalProduk = min(100, ($totalProduk / $targetKapasitasProduk) * 100);

        // Untuk admin (finansial): asumsi target omset harian Rp 1.000.000
        $targetOmsetHarian = 1000000; 
        $persenOmset = min(100, ($ringkasan['total_penjualan'] / max(1, $targetOmsetHarian)) * 100);

        $statCardCol = $isAdmin ? 'col-sm-6 col-xl-3' : 'col-sm-6 col-xl-4';
    @endphp

    @if($isAdmin)
    <!-- Penjualan Hari Ini (khusus Admin) -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 p-4 border-0 shadow-sm rounded-4 position-relative overflow-hidden bg-white" style="transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';">
            <div class="position-absolute top-0 start-0 h-100 bg-primary" style="width: 5px;"></div>
            
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-bold text-uppercase tracking-wider">Penjualan Hari Ini</span>
                <div class="bg-primary bg-opacity-10 text-primary p-2.5 rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
            </div>
            
            <h4 class="fw-bold text-dark mb-3">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</h4>
            
            <div class="progress mb-2 rounded-pill" style="height: 6px; background-color: #F1F5F9;">
                <div class="progress-bar bg-primary rounded-pill" style="width: {{ $persenOmset }}%;"></div>
            </div>
            
            <span class="text-muted small fw-medium d-flex align-items-center gap-1">
                <i class="bi bi-check-circle text-primary"></i> Transaksi berstatus selesai
            </span>
        </div>
    </div>
    @endif

    <!-- Total Transaksi -->
    <div class="{{ $statCardCol }}">
        <div class="card h-100 p-4 border-0 shadow-sm rounded-4 position-relative overflow-hidden bg-white" style="transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';">
            <div class="position-absolute top-0 start-0 h-100 bg-success" style="width: 5px;"></div>
            
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-bold text-uppercase tracking-wider">Total Transaksi</span>
                <div class="bg-success bg-opacity-10 text-success p-2.5 rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-bag-check fs-5"></i>
                </div>
            </div>
            
            <h4 class="fw-bold text-dark mb-3">{{ $ringkasan['total_transaksi'] }} <span class="fs-6 fw-normal text-muted">Order</span></h4>
            
            <div class="progress mb-2 rounded-pill" style="height: 6px; background-color: #F1F5F9;">
                <div class="progress-bar bg-success rounded-pill" style="width: {{ $persenTransaksi }}%;"></div>
            </div>
            
            <span class="text-muted small fw-medium d-flex align-items-center gap-1">
                <i class="bi bi-check-circle text-success"></i> Transaksi berstatus selesai
            </span>
        </div>
    </div>

    <!-- Stok Menipis -->
    <div class="{{ $statCardCol }}">
        <div class="card h-100 p-4 border-0 shadow-sm rounded-4 position-relative overflow-hidden bg-white" style="transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';">
            <div class="position-absolute top-0 start-0 h-100 bg-warning" style="width: 5px;"></div>
            
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-bold text-uppercase tracking-wider">Stok Menipis</span>
                <div class="bg-warning bg-opacity-10 text-warning p-2.5 rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-exclamation-triangle fs-5"></i>
                </div>
            </div>
            
            <h4 class="fw-bold text-dark mb-3">{{ $stokMenipis }} <span class="fs-6 fw-normal text-muted">Produk</span></h4>
            
            <div class="progress mb-2 rounded-pill" style="height: 6px; background-color: #F1F5F9;">
                <div class="progress-bar bg-warning rounded-pill" style="width: {{ $persenStokMenipis }}%;"></div>
            </div>
            
            <span class="text-muted small fw-medium d-flex align-items-center gap-1">
                <i class="bi bi-info-circle text-warning"></i> Segera lakukan restock
            </span>
        </div>
    </div>

    <!-- Total Produk -->
    <div class="{{ $statCardCol }}">
        <div class="card h-100 p-4 border-0 shadow-sm rounded-4 position-relative overflow-hidden bg-white" style="transition: all 0.25s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';">
            <div class="position-absolute top-0 start-0 h-100 bg-info" style="width: 5px;"></div>
            
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-bold text-uppercase tracking-wider">Total Produk</span>
                <div class="bg-info bg-opacity-10 text-info p-2.5 rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-box-seam fs-5"></i>
                </div>
            </div>
            
            <h4 class="fw-bold text-dark mb-3">{{ $totalProduk }} <span class="fs-6 fw-normal text-muted">Item</span></h4>
            
            <div class="progress mb-2 rounded-pill" style="height: 6px; background-color: #F1F5F9;">
                <div class="progress-bar bg-info rounded-pill" style="width: {{ $persenTotalProduk }}%;"></div>
            </div>
            
            <span class="text-info small fw-medium d-flex align-items-center gap-1">
                <i class="bi bi-check-circle"></i> Aktif dalam sistem
            </span>
        </div>
    </div>
</div>

<!-- Bagian Bawah: Produk Terlaris & Card Aksi Kasir (Mulai Transaksi) -->
<div class="row g-4 animate-section-bottom">
    <!-- Produk Terlaris -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-graph-up-arrow text-primary"></i> Produk Terlaris
                </h5>
                <a href="{{ route('produk.index') }}" class="text-decoration-none small fw-semibold text-primary">Lihat Semua</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover-custom align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 ps-3 rounded-start-3">Produk</th>
                            <th class="py-3">Terjual</th>
                            <th class="py-3 pe-3 rounded-end-3">Stok Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produkTerlaris as $produk)
                        <tr>
                            <td class="fw-semibold text-dark py-3 ps-3">
                                <div class="align-items-center gap-3 d-flex">
                                    @if($produk->foto)
                                        <button type="button" 
                                                class="btn btn-link p-0 text-decoration-none flex-shrink-0" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#productImageModal" 
                                                data-image="{{ asset('storage/' . $produk->foto) }}" 
                                                data-name="{{ $produk->nama }}"
                                                title="Klik untuk preview foto">
                                            <img src="{{ asset('storage/' . $produk->foto) }}" 
                                                alt="{{ $produk->nama }}" 
                                                class="rounded-3 shadow-sm border" 
                                                style="width: 40px; height: 40px; object-fit: cover; transition: transform 0.2s;" 
                                                onmouseover="this.style.transform='scale(1.08)';" 
                                                onmouseout="this.style.transform='scale(1)';">
                                        </button>
                                    @else
                                        <div class="product-icon bg-light rounded-3 p-2 text-primary d-flex align-items-center justify-content-center transition-all flex-shrink-0 border" style="width: 40px; height: 40px;">
                                            <i class="bi bi-box-seam fs-5"></i>
                                        </div>
                                    @endif
                                    
                                    <span class="fw-semibold text-dark text-truncate" style="max-width: 220px;">{{ $produk->nama }}</span>
                                </div>
                            </td>
                            <td class="fw-semibold text-secondary">{{ $produk->total_terjual }} Pcs</td>
                            <td>
                                @php
                                    if ($produk->stok == 0) {
                                        $badgeColor = 'danger'; // Merah (Stok Habis)
                                    } elseif ($produk->stok <= 5) {
                                        $badgeColor = 'warning'; // Kuning (Stok Menipis)
                                    } elseif ($produk->stok > 100) {
                                        $badgeColor = 'info'; // Biru (Stok di atas 100)
                                    } else {
                                        $badgeColor = 'success'; // Hijau (Stok Normal aman)
                                    }
                                @endphp
                                <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} px-2.5 py-1.5 fw-semibold">{{ $produk->stok }} Unit</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada transaksi selesai hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions / Tombol POS (Mulai Transaksi Baru) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 text-white position-relative overflow-hidden d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%);">
            <div class="position-absolute top-0 end-0 p-4 opacity-10 pointer-events-none">
                <i class="bi bi-cart3 display-1"></i>
            </div>
            
            <div class="position-relative z-1">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-white bg-opacity-25 text-white px-3 py-1.5 rounded-pill small fw-semibold">Kasir Cepat</span>
                    <!-- <div class="bg-warning bg-opacity-25 text-warning p-2 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div> -->
                </div>
                <h4 class="fw-bold mb-2 text-white">Mulai Transaksi Baru</h4>
                <p class="text-white-50 small mb-4 lh-base">
                    Catat penjualan barang secara cepat, akurat, dan langsung terhubung ke manajemen stok sistem POS.
                </p>
            </div>

            <!-- Card Informasi Ringkas di Dalam Box POS -->
            <div class="position-relative z-1 bg-white bg-opacity-10 rounded-3 p-3 border border-white border-opacity-10 mb-4 backdrop-blur">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-white-50 small d-block mb-1">Transaksi Berhasil</span>
                        <strong class="fs-5 text-white">{{ $ringkasan['total_transaksi'] }} Order Hari Ini</strong>
                    </div>
                    <div class="position-relative d-flex align-items-center justify-content-center">
                        <!-- Efek Glow / Lingkaran cahaya tipis di belakang (dikecilkan blurnya) -->
                        <div class="position-absolute rounded-circle bg-white opacity-25" style="width: 45px; height: 45px; filter: blur(2px);"></div>
                        
                        <!-- Lingkaran Utama dengan Icon Warna Putih Pekat -->
                        <div class="bg-white bg-opacity-25 text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm position-relative border border-white border-opacity-50" style="width: 44px; height: 44px; backdrop-filter: blur(8px);">
                            <i class="bi bi-graph-up-arrow fs-5 text-white fw-bold" style="opacity: 1 !important;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Utama Pemicu ke Halaman Penjualan/POS -->
            <div class="position-relative z-1">
                <a href="{{ route('penjualan.index') }}" class="btn btn-light text-primary fw-bold w-100 py-3 shadow rounded-3 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-plus-circle-fill fs-5"></i> Buka Kasir POS
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Peringatan Stok: Habis & Menipis -->
<div class="row g-4 mt-1">
    {{-- Kolom Stok Habis --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    Stok Habis
                </h6>
                <span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1 rounded-pill small">
                    {{ count($produkStokHabis ?? []) }} Item
                </span>
            </div>

            <div class="pe-1" style="max-height: 250px; overflow-y: auto;">
                @forelse($produkStokHabis as $produk)
                    <div class="d-flex align-items-center justify-content-between py-2.5 px-2 rounded-3 {{ !$loop->last ? 'border-bottom border-light' : '' }}" style="transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                        <div class="d-flex align-items-center gap-3">
                            {{-- Foto Produk dengan Tombol Trigger Modal Preview --}}
                            @if($produk->foto)
                                <button type="button" 
                                        class="btn btn-link p-0 text-decoration-none border-0 bg-transparent" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#productImageModal" 
                                        data-image="{{ asset('storage/' . $produk->foto) }}" 
                                        data-name="{{ $produk->nama }}"
                                        title="Klik untuk preview foto">
                                    <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="rounded-3 shadow-sm object-fit-cover border" style="width: 40px; height: 40px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                                </button>
                            @else
                                <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                    <i class="bi bi-image fs-6"></i>
                                </div>
                            @endif

                            <div>
                                <span class="small fw-semibold text-dark d-block text-truncate" style="max-width: 180px;">{{ $produk->nama }}</span>
                                <small class="text-muted" style="font-size: 0.75rem;">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</small>
                            </div>
                        </div>
                        <span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1 fw-bold">0 Unit</span>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle fs-3 text-success opacity-75 mb-2 d-block"></i>
                        <p class="small mb-0">Aman! Tidak ada produk yang stoknya habis.</p>
                    </div>
                @endforelse
            </div>
        </div>
   </div>

   {{-- Kolom Stok Menipis --}}
   <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    Stok Menipis
                </h6>
                <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1 rounded-pill small">
                    {{ count($produkStokRendah ?? []) }} Item
                </span>
            </div>

            <div class="pe-1" style="max-height: 250px; overflow-y: auto;">
                @forelse($produkStokRendah as $produk)
                    <div class="d-flex align-items-center justify-content-between py-2.5 px-2 rounded-3 {{ !$loop->last ? 'border-bottom border-light' : '' }}" style="transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                        <div class="d-flex align-items-center gap-3">
                            {{-- Foto Produk dengan Tombol Trigger Modal Preview --}}
                            @if($produk->foto)
                                <button type="button" 
                                        class="btn btn-link p-0 text-decoration-none border-0 bg-transparent" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#productImageModal" 
                                        data-image="{{ asset('storage/' . $produk->foto) }}" 
                                        data-name="{{ $produk->nama }}"
                                        title="Klik untuk preview foto">
                                    <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="rounded-3 shadow-sm object-fit-cover border" style="width: 40px; height: 40px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                                </button>
                            @else
                                <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                    <i class="bi bi-image fs-6"></i>
                                </div>
                            @endif

                            <div>
                                <span class="small fw-semibold text-dark d-block text-truncate" style="max-width: 180px;">{{ $produk->nama }}</span>
                                <small class="text-muted" style="font-size: 0.75rem;">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</small>
                            </div>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1 fw-bold">{{ $produk->stok }} Unit</span>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle fs-3 text-success opacity-75 mb-2 d-block"></i>
                        <p class="small mb-0">Aman! Semua stok produk dalam kondisi cukup.</p>
                    </div>
                @endforelse
            </div>
        </div>
   </div>
</div>

<!-- ========================================== -->
<!-- MODAL HTML UNTUK PREVIEW FOTO PRODUK      -->
<!-- ========================================== -->
<div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="productImageModalLabel">Preview Foto Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img src="" id="productImageModalSrc" class="img-fluid rounded-3 shadow-sm mb-3" alt="Foto Produk" style="max-height: 400px; object-fit: contain;">
                <h6 id="productImageModalName" class="fw-semibold text-dark mb-0"></h6>
            </div>
        </div>
    </div>
</div>

<!-- Script Jam Realtime & Logika Modal Preview -->
<script>
    // Jam Realtime
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const clockEl = document.getElementById('realtime-clock');
        if(clockEl) {
            clockEl.textContent = `${hours}:${minutes}:${seconds}`;
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Logika Modal Preview Foto Produk
    document.addEventListener("DOMContentLoaded", function() {
        var productImageModal = document.getElementById('productImageModal');
        if (productImageModal) {
            productImageModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var imageSrc = button.getAttribute('data-image');
                var imageName = button.getAttribute('data-name');

                var modalTitle = productImageModal.querySelector('.modal-title');
                var modalImage = document.getElementById('productImageModalSrc');
                var modalName = document.getElementById('productImageModalName');

                modalTitle.textContent = 'Preview Foto Produk';
                modalImage.src = imageSrc;
                modalImage.alt = imageName;
                modalName.textContent = imageName;
            });
        }
    });
</script>
@endsection