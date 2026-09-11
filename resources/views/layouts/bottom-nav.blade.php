<style>
    /* ============ BOTTOM NAVIGATION KHUSUS MOBILE ============ */
    .mobile-bottom-nav {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 64px;
        background-color: #ffffff;
        border-top: 1px solid #e2e8f0;
        box-shadow: 0 -2px 8px rgba(15, 23, 42, 0.06);
        align-items: center;
        padding: 0 0.5rem;
        z-index: 1040;
    }

    /* Kelompok ikon di sisi kiri/kanan bar, masing-masing mengambil separuh lebar
       agar tombol transaksi (FAB) di tengah selalu presisi center apapun jumlah ikonnya */
    .mobile-bottom-nav-side {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-evenly;
    }

    .mobile-bottom-nav-item {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background-color: transparent;
        border: none;
        color: #64748b;
        font-size: 1.05rem;
        text-decoration: none;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .mobile-bottom-nav-item:hover,
    .mobile-bottom-nav-item.active {
        color: #2563eb;
        background-color: #eff6ff;
    }

    .mobile-bottom-nav-fab-wrap {
        position: absolute;
        left: 50%;
        top: -28px;
        transform: translateX(-50%);
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background-color: var(--bg-body);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mobile-bottom-nav-fab {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        text-decoration: none;
        box-shadow: 0 6px 14px rgba(37, 99, 235, 0.4);
        transition: all 0.2s ease;
    }

    .mobile-bottom-nav-fab:hover {
        background-color: var(--primary-hover);
        color: #ffffff;
    }

    @media (max-width: 991.98px) {
        .mobile-bottom-nav {
            display: flex;
        }

        /* Beri jarak agar konten paling bawah tidak tertutup bottom nav */
        .main-content {
            padding-bottom: 76px;
        }
    }
</style>

<!-- Bottom Navigation Mobile: menu sama persis dengan sidebar desktop + 1 tombol transaksi (FAB) di tengah -->
<nav class="mobile-bottom-nav d-print-none">
    <div class="mobile-bottom-nav-side">
        <a href="{{ route('dashboard') }}" class="mobile-bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
            <i class="bi bi-speedometer2"></i>
        </a>

        @if(auth()->check() && (optional(auth()->user()->role)->name === 'admin' || auth()->user()->role_id == 1))
        <a href="{{ route('admin.users') }}" class="mobile-bottom-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}" title="User">
            <i class="bi bi-people"></i>
        </a>
        <a href="{{ route('jenis-produk.index') }}" class="mobile-bottom-nav-item {{ request()->routeIs('jenis-produk*') ? 'active' : '' }}" title="Jenis">
            <i class="bi bi-tags"></i>
        </a>
        @else
        <a href="{{ route('produk.index') }}" class="mobile-bottom-nav-item {{ request()->routeIs('produk*') ? 'active' : '' }}" title="Produk">
            <i class="bi bi-box-seam"></i>
        </a>
        @endif
    </div>

    <div class="mobile-bottom-nav-fab-wrap">
        <a href="{{ route('penjualan.create') }}" class="mobile-bottom-nav-fab" title="Transaksi Baru">
            <i class="bi bi-plus-lg"></i>
        </a>
    </div>

    <div class="mobile-bottom-nav-side">
        @if(auth()->check() && (optional(auth()->user()->role)->name === 'admin' || auth()->user()->role_id == 1))
        <a href="{{ route('produk.index') }}" class="mobile-bottom-nav-item {{ request()->routeIs('produk*') ? 'active' : '' }}" title="Produk">
            <i class="bi bi-box-seam"></i>
        </a>
        @endif

        <a href="{{ route('penjualan.index') }}" class="mobile-bottom-nav-item {{ request()->routeIs('penjualan*') ? 'active' : '' }}" title="Penjualan">
            <i class="bi bi-bag-check"></i>
        </a>
        <button type="button" class="mobile-bottom-nav-item" onclick="openLogoutModal()" title="Keluar">
            <i class="bi bi-box-arrow-right"></i>
        </button>
    </div>
</nav>