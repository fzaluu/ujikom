<style>
    .sidebar-pos {
        width: 270px;
        height: 100vh;
        position: fixed;
        top: 0; left: 0;
        z-index: 1050;
        background-color: #ffffff !important;
        border-right: 1px solid #e2e8f0;
    }

    .sidebar-pos .brand-title {
        letter-spacing: -0.02em;
        font-weight: 700;
        font-size: 1.1rem;
        line-height: 1.2;
        color: #0f172a;
    }

    .sidebar-pos .nav-link {
        color: #64748b;
        transition: all 0.2s ease;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .sidebar-pos .nav-link:hover:not(.active) {
        background-color: #f1f5f9;
        color: #0f172a !important;
    }

    .sidebar-pos .nav-link.active {
        background-color: #eff6ff !important;
        color: #2563eb !important;
        font-weight: 600;
    }

    .sidebar-divider {
        border-color: #e2e8f0 !important;
    }

    .sidebar-profile {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.85rem;
    }

    /* Tombol Logout Clean Light */
    .btn-logout-clean {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .btn-logout-clean:hover {
        background-color: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
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

<div class="d-flex flex-column flex-shrink-0 p-4 sidebar-pos shadow-sm">
    
    <!-- Logo / Brand -->
    <!-- Logo / Brand (Centered) -->
<!-- Judul Brand Saja (Centered) -->
<a href="{{ route('dashboard') }}" class="d-flex flex-column align-items-center text-center mb-3 text-decoration-none py-2">
    <span class="brand-title mb-0 fw-bold text-dark fs-5" style="letter-spacing: 0.5px;">POS SMART</span>
    <small class="text-muted" style="font-size: 0.65rem;">Modern Point Of Sale</small>
</a>
    
    <hr class="sidebar-divider my-1">
    
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
    
    <!-- Profil Pengguna & Logout -->
    <div class="mt-auto sidebar-profile">
        <div class="d-flex align-items-center mb-3 gap-3">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.85rem; letter-spacing: 0.5px;">
                {{ $initials }}
            </div>
            <div class="d-flex flex-column justify-content-center overflow-hidden" style="line-height: 1.3;">
                <strong class="text-dark text-truncate d-block small mb-0">{{ $name }}</strong>
                <span class="text-muted d-block" style="font-size: 0.7rem;">{{ ucfirst(optional(Auth::user()?->role)->name ?? 'Administrator') }}</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout-clean btn-sm w-100 rounded-2 py-1.5 d-flex align-items-center justify-content-center gap-2 shadow-none" style="font-size: 0.8rem;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>