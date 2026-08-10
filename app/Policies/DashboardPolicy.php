<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    public function viewAny(User $user): bool
    {
        // Izinkan jika rolenya adalah Admin (mendukung huruf besar/kecil atau role_id == 1)
        return strtolower(optional($user->role)->name) === 'admin' || $user->role_id == 1;
    }
}