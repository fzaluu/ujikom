@csrf

<div class="row gx-4 mb-4">
    <div class="col-md-5">
        <label class="form-label fw-semibold text-secondary small">Gambar Produk</label>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3 text-center bg-light">
                <img id="previewImage" src="{{ (isset($produk) && !empty($produk->foto)) ? asset($produk->foto) : 'https://via.placeholder.com/400x300?text=Preview+Image' }}" alt="Preview Produk" class="img-fluid rounded-3 shadow-sm" style="max-height: 240px; object-fit: cover; width: 100%;">
            </div>
            <div class="card-footer bg-white border-0 pt-0 pb-3">
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
                            <input type="text" inputmode="numeric" autocomplete="off" id="inputHargaBeli" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" value="{{ old('harga_beli', isset($produk) ? $produk->harga_beli : '') }}" placeholder="Contoh: 10.000" required>
                            @error('harga_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary small">Harga Jual</label>
                            <input type="text" inputmode="numeric" autocomplete="off" id="inputHargaJual" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual', isset($produk) ? $produk->harga_jual : '') }}" placeholder="Contoh: 15.000" required>
                            @error('harga_jual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="hargaRugiHint" class="text-danger small mt-1 d-none">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Harga jual lebih rendah dari harga beli (rugi).
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Stok</label>
                        <input type="text" inputmode="numeric" autocomplete="off" id="inputStok" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', isset($produk) ? $produk->stok : '') }}" placeholder="Masukkan jumlah stok..." required>
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

{{-- Modal Peringatan (Disamakan 100% dengan Halaman Transaksi/POS) --}}
<div class="modal fade" id="rugiWarningModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalTitleWarning">
                    <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> Peringatan Batas Nominal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="display-4 mb-3 text-danger" id="modalIconContainer">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <p class="text-dark fs-6 mb-1" id="modalTextMain">Yang bener masukin harganya!</p>
                <p class="text-muted small mb-0" id="modalTextSub">Jika melebihi batas, <strong class="text-danger">call owner</strong>!</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" id="modalActionBtn" class="btn btn-danger px-4 rounded-3 shadow-sm" data-bs-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

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

        let warningModalObj = null;
        function getWarningModal() {
            const modalEl = document.getElementById('rugiWarningModal');
            if (!warningModalObj) {
                warningModalObj = bootstrap.Modal.getOrCreateInstance(modalEl);
            }
            return warningModalObj;
        }

        function showCustomModal(titleHtml, iconHtml, mainText, subText, btnClass) {
            document.getElementById('modalTitleWarning').innerHTML = titleHtml;
            document.getElementById('modalIconContainer').innerHTML = iconHtml;
            document.getElementById('modalTextMain').innerHTML = mainText;
            document.getElementById('modalTextSub').innerHTML = subText;
            
            const btn = document.getElementById('modalActionBtn');
            if (btn) btn.className = `btn ${btnClass} px-4 rounded-3 shadow-sm`;

            getWarningModal().show();
        }

        // ===== Fungsi Format Titik Ribuan & Batasan Maksimal 9 Digit (Miliaran) =====
        function setupMoneyInput(input) {
            if (!input) return;

            if (input.value) {
                let cleanVal = input.value.replace(/[^\d]/g, '');
                if (cleanVal.length > 9) cleanVal = cleanVal.substring(0, 9);
                if (cleanVal !== "") {
                    input.value = Number(cleanVal).toLocaleString('id-ID');
                }
            }

            input.addEventListener('input', function () {
                let cleaned = this.value.replace(/[^\d]/g, '');

                // Batasan maksimal 9 digit (Miliaran)
                if (cleaned.length > 9) {
                    cleaned = cleaned.substring(0, 9);
                    
                    // Peringatan Batas Nominal (Warna Merah sama persis dengan POS)
                    showCustomModal(
                        `<i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> Peringatan Batas Nominal`,
                        `<i class="bi bi-exclamation-circle text-danger"></i>`,
                        `Yang bener masukin harganya!`,
                        `Jika melebihi batas, <strong class='text-danger'>call owner</strong>!`,
                        `btn-danger`
                    );
                }

                if (cleaned !== "") {
                    this.value = Number(cleaned).toLocaleString('id-ID');
                } else {
                    this.value = "";
                }
            });
        }

        const inputHargaBeli = document.getElementById('inputHargaBeli');
        const inputHargaJual = document.getElementById('inputHargaJual');
        const inputStok = document.getElementById('inputStok');

        setupMoneyInput(inputHargaBeli);
        setupMoneyInput(inputHargaJual);

        if (inputStok) {
            inputStok.addEventListener('input', function () {
                this.value = this.value.replace(/[^\d]/g, '');
            });
        }

        // ===== Peringatan Rugi: Harga Jual <= Harga Beli =====
        const hargaRugiHint = document.getElementById('hargaRugiHint');
        let rugiModalShown = false;

        function cekPotensiRugi() {
            const hargaBeli = parseInt((inputHargaBeli?.value || '0').replace(/\./g, ''), 10) || 0;
            const hargaJual = parseInt((inputHargaJual?.value || '0').replace(/\./g, ''), 10) || 0;

            const berpotensiRugi = hargaBeli > 0 && hargaJual > 0 && hargaJual <= hargaBeli;

            if (berpotensiRugi) {
                hargaRugiHint?.classList.remove('d-none');

                if (!rugiModalShown) {
                    rugiModalShown = true;
                    showCustomModal(
                        `<i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i> Peringatan Harga`,
                        `<i class="bi bi-graph-down-arrow text-warning"></i>`,
                        `Harga jual yang kamu masukkan <strong>lebih rendah atau sama dengan</strong> harga beli.`,
                        `Produk ini berpotensi membuat toko <strong class='text-danger'>rugi</strong> setiap kali terjual.`,
                        `btn-warning text-white`
                    );
                }
            } else {
                hargaRugiHint?.classList.add('d-none');
                rugiModalShown = false;
            }
        }

        inputHargaBeli?.addEventListener('blur', cekPotensiRugi);
        inputHargaJual?.addEventListener('blur', cekPotensiRugi);

        // Bersihkan titik sebelum form dikirim ke server
        const parentForm = document.querySelector('form');
        if (parentForm) {
            parentForm.addEventListener('submit', function () {
                if (inputHargaBeli) inputHargaBeli.value = inputHargaBeli.value.replace(/\./g, '');
                if (inputHargaJual) inputHargaJual.value = inputHargaJual.value.replace(/\./g, '');
            });
        }
    });
</script>