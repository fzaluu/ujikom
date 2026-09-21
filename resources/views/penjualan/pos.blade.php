@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="container-fluid px-0">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <span class="text-primary fw-semibold small text-uppercase tracking-wider">Point of Sale</span>
            <h3 class="fw-bold text-dark mb-1">Transaksi Penjualan</h3>
            <p class="text-muted small mb-0">Pilih produk di sebelah kiri untuk dimasukkan ke dalam keranjang.</p>
        </div>
        <!-- <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary shadow-sm rounded-3 px-3 py-2" onclick="markExplicitAction()">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Riwayat
        </a> -->
    </div>

    <div class="row g-4">
        {{-- BAGIAN KIRI: DAFTAR PRODUK --}}
        <div class="col-lg-7 align-self-start" >
            <div class="card border-0 shadow-sm rounded-4 ">
                <div class="card-body p-4 pb-3 d-flex flex-column">
                    <div class="mb-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Daftar Produk
                            </h6>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                {{ $totalProdukCount ?? 0 }} Produk
                            </span>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text"
                                       id="productSearchInput"
                                       value="{{ request('search') }}"
                                       class="form-control bg-light border-start-0 ps-0 shadow-none rounded-end-3"
                                       placeholder="Cari nama produk..."
                                       autocomplete="off">
                            </div>
                        </div>

                        {{-- Kotak list produk --}}
                        <div id="product-grid-container" class="product-list-container pe-1 ">
                            @include('penjualan.partials.product-grid', ['products' => $products, 'sale' =>$sale])
                        </div>
                    </div>

                    {{-- BAGIAN BAWAH: PAGINATION --}}
                    <div class="mt-3 pt-2 border-top d-flex justify-content-center">
                        <div class="pos-pagination-wrapper">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: KERANJANG & CHECKOUT --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column">
                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-cart3 text-primary"></i> Keranjang Belanja
                    </h5>

                    <div class="table-responsive mb-3 flex-grow-1" style="max-height: 36vh; overflow-y: auto;">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="table-light text-uppercase small text-muted">
                                <tr>
                                    <th class="rounded-start-3 ps-3">Produk</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th class="rounded-end-3 text-center pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sale->itemPenjualan as $item)
                                <tr class="border-bottom">
                                    <td class="py-2 ps-3">
                                        @if($item->produk)
                                            <strong class="text-dark d-block text-truncate" style="max-width: 140px;">
                                                {{ $item->nama_produk ?? optional($item->produk)->nama ?? 'Produk Dihapus' }}
                                            </strong>
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                            </small>
                                        @else
                                            <span class="text-danger fst-italic small">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Produk tidak tersedia
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        <span class="badge bg-light text-dark border px-2 py-1 fw-bold">
                                            {{ $item->kuantitas }}
                                        </span>
                                    </td>
                                    <td class="py-2 fw-semibold text-success small">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 text-center pe-3" width="10%">
                                        <form id="delete-item-form-{{ $item->id }}" method="POST" action="{{ route('itempenjualan.destroy', $item->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-light btn-sm border text-danger shadow-none rounded-circle"
                                                    style="width: 32px; height: 32px;"
                                                    title="Hapus Item"
                                                    onclick="openDeleteModal('{{ $item->id }}', 'Apakah Anda yakin ingin menghapus item ini dari keranjang?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x display-6 d-block mb-2 opacity-50"></i>
                                        <span class="small">Keranjang masih kosong</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer Checkout --}}
                    <div class="bg-light p-3 rounded-4 border mt-auto">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted fw-semibold">Total Pembayaran</span>
                            <h4 class="fw-bold text-success mb-0">
                                Rp {{ number_format($sale->total_pembayaran ?? 0, 0, ',', '.') }}
                            </h4>
                        </div>

                        <form id="checkoutForm" method="POST" action="{{ $sale->exists ? route('penjualan.update', $sale->id) : '#' }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted mb-1">Metode Pembayaran</label>
                                <select name="payment_method" id="paymentMethodSelect" class="form-select rounded-3 shadow-none" required>
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <option value="CASH" {{ ($sale->metode_pembayaran ?? '') == 'CASH' ? 'selected' : '' }}>Cash (Tunai)</option>
                                    <option value="QRIS" {{ ($sale->metode_pembayaran ?? '') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                    <option value="BAYAR_NANTI" {{ ($sale->metode_pembayaran ?? '') == 'BAYAR NANTI' || ($sale->metode_pembayaran ?? '') == 'BAYAR_NANTI' ? 'selected' : '' }}>BAYAR NANTI</option>
                                </select>
                            </div>

                            {{-- QRIS --}}
                            <div id="qrisContainer" class="mb-3 text-center d-none">
                                <div class="card p-3 border-0 bg-white shadow-sm rounded-3">
                                    <p class="fw-bold small text-dark mb-2">Scan QRIS untuk Pembayaran</p>
                                    <img src="{{ asset('images/qrcode.jpeg') }}" alt="QRIS Code" class="img-fluid rounded mx-auto" style="max-width: 160px;">
                                    <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem;">Gunakan E-Wallet / M-Banking</p>
                                </div>
                            </div>

                            {{-- Cash Input --}}
                            <div id="cashContainer" class="mb-3 d-none">
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted mb-1">Uang Tunai dari Pelanggan</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 rounded-start-3">Rp</span>
                                        <input type="text"
                                               name="uang_dibayar"
                                               id="inputUangDibayar"
                                               class="form-control border-start-0 rounded-end-3 shadow-none"
                                               placeholder="Contoh: 50.000"
                                               autocomplete="off"
                                               inputmode="numeric">
                                    </div>
                                    <div id="uangError" class="text-danger small mt-1 d-none"></div>
                                </div>
                                <div class="p-2 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                                    <span class="small fw-semibold text-muted">Uang Kembalian</span>
                                    <span id="textKembalian" class="fw-bold text-success fs-6">Rp 0</span>
                                </div>
                            </div>

                            {{-- Form Input Bayar Nanti (Langsung Tampil di Sini, Tanpa Pop-up) --}}
                            <div id="bayarNantiContainer" class="mb-3">
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted mb-1">Nama Pelanggan </label>
                                    <input type="text" name="customer_name" id="inputCustomerName" class="form-control form-control-sm rounded-3 shadow-none" placeholder="Masukkan nama pelanggan" value="{{ $sale->customer_name ?? '' }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted mb-1">No. HP / WhatsApp </label>
                                    <input type="text" name="customer_phone" id="inputCustomerPhone" class="form-control form-control-sm rounded-3 shadow-none" placeholder="Contoh: 08123456789" value="{{ $sale->customer_phone ?? '' }}">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small fw-bold text-muted mb-1">Tanggal Jatuh Tempo</label>
                                    <input type="date" name="due_date" id="inputDueDate" class="form-control form-control-sm rounded-3 shadow-none" value="{{ $sale->due_date ?? '' }}">
                                </div>
                            </div>

                            <input type="hidden" name="kembalian" id="inputHiddenKembalian" value="0">

                            <button type="button"
                                    id="checkoutBtn"
                                    class="btn btn-success w-100 py-2.5 rounded-3 fw-semibold shadow-sm {{ ($sale->status ?? '') == 'COMPLETED' ? 'disabled' : '' }}"
                                    onclick="openCustomConfirm('checkout')">
                                <i class="bi bi-check-circle me-1"></i>
                                <span id="checkoutBtnText">Checkout & Selesaikan</span>
                            </button>
                        </form>

                        @if($sale->exists)
                            <form id="cancelTransactionForm" method="POST" action="{{ route('penjualan.destroy', $sale->id) }}" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="btn btn-outline-danger w-100 py-2 rounded-3 small {{ ($sale->status ?? '') == 'COMPLETED' ? 'disabled' : '' }}"
                                        onclick="openCustomConfirm('cancel')">
                                    <i class="bi bi-x-circle me-1"></i> Batal Transaksi
                                </button>
                            </form>
                        @else
                            <a href="{{ route('penjualan.index') }}"
                               class="btn btn-outline-danger w-100 py-2 rounded-3 small mt-2 text-decoration-none text-center"
                               onclick="markExplicitAction()">
                                <i class="bi bi-x-circle me-1"></i> Batal Transaksi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Styling Pagination POS & Scroll --}}
