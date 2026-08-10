<style>
    .sidebar-pos {
        width: 270px;
        height: 100vh;
        position: fixed;
        top: 0; left: 0;
        z-index: 1050;
        background-color: var(--sidebar-bg, #0F172A) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.05);
    }

    .sidebar-pos .brand-title {
        letter-spacing: -0.02em;
        font-weight: 700;
        font-size: 1.1rem;
        line-height: 1.2;
    }

    .sidebar-pos .nav-link {
        color: #94A3B8;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .sidebar-pos .nav-link:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.05);
        color: #FFFFFF !important;
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .sidebar-pos .nav-link.active {
        background-color: var(--primary-color, #2563EB) !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .sidebar-divider {
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    .sidebar-profile {
        background-color: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        padding: 0.85rem;
    }
</style>

@php
    $name = Auth::user()?->name ?? 'User';
    $words = explode(' ', trim($name));
    $initials = '';
    if (count($words) >= 2) {
        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
    } else {
        $initials = strtoupper(substr($name, 0, 2));
    }
@endphp

<div class="d-flex flex-column flex-shrink-0 p-4 text-white sidebar-pos shadow">
    
    <!-- Logo / Brand (Centered Vertically) -->
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-2 text-white text-decoration-none gap-3">
        <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 42px; height: 42px;">
            <i class="bi bi-cart3 fs-5"></i>
        </div>
        <div class="d-flex flex-column justify-content-center">
            <span class="brand-title text-white mb-0">POS SMART</span>
            <small class="text-muted" style="font-size: 0.7rem; margin-top: -2px;">Modern Point Of Sale</small>
        </div>
    </a>
    
    <hr class="sidebar-divider my-3">
    
    <!-- Menu Navigasi -->
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 fs-5"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('penjualan.index') }}" class="nav-link {{ request()->routeIs('penjualan*') ? 'active' : '' }}">
                <i class="bi bi-bag-check fs-5"></i> Penjualan
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk*') ? 'active' : '' }}">
                <i class="bi bi-box-seam fs-5"></i> Produk
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('jenis-produk.index') }}" class="nav-link {{ request()->routeIs('jenis-produk*') ? 'active' : '' }}">
                <i class="bi bi-tags fs-5"></i> Jenis Produk
            </a>
        </li>

        @if(auth()->check() && (optional(auth()->user()->role)->name === 'admin' || auth()->user()->role_id == 1))
            <li class="nav-item">
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people fs-5"></i> User
                </a>
            </li>
        @endif
    </ul>
    
    <hr class="sidebar-divider my-3">
    
    <!-- Profil Pengguna & Logout (Avatar & Teks Pas di Tengah Vertikal) -->
    <div class="mt-auto sidebar-profile">
        <div class="d-flex align-items-center mb-3 gap-3">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.85rem; letter-spacing: 0.5px;">
                {{ $initials }}
            </div>
            <div class="d-flex flex-column justify-content-center overflow-hidden" style="line-height: 1.3;">
                <strong class="text-white text-truncate d-block small mb-0">{{ $name }}</strong>
                <span class="text-muted d-block" style="font-size: 0.7rem;">{{ ucfirst(optional(Auth::user()?->role)->name ?? 'Administrator') }}</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100 border-opacity-25 py-1.5 text-start d-flex align-items-center justify-content-center gap-2" style="font-size: 0.8rem;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>