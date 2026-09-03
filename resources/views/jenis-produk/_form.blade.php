@csrf

<div class="mb-4">
    <label class="form-label fw-semibold text-secondary small">Nama Jenis Produk</label>
    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
           value="{{ old('nama', isset($jenisProduk) ? $jenisProduk->nama : '') }}"
           placeholder="Contoh:produk digital dan produk fisik." required autofocus>
    @error('nama')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex flex-column flex-md-row gap-3 mt-4">
    <button type="submit" class="btn btn-primary py-2.5 px-4 shadow-sm rounded-3 fw-semibold">
        <i class="bi bi-save me-2"></i> Simpan Jenis Produk
    </button>
    <a href="{{ route('jenis-produk.index') }}" class="btn btn-outline-secondary py-2.5 px-4 shadow-sm rounded-3 fw-semibold">
        <i class="bi bi-arrow-left-circle me-2"></i> Kembali
    </a>
</div>
