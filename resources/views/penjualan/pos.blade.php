@extends('layouts.app')

@section('title', 'Kasir POS - POS SMART')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="text-primary fw-semibold small text-uppercase tracking-wider">Point of Sale</span>
            <h3 class="fw-bold text-dark mb-1">Kasir Transaksi Penjualan</h3>
            <p class="text-muted small mb-0">Pilih produk di sebelah kiri untuk dimasukkan ke dalam keranjang kasir.</p>
        </div>
        <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary shadow-sm rounded-3 py-2">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="row g-4">
        {{-- BAGIAN KIRI: DAFTAR PRODUK & PENCARIAN --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">Daftar Produk</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                        Total Tersedia: {{ $totalProdukCount }} Produk
                    </span>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="productSearchInput" value="{{ request('search') }}" class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="Cari nama produk secara langsung..." autocomplete="off">
                    </div>
                </div>

                <div id="product-grid-container" class="product-list-container pe-1" style="max-height: 55vh; overflow-y: auto;">
                    @include('penjualan.partials.product-grid', ['products' => $products, 'sale' => $sale])
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: KERANJANG BELANJA & CHECKOUT --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column justify-content-between p-4">
                <div>
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-cart3 text-primary"></i> Keranjang Belanja
                    </h5>
                    
                    <div class="table-responsive mb-3" style="max-height: 38vh; overflow-y: auto;">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="table-light text-uppercase fs-7 text-muted">
                                <tr>
                                    <th class="rounded-start-2">Produk</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th class="rounded-end-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sale->itemPenjualan as $item)
                                <tr class="border-bottom">
                                    <td class="py-2">
                                        @if($item->produk)
                                            <strong class="text-dark d-block text-truncate" style="max-width: 120px;">{{ $item->produk->nama }}</strong>
                                            <small class="text-muted" style="font-size: 0.75rem;">Rp {{ number_format($item->produk->harga_jual, 0, ',', '.') }}</small>
                                        @else
                                            <span class="text-danger fst-italic small">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Produk ini sudah tidak tersedia
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        <span class="badge bg-light text-dark border px-2.5 py-1 fw-bold">
                                            {{ $item->kuantitas }}
                                        </span>
                                    </td>
                                    <td class="py-2 fw-semibold text-success small">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 text-center" width="10%">
                                        <form id="delete-item-form-{{ $item->id }}" method="POST" action="{{ route('itempenjualan.destroy', $item->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-light btn-sm border text-danger shadow-none" 
                                                    style="transition: all 0.2s;"
                                                    onmouseover="this.style.backgroundColor='#fee2e2';" 
                                                    onmouseout="this.style.backgroundColor='#f8f9fa';"
                                                    title="Hapus Item"
                                                    onclick="openDeleteModal('{{ $item->id }}', 'Apakah anda yakin akan menghapus item ini dari keranjang?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">Keranjang masih kosong</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer Keranjang & Checkout --}}
                <div class="bg-light p-3 rounded-4 border">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted fw-semibold">Total Pembayaran:</span>
                        <h4 class="fw-bold text-success mb-0">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</h4>
                    </div>
                    
                    {{-- Form Checkout --}}
                    <form id="checkoutForm" method="POST" action="{{ route('penjualan.update', $sale->id) }}">
                        @csrf
                        @method('PUT')
                        <select name="payment_method" id="paymentMethodSelect" class="form-select mb-2 rounded-3 shadow-none" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="CASH" {{ $sale->metode_pembayaran == 'CASH' ? 'selected' : '' }}>Cash (Tunai)</option>
                            <option value="QRIS" {{ $sale->metode_pembayaran == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            <option value="BAYAR_NANTI" {{ $sale->metode_pembayaran == 'BAYAR_NANTI' ? 'selected' : '' }}>Bayar Nanti (Pending)</option>
                        </select>

                        <button type="button" id="checkoutBtn" class="btn btn-success w-100 py-2.5 rounded-3 fw-semibold shadow-sm {{ $sale->status == 'COMPLETED' ? 'disabled' : '' }}" onclick="openCustomConfirm('checkout')">
                            <i class="bi bi-check-circle me-1"></i> <span id="checkoutBtnText">Checkout & Selesaikan</span>
                        </button>
                    </form>

                    {{-- Form Batal Transaksi --}}
                    <form id="cancelTransactionForm" method="POST" action="{{ route('penjualan.destroy', $sale->id) }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger w-100 py-2 rounded-3 small {{ $sale->status == 'COMPLETED' ? 'disabled' : '' }}" onclick="openCustomConfirm('cancel')">
                            <i class="bi bi-x-circle me-1"></i> Batal Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Kustom di Tengah (Checkout, Batal Transaksi, & Hapus Item) --}}
