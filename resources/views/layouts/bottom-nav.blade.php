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
        justify-content: space-between;
        padding: 0 0.5rem;
        z-index: 1040;
    }

    .mobile-bottom-nav-side {
        width: calc(50% - 36px);
        display: flex;
        align-items: center;
        /* Menggunakan space-evenly agar jarak antar ikon selalu merata otomatis */
        justify-content: space-evenly; 
    }

    .mobile-bottom-nav-item {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: transparent;
        border: none;
        color: #64748b;
        font-size: 1.1rem;
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
        top: -26px;
        transform: translateX(-50%);
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 -3px 8px rgba(15, 23, 42, 0.04);
    }

    .mobile-bottom-nav-fab {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #2563eb;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        transition: all 0.2s ease;
    }

    .mobile-bottom-nav-fab:hover {
        background-color: #1d4ed8;
        color: #ffffff;
    }

    @media (max-width: 991.98px) {
        .mobile-bottom-nav {
            display: flex;
        }
        .main-content {
            padding-bottom: 76px;
        }
    }
</style>

<!-- Bottom Navigation Mobile -->
<nav class="mobile-bottom-nav d-print-none">
    {{-- Sisi Kiri --}}
    <div class="mobile-bottom-nav-side">
        <a href="{{ route('dashboard') }}" class="mobile-bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
            <i class="bi bi-speedometer2"></i>
        </a>

        @if(auth()->check() && (optional(auth()->user()->role)->name === 'admin' || auth()->user()->role_id == 1))
        <a href="{{ route('jenis-produk.index') }}" class="mobile-bottom-nav-item {{ request()->routeIs('jenis-produk*') ? 'active' : '' }}" title="Jenis Produk">
            <i class="bi bi-tags"></i>
        </a>
        @endif
        
        <a href="{{ route('produk.index') }}" class="mobile-bottom-nav-item {{ request()->routeIs('produk*') ? 'active' : '' }}" title="Produk">
            <i class="bi bi-box-seam"></i>
        </a>
    </div>

    {{-- Tombol Transaksi Tengah (FAB) --}}
    <div class="mobile-bottom-nav-fab-wrap">
        <a href="{{ route('penjualan.create') }}" class="mobile-bottom-nav-fab" title="Transaksi Baru">
            <i class="bi bi-plus-lg"></i>
        </a>
    </div>

    {{-- Sisi Kanan --}}
    <div class="mobile-bottom-nav-side">
        <a href="{{ route('penjualan.index') }}" class="mobile-bottom-nav-item {{ request()->routeIs('penjualan*') ? 'active' : '' }}" title="Penjualan">
            <i class="bi bi-bag-check"></i>
        </a>

        @if(auth()->check() && (optional(auth()->user()->role)->name === 'admin' || auth()->user()->role_id == 1))
        <a href="{{ route('admin.users') }}" class="mobile-bottom-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}" title="Pengguna">
            <i class="bi bi-people"></i>
        </a>

        
        <a href="{{ route('recap.index') }}" class="mobile-bottom-nav-item {{ request()->routeIs('recap*') ? 'active' : '' }}" title="Rekapitulasi">
            <i class="bi bi-file-earmark-text"></i>
        </a>


        @endif
    </div>
</nav>