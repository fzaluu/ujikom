@extends('layouts.app')

@section('title', 'Manajemen Penjualan - POS SMART')

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
                <span class="text-primary fw-semibold small text-uppercase tracking-wider">Transaksi Kasir</span>
                <h3 class="fw-bold text-dark mb-1">
                    Manajemen Penjualan
                </h3>
                <p class="text-muted small mb-0">
                    Kelola riwayat transaksi penjualan, status pesanan, dan pembayaran toko.
                </p>
            </div>

            <a href="{{ route('penjualan.create') }}" class="btn btn-primary shadow-sm rounded-3 py-2 px-3">
                <i class="bi bi-plus-circle me-1"></i> Transaksi Baru
            </a>
        </div>

        {{-- Search Bar --}}
        <div class="row mb-4">
            <div class="col-md-5">
                <form action="{{ route('penjualan.index') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request()->search }}" class="form-control bg-light border-start-0 ps-0" placeholder="Cari transaksi...">
                        <button class="btn btn-primary px-3" type="submit">Cari</button>
                        @if(request('search'))
                            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Penjualan --}}
        <div class="table-responsive">
            <table class="table table-hover-custom align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-muted">
                    <tr>
                        <th scope="col" width="5%" class="py-3 ps-3 rounded-start-3">No</th>
                        <th scope="col" class="py-3">Tanggal Transaksi</th>
                        <th scope="col" class="py-3">Kasir</th>
                        <th scope="col" class="py-3">Total Pembayaran</th>
                        <th scope="col" class="py-3">Metode</th>
                        <th scope="col" class="py-3">Status</th>
                        <th scope="col" width="18%" class="py-3 text-center pe-3 rounded-end-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <th scope="row" class="ps-3 py-3 text-muted">
                            {{ $sales->firstItem() + $loop->index }}
                        </th>
                        <td class="text-secondary small">
                            <i class="bi bi-calendar-event me-1 text-primary"></i> {{ $sale->created_at->translatedFormat('d-m-Y H:i:s') }}
                        </td>
                        <td class="fw-semibold text-dark">
                            <span class="badge bg-light text-dark border px-2 py-1">
                                <i class="bi bi-person me-1"></i> {{ $sale->user->name }}
                            </span>
                        </td>
                        <td class="fw-bold text-success">
                            Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                {{ $sale->metode_pembayaran ?? 'Belum Dipilih' }}
                            </span>
                        </td>
                        <td>
                            @if($sale->status === 'COMPLETED')
                                <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1">Completed</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1">Open</span>
                            @endif
                        </td>
                        <td class="pe-3">
    <div class="d-flex justify-content-center gap-1">
        {{-- Tombol Detail Transaksi (Clean Style dengan Aksen Biru Soft) --}}
        <a href="{{ route('penjualan.show', $sale) }}" 
           class="btn btn-light btn-sm border text-info shadow-none" 
           style="transition: all 0.2s;"
           onmouseover="this.style.backgroundColor='#e0f2fe';" 
           onmouseout="this.style.backgroundColor='#f8f9fa';"
           title="Detail Transaksi">
            <i class="bi bi-eye"></i>
        </a>
        
        @can('update', $sale)
            @if($sale->status === 'OPEN')
                {{-- Tombol Lanjut / Edit Kasir (Clean Style dengan Aksen Kuning/Oranye Soft) --}}
                <a href="{{ route('penjualan.edit', $sale) }}" 
                   class="btn btn-light btn-sm border text-warning shadow-none" 
                   style="transition: all 0.2s;"
                   onmouseover="this.style.backgroundColor='#fef3c7'; this.style.color='#d97706';" 
                   onmouseout="this.style.backgroundColor='#f8f9fa'; this.style.color='#f59e0b';"
                   title="Lanjut / Edit Kasir">
                    <i class="bi bi-cart-plus"></i>
                </a>
                
                {{-- Form & Tombol Hapus Transaksi (Modal Pop-up Tengah) --}}
                <form action="{{ route('penjualan.destroy', $sale) }}" 
                      method="POST" 
                      class="d-inline"
                      id="delete-form-penjualan-{{ $sale->id }}">
                    @csrf
                    @method('DELETE')
                    
                    <button type="button" 
                            class="btn btn-light btn-sm border text-danger shadow-none" 
                            style="transition: all 0.2s;"
                            onmouseover="this.style.backgroundColor='#fee2e2';" 
                            onmouseout="this.style.backgroundColor='#f8f9fa';"
                            title="Hapus Transaksi"
                            onclick="openDeleteModal('penjualan-{{ $sale->id }}', 'Apakah anda yakin akan menghapus penjualan ini?')">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            @endif
        @endcan
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-bag-x fs-1 text-muted opacity-50"></i>
                            <h6 class="mt-3 text-muted">Belum ada data transaksi penjualan.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-4 mt-3">
            <small class="text-muted mb-2 mb-md-0">
                Menampilkan total <strong>{{ $sales->total() }}</strong> riwayat transaksi
            </small>
            <div>
                {{ $sales->links() }}
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