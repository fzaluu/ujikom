@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container-fluid px-0">
    {{-- Pembungkus utama struk dengan ID #area-struk --}}
    <div id="area-struk" class="card shadow-sm border-0 rounded-4 col-lg-10 mx-auto p-4 bg-white">
        
        <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
            <div>
                <span class="text-primary fw-semibold small text-uppercase tracking-wider">Riwayat Kasir</span>
                <h3 class="fw-bold text-dark mb-1">Detail Penjualan</h3>
                <p class="text-muted small mb-0">Informasi lengkap transaksi dan rincian pembayaran.</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="cetakStruk()" class="btn btn-primary shadow-sm rounded-3 py-2">
                    <i class="bi bi-printer me-1"></i> Cetak Struk
                </button>
                <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary shadow-sm rounded-3 py-2">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                </a>
            </div>
        </div>

        {{-- Struk Cetak (Format E-Receipt Toko, hanya tampil saat print) --}}
        <div id="struk-print" class="d-none d-print-block">
            <div class="struk-header text-center">
                <div class="struk-logo">
                    <i class="bi bi-shop"></i>
                </div>
                <h2 class="struk-brand">RAJA CELL</h2>
                {{-- Data toko berikut masih placeholder, silakan sesuaikan dengan data asli --}}
                <p class="struk-address mb-0">Jl. Contoh Alamat No. 123, Kota Anda</p>
                <p class="struk-address mb-0">No. Telp 0812-0000-0000</p>
            </div>

            <div class="struk-divider-dashed"></div>

            <div class="struk-meta">
                <div class="struk-meta-row">
                    <span>{{ $sale->created_at->format('Y-m-d') }}</span>
                    <span>{{ optional($sale->user)->name ?? 'Admin' }}</span>
                </div>
                <div class="struk-meta-row">
                    <span>{{ $sale->created_at->format('H:i:s') }}</span>
                    <span></span>
                </div>
                <div class="struk-meta-row">
                    <span>No. {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</span>
                    <span></span>
                </div>
            </div>

            <div class="struk-divider-dashed"></div>

            <div class="struk-items">
                @php $totalQty = 0; @endphp
                @forelse($sale->itemPenjualan as $item)
                    @php $totalQty += $item->kuantitas; @endphp
                    <div class="struk-item-name">{{ $loop->iteration }}. {{ $item->nama_produk ?? optional($item->produk)->nama ?? 'Produk Tidak Diketahui' }}</div>
                    <div class="struk-item-detail">
                        <span>{{ $item->kuantitas }} x {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                        <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-center mb-0">Tidak ada item pada transaksi ini.</p>
                @endforelse
            </div>

            <div class="struk-divider-dashed"></div>

            <div class="struk-meta-row">
                <span>Total QTY</span>
                <span>: {{ $totalQty }}</span>
            </div>

            <div class="struk-divider-dashed"></div>

            <div class="struk-totals">
                <div class="struk-meta-row">
                    <span>Sub Total</span>
                    <span>Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</span>
                </div>
                <div class="struk-meta-row struk-total-grand">
                    <span>Total</span>
                    <span>Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</span>
                </div>
                @if($sale->metode_pembayaran === 'CASH')
                <div class="struk-meta-row">
                    <span>Bayar (Cash)</span>
                    <span>Rp {{ number_format($sale->uang_dibayar ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="struk-meta-row">
                    <span>Kembali</span>
                    <span>Rp {{ number_format($sale->kembalian ?? 0, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>

            <div class="struk-footer text-center">
                <p class="mb-2">Terimakasih Telah Berbelanja</p>
            </div>

            {{-- Placeholder link kritik & saran, silakan ganti dengan link asli jika ada --}}
            <div class="struk-feedback-box text-center">
                <p class="mb-1">Link Kritik dan Saran:</p>
                <p class="mb-0">rajacell.com/e-receipt/{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <div class="card border-0 bg-light bg-opacity-50 rounded-4 p-4 mb-4 d-print-none">
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
                            <i class="bi bi-person me-1"></i> {{ optional($sale->user)->name ?? 'Admin' }}
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
                    <div>
                        @if($sale->metode_pembayaran === 'CASH')
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-1">
                                <i class="bi bi-cash-stack"></i> Cash (Tunai)
                            </span>
                        @elseif($sale->metode_pembayaran === 'QRIS')
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-1">
                                <i class="bi bi-qr-code-scan"></i> QRIS
                            </span>
                        @elseif($sale->metode_pembayaran === 'BAYAR_NANTI')
                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-1">
                                <i class="bi bi-clock-history"></i> Bayar Nanti (Pending)
                            </span>
                        @else
                            <span class="text-muted fw-semibold">-</span>
                        @endif
                    </div>
                </div> 

                @if($sale->metode_pembayaran === 'CASH')
                    <div class="col-md-6">
                        <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Uang Tunai Dibayar</div>
                        <div class="fw-semibold text-dark">Rp {{ number_format($sale->uang_dibayar ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-uppercase text-muted fs-7 fw-semibold mb-1">Uang Kembalian</div>
                        <div class="fw-semibold text-success">Rp {{ number_format($sale->kembalian ?? 0, 0, ',', '.') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-0 overflow-hidden mb-4 d-print-none">
            <div class="card-header bg-white border-0 p-3 pb-0">
                <h5 class="fw-bold text-dark mb-0">Daftar Item Produk yang Dibeli</h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-muted">
                            <tr>
                                <th width="5%" class="py-3 ps-3 rounded-start">No</th>
                                <th class="py-3">Nama Produk</th>
                                <th class="py-3">Harga Satuan</th>
                                <th class="py-3">Jumlah</th>
                                <th class="py-3 pe-3 rounded-end text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sale->itemPenjualan as $item)
                            <tr>
                                <td class="ps-3 py-3 text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold text-dark">
                                    {{ $item->nama_produk ?? optional($item->produk)->nama ?? 'Produk Tidak Diketahui' }}
                                    
                                    @if(is_null($item->produk_id) || !$item->produk)
                                        <span class="badge bg-danger bg-opacity-10 text-danger ms-2 px-2 py-0.5" style="font-size: 0.7rem;">
                                            <i class="bi bi-exclamation-circle me-1"></i> Produk Telah Dihapus
                                        </span>
                                    @endif
                                </td>
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
                            @if($sale->metode_pembayaran === 'CASH')
                            <tr>
                                <th colspan="4" class="text-end py-2">Tunai:</th>
                                <th class="text-end py-2 text-dark fs-6 pe-3">Rp {{ number_format($sale->uang_dibayar ?? 0, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end py-2">Kembalian:</th>
                                <th class="text-end py-2 text-success fs-6 pe-3">Rp {{ number_format($sale->kembalian ?? 0, 0, ',', '.') }}</th>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if($sale->status === 'OPEN')
            <div class="mt-4 pt-3 border-top d-print-none">
                <a href="{{ route('penjualan.edit', $sale->id) }}" 
                   id="btnSelesaikanBayar"
                   class="btn w-100 py-3 rounded-4 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 text-white position-relative overflow-hidden text-decoration-none" 
                   style="background: linear-gradient(135deg, #059669 0%, #10B981 100%); transition: all 0.2s ease;"
                   onclick="handleLoading(this)">
                    <i class="bi bi-cart-check-fill fs-5" id="btnIcon"></i> 
                    <span id="btnText">Selesaikan Pembayaran</span>
                </a>
            </div>
        @endif

    </div>
</div>

{{-- STRUK: Tampilan e-receipt toko saat dicetak --}}
<style>
    #struk-print {
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        color: #111827;
        font-size: 17px;
        line-height: 1.55;
    }

    .struk-logo {
        font-size: 3rem;
        color: #111827;
        margin-bottom: 6px;
    }

    .struk-brand {
        font-weight: 700;
        margin: 0 0 6px;
        font-size: 2rem;
        color: #111827;
    }

    .struk-address {
        font-size: 15px;
        color: #111827;
    }

    .struk-divider-dashed {
        border-top: 2px dashed #9ca3af;
        margin: 14px 0;
    }

    .struk-meta-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
    }

    .struk-item-name {
        font-weight: 700;
        margin-top: 8px;
    }

    .struk-item-detail {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding-left: 16px;
    }

    .struk-total-grand {
        font-weight: 700;
        font-size: 19px;
    }

    .struk-footer {
        font-size: 17px;
        margin-top: 14px;
    }

    .struk-feedback-box {
        border: 2px solid #16a34a;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 15px;
        margin-top: 12px;
    }

    @media print {
        /* Sembunyikan sidebar, navbar, dan seluruh elemen bawaan aplikasi */
        .sidebar-pos, .mobile-bottom-nav, .sidebar-toggle-btn, nav, aside, header, footer, .d-print-none {
            display: none !important;
        }

        /* Paksa body bersih tanpa background abu-abu */
        body, html, .container-fluid {
            background-color: white !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* Ukuran default/fallback kalau JS di bawah gagal jalan.
           Nilai sebenarnya akan ditimpa oleh #page-size-dinamis lewat cetakStruk(). */
        @page {
            size: 100mm 200mm;
            margin: 6mm;
        }

        #area-struk {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: white !important;
        }

        /* Lebar mengikuti area cetak halaman (lihat @page size) supaya kertas
           ikut menyusut mengikuti ukuran struk, bukan struk kecil di kertas A4 besar. */
        #struk-print {
            width: 100%;
            margin: 0 auto;
        }
    }
</style>

<script>
    function handleLoading(element) {
        element.style.pointerEvents = 'none';
        element.style.opacity = '0.85';
        
        const icon = document.getElementById('btnIcon');
        const text = document.getElementById('btnText');
        
        if (icon) icon.className = 'spinner-border spinner-border-sm me-2';
        if (text) text.textContent = 'Memuat Halaman Kasir...';
    }

    // Menghitung tinggi struk asli lalu mengatur ukuran kertas cetak (@page) persis
    // sesuai tinggi itu, supaya tidak ada sisa kertas kosong yang panjang saat print.
    // Catatan: printer virtual seperti "Microsoft Print to PDF" kadang tetap memaksa
    // ukuran kertas standar (Letter/A4) karena keterbatasan drivernya sendiri, di luar
    // kendali kode ini. Printer nota/thermal asli umumnya mendukung ukuran custom ini.
    function cetakStruk() {
        const strukEl = document.getElementById('struk-print');
        let styleEl = document.getElementById('page-size-dinamis');

        if (!styleEl) {
            styleEl = document.createElement('style');
            styleEl.id = 'page-size-dinamis';
            document.head.appendChild(styleEl);
        }

        if (strukEl) {
            const tinggiPx = strukEl.scrollHeight;
            // 1px = 25.4/96 mm, ditambah sedikit ruang ekstra untuk margin cetak
            const tinggiMm = Math.ceil((tinggiPx * 25.4) / 96) + 15;

            styleEl.innerHTML = `
                @media print {
                    @page {
                        size: 100mm ${tinggiMm}mm;
                        margin: 6mm;
                    }
                }
            `;
        }

        window.print();
    }
</script>
@endsection