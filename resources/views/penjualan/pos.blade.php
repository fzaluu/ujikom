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
                <div class="mb-3">
                    <form method="GET" action="{{ route('penjualan.create') }}">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0 ps-0" placeholder="Cari nama produk..." onkeyup="this.form.submit()">
                        </div>
                    </form>
                </div>

                <div class="product-list-container pe-1" style="max-height: 60vh; overflow-y: auto;">
                    @foreach($products as $product)
                    <form method="POST" action="{{ route('itempenjualan.store') }}" class="row g-2 align-items-center mb-2 p-2 border rounded-3 bg-white shadow-sm transition-all hover-shadow">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="col-7">
                            <button type="submit" class="btn btn-link text-decoration-none text-start p-0 w-100 {{ $sale->status == 'COMPLETED' ? 'disabled' : '' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $product->foto ? asset('storage/' . $product->foto) : 'https://via.placeholder.com/45' }}" alt="Gambar" class="rounded-3 shadow-sm flex-shrink-0" style="width: 45px; height: 45px; object-fit:cover">
                                    <div class="overflow-hidden">
                                        <div class="fw-semibold text-dark text-truncate">{{ $product->nama }}</div>
                                        <small class="text-success fw-bold">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div class="col-3">
                            <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm rounded-2" {{ $sale->status == 'COMPLETED' ? 'readonly' : '' }}>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-2 {{ $sale->status == 'COMPLETED' ? 'disabled' : '' }}" title="Tambah ke Keranjang">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </form>
                    @endforeach
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
                                    <!-- <th>Qty</th> -->
                                    <th>Subtotal</th>
                                    <th class="rounded-end-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sale->itemPenjualan as $item)
                                <tr class="border-bottom">
                                    <td class="py-2">
                                        <strong class="text-dark d-block text-truncate" style="max-width: 130px;">{{ $item->produk->nama }}</strong>
                                        <small class="text-muted" style="font-size: 0.75rem;">Rp {{ number_format($item->produk->harga_jual, 0, ',', '.') }}</small>
                                    </td>
                                    <!-- <td class="py-2" width="22%">
                                        <form method="POST" action="{{ route('itempenjualan.update', $item->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->kuantitas }}" min="1" class="form-control form-control-sm text-center" onchange="this.form.submit()">
                                        </form>
                                    </td> -->
                                    <td class="py-2 fw-semibold text-success small">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 text-center" width="10%">
                                        <form method="POST" action="{{ route('itempenjualan.destroy', $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-link text-danger p-0" title="Hapus Item" onclick="return confirm('Hapus item ini?')">
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
                    <form method="POST" action="{{ route('penjualan.update', $sale->id) }}" onsubmit="return confirm('Yakin ingin menyelesaikan transaksi ini?')">
                        @csrf
                        @method('PUT')
                        <select name="payment_method" class="form-select mb-2 rounded-3" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="CASH">Cash (Tunai)</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                        <button type="submit" class="btn btn-success w-100 py-2.5 rounded-3 fw-semibold shadow-sm {{ $sale->status == 'COMPLETED' ? 'disabled' : '' }}">
                            <i class="bi bi-check-circle me-1"></i> Checkout & Selesaikan
                        </button>
                    </form>

                    {{-- Form Batal Transaksi --}}
                    <form method="POST" action="{{ route('penjualan.destroy', $sale->id) }}" class="mt-2" onsubmit="return confirm('Yakin ingin membatalkan transaksi?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 py-2 rounded-3 small {{ $sale->status == 'COMPLETED' ? 'disabled' : '' }}">
                            <i class="bi bi-x-circle me-1"></i> Batal Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection