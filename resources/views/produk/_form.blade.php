@csrf

<div class="row gx-4 mb-4">
    <div class="col-md-5">
        <label class="form-label fw-semibold text-secondary small">Gambar Produk</label>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3 text-center bg-light">
                <img id="previewImage" src="{{ (isset($produk) && !empty($produk->foto)) ? asset('storage/' . $produk->foto) : 'https://via.placeholder.com/400x300?text=Preview+Image' }}" alt="Preview Produk" class="img-fluid rounded-3 shadow-sm" style="max-height: 240px; object-fit: cover; width: 100%;">
            </div>
            <div class="card-footer bg-white border-0 pt-0 pb-3">
                {{-- Tanpa onchange, kita tangkap lewat script di bawah --}}
                <input id="fotoInput" type="file" accept="image/*" name="foto" class="form-control @error('foto') is-invalid @enderror" {{ (isset($produk) && !empty($produk->foto)) ? '' : 'required' }}>
                @error('foto')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Jenis Produk</label>
                        <select name="jenis_id" class="form-select @error('jenis_id') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Jenis Produk --</option>
                            @foreach($jenisProduk as $jenis)
                                <option value="{{ $jenis->id }}" {{ (string) old('jenis_id', isset($produk) ? $produk->jenis_id : '') === (string) $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Nama Produk</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', isset($produk) ? $produk->nama : '') }}" placeholder="Masukkan nama produk..." required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row gx-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary small">Harga Beli</label>
                            <input type="number" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" value="{{ old('harga_beli', isset($produk) ? $produk->harga_beli : '') }}" placeholder="Contoh: 10000" required>
                            @error('harga_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary small">Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual', isset($produk) ? $produk->harga_jual : '') }}" placeholder="Contoh: 15000" required>
                            @error('harga_jual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Stok</label>
                        <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', isset($produk) ? $produk->stok : '') }}" placeholder="Masukkan jumlah stok..." required>
                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-column flex-md-row gap-3 mt-4">
    <button type="submit" class="btn btn-primary py-2.5 px-4 shadow-sm rounded-3 fw-semibold">
        <i class="bi bi-save me-2"></i> Simpan Produk
    </button>
    <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary py-2.5 px-4 shadow-sm rounded-3 fw-semibold">
        <i class="bi bi-arrow-left-circle me-2"></i> Kembali
    </a>
</div>

{{-- Script Khusus Preview dengan Event Listener Mandiri --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const fotoInput = document.getElementById('fotoInput');
        const previewImage = document.getElementById('previewImage');

        if (fotoInput && previewImage) {
            fotoInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>