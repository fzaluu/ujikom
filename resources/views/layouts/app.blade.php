<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS SMART')</title>

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

        .card-hover-up:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
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

    <!-- Global Loading Overlay -->
    <div id="page-loader">
        <div class="spinner-ring mb-3"></div>
        <div class="fw-medium text-light small tracking-wide">POS SMART Loading...</div>
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
        <!-- Sidebar (Pusat Navigasi Utama) -->
        @auth
            @include('layouts.navbar')
        @endauth

        <!-- Main Content Wrapper (Tanpa Navbar Atas) -->
        <div class="main-content">
            <main class="container-fluid p-4 p-md-5">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Page Loader on Link & Form Submit
        document.addEventListener("DOMContentLoaded", function() {
            const loader = document.getElementById('page-loader');
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    if(!form.classList.contains('no-loader')) loader.classList.add('show');
                });
            });
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    let href = this.getAttribute('href');
                    if (href && href !== '#' && !href.startsWith('javascript') && !this.hasAttribute('data-bs-toggle')) {
                        loader.classList.add('show');
                    }
                });
            });
        });
    </script>
</body>
</html>