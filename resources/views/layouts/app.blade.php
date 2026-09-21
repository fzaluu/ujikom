<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RAJA CELL')</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230d6efd'><path d='M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1.5a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-10a.5.5 0 0 0-.5-.5H11.5z'/></svg>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-width: 270px;
            --sidebar-width-collapsed: 84px;
            --primary-color: #2563EB;
            --primary-hover: #1D4ED8;
            --bg-body: #F8FAFC;
            --sidebar-bg: #0F172A;
            --card-radius: 16px;
            --transition-smooth: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            color: #1E293B;
            overflow-x: hidden;
        }

        /* Layout Utama dengan Sidebar Fixed & Tanpa Navbar Atas */
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-width: 0;
            transition: var(--transition-smooth);
        }

        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
                /* Beri jarak atas agar konten tidak tertutup header melayang */
                padding-top: 60px; 
            }
        }

        /* Konten menyesuaikan saat sidebar diciutkan (hanya layar besar) */
        @media (min-width: 992px) {
            body.sidebar-collapsed .main-content {
                margin-left: var(--sidebar-width-collapsed);
            }
        }

        /* Card & Button Modern SaaS Style */
        .card {
            border-radius: var(--card-radius);
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            transition: var(--transition-smooth);
            background-color: #ffffff;
        }

        .btn {
            border-radius: 10px;
            padding: 0.55rem 1.25rem;
            font-weight: 500;
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn:active {
            transform: scale(0.97) !important;
        }

        /* Form Input Modern */
        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.65rem 1rem;
            border: 1px solid #E2E8F0;
            font-size: 0.95rem;
            transition: var(--transition-smooth);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        /* ============ FLOATING HEADER MOBILE (Melayang di Atas) ============ */
        .mobile-floating-header {
            display: none;
            position: fixed;
            top: 12px;
            left: 12px;
            right: 12px;
            z-index: 1050;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08);
            border-radius: 14px;
            padding: 0.5rem 1rem;
            align-items: center;
            justify-content: space-between;
        }

        @media (max-width: 991.98px) {
            .mobile-floating-header {
                display: flex;
            }
        }

        /* Page Loader & Toast */
        #page-loader {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(6px);
            z-index: 9999;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            color: #ffffff; opacity: 0; visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        #page-loader.show { opacity: 1; visibility: visible; }
        .spinner-ring {
            width: 3rem; height: 3rem; border: 3px solid rgba(255,255,255,0.2);
            border-top-color: #fff; border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <script>
        // Terapkan status sidebar (ciut/lebar) sesegera mungkin
        (function () {
            try {
                if (localStorage.getItem('sidebarCollapsed') === '1') {
                    document.body.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>

    <!-- Header / Bar Melayang di Atas (Khusus Mobile) -->
    @auth
    <div class="mobile-floating-header d-print-none">
        <span class="fw-bold text-dark tracking-tight">RAJA CELL</span>
        <div class="d-flex align-items-center gap-2">
            <span class="small text-muted fw-medium">{{ auth()->user()->name ?? 'User' }}</span>
            <button type="button" class="btn btn-light btn-sm text-danger border rounded-3 p-1 px-2 shadow-sm" onclick="openLogoutModal()" title="Keluar">
                <i class="bi bi-box-arrow-right fs-6"></i>
            </button>
        </div>
    </div>
    @endauth

    <!-- Global Loading Overlay -->
    <div id="page-loader">
        <div class="spinner-ring mb-3"></div>
        <div class="fw-medium text-light small tracking-wide"></div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        @if(session('success'))
            <div class="toast align-items-center text-white bg-success border-0 shadow-lg rounded-4 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <span class="fw-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast align-items-center text-white bg-danger border-0 shadow-lg rounded-4 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <span class="fw-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
    </div>

    <div class="app-container">
        <!-- Sidebar (Desktop) & Bottom Nav (Mobile) -->
        @auth
            @include('layouts.navbar')
            @include('layouts.bottom-nav')
        @endauth

        <!-- Main Content Wrapper -->
        <div class="main-content">
            <main class="container-fluid p-4 p-md-5">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loader = document.getElementById('page-loader');

            // 1. Loader untuk semua form submit (kecuali yang ada class 'no-loader')
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    if(!form.classList.contains('no-loader')) loader.classList.add('show');
                });
            });

            // 2. Loader untuk klik link menu di sidebar/navigasi
            document.querySelectorAll('.sidebar-pos a').forEach(link => {
                link.addEventListener('click', function(e) {
                    let href = this.getAttribute('href');
                    
                    // ALUR BARU: 
                    // Jika kita sedang berada di halaman POS (dideteksi dari variabel HAS_ITEMS yang ada di POS)
                    // dan keranjang belanja ADA ISINYA (HAS_ITEMS === true), 
                    // JANGAN NYALAKAN LOADER GLOBAL dan BIARKAN SCRIPT POS YANG MENCEGATNYA.
                    if (typeof HAS_ITEMS !== 'undefined' && HAS_ITEMS === true) {
                        return; // Keluar dari fungsi ini agar modal konfirmasi POS bisa tampil mulus!
                    }

                    if (href && href !== '#' && !href.startsWith('javascript') && !this.hasAttribute('data-bs-toggle')) {
                        loader.classList.add('show');
                    }
                });
            });

            // 3. Auto-show Toast Notifikasi
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toastEl => {
                let toast = new bootstrap.Toast(toastEl, {
                    delay: 6000
                });
                toast.show();
            });
        });
    </script>   
</body>
</html>