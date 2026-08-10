@extends('layouts.app')

@section('title', 'Tambah User - POS SMART')

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm border-0 rounded-4 col-lg-8 mx-auto p-4">
        <div class="card-header bg-white border-0 p-0 mb-4">
            <span class="text-primary fw-semibold small text-uppercase tracking-wider">Manajemen Sistem</span>
            <h3 class="fw-bold text-dark mb-1">Tambah User Baru</h3>
            <p class="text-muted small mb-0">Formulir pendaftaran akun pengguna baru sistem POS.</p>
        </div>

        <div class="card-body p-0">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @include('users._form')
            </form>
        </div>
    </div>
</div>
@endsection