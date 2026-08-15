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
                <span class="text-primary fw-semibold small text-uppercase tracking-wider">di tes uy </span>
                <h3 class="fw-bold text-dark mb-1">
                    perulangan
                </h3>
            </div>
        </div>


        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover-custom align-middle mb-0">
                

    @for ($i = 1; $i <= 5; $i++)
        <p>angka ke-{{ $i }}</p>
    @endfor

            </table>
        </div>

@endsection