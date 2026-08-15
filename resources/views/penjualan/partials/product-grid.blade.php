@forelse($products as $product)
<form method="POST" action="{{ route('itempenjualan.store') }}" class="row g-2 align-items-center mb-2 p-2 border rounded-3 {{ $product->stok <= 0 ? 'bg-secondary bg-opacity-10 opacity-75' : 'bg-white shadow-sm' }}">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <div class="col-7">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $product->foto ? asset('storage/' . $product->foto) : 'https://via.placeholder.com/45' }}" alt="Gambar" class="rounded-3 shadow-sm flex-shrink-0" style="width: 45px; height: 45px; object-fit:cover">
            <div class="overflow-hidden">
                <div class="fw-semibold {{ $product->stok <= 0 ? 'text-muted text-decoration-line-through' : 'text-dark' }} text-truncate">{{ $product->nama }}</div>
                @if($product->stok <= 0)
                    <small class="text-danger fw-bold">Stok Habis</small>
                @else
                    <small class="text-success fw-bold">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</small>
                @endif
            </div>
        </div>
    </div>
    <div class="col-3">
        {{-- Input quantity tanpa atribut max agar pesan error bisa dikontrol lewat JS --}}
        <input type="number" 
               name="quantity" 
               value="1" 
               min="1" 
               class="form-control form-control-sm rounded-2 shadow-none qty-input" 
               data-stok="{{ $product->stok }}"
               {{ $sale->status == 'COMPLETED' || $product->stok <= 0 ? 'disabled' : '' }}>
    </div>
    <div class="col-2">
        @if($product->stok <= 0)
            <button type="button" class="btn btn-secondary btn-sm w-100 rounded-2" disabled title="Produk Habis">
                <i class="bi bi-slash-circle"></i>
            </button>
        @else
            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-2 shadow-sm {{ $sale->status == 'COMPLETED' ? 'disabled' : '' }}" title="Tambah ke Keranjang">
                <i class="bi bi-plus-lg"></i>
            </button>
        @endif
    </div>
</form>
@empty
<div class="text-center py-5">
    <p class="text-muted small mb-0">Produk tidak ditemukan.</p>
</div>
@endforelse

{{-- Script untuk memunculkan pesan peringatan bahasa Indonesia --}}
<script>
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
</script>