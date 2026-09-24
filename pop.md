

### **1. Perbarui Bagian Tabel Item Produk di `show.blade.php**`

Cari bagian tabel daftar item produk di file view `show.blade.php`, lalu tambahkan kolom **Foto** di antara kolom *Nama Produk* dan *Harga Satuan*, serta masukkan modal Bootstrap di bagian bawah file.

```html
        {{-- Tabel Daftar Item Produk --}}
        <div class="card border-0 shadow-sm rounded-4 p-0 overflow-hidden mb-4 d-print-none">
            <div class="card-header bg-white border-0 p-3 pb-0">
                <h5 class="fw-bold text-dark mb-0">Daftar Item Produk yang Dibeli</h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 600px;">
                        <thead class="table-light text-uppercase fs-7 text-muted">
                            <tr>
                                <th width="5%" class="py-3 ps-3 rounded-start">No</th>
                                <th class="py-3">Nama Produk</th>
                                <th class="py-3 text-center" style="width: 100px;">Foto</th> <!-- Kolom Foto Ditambahkan -->
                                <th class="py-3">Harga Satuan</th>
                                <th class="py-3">Jumlah</th>
                                <th class="py-3 pe-3 rounded-end text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sale->itemPenjualan as $item)
                            <tr>
                                <td class="ps-3 py-3 text-muted">{{ $loop->index + 1 }}</td>
                                <td class="fw-semibold text-dark">
                                    {{ $item->nama_produk ?? optional($item->produk)->nama ?? 'Produk Tidak Diketahui' }}
                                    
                                    @if(is_null($item->produk_id) || !$item->produk)
                                        <span class="badge bg-danger bg-opacity-10 text-danger ms-2 px-2 py-0.5" style="font-size: 0.7rem;">
                                            <i class="bi bi-exclamation-circle me-1"></i> Produk Telah Dihapus
                                        </span>
                                    @endif
                                </td>
                                    
                                {{-- Kolom Tombol Foto & Thumbnail --}}
                                <td class="text-center">
                                    @php 
                                        // Mengambil foto dari relasi produk atau fallback ke data item jika tersimpan
                                        $fotoProduk = optional($item->produk)->foto ?? $item->foto ?? null;
                                    @endphp

                                    @if($fotoProduk)
                                        <button type="button"
                                            class="btn btn-link p-0 text-decoration-none"
                                            data-bs-toggle="modal"
                                            data-bs-target="#productImageModal"
                                            data-image="{{ asset($fotoProduk) }}"
                                            data-name="{{ $item->nama_produk ?? optional($item->produk)->nama ?? 'Produk' }}">
                                            <img src="{{ asset($fotoProduk) }}" 
                                                alt="Foto Produk" 
                                                class="img-thumbnail rounded-3 shadow-sm border" 
                                                style="width: 42px; height: 42px; object-fit: cover; transition: transform 0.2s;"
                                                onmouseover="this.style.transform='scale(1.08)'"
                                                onmouseout="this.style.transform='scale(1)'">
                                        </button>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1" style="font-size: 0.75rem;">No Image</span>
                                    @endif
                                </td>

                                <td class="text-muted small">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td>{{ $item->kuantitas }} Unit</td>
                                <td class="pe-3 fw-bold text-success text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada item produk pada transaksi ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end py-3">Total Pembayaran:</th>
                                <th class="text-end py-3 text-success fs-5 pe-3">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</th>
                            </tr>
                            @if($sale->metode_pembayaran === 'CASH')
                            <tr>
                                <th colspan="5" class="text-end py-2">Tunai:</th>
                                <th class="text-end py-2 text-dark fs-6 pe-3">Rp {{ number_format($sale->uang_dibayar ?? 0, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-end py-2">Kembalian:</th>
                                <th class="text-end py-2 text-success fs-6 pe-3">Rp {{ number_format($sale->kembalian ?? 0, 0, ',', '.') }}</th>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

```

---

### **2. Tambahkan Elemen Modal Pop-Up di Bawah File View (`show.blade.php`)**

Letakkan kode modal Bootstrap ini di bagian paling bawah sebelum `@endsection`:

```html
{{-- Modal Pop-Up Preview Foto Produk --}}
<div class="modal fade" id="productImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark text-truncate pe-2">Preview Produk</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="productImageModalSrc" src="" alt="Preview" class="img-fluid rounded-3 shadow-sm border" style="max-height: 300px; width: 100%; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

```

---

### **3. Pastikan Script JavaScript Terpasang**

Pastikan skrip JavaScript untuk menangkap data tombol dan memasukkannya ke dalam modal sudah ada di bagian bawah file (di dalam atau setelah tag `<script>` yang sudah ada):

```javascript
<script>
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

```

Dengan penyesuaian ini, tabel detail produk di halaman *Show* akan menampilkan thumbnail foto produk yang interaktif. Ketika diklik, foto tersebut akan memunculkan pop-up modal ukuran penuh secara mulus!

