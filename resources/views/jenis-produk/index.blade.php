@extends('layouts.app')

@section('title', 'Jenis Produk')

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
        </div>

        {{-- Baris Tombol Aksi di Kiri --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3 mb-4">
            <div>
                @if($isAdmin)
                    <a href="{{ route('jenis-produk.create') }}" class="btn btn-primary shadow-sm rounded-3 py-2 px-3 text-nowrap">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Jenis Produk
                    </a>
                @endif
            </div>
        </div>

        {{-- Table Jenis Produk --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary fw-bold">
                    <tr>
                        <th width="5%" class="py-3 ps-3 rounded-start-3">No</th>
                        <th width="55%" class="py-3">Nama Jenis</th>
                        <th width="25%" class="py-3">Jumlah</th>
                        <th width="15%" class="py-3 text-center pe-3 rounded-end-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($jenisProduk as $index => $jenis)
                    <tr>
                        <td class="ps-3 py-3 text-muted fw-medium">
                            {{ $jenisProduk->firstItem() + $index }}
                        </td>

                        <td class="fw-semibold text-dark">
                            <a href="{{ route('produk.index', ['jenis_id' => $jenis->id]) }}" class="text-decoration-none text-dark">
                                <i class="bi bi-tag me-1 text-primary"></i> {{ $jenis->nama }}
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('produk.index', ['jenis_id' => $jenis->id]) }}" class="text-decoration-none">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2.5 py-1 fw-semibold">
                                    {{ $jenis->produk_count }} Produk
                                </span>
                            </a>
                        </td>

                        <td class="pe-3 text-center">
                            <div class="d-flex justify-content-center gap-1">
                                @if($isAdmin)
                                    <a href="{{ route('jenis-produk.edit', $jenis) }}" 
                                       class="btn btn-light btn-sm border text-secondary shadow-none px-2" 
                                       style="transition: all 0.2s;"
                                       onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.color='#1e293b';" 
                                       onmouseout="this.style.backgroundColor='#f8f9fa'; this.style.color='#6c757d';"
                                       title="Edit Jenis">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('jenis-produk.destroy', $jenis) }}"
                                          method="POST"
                                          class="d-inline"
                                          id="delete-form-jenis-{{ $jenis->id }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" 
                                                class="btn btn-light btn-sm border text-danger shadow-none px-2" 
                                                style="transition: all 0.2s;"
                                                onmouseover="this.style.backgroundColor='#fee2e2';" 
                                                onmouseout="this.style.backgroundColor='#f8f9fa';"
                                                title="Hapus Jenis"
                                                onclick="openDeleteModal('jenis-{{ $jenis->id }}', 'Apakah Anda yakin ingin menghapus jenis produk ini? Jenis tidak dapat dihapus jika masih terikat dengan produk.')">
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
        
        let btn = document.getElementById('confirmDeleteBtn');
        btn.disabled = false;
        btn.innerHTML = 'Ya, Hapus';

        var myModal = new bootstrap.Modal(document.getElementById('customDeleteModal'));
        myModal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (activeDeleteFormId) {
            let btn = this;
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...`;
            
            document.getElementById('cancelDeleteBtn').disabled = true;

            document.getElementById(activeDeleteFormId).submit();
        }
    });
</script>
@endsection