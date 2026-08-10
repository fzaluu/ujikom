<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use App\Models\User;

use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use App\Models\Produk;
use App\Policies\DashboardPolicy;
use App\Policies\ProdukPolicy;
use App\Policies\PenjualanPolicy;
use App\Policies\ItemPenjualanPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class          => DashboardPolicy::class,
        Produk::class        => ProdukPolicy::class,
        Penjualan::class     => PenjualanPolicy::class,
        ItemPenjualan::class => ItemPenjualanPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();
        Carbon::setLocale('id');
        $this->registerPolicies();
        Gate::policy(Penjualan::class, PenjualanPolicy::class);
    }
}