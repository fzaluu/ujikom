<div class="mb-0"> 
    @forelse ($products as $product)
    <div class="row g-2 align-items-center mb-2 p-2 border rounded-3 bg-white shadow-sm">
        <form class="add-to-cart-form w-100 m-0" action="{{ route('itempenjualan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="penjualan_id" value="{{ isset($sale) ? $sale->id : '' }}">
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <div class="d-flex align-items-center justify-content-between w-100 gap-2">
                {{-- Bagian Kiri: Tombol Preview Foto & Nama (Memanjang) --}}
                <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 0;">
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
                                 style="width: 46px; height: 46px; object-fit: cover; transition: transform 0.2s;"
                                 onmouseover="this.style.transform='scale(1.08)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        </button>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 flex-shrink-0" style="font-size: 0.7rem;">No Image</span>
                    @endif

                    <div style="min-width: 0; padding-right: 5px;">
                        {{-- Menggunakan teks membungkus ke bawah tanpa terpotong (text-wrap) --}}
                        <div class="fw-semibold small lh-sm text-wrap text-break mb-1 {{ $product->stok <= 0 ? 'text-muted text-decoration-line-through' : 'text-dark' }}">
                            {{ $product->nama }}
                        </div>
                        @if($product->stok <= 0)
                            <small class="text-danger fw-bold" style="font-size: 0.75rem;">Stok Habis</small>
                        @else
                            <small class="text-success fw-bold" style="font-size: 0.75rem;">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</small>
                        @endif
                    </div>
                </div>

                {{-- Bagian Kanan: Input Qty & Tombol Tambah (Lebar tetap & konsisten) --}}
                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                    <input type="number" 
                           name="quantity" 
                           value="1" 
                           min="1" 
                           max="{{ $product->stok }}"
                           class="form-control rounded-2 shadow-none qty-input text-center px-1" 
                           style="width: 60px; height: 36px; -moz-appearance: textfield;" 
                           data-stok="{{ $product->stok }}"
                           title="Masukkan Jumlah"
                           {{ isset($sale) && $sale->status == 'COMPLETED' || $product->stok <= 0 ? 'disabled' : '' }}>
                    
                    @if($product->stok <= 0)
                        <button type="button" class="btn btn-secondary rounded-2 px-3" style="height: 36px;" disabled title="Produk Habis">
                            <i class="bi bi-slash-circle"></i>
                        </button>
                    @else
                        <button type="submit" class="btn btn-primary rounded-2 shadow-sm px-3 {{ isset($sale) &&$sale->status == 'COMPLETED' ? 'disabled' : '' }}" style="height: 36px;" title="Tambah ke Keranjang">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
    @empty
    <div class="text-center py-5">
        <p class="text-muted small mb-0">Produk tidak ditemukan.</p>
    </div>
    @endforelse
</div>

{{-- MODAL PREVIEW FOTO PRODUK --}}
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

<style>
    /* Menghilangkan panah spinner (up/down arrow) di input number agar teks punya ruang penuh */
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

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

    // Script Modal Foto
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