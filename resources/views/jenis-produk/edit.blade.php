@extends('layouts.app')

@section('title', 'Edit Jenis Produk - POS SMART')

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm border-0 rounded-4 col-lg-6 mx-auto p-4">
        <div class="card-header bg-white border-0 p-0 mb-4">
            <span class="text-primary fw-semibold small text-uppercase tracking-wider">Inventaris</span>
            <h3 class="fw-bold text-dark mb-1">Edit Jenis Produk</h3>
            <p class="text-muted small mb-0">Perbarui nama kategori produk.</p>
        </div>
        <div class="card-body p-0">
            <form action="{{ route('jenis-produk.update', $jenisProduk) }}" method="POST">
                @method('PUT')
                @include('jenis-produk._form')
            </form>
        </div>
    </div>
</div>
@endsection
