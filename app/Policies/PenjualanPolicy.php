<?php

namespace App\Policies;

use App\Models\Penjualan;
use App\Models\User;

class PenjualanPolicy
{
    public function view(User $user, Penjualan $penjualan): bool
    {
        return true;
    }

    public function update(User $user, Penjualan $penjualan): bool
    {
        $isAdmin = optional($user->role)->name === 'admin' || optional($user->role)->name === 'Admin' || $user->role_id == 1;
        $isOwner = $user->id === $penjualan->user_id;

        return ($isAdmin || $isOwner) && $penjualan->status === 'OPEN';
    }

    public function delete(User $user, Penjualan $penjualan): bool
    {
        // 1. Jika user adalah admin (sesuaikan dengan cara aplikasi Anda mengecek admin, misal ID role = 1 atau nama role)
        // Kita gunakan pengecekan aman untuk mengantisipasi struktur relasi role:
        $isAdmin = optional($user->role)->name === 'admin' || optional($user->role)->name === 'Admin' || $user->role_id == 1; 
        
        // 2. Cek apakah user adalah pemilik transaksi
        $isOwner = $user->id === $penjualan->user_id;

        // Boleh hapus jika (Admin ATAU Pemilik) DAN statusnya masih OPEN
        return ($isAdmin || $isOwner) && $penjualan->status === 'OPEN';
    }
}