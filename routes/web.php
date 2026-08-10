<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ItemPenjualanController;
use App\Http\Controllers\JenisProdukController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'auth'])->name('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Grup manajemen user (khusus role admin, ditegakkan lewat RoleMiddleware)
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
        Route::resource('/jenis-produk', JenisProdukController::class)->except(['index', 'create', 'edit']);
    });
    // Jenis Produk: Rute publik (index) untuk semua role login
    Route::resource('/jenis-produk', JenisProdukController::class)->only(['index']);

    // Penjualan & item penjualan: transaksi POS, dapat diakses semua role login
    Route::resource('/penjualan', PenjualanController::class);
    Route::resource('/itempenjualan', ItemPenjualanController::class);
});