<div class="modal fade" id="posConfirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg animate-page">
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

    document.addEventListener("DOMContentLoaded", function() {
        // --- Live Search Produk ---
        const searchInput = document.getElementById('productSearchInput');
        const gridContainer = document.getElementById('product-grid-container');
        let searchTimeout = null;

        if(searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                let keyword = this.value;

                searchTimeout = setTimeout(function() {
                    let currentUrl = window.location.pathname;
                    
                    fetch(`${currentUrl}?search=${keyword}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        gridContainer.innerHTML = data.html;
                    })
                    .catch(error => console.error('Error:', error));
                }, 300);
            });
        }

        // --- Logika Dinamis Tombol Checkout ---
        const paymentSelect = document.getElementById('paymentMethodSelect');
        const checkoutBtnText = document.getElementById('checkoutBtnText');

        function updateCheckoutUI() {
            if (paymentSelect && paymentSelect.value === 'BAYAR_NANTI') {
                checkoutBtnText.innerText = 'Simpan & Bayar Nanti';
            } else if (checkoutBtnText) {
                checkoutBtnText.innerText = 'Checkout & Selesaikan';
            }
        }

        if (paymentSelect) {
            paymentSelect.addEventListener('change', updateCheckoutUI);
            updateCheckoutUI(); // Panggil saat halaman pertama kali dimuat
        }
    });

    // Fungsi untuk memunculkan Modal Konfirmasi di Tengah (Checkout & Batal Transaksi)
    function openCustomConfirm(type) {
        activeActionType = type;
        const paymentSelect = document.getElementById('paymentMethodSelect');
        
        let titleEl = document.getElementById('posModalTitle');
        let iconEl = document.getElementById('posModalIcon');
        let msgEl = document.getElementById('posModalMessage');
        let confirmBtn = document.getElementById('posModalConfirmBtn');
        let cancelBtn = document.getElementById('posCancelBtn');

        confirmBtn.disabled = false;
        if (cancelBtn) cancelBtn.disabled = false;

        if (type === 'checkout') {
            if (!paymentSelect.value) {
                alert('Silakan pilih metode pembayaran terlebih dahulu!');
                paymentSelect.focus();
                return;
            }

            let method = paymentSelect.value;
            let isBayarNanti = (method === 'BAYAR_NANTI');

            titleEl.innerHTML = `<i class="bi bi-check-circle-fill me-2 text-success"></i> Konfirmasi Checkout`;
            iconEl.innerHTML = `<i class="bi bi-cart-check text-success"></i>`;
            msgEl.innerText = isBayarNanti 
                ? 'Apakah Anda yakin ingin menyimpan transaksi ini dengan metode Bayar Nanti?' 
                : 'Apakah Anda yakin ingin menyelesaikan transaksi ini?';
            
            confirmBtn.className = 'btn btn-success px-4 rounded-3 shadow-sm';
            confirmBtn.innerText = 'Ya, Selesaikan';
        } else if (type === 'cancel') {
            titleEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> Konfirmasi Pembatalan`;
            iconEl.innerHTML = `<i class="bi bi-trash text-danger"></i>`;
            msgEl.innerText = 'Yakin ingin membatalkan transaksi ini? Semua item di keranjang akan dihapus.';
            
            confirmBtn.className = 'btn btn-danger px-4 rounded-3 shadow-sm';
            confirmBtn.innerText = 'Ya, Batalkan';
        }

        var myModal = new bootstrap.Modal(document.getElementById('posConfirmModal'));
        myModal.show();
    }

    // Fungsi khusus untuk memunculkan Modal Hapus Item di Keranjang
    function openDeleteModal(identifier, message) {
        activeActionType = 'delete_item';
        activeDeleteFormId = 'delete-item-form-' + identifier;

        let titleEl = document.getElementById('posModalTitle');
        let iconEl = document.getElementById('posModalIcon');
        let msgEl = document.getElementById('posModalMessage');
        let confirmBtn = document.getElementById('posModalConfirmBtn');
        let cancelBtn = document.getElementById('posCancelBtn');

        confirmBtn.disabled = false;
        if (cancelBtn) cancelBtn.disabled = false;

        titleEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> Konfirmasi Hapus`;
        iconEl.innerHTML = `<i class="bi bi-trash text-danger"></i>`;
        msgEl.innerText = message;
        
        confirmBtn.className = 'btn btn-danger px-4 rounded-3 shadow-sm';
        confirmBtn.innerText = 'Ya, Hapus';

        var myModal = new bootstrap.Modal(document.getElementById('posConfirmModal'));
        myModal.show();
    }

    // Submit form ketika tombol konfirmasi di dalam modal diklik
    document.getElementById('posModalConfirmBtn').addEventListener('click', function() {
        let btn = this;
        let cancelBtn = document.getElementById('posCancelBtn');
        
        btn.disabled = true;
        if (cancelBtn) cancelBtn.disabled = true;

        if (activeActionType === 'checkout') {
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...`;
            document.getElementById('checkoutForm').submit();
        } else if (activeActionType === 'cancel') {
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Membatalkan...`;
            document.getElementById('cancelTransactionForm').submit();
        } else if (activeActionType === 'delete_item') {
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...`;
            if (activeDeleteFormId) {
                document.getElementById(activeDeleteFormId).submit();
            }
        }
    });
</script>
@endsection