<div align="center">

# 🛒 RAJA CELL
### Sistem Aplikasi Kasir (Point of Sale) Berbasis Web

Aplikasi POS untuk mengelola transaksi penjualan, produk, dan stok pada usaha konter HP — dibangun sebagai proyek Uji Kompetensi Keahlian (UKK) Kompetensi Keahlian Pengembangan Perangkat Lunak dan Gim (PPLG), SMK Negeri 4 Tasikmalaya.

![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

</div>

---

cara menghapus data dummy

Buka halaman phpMyAdmin, lalu klik nama database kamu (ujikom atau contoh).

Pilih menu SQL di bagian atas.

PENTING: Pastikan kotak pilihan "Enable foreign key checks" yang ada di bawah kotak teks SQL dihilangkan centangnya (di-uncheck).

Salin dan tempel kode SQL ini secara utuh ke dalam kotak:

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE item_penjualan;
TRUNCATE TABLE penjualan;
TRUNCATE TABLE produk;

SET FOREIGN_KEY_CHECKS = 1;


Setelah jalan
Login default setelah seed:

admin / Admin@2026!
kasir / Kasir@2026!

## 📖 Tentang Aplikasi

**RAJA CELL** adalah sistem Point of Sale (POS) berbasis web yang dibangun untuk menggantikan proses pencatatan transaksi manual pada usaha konter HP. Aplikasi ini menangani pengelolaan produk, kategori produk, transaksi penjualan, manajemen pengguna, serta menyediakan dashboard ringkasan bisnis secara real-time.

## ✨ Fitur Utama

- 🔐 **Autentikasi & Role-Based Access Control** — dua peran (Admin & Kasir) dengan hak akses yang berbeda pada level route maupun tampilan
- 📊 **Dashboard** — ringkasan penjualan hari ini, produk terlaris (lengkap dengan foto asli), status stok menipis/habis
- 📦 **Manajemen Produk** — CRUD produk lengkap dengan foto (dengan pratinjau otomatis) dan kategori/jenis produk
- 🏷️ **Manajemen Jenis Produk** — pengelompokan produk berdasarkan kategori, dengan filter langsung dari halaman kategori
- 🧾 **Transaksi Penjualan (Kasir POS)** — keranjang belanja, validasi stok otomatis, perhitungan total otomatis, metode pembayaran CASH/QRIS
- 👥 **Manajemen Pengguna** — khusus Admin, dengan proteksi agar Admin tidak bisa menghapus akunnya sendiri
- 🔔 **Notifikasi Real-Time** — dikumpulkan otomatis dari transaksi terbaru, status stok, dan aktivitas login (bukan data dummy)

## 🛠️ Teknologi

| Komponen | Versi |
|---|---|
| PHP | 8.3.16 |
| Laravel | 12.64.0 |
| MySQL | 8.0.30 |
| Bootstrap | 5.3.8 |
| Composer | 2.4.1 |
| Vite | 6.0.11 |

## 🚀 Instalasi & Menjalankan Secara Lokal

### Prasyarat
Pastikan sudah terpasang: PHP ≥ 8.2, Composer, Node.js & npm, MySQL/MariaDB.

```bash
# 1. Clone repository
git clone https://github.com/username/raja-cell.git
cd raja-cell

# 2. Install dependency PHP
composer install

# 3. Install dependency JS & build asset
npm install
npm run build

# 4. Siapkan file environment
cp .env.example .env
php artisan key:generate
```

### Konfigurasi Database
Buat database kosong (misalnya `ujikom`), lalu sesuaikan kredensial di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ujikom
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 5. Jalankan migration + seeder
php artisan migrate --seed

# 6. Hubungkan storage (wajib, agar foto produk bisa tampil)
php artisan storage:link

# 7. Jalankan server
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser. Kredensial akun default dapat dilihat pada `database/seeders/UserSeeder.php`.

> ⚠️ Jika database sudah berisi data (bukan instalasi baru), jalankan `php artisan migrate` saja **tanpa** `--seed` atau `--fresh`, supaya data lama tidak ikut terhapus.

## 👤 Hak Akses Pengguna

| Peran | Akses |
|---|---|
| **Admin** | Akses penuh: kelola produk, jenis produk, pengguna, lihat seluruh transaksi & data finansial |
| **Kasir** | Melakukan transaksi penjualan, melihat produk (tanpa harga beli), melihat transaksi miliknya sendiri |

## 📁 Struktur Proyek Singkat

```
app/
├── Http/Controllers/   # Logika request (Produk, Penjualan, User, dll.)
├── Http/Requests/      # Validasi form terpisah per aksi
├── Models/             # Eloquent model (Produk, Penjualan, User, dll.)
├── Policies/           # Aturan otorisasi per model
└── Services/           # Logika bisnis (laporan, notifikasi, monitoring stok)

database/migrations/    # Struktur tabel database
resources/views/        # Tampilan Blade
routes/web.php          # Definisi seluruh route
```

## 📄 Dokumentasi

Dokumentasi lengkap (BAB I–V, ERD, use case, activity diagram, dsb.) untuk keperluan UKK tersedia terpisah pada laporan proyek.

## 📝 Lisensi

Proyek ini dibuat untuk keperluan pembelajaran dan Uji Kompetensi Keahlian (UKK). Bebas digunakan sebagai referensi belajar.

---

<div align="center">
Dibuat oleh <b>Fraza Saka Afgani</b> — XII PPLG 2, SMK Negeri 4 Tasikmalaya
</div>