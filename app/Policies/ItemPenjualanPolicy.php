<?php

namespace App\Policies;

use App\Models\ItemPenjualan;
use App\Models\User;

class ItemPenjualanPolicy
{
    public function update(User $user, ItemPenjualan $itempenjualan): bool
    {
        $isAdmin = strtolower(optional($user->role)->name) === 'admin' || $user->role_id == 1;

        // Admin atau kasir pemilik transaksi yang statusnya masih OPEN boleh mengubah kuantitas item
        return $isAdmin || ($user->id === $itempenjualan->penjualan->user_id && $itempenjualan->penjualan->status === 'OPEN');
    }

    public function delete(User $user, ItemPenjualan $itempenjualan): bool
    {
        // Cek admin dengan aman menggunakan strtolower atau role_id == 1
        $isAdmin = strtolower(optional($user->role)->name) === 'admin' || $user->role_id == 1;
        
        // Admin atau kasir pemilik transaksi yang statusnya masih OPEN boleh menghapus item
        return $isAdmin || ($user->id === $itempenjualan->penjualan->user_id && $itempenjualan->penjualan->status === 'OPEN');
    }
}