Oh, saya paham! Error **"Undefined variable: produc"** (atau `Undefined variable $product`) itu muncul karena di dalam loop `@forelse ($sale->itemPenjualan as $item)` kita menggunakan variabel `$product`, padahal variabel yang didefinisikan di perulangan tersebut adalah **`$item`** (bukan `$product`).

Mari kita perbaiki bagian pemanggilan foto di dalam file view `show.blade.php` agar menggunakan `$item` yang benar.

### **Cara Memperbaikinya:**

Cari bagian kolom foto di dalam file `show.blade.php` milikmu, lalu ubah variabel `$product` menjadi `$item` seperti ini:

```html
{{-- Kolom Tombol Foto & Thumbnail --}}
<td class="text-center">
    @php 
        // Mengambil foto dari relasi produk melalui item penjualan
        $fotoProduk = optional($item->produk)->foto ?? $item->foto ?? null;
        $namaProduk = $item->nama_produk ?? optional($item->produk)->nama ?? 'Produk';
    @endphp

    @if($fotoProduk)
        <button type="button"
            class="btn btn-link p-0 text-decoration-none"
            data-bs-toggle="modal"
            data-bs-target="#productImageModal"
            data-image="{{ asset($fotoProduk) }}"
            data-name="{{ $namaProduk }}">
            <img src="{{ asset($fotoProduk) }}" 
                alt="Foto Produk" 
                class="img-thumbnail rounded-3 shadow-sm border" 
                style="width: 42px; height: 42px; object-fit: cover; transition: transform 0.2s;"
                onmouseover="this.style.transform='scale(1.08)'"
                onmouseout="this.style.transform='scale(1)'">
        </button>
    @else
        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1" style="font-size: 0.75rem;">No Image</span>
    @endif
</td>

```

### **Penyebabnya:**

Di dalam perulangan `@forelse ($sale->itemPenjualan as $item)`, objek tunggalnya bernama **`$item`**, bukan `$product`. Dengan mengubahnya menjadi `$item->produk` atau `$item->foto`, error `undefined variable` tersebut akan langsung hilang dan foto produk beserta modal pop-up-nya akan berjalan normal tanpa kendala!

