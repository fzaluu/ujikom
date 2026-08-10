<?php

namespace App\Policies;

use App\Models\Produk;
use App\Models\User;

class ProdukPolicy
{
    public function view(User $user, Produk $produk): bool
    {
        // Gunakan strtolower agar aman dari perbedaan huruf besar/kecil
        $roleName = strtolower(optional($user->role)->name);
        return in_array($roleName, ['admin', 'kasir']) || $user->role_id == 1 || $user->role_id == 2;
    }
}