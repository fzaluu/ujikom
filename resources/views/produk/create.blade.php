@extends('layouts.app')

@section('title', 'Tambah Produk - POS SMART')

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm border-0 rounded-4 col-lg-10 mx-auto p-4">
        <div class="card-header bg-white border-0 p-0 mb-4">
            <span class="text-primary fw-semibold small text-uppercase tracking-wider">Inventaris</span>
            <h3 class="fw-bold text-dark mb-1">
                Tambah Produk Baru
            </h3>
            <p class="text-muted small mb-0">Formulir penambahan data produk dan inventaris toko.</p>
        </div>
        <div class="card-body p-0">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @include('produk._form')
            </form>
        </div>
    </div>
</div>
@endsection