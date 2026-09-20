<div class="mb-0">
    @forelse($products as $product)
    <div class="row g-2 align-items-center mb-2 p-2 border rounded-3 bg-white shadow-sm">
        <form class="add-to-cart-form d-flex align-items-center justify-content-between w-100 m-0 flex-wrap flex-sm-nowrap gap-2" action="{{ route('itempenjualan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="penjualan_id" value="{{ $sale->id }}">
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            {{-- Bagian Kiri: Tombol Preview Foto & Nama --}}
            <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden" style="min-width: 0;">
                @if($product->foto)
                    <button type="button"
                            class="btn btn-link p-0 text-decoration-none flex-shrink-0"
                            data-bs-toggle="modal"
                            data-bs-target="#productImageModal"
                            data-image="{{ asset($product->foto) }}"
                            data-name="{{ $product->nama }}">
                        <img src="{{ asset($product->foto) }}" 
                             alt="{{ $product->nama }}" 
                             class="img-thumbnail rounded-3 shadow-sm border" 
                             style="width: 42px; height: 42px; object-fit: cover; transition: transform 0.2s;"
                             onmouseover="this.style.transform='scale(1.08)'"
                             onmouseout="this.style.transform='scale(1)'">
                    </button>
                @else
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 flex-shrink-0" style="font-size: 0.7rem;">No Image</span>
                @endif

                <div class="overflow-hidden">
                    <div class="fw-semibold small {{ $product->stok <= 0 ? 'text-muted text-decoration-line-through' : 'text-dark' }} text-truncate">
                        {{ $product->nama }}
                    </div>
                    @if($product->stok <= 0)
                        <small class="text-danger fw-bold" style="font-size: 0.72rem;">Stok Habis</small>
                    @else
                        <small class="text-success fw-bold" style="font-size: 0.72rem;">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</small>
                    @endif
                </div>
            </div>

            {{-- Bagian Kanan: Input Qty & Tombol Tambah --}}
            <div class="d-flex align-items-center gap-1 ms-auto ms-sm-0 flex-shrink-0">
                <input type="number" 
                       name="quantity" 
                       value="1" 
                       min="1" 
                       class="form-control form-control-sm rounded-2 shadow-none qty-input text-center" 
                       style="width: 55px;" 
                       data-stok="{{ $product->stok }}"
                       {{ $sale->status == 'COMPLETED' || $product->stok <= 0 ? 'disabled' : '' }}>
                
                @if($product->stok <= 0)
                    <button type="button" class="btn btn-secondary btn-sm rounded-2 px-2.5" disabled title="Produk Habis">
                        <i class="bi bi-slash-circle"></i>
                    </button>
                @else
                    <button type="submit" class="btn btn-primary btn-sm rounded-2 shadow-sm px-2.5 {{ $sale->status == 'COMPLETED' ? 'disabled' : '' }}" title="Tambah ke Keranjang">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                @endif
            </div>
        </form>
    </div>
    @empty
    <div class="text-center py-5">
        <p class="text-muted small mb-0">Produk tidak ditemukan.</p>
    </div>
    @endforelse
</div>

{{-- 1. TAMBAHKAN MODAL PREVIEW FOTO PRODUK DI SINI --}}
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
    // Validasi stok input quantity
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function() {
            let maxStok = parseInt(this.getAttribute('data-stok'));
            let currentVal = parseInt(this.value);

            if (currentVal > maxStok) {
                this.setCustomValidity(`Jumlah produk hanya ${maxStok}`);
                this.reportValidity();
            } else {
                this.setCustomValidity('');
            }
        });
    });

    // 2. SCRIPT UNTUK MENGISI GAMBAR KE DALAM MODAL PREVIEW
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