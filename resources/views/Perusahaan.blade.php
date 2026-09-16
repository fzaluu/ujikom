@extends('layouts.app')

@section('title', 'Manajemen User - POS SMART')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4 p-4 text-center text-white position-relative overflow-hidden" 
                 style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/imgr.jpg') center/cover no-repeat;">
                <div class="card-body">
                    <div class="mb-3">
                        <span class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle d-inline-block bg-white shadow-sm">
                            <i class="bi bi-shop fs-4"></i> 
                        </span>
                    </div>

                    <h3 class="fw-bold mb-2 text-white text-shadow">Perusahaan</h3>
                    <p class="text-white-50 small mb-4"></p>
                    
                    <div class="p-3 bg-dark bg-opacity-50 backdrop-blur rounded-3 border border-secondary border-opacity-25 mb-4 shadow">
                        <h5 class="fw-bold text-info mb-1">Raja Cell</h5>
                        <p class="text-light small mb-0">Berdirinya pada Tahun 2013</p>
                        <br>
                        <p class="text-light small mb-2" style="font-size: 0.75rem;">Layanan: Menyediakan Produk Digital dan Fiksi</p>
                        
                        <p class="text-light small mb-2" style="font-size: 0.75rem;">Alamat: Jalan Paseh Gang Sukawargi</p>
                        
                        <p class="text-light small mb-0" style="font-size: 0.75rem;">Email: rajacell@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    
    .text-shadow {
        text-shadow: 0 2px 4px rgba(0,0,0,0.7);
    }
</style>
@endsection