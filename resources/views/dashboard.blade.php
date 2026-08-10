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
    
    <!-- Bagian Kanan Header: Tanggal, Jam Realtime, & Lonceng Notifikasi Interaktif -->
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

        <!-- Lonceng Notifikasi Interaktif -->
        <!-- <div class="position-relative">
            <button class="btn btn-white bg-white rounded-circle position-relative border p-0 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" type="button" id="notifDropdownBtn">
                <i class="bi bi-bell text-secondary fs-6"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" id="notifBadge">
                    <span class="visually-hidden">Notifikasi baru</span>
                </span>
            </button> -->
            
            <!-- Elemen Menu Notifikasi Tersembunyi untuk di-clone/posisikan secara mutlak via JS -->
            <!-- <div id="notifMenuTemplate" style="display: none;">
                <div class="dropdown-menu shadow-lg border-0 rounded-4 py-2 show" style="width: 320px; background-color: #ffffff;">
                    <div class="px-3 py-2 border-bottom d-flex align-items-center justify-content-between bg-white rounded-top-4">
                        <strong class="text-dark small">Notifikasi Sistem</strong>
                        <span class="badge bg-primary bg-opacity-10 text-primary small">3 Baru</span>
                    </div>
                    <a class="dropdown-item px-3 py-2.5 text-wrap border-bottom bg-white" href="#">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle text-warning fs-5 mt-1"></i>
                            <div>
                                <p class="mb-0 small fw-semibold text-dark">Stok Aqua Botol Menipis</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Sisa stok tinggal 20 unit di gudang.</span>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item px-3 py-2.5 text-wrap border-bottom bg-white" href="#">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-bag-check text-success fs-5 mt-1"></i>
                            <div>
                                <p class="mb-0 small fw-semibold text-dark">Transaksi Berhasil</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Penjualan senilai Rp 150.000 sukses dicatat.</span>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item px-3 py-2.5 text-wrap bg-white" href="#">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-person-plus text-primary fs-5 mt-1"></i>
                            <div>
                                <p class="mb-0 small fw-semibold text-dark">User Baru Ditambahkan</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Akun kasir baru berhasil didaftarkan.</span>
                            </div>
                        </div>
                    </a>
                    <div class="px-3 pt-2 text-center border-top mt-1 bg-white rounded-bottom-4">
                        <a href="#" class="text-decoration-none small fw-semibold text-primary" id="markAllRead">Tandai semua dibaca</a>
                    </div>
                </div>
            </div> -->
        <!-- </div> -->
    </div>
</div>

