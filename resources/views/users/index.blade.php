@extends('layouts.app')

@section('title', ' User')

@section('content')
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
                <span class="text-primary fw-semibold small text-uppercase tracking-wider">Manajemen Sistem</span>
                <h3 class="fw-bold text-dark mb-1">
                    Manajemen User
                </h3>
                <p class="text-muted small mb-0">
                    Kelola akun pengguna dan hak akses sistem POS.
                </p>
            </div>
        </div>

        {{-- Baris Tombol Tambah User di Kiri & Search Bar Mentok ke Kanan --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3 mb-4">
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm rounded-3 py-2 px-3 text-nowrap">
                    <i class="bi bi-plus-circle me-1"></i> Tambah User
                </a>
            </div>

            {{-- Search Bar --}}
            <form action="{{ route('admin.users') }}" method="GET" class="mb-0" style="max-width: 350px; width: 100%;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        class="form-control bg-light border-start-0 ps-0 shadow-none"
                        name="search"
                        placeholder="Cari nama atau email..."
                        value="{{ request('search') }}"
                    >

                    <button class="btn btn-outline-primary px-3" type="submit">
                        Cari
                    </button>

                    @if(request('search'))
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary fw-bold">
                    <tr>
                        <th width="5%" class="py-3 ps-3 rounded-start-3">No</th>
                        <th width="32%" class="py-3">Nama</th>
                        <th width="35%" class="py-3">Email</th>
                        <th width="15%" class="py-3">Role</th>
                        <th width="13%" class="py-3 text-center pe-3 rounded-end-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td class="ps-3 py-3 text-muted fw-medium">
                            {{ $users->firstItem() + $index }}
                        </td>

                        <td class="fw-semibold text-dark">
                            {{ $user->name }}
                        </td>

                        <td class="text-muted small">
                            {{ $user->email }}
                        </td>

                        <td>
                            @php
                                $role = strtolower($user->role->name ?? '');
                            @endphp

                            @if($role == 'admin')
                                <span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1.5 fw-semibold">
                                    {{ ucfirst($user->role->name) }}
                                </span>
                            @elseif($role == 'kasir')
                                <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 fw-semibold">
                                    {{ ucfirst($user->role->name) }}
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2.5 py-1.5 fw-semibold">
                                    {{ ucfirst($user->role->name ?? '-') }}
                                </span>
                            @endif
                        </td>

                        <td class="pe-3 text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                class="btn btn-light btn-sm border text-secondary shadow-none px-2" 
                                style="transition: all 0.2s;"
                                onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.color='#1e293b';" 
                                onmouseout="this.style.backgroundColor='#f8f9fa'; this.style.color='#6c757d';"
                                title="Edit User">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- Sembunyikan atau nonaktifkan tombol hapus jika itu akun sendiri --}}
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST"
                                        class="d-inline"
                                        id="delete-form-{{ $user->id }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" 
                                                class="btn btn-light btn-sm border text-danger shadow-none px-2" 
                                                style="transition: all 0.2s;"
                                                onmouseover="this.style.backgroundColor='#fee2e2';" 
                                                onmouseout="this.style.backgroundColor='#f8f9fa';"
                                                title="Hapus User"
                                                onclick="openDeleteModal('{{ $user->id }}', 'Apakah Anda yakin ingin menghapus user ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-light btn-sm border text-muted shadow-none px-2" disabled title="Akun sedang digunakan">
                                        <i class="bi bi-slash-circle"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted opacity-50"></i>
                            <h6 class="mt-3 text-muted">Belum ada data user yang tersedia.</h6>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Footer Pagination --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-4 mt-3">
            <small class="text-muted mb-2 mb-md-0">
                Total User : <strong>{{ $users->total() }}</strong>
            </small>
            <div>
                {{ $users->links() }}
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

    function openDeleteModal(id, message) {
        activeDeleteFormId = 'delete-form-' + id;
        document.getElementById('deleteModalMessage').innerText = message;
        
        let btn = document.getElementById('confirmDeleteBtn');
        btn.disabled = false;
        btn.innerHTML = 'Ya, Hapus';

        let cancelBtn = document.getElementById('cancelDeleteBtn');
        if (cancelBtn) cancelBtn.disabled = false;

        var myModal = new bootstrap.Modal(document.getElementById('customDeleteModal'));
        myModal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (activeDeleteFormId) {
            let btn = this;
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...`;
            
            let cancelBtn = document.getElementById('cancelDeleteBtn');
            if (cancelBtn) cancelBtn.disabled = true;

            document.getElementById(activeDeleteFormId).submit();
        }
    });
</script>
@endsection