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
                        <th width="5%" class="py-3 ps-3 rounded-start-3">No</th>
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
                                <img src="{{ asset('storage/' . $product->foto) }}" 
                                    alt="{{ $product->nama }}" 
                                    class="img-thumbnail rounded-3 shadow-sm border" 
                                    style="width: 48px; height: 48px; object-fit: cover; transition: transform 0.2s;"
                                    onmouseover="this.style.transform='scale(1.08)'"
                                    onmouseout="this.style.transform='scale(1)'">
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
                            @php
                                if ($product->stok == 0) {
                                    $badgeColor = 'danger'; // Merah (Stok Habis)
                                } elseif ($product->stok <= 5) {
                                    $badgeColor = 'warning'; // Kuning (Stok Menipis)
                                } elseif ($product->stok > 100) {
                                    $badgeColor = 'info'; // Biru (Stok di atas 100)
                                } else {
                                    $badgeColor = 'success'; // Hijau (Stok Normal aman)
                                }
                            @endphp
                            <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} px-2.5 py-1.5 fw-semibold">{{ $product->stok }} Unit</span>
                        </td>

                        <td class="pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- Tombol Detail (Clean Style dengan Aksen Biru Soft) --}}
                                <a href="{{ route('produk.show', $product) }}" 
                                class="btn btn-light btn-sm border text-info shadow-none" 
                                style="transition: all 0.2s;"
                                onmouseover="this.style.backgroundColor='#e0f2fe';" 
                                onmouseout="this.style.backgroundColor='#f8f9fa';"
                                title="Detail Produk">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                {{-- Tombol Edit & Hapus (Hanya Admin) --}}
                                @if($isAdmin)
                                    {{-- Tombol Edit Produk (Clean Style) --}}
                                    <a href="{{ route('produk.edit', $product) }}" 
                                    class="btn btn-light btn-sm border text-secondary shadow-none" 
                                    style="transition: all 0.2s;"
                                    onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.color='#1e293b';" 
                                    onmouseout="this.style.backgroundColor='#f8f9fa'; this.style.color='#6c757d';"
                                    title="Edit Produk">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Form & Tombol Hapus Produk (Modal Pop-up Tengah) --}}
                                    <form action="{{ route('produk.destroy', $product) }}"
                                        method="POST"
                                        class="d-inline"
                                        id="delete-form-produk-{{ $product->id }}">
                                        @csrf
                                        @method('DELETE')
                                        
                                        <button type="button" 
                                                class="btn btn-light btn-sm border text-danger shadow-none" 
                                                style="transition: all 0.2s;"
                                                onmouseover="this.style.backgroundColor='#fee2e2';" 
                                                onmouseout="this.style.backgroundColor='#f8f9fa';"
                                                title="Hapus Produk"
                                                onclick="openDeleteModal('produk-{{ $product->id }}', 'Apakah Anda yakin ingin menghapus produk ini?')">
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
{{-- Modal Konfirmasi Hapus di Tengah --}}
<div class="modal fade" id="customDeleteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg animate-page">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-trash text-danger display-4 mb-3"></i>
                <p id="deleteModalMessage" class="text-dark fs-6 mb-0">Apakah Anda yakin ingin menghapus data ini?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4 gap-2">
                <button type="button" class="btn btn-light px-4 rounded-3 shadow-none border" data-bs-dismiss="modal" id="cancelDeleteBtn">Batal</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger px-4 rounded-3 shadow-sm">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    let activeDeleteFormId = null;

    function openDeleteModal(identifier, message) {
        activeDeleteFormId = 'delete-form-' + identifier;
        document.getElementById('deleteModalMessage').innerText = message;
        
        // Reset tombol hapus ke kondisi semula jika sebelumnya sempat loading
        let btn = document.getElementById('confirmDeleteBtn');
        btn.disabled = false;
        btn.innerHTML = 'Ya, Hapus';

        // Reset tombol batal agar bisa diklik lagi
        let cancelBtn = document.getElementById('cancelDeleteBtn');
        if (cancelBtn) cancelBtn.disabled = false;

        var myModal = new bootstrap.Modal(document.getElementById('customDeleteModal'));
        myModal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (activeDeleteFormId) {
            // Ubah tombol menjadi status loading dengan spinner
            let btn = this;
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...`;
            
            // Nonaktifkan tombol batal agar user tidak menutup modal saat proses berjalan
            let cancelBtn = document.getElementById('cancelDeleteBtn');
            if (cancelBtn) cancelBtn.disabled = true;

            // Kirim form
            document.getElementById(activeDeleteFormId).submit();
        }
    });
</script>
@endsection