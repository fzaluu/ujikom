<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ItemPenjualanController;
use App\Http\Controllers\JenisProdukController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\perulanganController;
use App\Http\Controllers\percabanganController;
use App\Http\Controllers\variabelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RekapController;


// Halaman informasi publik (tanpa login) - profil toko, layanan, produk best seller
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home-search', [HomeController::class, 'searchAjax'])->name('home.search');

// Rute untuk Tamu (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'auth'])->name('auth.login');
});

// Rute untuk Pengguna yang Sudah Login (Auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rute untuk menyimpan target dashboard (Dilindungi middleware admin agar kasir tidak bisa akses via POST)
    Route::middleware('role:admin')->post('/dashboard/update-target', [DashboardController::class, 'updateTarget'])->name('settings.update-target');

    // Grup manajemen user (khusus role admin)
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/update/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/destroy/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Halaman Rekapitulasi (Dipisah dari prefix admin agar URL bersih, tapi dikunci khusus Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/recap', [RekapController::class, 'index'])->name('recap.index');
        Route::get('/recap/export', [RekapController::class, 'export'])->name('recap.export');
    });

    // Produk: Rute khusus ADMIN (ditaruh di ATAS agar rute 'create' tidak dianggap parameter ID)
    Route::middleware('role:admin')->group(function () {
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::resource('/produk', ProdukController::class)->except(['index', 'show', 'create', 'edit']);
    });
    
    // Produk: Rute publik (index & show) untuk semua role login
    Route::resource('/produk', ProdukController::class)->only(['index', 'show']);

    // Jenis Produk: Rute khusus ADMIN (ditaruh di ATAS)
    Route::middleware('role:admin')->group(function () {
        Route::get('/jenis-produk/create', [JenisProdukController::class, 'create'])->name('jenis-produk.create');
        Route::get('/jenis-produk/{jenis_produk}/edit', [JenisProdukController::class, 'edit'])->name('jenis-produk.edit');
        Route::resource('/jenis-produk', JenisProdukController::class)->except(['create', 'edit']);
    });

    // ==========================================
    // PERBAIKAN UTAMA: PENJUALAN (EXPLICIT ROUTES)
    // ==========================================
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    
    // Rute aksi khusus (Diletakkan di ATAS rute parameter {penjualan} agar tidak tertukar)
    Route::post('/penjualan/{penjualan}/bayar-nanti-auto', [PenjualanController::class, 'setBayarNanti'])->name('penjualan.bayarNantiAuto'); 
    Route::delete('/penjualan/{penjualan}/batal-edit', [PenjualanController::class, 'batalEdit'])->name('penjualan.batalEdit');

    // Rute dengan parameter ID dinamis
    Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::get('/penjualan/{penjualan}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::put('/penjualan/{penjualan}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::delete('/penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

    // Item Penjualan (Kecuali store, update, destroy yang dipakai sistem POS)
    Route::resource('/itempenjualan', ItemPenjualanController::class)->except(['create', 'edit', 'show', 'index']);

    // Rute Pembelajaran / Tugas
    Route::get('/tes/perulangan', [perulanganController::class, 'index'])->name('tes.perulangan');
    Route::get('/tes/percabangan', [percabanganController::class, 'index'])->name('tes.percabangan');
    Route::get('/tes/variable', [variabelController::class, 'index'])->name('tes.variable');
    
    Route::get('/Perusahaan', [PerusahaanController::class, 'index'])->name('Perusahaan');
});

// Halaman About Publik
Route::view('/about', 'about')->name('about');