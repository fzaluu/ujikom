@extends('layouts.app')

@section('title', 'Manajemen User - POS SMART')

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

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm rounded-3 py-2 px-3">
                <i class="bi bi-plus-circle me-1"></i> Tambah User
            </a>
        </div>

        {{-- Search Bar --}}
        <div class="row mb-4">
            <div class="col-md-5">
                <form action="{{ route('admin.users') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            class="form-control bg-light border-start-0 ps-0"
                            name="search"
                            placeholder="Cari nama atau email..."
                            value="{{ request('search') }}"
                        >

                        <button class="btn btn-primary px-3">
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
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover-custom align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-muted">
                    <tr>
                        <th width="5%" class="py-3 ps-3 rounded-start-3">#</th>
                        <th class="py-3">Nama</th>
                        <th class="py-3">Email</th>
                        <th width="15%" class="py-3">Role</th>
                        <th width="18%" class="py-3 text-center pe-3 rounded-end-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td class="ps-3 py-3 text-muted">
                            {{ $users->firstItem() + $index }}
                        </td>

                        <td class="fw-semibold text-dark">
                            {{ $user->name }}
                        </td>

                        <td class="text-muted">
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

                        <td class="pe-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-warning btn-sm text-white shadow-sm rounded-2" title="Edit User">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.users.destroy', $user) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm shadow-sm rounded-2" title="Hapus User">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
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
@endsection