<!-- Statistik Kartu Ringkasan -->
<div class="row g-4 mb-4">
    @if($isAdmin)
    <!-- Penjualan Hari Ini (khusus Admin: data finansial toko) -->
    <div class="col-sm-6 col-xl-3 animate-card-1">
        <div class="card h-100 p-4 card-hover-up position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 h-100 bg-primary" style="width: 4px;"></div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-semibold text-uppercase">Penjualan Hari Ini</span>
                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</h4>
            <div class="progress mb-2" style="height: 4px; background-color: #E2E8F0;">
                <div class="progress-bar bg-primary" style="width: 75%;"></div>
            </div>
            <span class="text-muted small fw-medium d-flex align-items-center gap-1">
                <i class="bi bi-check-circle"></i> Transaksi berstatus selesai
            </span>
        </div>
    </div>
    @endif

    @php
        // Saat card Penjualan Hari Ini disembunyikan (non-admin), 3 card sisanya
        // melebar rata (xl-4) supaya grid tidak menyisakan ruang kosong.
        $statCardCol = $isAdmin ? 'col-sm-6 col-xl-3' : 'col-sm-6 col-xl-4';
    @endphp

    <!-- Total Transaksi -->
    <div class="{{ $statCardCol }} animate-card-2">
        <div class="card h-100 p-4 card-hover-up position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 h-100 bg-success" style="width: 4px;"></div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-semibold text-uppercase">Total Transaksi</span>
                <div class="bg-success bg-opacity-10 text-success p-2 rounded-3">
                    <i class="bi bi-bag-check fs-5"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">{{ $ringkasan['total_transaksi'] }} <span class="fs-6 fw-normal text-muted">Order</span></h4>
            <div class="progress mb-2" style="height: 4px; background-color: #E2E8F0;">
                <div class="progress-bar bg-success" style="width: 60%;"></div>
            </div>
            <span class="text-muted small fw-medium d-flex align-items-center gap-1">
                <i class="bi bi-check-circle"></i> Transaksi berstatus selesai
            </span>
        </div>
    </div>

    <!-- Stok Menipis -->
    <div class="{{ $statCardCol }} animate-card-3">
        <div class="card h-100 p-4 card-hover-up position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 h-100 bg-warning" style="width: 4px;"></div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-semibold text-uppercase">Stok Menipis</span>
                <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                    <i class="bi bi-exclamation-triangle fs-5"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">{{ $stokMenipis }} <span class="fs-6 fw-normal text-muted">Produk</span></h4>
            <div class="progress mb-2" style="height: 4px; background-color: #E2E8F0;">
                <div class="progress-bar bg-warning" style="width: 30%;"></div>
            </div>
            <span class="text-muted small fw-medium">Segera lakukan restock</span>
        </div>
    </div>

    <!-- Total Produk -->
    <div class="{{ $statCardCol }} animate-card-4">
        <div class="card h-100 p-4 card-hover-up position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 h-100 bg-info" style="width: 4px;"></div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted small fw-semibold text-uppercase">Total Produk</span>
                <div class="bg-info bg-opacity-10 text-info p-2 rounded-3">
                    <i class="bi bi-box-seam fs-5"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">{{ $totalProduk }} <span class="fs-6 fw-normal text-muted">Item</span></h4>
            <div class="progress mb-2" style="height: 4px; background-color: #E2E8F0;">
                <div class="progress-bar bg-info" style="width: 90%;"></div>
            </div>
            <span class="text-info small fw-medium">Aktif dalam sistem</span>
        </div>
    </div>
</div>

<!-- Bagian Bawah: Produk Terlaris & Card Aksi Kasir -->
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
                                <div class="d-flex align-items-center gap-3">
                                    @if($produk->foto)
                                        <button type="button" 
                                                class="btn btn-link p-0 text-decoration-none flex-shrink-0" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#productImageModal" 
                                                data-image="{{ asset('storage/' . $produk->foto) }}" 
                                                data-name="{{ $produk->nama }}">
                                            <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="rounded-3" style="width: 38px; height: 38px; object-fit: cover;">
                                        </button>
                                    @else
                                        <div class="product-icon bg-light rounded-3 p-2 text-primary d-flex align-items-center justify-content-center transition-all flex-shrink-0" style="width: 38px; height: 38px;">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                    @endif
                                    
                                    <span class="fw-semibold text-dark">{{ $produk->nama }}</span>
                                </div>
                            </td>
                            <td>{{ $produk->total_terjual }} Pcs</td>
                            <td>
                                @php
                                    $badgeClass = $produk->stok == 0 ? 'bg-danger' : ($produk->stok <= 5 ? 'bg-warning' : 'bg-success');
                                @endphp
                                <span class="badge {{ $badgeClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $badgeClass) }} px-2.5 py-1.5">{{ $produk->stok }} Unit</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Belum ada transaksi selesai hari ini.
                            </td>
                        </tr>
                        
                        @endforelse
                  </tbody>
              </table>
          </div>
        </div>
    </div>

    <!-- Quick Actions / Tombol POS -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 text-white position-relative overflow-hidden d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);">
            <div class="position-absolute top-0 end-0 p-4 opacity-10">
                <i class="bi bi-cart3 display-1"></i>
            </div>
            
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-white bg-opacity-25 text-white px-2.5 py-1 rounded-pill small">Kasir Cepat</span>
                    <i class="bi bi-lightning-charge-fill text-warning fs-5"></i>
                </div>
                <h4 class="fw-bold mb-2">Mulai Transaksi Baru</h4>
                <p class="text-white-50 small mb-4">Lakukan pencatatan penjualan barang secara cepat, akurat, dan efisien langsung melalui halaman kasir POS.</p>
            </div>

            <div class="bg-white bg-opacity-10 rounded-3 p-3 border border-white border-opacity-10 mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-white-50 small d-block">Total Hari Ini</span>
                        <strong class="fs-5 text-white">{{ $ringkasan['total_transaksi'] }} Transaksi</strong>
                    </div>
                    <div class="bg-white bg-opacity-20 text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
        </div>

            <div>
                <a href="{{ route('penjualan.index') }}" class="btn btn-light text-primary fw-semibold w-100 py-3 shadow-sm rounded-3 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-plus-circle fs-5"></i> Buka Kasir POS
                </a>
            </div>
        </div>
   </div>
