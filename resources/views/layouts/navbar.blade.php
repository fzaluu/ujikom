<style>
    .sidebar-pos {
        width: 270px;
        height: 100vh;
        position: fixed;
        top: 0; left: 0;
        z-index: 1050;
        background-color: #ffffff !important;
        border-right: 1px solid #e2e8f0;
        transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-x: hidden;
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

    /* ============ TOMBOL LOGOUT ============ */
    .btn-logout-clean {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        color: #64748b;
        transition: all 0.2s ease;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .btn-logout-clean:hover {
        background-color: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
    }

    .btn-logout-clean i.bi-box-arrow-right {
        margin: 0 !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* ============ TOMBOL TOGGLE SIDEBAR ============ */
    .sidebar-toggle-btn {
        position: fixed;
        top: 22px;
        left: calc(var(--sidebar-width) - 13px);
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        padding: 0;
        z-index: 1070;
        cursor: pointer;
        transition: left 0.25s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }
    
    .sidebar-toggle-btn:hover {
        background-color: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
    }
    
    .sidebar-toggle-btn i {
        font-size: 0.85rem;
        transition: transform 0.25s ease;
    }
    
    body.sidebar-collapsed .sidebar-toggle-btn {
        left: calc(var(--sidebar-width-collapsed) - 13px);
    }

    /* ============ MODE DICIUTKAN (ICON-ONLY) ============ */
    body.sidebar-collapsed .sidebar-pos {
        width: 84px;
    }

    body.sidebar-collapsed .sidebar-pos.p-4 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    body.sidebar-collapsed .nav-label,
    body.sidebar-collapsed .profile-text,
    body.sidebar-collapsed .logout-text {
        display: none !important;
    }

    body.sidebar-collapsed .brand-title,
    body.sidebar-collapsed .brand-subtitle {
        display: none !important;
    }

    .brand-icon-collapsed {
        display: none;
    }
    
    body.sidebar-collapsed .brand-icon-collapsed {
        display: flex !important;
    }

    body.sidebar-collapsed .nav-link {
        justify-content: center;
        padding: 0.75rem 0.5rem;
    }

    body.sidebar-collapsed .sidebar-profile {
        padding: 0.6rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    body.sidebar-collapsed .sidebar-profile .profile-row {
        justify-content: center !important;
        margin-bottom: 0.5rem !important;
        gap: 0 !important;
        width: 100%;
    }

    /* Tombol Logout khusus Mode Ciut */
    body.sidebar-collapsed .btn-logout-clean {
        width: 38px !important;
        height: 38px !important;
        padding: 0 !important;
        margin: 0 auto !important;
    }

    body.sidebar-collapsed .btn-logout-clean i.bi-box-arrow-right {
        position: relative;
        left: 1px;
    }

    body.sidebar-collapsed .sidebar-toggle-btn i {
        transform: rotate(180deg);
    }

    /* ============ RESPONSIVE / MOBILE ============ */
    @media (max-width: 991.98px) {
        .sidebar-toggle-btn {
            display: none;
        }
        .sidebar-pos {
            display: none !important;
        }
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

<!-- Tombol Toggle Buka/Tutup Sidebar (hanya ikon, di luar sidebar-pos agar tidak ter-clip) -->
<button type="button" id="sidebarToggleBtn" class="sidebar-toggle-btn" title="Ciutkan/Lebarkan Sidebar">
    <i class="bi bi-chevron-left"></i>
</button>

<div class="d-flex flex-column flex-shrink-0 p-4 sidebar-pos shadow-sm">

    <!-- Logo / Brand (Centered) -->
    <a href="{{ route('dashboard') }}" class="d-flex flex-column align-items-center text-center mb-3 text-decoration-none py-2">
        <div class="brand-icon-collapsed align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold shadow-sm mb-0" style="width: 38px; height: 38px; font-size: 0.8rem; letter-spacing: 0.5px;" title="RAJA CELL">
            RC
        </div>
        <span class="brand-title mb-0 fw-bold text-dark fs-5" style="letter-spacing: 0.5px;">RAJA CELL</span>
        <small class="text-muted brand-subtitle" style="font-size: 0.65rem;">Sistem Aplikasi Kasir</small>
    </a>

    <hr class="sidebar-divider my-1">

    <!-- Menu Navigasi -->
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
                <i class="bi bi-speedometer2 fs-5"></i> <span class="nav-label">Dashboard</span>
            </a>
        </li>

        @if(auth()->check() && (optional(auth()->user()->role)->name === 'admin' || auth()->user()->role_id == 1))
        <li class="nav-item">
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" title="User">
                    <i class="bi bi-people fs-5"></i> <span class="nav-label">Pengguna</span>
                </a>
            </li>
        <li class="nav-item">
            <a href="{{ route('jenis-produk.index') }}" class="nav-link {{ request()->routeIs('jenis-produk*') ? 'active' : '' }}" title="Jenis">
                <i class="bi bi-tags fs-5"></i> <span class="nav-label">Jenis</span>
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk*') ? 'active' : '' }}" title="Produk">
                <i class="bi bi-box-seam fs-5"></i> <span class="nav-label">Produk</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('penjualan.index') }}" class="nav-link {{ request()->routeIs('penjualan*') ? 'active' : '' }}" title="Penjualan">
                <i class="bi bi-bag-check fs-5"></i> <span class="nav-label">Penjualan</span>
            </a>
        </li>
        
        
    </ul>

    <hr class="sidebar-divider my-3">

    <!-- Profil Pengguna & Logout -->
    <div class="mt-auto sidebar-profile">
        <div class="d-flex align-items-center mb-3 gap-3 profile-row">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.85rem; letter-spacing: 0.5px;" title="{{ $name }}">
                {{ $initials }}
            </div>
            <div class="d-flex flex-column justify-content-center overflow-hidden profile-text" style="line-height: 1.3;">
                <strong class="text-dark text-truncate d-block small mb-0">{{ $name }}</strong>
                <span class="text-muted d-block" style="font-size: 0.7rem;">{{ ucfirst(optional(Auth::user()?->role)->name ?? 'Administrator') }}</span>
            </div>
        </div>
        
        <!-- Form Logout dengan pemicu modal kustom -->
        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="button" onclick="openLogoutModal()" class="btn btn-logout-clean btn-sm w-100 rounded-2 py-1.5 d-flex align-items-center justify-content-center gap-2 shadow-none" style="font-size: 0.8rem;" title="Logout">
                <i class="bi bi-box-arrow-right"></i> <span class="logout-text">Logout</span>
            </button>
        </form>
    </div>
</div>

{{-- Modal Pop-up Konfirmasi Logout di Tengah --}}
<div class="modal fade" id="customLogoutModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Konfirmasi Logout
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="logoutCloseBtn"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-exclamation-circle text-danger display-4 mb-3"></i>
                <p class="text-dark fs-6 mb-0">Apakah Anda yakin ingin keluar dari sistem?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4 gap-2">
                <button type="button" class="btn btn-light px-4 rounded-3 shadow-none border" data-bs-dismiss="modal" id="logoutCancelBtn">Batal</button>
                <button type="button" id="confirmLogoutBtn" class="btn btn-danger px-4 rounded-3 shadow-sm" onclick="executeLogout()">Ya, Keluar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openLogoutModal() {
        let modalEl = document.getElementById('customLogoutModal');
        let logoutModal = new bootstrap.Modal(modalEl);
        
        // Reset tombol jika sebelumnya sempat loading
        let confirmBtn = document.getElementById('confirmLogoutBtn');
        let cancelBtn = document.getElementById('logoutCancelBtn');
        let closeBtn = document.getElementById('logoutCloseBtn');
        
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = 'Ya, Keluar';
        if(cancelBtn) cancelBtn.disabled = false;
        if(closeBtn) closeBtn.disabled = false;

        logoutModal.show();
    }

    function executeLogout() {
        let confirmBtn = document.getElementById('confirmLogoutBtn');
        let cancelBtn = document.getElementById('logoutCancelBtn');
        let closeBtn = document.getElementById('logoutCloseBtn');

        // Ubah tombol menjadi status loading dengan spinner
        confirmBtn.disabled = true;
        if(cancelBtn) cancelBtn.disabled = true;
        if(closeBtn) closeBtn.disabled = true;
        
        confirmBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Keluar...`;

        // Submit form logout
        document.getElementById('logoutForm').submit();
    }

    // ============ TOGGLE BUKA/TUTUP SIDEBAR (DESKTOP) ============
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        if (!toggleBtn) return;

        toggleBtn.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');

            try {
                localStorage.setItem(
                    'sidebarCollapsed',
                    document.body.classList.contains('sidebar-collapsed') ? '1' : '0'
                );
            } catch (e) {}
        });
    });
</script>