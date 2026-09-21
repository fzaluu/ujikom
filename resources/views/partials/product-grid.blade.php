@if($bestSellers->isEmpty())
    <div class="text-center text-muted py-5 reveal is-visible">
        <i class="bi bi-search fs-1 opacity-50 d-block mb-2"></i>
        @if(!empty($search))
            Produk "{{ $search }}" tidak ditemukan di inventaris toko.
        @else
            Produk akan tampil di sini setelah toko mulai mencatat transaksi.
        @endif
    </div>
@else
    <div class="row g-4" id="product-list">
        @foreach($bestSellers as $produk)
            <div class="col-sm-6 col-lg-4 reveal is-visible product-item">
                <div class="product-card">
                    @if(!empty($produk->foto))
                        <img src="{{ asset($produk->foto) }}" alt="{{ $produk->nama }}" class="product-photo">
                    @else
                        <div class="product-photo-fallback"><i class="bi bi-image"></i></div>
                    @endif
                    <div class="product-body">
                        @if(isset($produk->total_terjual) && empty($search))
                            <span class="badge-bestseller mb-2 d-inline-block">Best Seller</span>
                        @endif
                        <h6>{{ $produk->nama }}</h6>
                        @if(!empty($produk->harga_jual))
                            <div class="product-price">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif