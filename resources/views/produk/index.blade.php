@extends('layouts.app')

@section('title', 'Manajemen Produk - POS SMART')

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
                <span class="text-primary fw-semibold small text-uppercase tracking-wider">Inventaris Toko</span>
                <h3 class="fw-bold text-dark mb-1">
                    Manajemen Produk & Stok
                </h3>
                <p class="text-muted small mb-0">
                    Kelola data produk barang, harga, dan ketersediaan stok sistem POS.
                </p>
            </div>

            {{-- Tombol Tambah Produk HANYA MUNCUL JIKA ADMIN --}}
            @if($isAdmin)
                <a href="{{ route('produk.create') }}" class="btn btn-primary shadow-sm rounded-3 py-2 px-3">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
                </a>
            @endif
        </div>

        @if($selectedJenis)
            <div class="alert bg-primary bg-opacity-10 text-primary border-0 rounded-3 d-flex align-items-center justify-content-between mb-4 py-2 px-3">
                <span class="small fw-medium">
                    <i class="bi bi-funnel-fill me-1"></i> Menampilkan produk jenis: <strong>{{ $selectedJenis->nama }}</strong>
                </span>
                <a href="{{ route('produk.index') }}" class="btn btn-sm btn-outline-primary rounded-3">Reset Filter</a>
            </div>
        @endif

        {{-- Search Bar & Filter --}}
        <div class="row mb-4">
            <div class="col-md-5">
                <form action="{{ route('produk.index') }}" method="GET">
                    @if($selectedJenis)
                        <input type="hidden" name="jenis_id" value="{{ $selectedJenis->id }}">
                    @endif
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control bg-light border-start-0 ps-0"
                            name="search"
                            placeholder="Cari nama produk..."
                            value="{{ request('search') }}"
                        >
                        <button class="btn btn-primary px-3">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Table Produk --}}
        <div class="table-responsive">
            <table class="table table-hover-custom align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-muted">
                    <tr>
                        <th width="5%" class="py-3 ps-3 rounded-start-3">#</th>
                        <th class="py-3">User Input</th>
                        <th width="12%" class="py-3">Foto</th>
                        <th class="py-3">Nama Produk</th>
                        <th class="py-3">Jenis</th>
                        @if($isAdmin)
                            <th class="py-3">Harga Beli</th>
                        @endif
                        <th class="py-3">Harga Jual</th>
                        <th width="10%" class="py-3">Stok</th>
                        <th width="18%" class="py-3 text-center pe-3 rounded-end-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($products as $index => $product)
                    <tr>
                        <td class="ps-3 py-3 text-muted">
                            {{ $products->firstItem() + $index }}
                        </td>

                        <td class="text-muted small">
                            <span class="badge bg-light text-dark border px-2 py-1">
                                <i class="bi bi-person me-1"></i> {{ $product->user->name ?? '-' }}
                            </span>
                        </td>

                        <td>
                            @if($product->foto)
                                <button type="button"
                                    class="btn btn-link p-0 text-decoration-none"
                                    data-bs-toggle="modal"
                                    data-bs-target="#productImageModal"
                                    data-image="{{ asset('storage/' . $product->foto) }}"
                                    data-name="{{ $product->nama }}">
                                <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}" class="img-thumbnail rounded-3 shadow-sm" style="width: 48px; height: 48px; object-fit: cover;">
                            </button>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">No Image</span>
                            @endif
                        </td>

                        <td class="fw-semibold text-dark">
                            {{ $product->nama }}
                        </td>

                        <td>
                            @if($product->jenisProduk)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">{{ $product->jenisProduk->nama }}</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        @if($isAdmin)
                            <td class="text-muted small">
                                Rp {{ number_format($product->harga_beli, 0, ',', '.') }}
                            </td>
                        @endif

                        <td class="fw-bold text-success">
                            Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                        </td>

                        <td>
                            <span class="badge {{ $product->stok > 5 ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }} px-2 py-1">
                                {{ $product->stok }} Unit
                            </span>
                        </td>

                        <td class="pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('produk.show', $product) }}" class="btn btn-info btn-sm text-white shadow-sm rounded-2" title="Detail Produk">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                {{-- Tombol Edit & Hapus (Hanya Admin) --}}
                                @if($isAdmin)
                                    <a href="{{ route('produk.edit', $product) }}" class="btn btn-warning btn-sm text-white shadow-sm rounded-2" title="Edit Produk">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('produk.destroy', $product) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm shadow-sm rounded-2" title="Hapus Produk">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isAdmin ? 9 : 8 }}" class="text-center py-5">
                            <i class="bi bi-box-seam fs-1 text-muted opacity-50"></i>
                            <h6 class="mt-3 text-muted">Belum ada data produk yang tersedia.</h6>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-4 mt-3">
            <small class="text-muted mb-2 mb-md-0">
                Menampilkan total <strong>{{ $products->total() }}</strong> data produk
            </small>
            <div>
                {{ $products->links() }}
            </div>
        </div>

    </div>
</div>

{{-- Modal Preview Foto Produk --}}
<div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="productImageModalLabel">Preview Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center bg-light rounded-bottom-4">
                <img id="productImageModalSrc" src="" alt="Preview Produk" class="img-fluid rounded-3 shadow-sm" style="max-height: 400px; width: auto;">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var productImageModal = document.getElementById('productImageModal');
        if (productImageModal) {
            productImageModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var imageSrc = button.getAttribute('data-image');
                var imageName = button.getAttribute('data-name');

                var modalTitle = productImageModal.querySelector('.modal-title');
                var modalImage = document.getElementById('productImageModalSrc');

                modalTitle.textContent = 'Produk: ' + imageName;
                modalImage.src = imageSrc;
                modalImage.alt = imageName;
            });
        }
    });
</script>
@endsection