public function index(SearchRequest $request)
    {
        $user = Auth::user();
        $keyword = $request->input('search');

        $sales = Penjualan::with(['user', 'itemPenjualan.produk']) // <-- Pastikan ini ada
            ->when(strtolower(optional($user->role)->name) === 'kasir', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->orderByRaw("CASE WHEN status = 'OPEN' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('penjualan.partials.table', compact('sales'))->render();
        }

        return view('penjualan.index', compact('sales'));
    }

    <td class="fw-semibold text-dark">
                        @php
                            // Mengambil nama produk dari relasi item penjualan
                            $itemPertama = $sale->itemPenjualan->first();
                            $namaProduk = optional($itemPertama->produk)->nama ?? $itemPertama->nama_produk ?? '-';
                            $jumlahItem = $sale->itemPenjualan->count();
                        @endphp

                        {{ $namaProduk }}
                        @if($jumlahItem > 1)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1" style="font-size: 0.7rem;">
                                +{{ $jumlahItem - 1 }} produk lainnya
                            </span>
                        @endif
                    </td>



                    astaga nagas







                    aduai cinta


                    ajbja


                    adbjaaj


/?
        akuu sayanggg mamah dan mamah sayang aku??;"
        "

        mamah pengen eeeeee


        awhhh




Maksudnya biar setiap user yang berbeda punya warna badge atau background yang unik ya, Fraza? Ide yang keren banget biar tampilannya lebih hidup dan gampang dibedakan secara visual!

Kita bisa bikin fungsi kecil untuk mencocokkan ID user atau namanya dengan pilihan warna badge tertentu secara otomatis.

Berikut adalah **cara menerapkannya di file Blade** milikmu:

### **1. Tambahkan Fungsi Warna Otomatis di Bagian Atas View (`@php`)**

Kamu bisa menyisipkan fungsi sederhana ini menggunakan `hash` dari ID user atau nama kasirnya di bagian atas sebelum tabel, atau langsung di dalam tag `<td>`-nya.

```php
@php
    // Daftar warna badge Bootstrap yang bisa dipakai bergantian
    $colors = ['primary', 'success', 'warning', 'danger', 'info', 'dark'];
    
    // Fungsi untuk menentukan warna berdasarkan ID user agar warnanya selalu konsisten untuk user yang sama
    function getUserBadgeColor($userId, $colorList) {
        if (!$userId) return 'secondary';
        $index = $userId % count($colorList);
        return $colorList[$index];
    }
@endphp

```

---

### **2. Terapkan pada Kode Badgenya**

Ubah bagian `<td>` user input kamu menjadi seperti ini:

```html
<td>
    @php
        $userId = $product->user_id ?? optional($product->user)->id;
        $badgeColor = getUserBadgeColor($userId, $colors);
    @endphp

    <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} border border-{{ $badgeColor }} border-opacity-25 px-2.5 py-1 fw-semibold">
        <i class="bi bi-person me-1"></i> {{ optional($product->user)->name ?? '-' }}
    </span>
</td>

```

### **Hasilnya:**

* **User 1 (Misal ID: 1)** akan otomatis mendapat warna *Primary* (Biru).
* **User 2 (Misal ID: 2)** akan otomatis mendapat warna *Success* (Hijau).
* **User 3** mendapat warna *Warning* (Kuning/Oranye), dan seterusnya secara otomatis dan konsisten!

Jadi, setiap user punya warna badge identitas yang berbeda-beda di setiap produk yang mereka input. Silakan dicoba ya, Fraza!




 {{-- Footer Checkout --}}
                    <div class="bg-light p-3 rounded-4 border mt-auto">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted fw-semibold">Total Pembayaran</span>
                            <h4 class="fw-bold text-success mb-0">
                                Rp {{ number_format($sale->total_pembayaran ?? 0, 0, ',', '.') }}
                            </h4>
                        </div>

                            <form id="checkoutForm" method="POST" action="{{ ($sale && $sale->exists) ? route('penjualan.update', $sale->id) : route('penjualan.index') }}">                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted mb-1">Metode Pembayaran</label>
                                <select name="payment_method" id="paymentMethodSelect" class="form-select rounded-3 shadow-none" required>
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <option value="CASH" {{ ($sale->metode_pembayaran ?? '') == 'CASH' ? 'selected' : '' }}>Cash (Tunai)</option>
                                    <option value="QRIS" {{ ($sale->metode_pembayaran ?? '') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                    <option value="transfer" {{ ($sale->metode_pembayaran ?? '') == 'transfer' ? 'selected' : '' }}>TRANSFER </option>
                                    <option value="BAYAR_NANTI" {{ ($sale->metode_pembayaran ?? '') == 'BAYAR NANTI' || ($sale->metode_pembayaran ?? '') == 'BAYAR_NANTI' ? 'selected' : '' }}>BAYAR NANTI</option>
                                </select>
                            </div>

                            {{-- QRIS --}}
                            <div id="qrisContainer" class="mb-3 text-center d-none">
                                <div class="card p-3 border-0 bg-white shadow-sm rounded-3">
                                    <p class="fw-bold small text-dark mb-2">Scan QRIS untuk Pembayaran</p>
                                    <img src="{{ asset('images/qrcode.jpeg') }}" alt="QRIS Code" class="img-fluid rounded mx-auto" style="max-width: 160px;">
                                    <p class="text-muted small mt-2 mb-0" style="font-size: 0.75rem;">Gunakan E-Wallet / M-Banking</p>
                                </div>
                            </div>

                            

                            {{-- Cash Input --}}
                            <div id="transferContainer" class="mb-3 d-none">
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted mb-1">No Rekeneing</label>
                                    <div class="p-2 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                                    <span class="small fw-semibold text-muted">12345678</span>
                                </div>
                                    <div id="uangError" class="text-danger small mt-1 d-none"></div>
                                </div>                                    
                                    <div id="uangError" class="text-danger small mt-1 d-none"></div>
                                </div>
                                
                            </div>


                            {{-- Cash Input --}}
                            <div id="cashContainer" class="mb-3 d-none">
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted mb-1">Uang Tunai dari Pelanggan</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 rounded-start-3">Rp</span>
                                        <input type="text"
                                               name="uang_dibayar"
                                               id="inputUangDibayar"
                                               class="form-control border-start-0 rounded-end-3 shadow-none"
                                               placeholder="Contoh: 50.000"
                                               autocomplete="off"
                                               inputmode="numeric">
                                    </div>
                                    <div id="uangError" class="text-danger small mt-1 d-none"></div>
                                </div>
                                <div class="p-2 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                                    <span class="small fw-semibold text-muted">Uang Kembalian</span>
                                    <span id="textKembalian" class="fw-bold text-success fs-6">Rp 0</span>
                                </div>
                            </div>

                            {{-- Form Input Bayar Nanti (Langsung Tampil di Sini, Tanpa Pop-up) --}}
                            <div id="bayarNantiContainer" class="mb-3">
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted mb-1">Nama Pelanggan </label>
                                    <input type="text" name="customer_name" id="inputCustomerName" class="form-control form-control-sm rounded-3 shadow-none" placeholder="Masukkan nama pelanggan" value="{{ $sale->customer_name ?? '' }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-muted mb-1">No. HP / WhatsApp </label>
                                    <input type="text" name="customer_phone" id="inputCustomerPhone" class="form-control form-control-sm rounded-3 shadow-none" placeholder="Contoh: 08123456789" value="{{ $sale->customer_phone ?? '' }}">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small fw-bold text-muted mb-1">Tanggal Jatuh Tempo</label>
                                    <input type="date" name="due_date" id="inputDueDate" class="form-control form-control-sm rounded-3 shadow-none" value="{{ $sale->due_date ?? '' }}">
                                </div>
                            </div>

                            <input type="hidden" name="kembalian" id="inputHiddenKembalian" value="0">

                            <button type="button"
                                    id="checkoutBtn"
                                    class="btn btn-success w-100 py-2.5 rounded-3 fw-semibold shadow-sm {{ ($sale->status ?? '') == 'COMPLETED' ? 'disabled' : '' }}"
                                    onclick="openCustomConfirm('checkout')">
                                <i class="bi bi-check-circle me-1"></i>
                                <span id="checkoutBtnText">Checkout & Selesaikan</span>
                            </button>
                        </form>

                        @if($sale->exists)
                            <form id="cancelTransactionForm" method="POST" action="{{ route('penjualan.destroy', $sale->id) }}" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="btn btn-outline-danger w-100 py-2 rounded-3 small {{ ($sale->status ?? '') == 'COMPLETED' ? 'disabled' : '' }}"
                                        onclick="openCustomConfirm('cancel')">
                                    <i class="bi bi-x-circle me-1"></i> Batal Transaksi
                                </button>
                            </form>
                        @else
                            <a href="{{ route('penjualan.index') }}"
                               class="btn btn-outline-danger w-100 py-2 rounded-3 small mt-2 text-decoration-none text-center"
                               onclick="markExplicitAction()">
                                <i class="bi bi-x-circle me-1"></i> Batal Transaksi
                            </a>
                        @endif
                    </div>



                    const paymentSelect        = document.getElementById('paymentMethodSelect');
        const qrisContainer        = document.getElementById('qrisContainer');
        const transferContainer    = document.getElementById('transferContainer');
        const cashContainer        = document.getElementById('cashContainer');
        const bayarNantiContainer  = document.getElementById('bayarNantiContainer');
        const inputUangDibayar     = document.getElementById('inputUangDibayar');
        const textKembalian        = document.getElementById('textKembalian');
        const inputHiddenKembalian = document.getElementById('inputHiddenKembalian');
        const checkoutBtnText      = document.getElementById('checkoutBtnText');
        const uangError            = document.getElementById('uangError');

        function updatePaymentUI() {
            if (!paymentSelect) return;

            const method = paymentSelect.value;

            qrisContainer?.classList.add('d-none');
            transferContainer?.classList.add('d-none');
            cashContainer?.classList.add('d-none');
            bayarNantiContainer?.classList.add('d-none');
            uangError?.classList.add('d-none');

            if (method === 'QRIS') {
                qrisContainer?.classList.remove('d-none');
                if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
            } else if (method === 'transfer') {
                transferContainer?.classList.remove('d-none');
            } else if (method === 'CASH') {
                cashContainer?.classList.remove('d-none');
                if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
                hitungKembalian();
            } else if (method === 'BAYAR_NANTI') {
                bayarNantiContainer?.classList.remove('d-none');
                if (checkoutBtnText) checkoutBtnText.innerText = 'Simpan & Bayar Nanti';
            } else {
                if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
            }
        }


            <?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(SearchRequest $request)
    {
        $user = Auth::user();
        $keyword = $request->input('search');

        $sales = Penjualan::with(['user', 'itemPenjualan.produk']) 
            ->when(strtolower(optional($user->role)->name) === 'kasir', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->orderByRaw("CASE WHEN status = 'OPEN' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('penjualan.partials.table', compact('sales'))->render();
        }

        return view('penjualan.index', compact('sales'));
    }

    public function create(Request $request) 
    {
        $user = Auth::user();

        // === TAMBAHKAN BARIS INI (Pembersih transaksi open kosong) ===
        Penjualan::where('user_id', $user->id)
            ->where('status', 'OPEN')
            ->whereNull('customer_name')
            ->doesntHave('itemPenjualan')
            ->delete();
        // ============================================================

        // Cari transaksi OPEN yang murni keranjang aktif milik user (belum di-checkout/belum ada nama pelanggannya)
        $sale = Penjualan::where('user_id', $user->id)
            ->where('status', 'OPEN')
            ->whereNull('customer_name')
            ->latest()
            ->first();


        // Jika tidak ada keranjang aktif yang kosong, buat transaksi baru yang bersih
        if (!$sale) {
            $sale = Penjualan::create([
                'user_id' => $user->id,
                'status' => 'OPEN',
                'total_pembayaran' => 0,
                'metode_pembayaran' => 'BAYAR_NANTI'
            ]);
        }

        $keyword = $request->input('search');

        $products = Produk::when($keyword, function ($query) use ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        })
        ->orderByRaw('CASE WHEN stok <= 0 THEN 1 ELSE 0 END')
        ->orderBy('stok', 'desc')
        ->orderBy('nama')
        ->paginate(5)
        ->appends($request->all());

        $totalProdukCount = Produk::count();
        $mode = 'create';

        $sale->load('itemPenjualan.produk');

        if ($request->ajax()) {
            return response()->json([
                'html' => view('penjualan.partials.product-grid', compact('products', 'sale'))->render()
            ]);
        }

        return view('penjualan.pos', compact('sale', 'products', 'mode', 'totalProdukCount'));
    }

    public function edit($id, Request $request)
    {
        $user = Auth::user();
        $isAdmin = ($user->role_id == 1) || (isset($user->role) && strtolower($user->role->name) === 'admin');

        $sale = Penjualan::where('id', $id)
            ->when(!$isAdmin, function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        // Hanya blokir jika transaksi tidak ada atau statusnya sudah COMPLETED
        if (!$sale || $sale->status == 'COMPLETED') {
            return redirect()->route('penjualan.index')->with('error', 'Transaksi tidak ditemukan atau sudah selesai.');
        }

        // === TAMBAHKAN BARIS INI (Hapus transaksi open jika itemnya kosong) ===
        if ($sale->status == 'OPEN' && $sale->itemPenjualan()->count() == 0 && is_null($sale->customer_name)) {
            $sale->delete();
            return redirect()->route('penjualan.create');
        }

        $this->authorize('update', $sale);

        $sale->load('itemPenjualan.produk');
        
        $keyword = $request->input('search');
        
        $products = Produk::when($keyword, function ($query) use ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        })
        ->orderByRaw('CASE WHEN stok <= 0 THEN 1 ELSE 0 END')
        ->orderBy('stok', 'desc')
        ->orderBy('nama')
        ->paginate(5)
        ->appends($request->all());

        $totalProdukCount = Produk::count();
        $mode = 'edit';

        if ($request->ajax()) {
            return response()->json([
                'html' => view('penjualan.partials.product-grid', compact('products', 'sale'))->render()
            ]);
        }

        return view('penjualan.pos', compact('sale', 'products', 'mode', 'totalProdukCount'));
    }

    public function show(Penjualan $penjualan)
    {
        // Pastikan method view di policy mengizinkan, atau langsung load data tanpa batasan ketat 404
        $sale = $penjualan->load('itemPenjualan.produk', 'user');
        return view('penjualan.show', compact('sale'));
        
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        
        $request->validate([
            'payment_method' => 'required|in:CASH,QRIS,BAYAR_NANTI',
            'uang_dibayar' => 'nullable',
            'kembalian' => 'nullable',
            'customer_name' => 'required_if:payment_method,BAYAR_NANTI|nullable|string|max:255',
            'customer_phone' => 'required_if:payment_method,BAYAR_NANTI|nullable|string|max:255',
            'due_date' => 'required_if:payment_method,BAYAR_NANTI|nullable|date',
            // 'produc'   => 're'
        ]);

        if ($penjualan->status == 'COMPLETED') {
            return back()->with('error', 'Transaksi sudah diproses');
        }

        $this->authorize('update', $penjualan);

        if ($penjualan->itemPenjualan()->count() == 0) {
            return back()->with('error', 'Keranjang masih kosong');
        }

        $total = $penjualan->itemPenjualan()->sum('subtotal');
        
        $uangDibayar = null;
        $kembalian = null;

        if ($request->payment_method === 'CASH') {
            $uangDibayar = floatval($request->input('uang_dibayar', 0));
            $kembalian = floatval($request->input('kembalian', 0));

            if ($uangDibayar < $total) {
                return back()->withErrors(['uang_dibayar' => 'Uang tunai dari pelanggan kurang dari total pembayaran!'])->withInput();
            }
        }

        $newStatus = ($request->payment_method === 'BAYAR_NANTI') ? 'OPEN' : 'COMPLETED';

        DB::transaction(function () use ($penjualan, $request, $total, $newStatus, $uangDibayar, $kembalian) {
            $penjualan->update([
                'metode_pembayaran' => $request->payment_method,
                'total_pembayaran' => $total,
                'uang_dibayar' => $uangDibayar,
                'kembalian' => $kembalian,
                'status' => $newStatus,
                'customer_name' => $request->payment_method === 'BAYAR_NANTI' ? $request->customer_name : null,
                'customer_phone' => $request->payment_method === 'BAYAR_NANTI' ? $request->customer_phone : null,
                'due_date' => $request->payment_method === 'BAYAR_NANTI' ? $request->due_date : null,
            ]);
        });

        $message = ($newStatus === 'OPEN') 
            ? 'Transaksi Bayar Nanti berhasil disimpan' 
            : 'Transaksi berhasil diselesaikan';

        return redirect()
            ->route('penjualan.index')
            ->with('success', $message);
    }

    public function destroy(Penjualan $penjualan)
    {
        $user = Auth::user();
        $isAdmin = strtolower(optional($user->role)->name) === 'admin';
        $isOwner = $user->id === $penjualan->user_id;

        if (!($isAdmin || $isOwner) || $penjualan->status !== 'OPEN') {
            return redirect()->route('penjualan.index')->with('error', 'Aksi tidak diizinkan.');
        }

        DB::transaction(function () use ($penjualan) {
            foreach ($penjualan->itemPenjualan as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->kuantitas);
                }
            }

            $penjualan->itemPenjualan()->delete();
            $penjualan->delete();
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dibatalkan');
    }

    public function destroyAuto(Penjualan $penjualan)
    {
        if ($penjualan->status === 'OPEN') {
            DB::transaction(function () use ($penjualan) {
                foreach ($penjualan->itemPenjualan as $item) {
                    if ($item->produk) {
                        $item->produk->increment('stok', $item->kuantitas);
                    }
                }
                $penjualan->itemPenjualan()->delete();
                $penjualan->delete();
            });
        }
        return response()->noContent();
    }

    

    public function batalEdit(Penjualan $penjualan)
    {
        $user = Auth::user();
        $isAdmin = strtolower(optional($user->role)->name) === 'admin';
        $isOwner = $user->id === $penjualan->user_id;

        if (!($isAdmin || $isOwner)) {
            return redirect()->route('penjualan.index')->with('error', 'Aksi tidak diizinkan.');
        }

        DB::transaction(function () use ($penjualan) {
            if ($penjualan->status === 'OPEN' && $penjualan->itemPenjualan()->count() === 0) {
                $penjualan->delete();
            } else {
                foreach ($penjualan->itemPenjualan as $item) {
                    if ($item->produk) {
                        $item->produk->increment('stok', $item->kuantitas);
                    }
                }
                $penjualan->itemPenjualan()->delete();
                $penjualan->delete();
            }
        });

        return redirect()->route('penjualan.index');
    }
}

        SAYA SEDANG MENAMBAHKAN METODE TRANASAKSII KENAPA INI TIDA BISAA KESIMPAN apakahh
        harus ada perbaruan data base berikan cara nya gimana dan filee nyaa hrus apa 




        Ya, **benar sekali, Fraza!** Kamu wajib melakukan pembaruan pada **Database (Kolom Enum/Tabel)** dan **Validasi Controller** karena saat ini sistem menolak metode pembayaran baru seperti `"transfer"` atau lainnya jika database dan aturan validasinya belum mengizinkan.

Berikut adalah langkah-langkah lengkap untuk memperbaikinya:

---

### **Langkah 1: Perbarui Database (Migration / Database Langsung)**

Di controller kamu, validasi metode pembayaran dibatasi hanya untuk `CASH, QRIS, BAYAR_NANTI`. Karena kamu menambahkan metode baru seperti **`transfer`**, kolom `metode_pembayaran` di database (atau tipe kolomnya jika menggunakan ENUM) harus mengizinkan nilai tersebut.

Jika di database kamu menggunakan tipe data `ENUM`, jalankan perintah migrasi atau ubah langsung via phpMyAdmin/MySQL:

**Pilihan A: Jika menggunakan MySQL / phpMyAdmin langsung**
Buka tabel `penjualans` -> struktur kolom `metode_pembayaran`, ubah menjadi tipe **`VARCHAR(50)`** atau perbarui nilai **`ENUM`**-nya agar mencakup `'transfer'` (atau sesuaikan dengan huruf besar/kecilnya, misalnya `'TRANSFER'`).
*Sangat disarankan mengubah tipe kolomnya menjadi `VARCHAR(50)` supaya ke depannya tidak error lagi jika menambah metode pembayaran lain.*

**Pilihan B: Jika ingin via Migration Laravel**
Buat migration baru:

```bash
php artisan make:migration update_metode_pembayaran_in_penjualans_table

```

Lalu isi file migration tersebut:

```php
public function up()
{
    Schema::table('penjualans', function (Blueprint $table) {
        $table->string('metode_pembayaran', 50)->default('CASH')->change();
    });
}

```

Lalu jalankan terminal:

```bash
php artisan migrate

```

---

### **Langkah 2: Perbarui Validasi di `PenjualanController.php**`

Di method `update` pada file `PenjualanController.php`, validasi `payment_method` saat ini hanya membolehkan `CASH,QRIS,BAYAR_NANTI`. Kita harus menambahkan **`transfer`** ke dalamnya agar tidak ditolak oleh Laravel.

Cari bagian ini di `PenjualanController.php`:

```php
$request->validate([
    'payment_method' => 'required|in:CASH,QRIS,BAYAR_NANTI',
    // ...
]);

```

Ubah menjadi seperti ini (tambahkan `transfer`):

```php
$request->validate([
    'payment_method' => 'required|in:CASH,QRIS,transfer,BAYAR_NANTI',
    'uang_dibayar' => 'nullable',
    'kembalian' => 'nullable',
    'customer_name' => 'required_if:payment_method,BAYAR_NANTI|nullable|string|max:255',
    'customer_phone' => 'required_if:payment_method,BAYAR_NANTI|nullable|string|max:255',
    'due_date' => 'required_if:payment_method,BAYAR_NANTI|nullable|date',
]);

```

---

### **Langkah 3: Perbaiki HTML & JavaScript View POS Kamu**

Di kode HTML dropdown kamu sebelumnya, penulisan value ada yang huruf besar dan kecil (`'CASH'`, `'QRIS'`, `'transfer'`, `'BAYAR_NANTI'`). Pastikan JavaScript penangan tampilannya juga selaras.

Perhatikan perbaikan pada bagian form HTML dan script JavaScript kamu:

#### **1. Perbaikan HTML Dropdown & Container Transfer:**

Pastikan bagian `transferContainer` tidak memiliki duplikat `#uangError` dan valuenya konsisten:

```html
<div class="mb-3">
    <label class="form-label small fw-bold text-muted mb-1">Metode Pembayaran</label>
    <select name="payment_method" id="paymentMethodSelect" class="form-select rounded-3 shadow-none" required>
        <option value="">-- Pilih Metode Pembayaran --</option>
        <option value="CASH" {{ ($sale->metode_pembayaran ?? '') == 'CASH' ? 'selected' : '' }}>Cash (Tunai)</option>
        <option value="QRIS" {{ ($sale->metode_pembayaran ?? '') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
        <option value="transfer" {{ ($sale->metode_pembayaran ?? '') == 'transfer' ? 'selected' : '' }}>TRANSFER</option>
        <option value="BAYAR_NANTI" {{ ($sale->metode_pembayaran ?? '') == 'BAYAR_NANTI' ? 'selected' : '' }}>BAYAR NANTI</option>
    </select>
</div>

{{-- Transfer Container --}}
<div id="transferContainer" class="mb-3 d-none">
    <div class="mb-2">
        <label class="form-label small fw-bold text-muted mb-1">No. Rekening Tujuan</label>
        <div class="p-2 bg-white rounded-3 border d-flex justify-content-between align-items-center">
            <span class="small fw-semibold text-dark">1234-5678-9012 (BCA)</span>
        </div>
        <p class="text-muted small mt-1 mb-0" style="font-size: 0.75rem;">Silakan transfer sesuai total pembayaran lalu klik tombol simpan.</p>
    </div>                        
</div>

```

#### **2. Perbaikan JavaScript `updatePaymentUI()`:**

Pastikan fungsi JavaScript-nya mendeteksi string `transfer` dengan benar untuk memunculkan atau menyembunyikan elemen:

```javascript
const paymentSelect        = document.getElementById('paymentMethodSelect');
const qrisContainer        = document.getElementById('qrisContainer');
const transferContainer    = document.getElementById('transferContainer');
const cashContainer        = document.getElementById('cashContainer');
const bayarNantiContainer  = document.getElementById('bayarNantiContainer');
const inputUangDibayar     = document.getElementById('inputUangDibayar');
const textKembalian        = document.getElementById('textKembalian');
const inputHiddenKembalian = document.getElementById('inputHiddenKembalian');
const checkoutBtnText      = document.getElementById('checkoutBtnText');
const uangError            = document.getElementById('uangError');

function updatePaymentUI() {
    if (!paymentSelect) return;

    const method = paymentSelect.value;

    qrisContainer?.classList.add('d-none');
    transferContainer?.classList.add('d-none');
    cashContainer?.classList.add('d-none');
    bayarNantiContainer?.classList.add('d-none');
    uangError?.classList.add('d-none');

    if (method === 'QRIS') {
        qrisContainer?.classList.remove('d-none');
        if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
    } else if (method === 'transfer') {
        transferContainer?.classList.remove('d-none');
        if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
    } else if (method === 'CASH') {
        cashContainer?.classList.remove('d-none');
        if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
        if (typeof hitungKembalian === 'function') hitungKembalian();
    } else if (method === 'BAYAR_NANTI') {
        bayarNantiContainer?.classList.remove('d-none');
        if (checkoutBtnText) checkoutBtnText.innerText = 'Simpan & Bayar Nanti';
    } else {
        if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
    }
}

// Jalankan saat halaman dimuat dan saat pilihan berubah
document.addEventListener('DOMContentLoaded', function() {
    updatePaymentUI();
    paymentSelect?.addEventListener('change', updatePaymentUI);
});

```

### **Ringkasan File yang Harus Diperbarui:**

1. **Database:** Ubah tipe kolom `metode_pembayaran` di tabel `penjualans` menjadi `VARCHAR(50)` (atau tambahkan `'transfer'` ke dalam setting ENUM-nya).
2. **`PenjualanController.php`:** Perbarui baris validasi `'payment_method' => 'required|in:CASH,QRIS,transfer,BAYAR_NANTI'` di fungsi `update`.
3. **File View POS / Checkout (Blade):** Sesuaikan value opsi dropdown `<option value="transfer">` dan pastikan fungsi JavaScript `updatePaymentUI()` terpanggil dengan mulus.





Langkah 2: Perbarui Validasi di PenjualanController.php
Di method update pada file PenjualanController.php, validasi payment_method saat ini hanya membolehkan CASH,QRIS,BAYAR_NANTI. Kita harus menambahkan transfer ke dalamnya agar tidak ditolak oleh Laravel.

Cari bagian ini di PenjualanController.php:

PHP
$request->validate([
    'payment_method' => 'required|in:CASH,QRIS,BAYAR_NANTI',
    // ...
]);
Ubah menjadi seperti ini (tambahkan transfer):

PHP
$request->validate([
    'payment_method' => 'required|in:CASH,QRIS,transfer,BAYAR_NANTI',
    'uang_dibayar' => 'nullable',
    'kembalian' => 'nullable',
    'customer_name' => 'required_if:payment_method,BAYAR_NANTI|nullable|string|max:255',
    'customer_phone' => 'required_if:payment_method,BAYAR_NANTI|nullable|string|max:255',
    'due_date' => 'required_if:payment_method,BAYAR_NANTI|nullable|date',
]);
Langkah 3: Perbaiki HTML & JavaScript View POS Kamu
Di kode HTML dropdown kamu sebelumnya, penulisan value ada yang huruf besar dan kecil ('CASH', 'QRIS', 'transfer', 'BAYAR_NANTI'). Pastikan JavaScript penangan tampilannya juga selaras.

Perhatikan perbaikan pada bagian form HTML dan script JavaScript kamu:

1. Perbaikan HTML Dropdown & Container Transfer:
Pastikan bagian transferContainer tidak memiliki duplikat #uangError dan valuenya konsisten:

HTML
<div class="mb-3">
    <label class="form-label small fw-bold text-muted mb-1">Metode Pembayaran</label>
    <select name="payment_method" id="paymentMethodSelect" class="form-select rounded-3 shadow-none" required>
        <option value="">-- Pilih Metode Pembayaran --</option>
        <option value="CASH" {{ ($sale->metode_pembayaran ?? '') == 'CASH' ? 'selected' : '' }}>Cash (Tunai)</option>
        <option value="QRIS" {{ ($sale->metode_pembayaran ?? '') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
        <option value="transfer" {{ ($sale->metode_pembayaran ?? '') == 'transfer' ? 'selected' : '' }}>TRANSFER</option>
        <option value="BAYAR_NANTI" {{ ($sale->metode_pembayaran ?? '') == 'BAYAR_NANTI' ? 'selected' : '' }}>BAYAR NANTI</option>
    </select>
</div>

{{-- Transfer Container --}}
<div id="transferContainer" class="mb-3 d-none">
    <div class="mb-2">
        <label class="form-label small fw-bold text-muted mb-1">No. Rekening Tujuan</label>
        <div class="p-2 bg-white rounded-3 border d-flex justify-content-between align-items-center">
            <span class="small fw-semibold text-dark">1234-5678-9012 (BCA)</span>
        </div>
        <p class="text-muted small mt-1 mb-0" style="font-size: 0.75rem;">Silakan transfer sesuai total pembayaran lalu klik tombol simpan.</p>
    </div>                        
</div>
2. Perbaikan JavaScript updatePaymentUI():
Pastikan fungsi JavaScript-nya mendeteksi string transfer dengan benar untuk memunculkan atau menyembunyikan elemen:

JavaScript
const paymentSelect        = document.getElementById('paymentMethodSelect');
const qrisContainer        = document.getElementById('qrisContainer');
const transferContainer    = document.getElementById('transferContainer');
const cashContainer        = document.getElementById('cashContainer');
const bayarNantiContainer  = document.getElementById('bayarNantiContainer');
const inputUangDibayar     = document.getElementById('inputUangDibayar');
const textKembalian        = document.getElementById('textKembalian');
const inputHiddenKembalian = document.getElementById('inputHiddenKembalian');
const checkoutBtnText      = document.getElementById('checkoutBtnText');
const uangError            = document.getElementById('uangError');

function updatePaymentUI() {
    if (!paymentSelect) return;

    const method = paymentSelect.value;

    qrisContainer?.classList.add('d-none');
    transferContainer?.classList.add('d-none');
    cashContainer?.classList.add('d-none');
    bayarNantiContainer?.classList.add('d-none');
    uangError?.classList.add('d-none');

    if (method === 'QRIS') {
        qrisContainer?.classList.remove('d-none');
        if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
    } else if (method === 'transfer') {
        transferContainer?.classList.remove('d-none');
        if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
    } else if (method === 'CASH') {
        cashContainer?.classList.remove('d-none');
        if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
        if (typeof hitungKembalian === 'function') hitungKembalian();
    } else if (method === 'BAYAR_NANTI') {
        bayarNantiContainer?.classList.remove('d-none');
        if (checkoutBtnText) checkoutBtnText.innerText = 'Simpan & Bayar Nanti';
    } else {
        if (checkoutBtnText) checkoutBtnText.innerText = 'Checkout & Selesaikan';
    }
}

// Jalankan saat halaman dimuat dan saat pilihan berubah
document.addEventListener('DOMContentLoaded', function() {
    updatePaymentUI();
    paymentSelect?.addEventListener('change', updatePaymentUI);
});
Ringkasan File yang Harus Diperbarui:
Database: Ubah tipe kolom metode_pembayaran di tabel penjualans menjadi VARCHAR(50) (atau tambahkan 'transfer' ke dalam setting ENUM-nya).

PenjualanController.php: Perbarui baris validasi 'payment_method' => 'required|in:CASH,QRIS,transfer,BAYAR_NANTI' di fungsi update.

File View POS / Checkout (Blade): Sesuaikan value opsi dropdown <option value="transfer"> dan pastikan fungsi JavaScript updatePaymentUI() terpanggil dengan mulus.