</div>

<!-- Peringatan Stok: Habis & Menipis -->
<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-x-circle text-danger"></i> Stok Habis
            </h6>
            @forelse($produkStokHabis as $produk)
                <div class="d-flex align-items-center justify-content-between py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <span class="small fw-medium text-dark">{{ $produk->nama }}</span>
                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">0 Unit</span>
                </div>
            @empty
                <p class="text-muted small mb-0">Tidak ada produk yang stoknya habis.</p>
            @endforelse
        </div>
   </div>

   <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle text-warning"></i> Stok Menipis
            </h6>
            @forelse($produkStokRendah as $produk)
                <div class="d-flex align-items-center justify-content-between py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <span class="small fw-medium text-dark">{{ $produk->nama }}</span>
                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">{{ $produk->stok }} Unit</span>
                </div>
            @empty
                <p class="text-muted small mb-0">Tidak ada produk dengan stok menipis.</p>
            @endforelse
        </div>
   </div>
</div>

<!-- ========================================== -->
<!-- MODAL HTML UNTUK PREVIEW FOTO PRODUK       -->
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

<!-- Script Jam Realtime & Logika Floating Popup Notifikasi -->
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

    // Logika Pop-up Notifikasi Melayang Bebas (Floating Above All)
    document.addEventListener("DOMContentLoaded", function() {
        const notifBtn = document.getElementById('notifDropdownBtn');
        const notifBadge = document.getElementById('notifBadge');
        const notifTemplate = document.getElementById('notifMenuTemplate');
        let activePopup = null;
        let closeTimer = null;

        // Logika Modal Preview Foto Produk
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

        if(notifBtn && notifTemplate) {
            notifBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Jika popup sudah ada, tutup (toggle)
                if (activePopup) {
                    activePopup.remove();
                    activePopup = null;
                    clearTimeout(closeTimer);
                    return;
                }

                // Buat elemen popup baru yang melayang sejajar pas di bawah ikon lonceng
                const rect = notifBtn.getBoundingClientRect();
                const popup = document.createElement('div');
                popup.style.position = 'fixed';
                popup.style.top = (rect.bottom + 8) + 'px';
                popup.style.left = (rect.right - 320) + 'px'; // Lebar pop-up 320px disamakan agar rata kanan dengan tombol
                popup.style.zIndex = '99999999';
                popup.innerHTML = notifTemplate.innerHTML;

                document.body.appendChild(popup);
                activePopup = popup;

                // Hilangkan badge merah saat dibuka
                if(notifBadge) notifBadge.style.display = 'none';

                // Tombol "Tandai semua dibaca" di dalam popup
                const markAll = popup.querySelector('#markAllRead');
                if(markAll) {
                    markAll.addEventListener('click', function(ev) {
                        ev.preventDefault();
                        if(activePopup) {
                            activePopup.remove();
                            activePopup = null;
                        }
                        clearTimeout(closeTimer);
                    });
            }

            // Auto-close setelah 6 detik
            clearTimeout(closeTimer);
            closeTimer = setTimeout(() => {
                if (activePopup) {
                    activePopup.remove();
                    activePopup = null;
                }
            }, 6000);
        });

        // Tutup jika klik di luar area popup
        document.addEventListener('click', function(e) {
            if (activePopup && !activePopup.contains(e.target) && !notifBtn.contains(e.target)) {
                activePopup.remove();
                activePopup = null;
                clearTimeout(closeTimer);
            }
        });
    }
    });
</script>
@endsection