<style>
    .pos-pagination-wrapper {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
        padding-bottom: 2px;
        text-align: center;
    }
    .pos-pagination-wrapper nav {
        display: inline-block;
    }
    .pos-pagination-wrapper .pagination {
        font-size: 0.75rem;
        margin-bottom: 0;
        display: inline-flex;
        gap: 2px;
    }
    .pos-pagination-wrapper .page-link {
        padding: 0.2rem 0.5rem;
        color: #0d6efd;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }
    .pos-pagination-wrapper .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
</style>

{{-- Modal Konfirmasi Umum --}}
<div class="modal fade" id="posConfirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="posModalTitle">
                    <i class="bi bi-question-circle-fill me-2 text-primary"></i> Konfirmasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div id="posModalIcon" class="display-4 mb-3"></div>
                <p id="posModalMessage" class="text-dark fs-6 mb-0">Apakah Anda yakin?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4 gap-2">
                <button type="button" class="btn btn-light px-4 rounded-3 shadow-none border" data-bs-dismiss="modal" id="posCancelBtn">Batal</button>
                <button type="button" id="posModalConfirmBtn" class="btn px-4 rounded-3 shadow-sm">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script>
    let activeActionType = null;
    let activeDeleteFormId = null;
    let isExplicitAction = false;
    let posModalObj = null;
    let pendingRedirectUrl = null;

    const TOTAL_BELANJA = {{ (float) ($sale->total_pembayaran ?? 0) }};
    const HAS_ITEMS = {{ ($sale->itemPenjualan->count() > 0) ? 'true' : 'false' }};
    const SALE_ID = "{{ $sale->id ?? '' }}";

    function markExplicitAction() {
        isExplicitAction = true;
    }

    function parseUang(value) {
        if (!value) return 0;
        const cleaned = String(value).replace(/\./g, '').replace(/[^\d]/g, '');
        return parseFloat(cleaned) || 0;
    }

    function getPosModal() {
        const modalEl = document.getElementById('posConfirmModal');
        if (!posModalObj) {
            posModalObj = bootstrap.Modal.getOrCreateInstance(modalEl);
        }
        return posModalObj;
    }

    function showErrorModal(mainMessage, subMessage) {
        const titleEl = document.getElementById('posModalTitle');
        const iconEl  = document.getElementById('posModalIcon');
        const msgEl   = document.getElementById('posModalMessage');
        const confirmBtn = document.getElementById('posModalConfirmBtn');
        const cancelBtn  = document.getElementById('posCancelBtn');

        titleEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> Peringatan`;
        iconEl.innerHTML  = `<i class="bi bi-exclamation-circle text-danger"></i>`;
        msgEl.innerHTML   = `${mainMessage}<br><small class="text-muted">${subMessage}</small>`;

        confirmBtn.className = 'btn btn-danger px-4 rounded-3 shadow-sm';
        confirmBtn.innerText = 'Mengerti';

        if (cancelBtn) cancelBtn.classList.add('d-none');
        getPosModal().show();
    }

    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById('productSearchInput');
        const gridContainer = document.getElementById('product-grid-container');
        let searchTimeout = null;

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                const keyword = this.value;

                searchTimeout = setTimeout(function () {
                    fetch(`${window.location.pathname}?search=${encodeURIComponent(keyword)}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        gridContainer.innerHTML = data.html;
                    })
                    .catch(err => console.error('Search error:', err));
                }, 300);
            });
        }

        const paymentSelect        = document.getElementById('paymentMethodSelect');
        const qrisContainer        = document.getElementById('qrisContainer');
        const cashContainer        = document.getElementById('cashContainer');
        const bayarNantiContainer  = document.getElementById('bayarNantiContainer');
        const inputUangDibayar     = document.getElementById('inputUangDibayar');
        const textKembalian        = document.getElementById('textKembalian');
        const inputHiddenKembalian = document.getElementById('inputHiddenKembalian');
        const checkoutBtnText      = document.getElementById('checkoutBtnText');
        const uangError            = document.getElementById('uangError');

        function updatePaymentUI() {
            if (!paymentSelect) return;

            const method = paymentSelect.value;

            qrisContainer?.classList.add('d-none');
            cashContainer?.classList.add('d-none');
            bayarNantiContainer?.classList.add('d-none');
            uangError?.classList.add('d-none');

            if (method === 'QRIS') {
                qrisContainer?.classList.remove('d-none');
                if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
            } else if (method === 'CASH') {
                cashContainer?.classList.remove('d-none');
                if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
                hitungKembalian();
            } else if (method === 'BAYAR_NANTI') {
                bayarNantiContainer?.classList.remove('d-none');
                if (checkoutBtnText) checkoutBtnText.innerText = 'Simpan & Bayar Nanti';
            } else {
                if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
            }
        }

        function hitungKembalian() {
            if (!inputUangDibayar || !textKembalian) return;

            const uangBayar = parseUang(inputUangDibayar.value);
            const kembalian = uangBayar - TOTAL_BELANJA;

            if (uangBayar <= 0) {
                textKembalian.innerText = 'Rp 0';
                textKembalian.className = 'fw-bold text-muted fs-6';
                if (inputHiddenKembalian) inputHiddenKembalian.value = 0;
            } else if (kembalian >= 0) {
                textKembalian.innerText = 'Rp ' + kembalian.toLocaleString('id-ID');
                textKembalian.className = 'fw-bold text-success fs-6';
                if (inputHiddenKembalian) inputHiddenKembalian.value = kembalian;
            } else {
                textKembalian.innerText = 'Uang Kurang (Rp ' + Math.abs(kembalian).toLocaleString('id-ID') + ')';
                textKembalian.className = 'fw-bold text-danger fs-6';
                if (inputHiddenKembalian) inputHiddenKembalian.value = 0;
            }
        }

        if (inputUangDibayar) {
            inputUangDibayar.addEventListener('input', function () {
                let cleaned = this.value.replace(/[^\d]/g, '');
                if (cleaned !== "") {
                    this.value = Number(cleaned).toLocaleString('id-ID');
                } else {
                    this.value = "";
                }
                hitungKembalian();
            });
        }

        if (paymentSelect) {
            paymentSelect.addEventListener('change', updatePaymentUI);
            updatePaymentUI();
        }

        // INTERCEPT NAVIGASI: Jika ada produk di keranjang dan user klik menu lain, munculkan modal!
        if (HAS_ITEMS && SALE_ID) {
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    
                    // Abaikan anchor kosong, tombol modal, link internal/javascript, atau tombol di dalam form POS
                    if (!href || href.startsWith('#') || href.startsWith('javascript') || this.hasAttribute('data-bs-toggle') || this.classList.contains('no-intercept') || this.closest('form')) {
                        return;
                    }

                    e.preventDefault();
                    pendingRedirectUrl = href;
                    openLeavePageModal();
                });
            });
        }
    });

    function openLeavePageModal() {
        activeActionType = 'leave_page';
        const titleEl = document.getElementById('posModalTitle');
        const iconEl  = document.getElementById('posModalIcon');
        const msgEl   = document.getElementById('posModalMessage');
        const confirmBtn = document.getElementById('posModalConfirmBtn');
        const cancelBtn  = document.getElementById('posCancelBtn');

        confirmBtn.disabled = false;
        if (cancelBtn) {
            cancelBtn.disabled = false;
            cancelBtn.classList.remove('d-none');
        }

        titleEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i> Perhatian: Keranjang Aktif`;
        iconEl.innerHTML  = `<i class="bi bi-cart-dash text-warning"></i>`;
        msgEl.innerText   = 'Masih ada produk di dalam keranjang! Jika Anda meninggalkan halaman ini, transaksi akan dibatalkan dan stok dikembalikan. Tetap tinggalkan halaman?';

        confirmBtn.className = 'btn btn-danger px-4 rounded-3 shadow-sm';
        confirmBtn.innerText = 'Ya, Tinggalkan & Batalkan';

        getPosModal().show();
    }

    function openCustomConfirm(type) {
        activeActionType = type;
        const paymentSelect = document.getElementById('paymentMethodSelect');
        const titleEl   = document.getElementById('posModalTitle');
        const iconEl    = document.getElementById('posModalIcon');
        const msgEl     = document.getElementById('posModalMessage');
        const confirmBtn = document.getElementById('posModalConfirmBtn');
        const cancelBtn  = document.getElementById('posCancelBtn');

        confirmBtn.disabled = false;
        if (cancelBtn) {
            cancelBtn.disabled = false;
            cancelBtn.classList.remove('d-none');
        }

        if (type === 'checkout') {
            if (!paymentSelect || !paymentSelect.value) {
                showErrorModal('Perhatian', 'Silakan pilih metode pembayaran terlebih dahulu!');
                paymentSelect?.focus();
                return;
            }

            const method = paymentSelect.value;

            if (method === 'CASH') {
                const inputUang = document.getElementById('inputUangDibayar');
                const uangBayar = parseUang(inputUang?.value);
                if (uangBayar < TOTAL_BELANJA) {
                    showErrorModal('Perhatian', 'Uang tunai dari pelanggan kurang dari total pembayaran!');
                    inputUang?.focus();
                    return;
                }
            }

            if (method === 'BAYAR_NANTI') {
                const nameInput  = document.getElementById('inputCustomerName').value.trim();
                const phoneInput = document.getElementById('inputCustomerPhone').value.trim();
                const dateInput  = document.getElementById('inputDueDate').value;

                if (!nameInput || !phoneInput || !dateInput) {
                    showErrorModal('Form Belum Lengkap', 'Nama Pelanggan, No. HP, dan Tanggal Jatuh Tempo wajib diisi untuk Bayar Nanti!');
                    return;
                }
            }

            titleEl.innerHTML = `<i class="bi bi-check-circle-fill me-2 text-success"></i> Konfirmasi Checkout`;
            iconEl.innerHTML  = `<i class="bi bi-cart-check text-success"></i>`;
            msgEl.innerText   = (method === 'BAYAR_NANTI')
                ? 'Apakah Anda yakin ingin menyimpan transaksi Bayar Nanti ini?'
                : 'Apakah Anda yakin ingin menyelesaikan transaksi ini?';

            confirmBtn.className = 'btn btn-success px-4 rounded-3 shadow-sm';
            confirmBtn.innerText = 'Ya, Selesaikan';
        } else if (type === 'cancel') {
            titleEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> Konfirmasi Pembatalan`;
            iconEl.innerHTML  = `<i class="bi bi-trash text-danger"></i>`;
            msgEl.innerText   = 'Yakin ingin membatalkan transaksi ini? Semua item di keranjang akan dihapus.';
            confirmBtn.className = 'btn btn-danger px-4 rounded-3 shadow-sm';
            confirmBtn.innerText = 'Ya, Batalkan';
        }

        getPosModal().show();
    }

    function openDeleteModal(identifier, message) {
        activeActionType = 'delete_item';
        activeDeleteFormId = 'delete-item-form-' + identifier;

        const titleEl   = document.getElementById('posModalTitle');
        const iconEl    = document.getElementById('posModalIcon');
        const msgEl     = document.getElementById('posModalMessage');
        const confirmBtn = document.getElementById('posModalConfirmBtn');
        const cancelBtn  = document.getElementById('posCancelBtn');

        confirmBtn.disabled = false;
        if (cancelBtn) cancelBtn.classList.remove('d-none');

        titleEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> Konfirmasi Hapus`;
        iconEl.innerHTML  = `<i class="bi bi-trash text-danger"></i>`;
        msgEl.innerText   = message;
        confirmBtn.className = 'btn btn-danger px-4 rounded-3 shadow-sm';
        confirmBtn.innerText = 'Ya, Hapus';

        getPosModal().show();
    }

    document.getElementById('posModalConfirmBtn').addEventListener('click', function () {
        if (this.innerText === 'Mengerti') {
            getPosModal().hide();
            return;
        }

        isExplicitAction = true;
        const btn = this;
        const cancelBtn = document.getElementById('posCancelBtn');
        btn.disabled = true;
        if (cancelBtn) cancelBtn.disabled = true;

        if (activeActionType === 'checkout') {
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...`;
            const paymentSelect = document.getElementById('paymentMethodSelect');
            if (paymentSelect?.value === 'CASH') {
                const inputUang = document.getElementById('inputUangDibayar');
                const hiddenKembalian = document.getElementById('inputHiddenKembalian');
                const uangBayar = parseUang(inputUang?.value);
                if (inputUang) inputUang.value = uangBayar;
                if (hiddenKembalian) hiddenKembalian.value = Math.max(0, uangBayar - TOTAL_BELANJA);
            }
            document.getElementById('checkoutForm').submit();
        } else if (activeActionType === 'cancel') {
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Membatalkan...`;
            document.getElementById('cancelTransactionForm').submit();
        } else if (activeActionType === 'delete_item') {
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Menghapus...`;
            if (activeDeleteFormId) document.getElementById(activeDeleteFormId)?.submit();
        } else if (activeActionType === 'leave_page') {
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Membatalkan...`;
            
            // Menggunakan navigator.sendBeacon agar request penghapusan terkirim stabil di background tanpa konflik modal
            const destroyUrl = `{{ url('penjualan') }}/${SALE_ID}`;
            navigator.sendBeacon(destroyUrl, new URLSearchParams({
                '_token': '{{ csrf_token() }}',
                '_method': 'DELETE'
            }));

            // Beri jeda singkat agar modal tertutup mulus lalu pindah halaman
            setTimeout(() => {
                window.location.href = pendingRedirectUrl;
            }, 250);
        }
    });

    // Background cleanup jika tab browser ditutup total
    window.addEventListener('beforeunload', function (e) {
        if (isExplicitAction) return;
        if (HAS_ITEMS && SALE_ID) {
            const url = `{{ url('penjualan') }}/${SALE_ID}`;
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');
            navigator.sendBeacon(url, formData);
        }
    });
</script>
@endsection