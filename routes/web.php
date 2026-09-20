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

// Rute untuk Tamu (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'auth'])->name('auth.login');
});

// Rute untuk Pengguna yang Sudah Login (Auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rute untuk menyimpan target dashboard
    Route::post('/dashboard/update-target', [DashboardController::class, 'updateTarget'])->name('settings.update-target');

    // Halaman Rekapitulasi (Dapat diakses semua user yang login)
    Route::get('/recap', [RekapController::class, 'index'])->name('recap.index');

    // Grup manajemen user (khusus role admin)
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/update/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/destroy/{user}', [UserController::class, 'destroy'])->name('users.destroy');
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

    // Penjualan & item penjualan: transaksi POS, dapat diakses semua role login
    Route::resource('/penjualan', PenjualanController::class);
    
    // Rute otomatis untuk mengubah status jadi BAYAR_NANTI
    Route::post('/penjualan/{penjualan}/bayar-nanti-auto', [PenjualanController::class, 'setBayarNanti'])->name('penjualan.bayarNantiAuto'); 
    
    // Rute untuk membatalkan edit penjualan
    Route::delete('/penjualan/{penjualan}/batal-edit', [PenjualanController::class, 'batalEdit'])->name('penjualan.batalEdit');

    Route::resource('/itempenjualan', ItemPenjualanController::class);

    // Rute Pembelajaran / Tugas
    Route::get('/tes/perulangan', [perulanganController::class, 'index'])->name('tes.perulangan');
    Route::get('/tes/percabangan', [percabanganController::class, 'index'])->name('tes.percabangan');
    Route::get('/tes/variable', [variabelController::class, 'index'])->name('tes.variable');
    
    Route::get('/Perusahaan', [PerusahaanController::class, 'index'])->name('Perusahaan');
});

// Halaman About Publik
Route::view('/about', 'about')->name('about');