<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>403 - Akses Ditolak - POS SMART</title>

    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --primary: #2F66B5;
            --primary-dark: #24559C;
            --primary-light: #EAF2FF;

            --background: #F4F6FA;
            --card: #FFFFFF;

            --text-dark: #2F3742;
            --text-secondary: #667085;
            --text-light: #98A2B3;

            --border: #E3E8F0;

            /* Warna khusus status akses ditolak */
            --danger: #D9534F;
            --danger-light: #FFF1F0;
            --danger-border: #F6D5D2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            position: relative;
            overflow: hidden;

            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: var(--background);
        }


        /* =========================================
            BACKGROUND
        ========================================= */

        body::before {
            content: '';

            position: fixed;
            inset: 0;

            pointer-events: none;

            background:
                radial-gradient(
                    circle at 10% 10%,
                    rgba(47, 102, 181, 0.05),
                    transparent 25%
                ),
                radial-gradient(
                    circle at 90% 90%,
                    rgba(47, 102, 181, 0.04),
                    transparent 25%
                );
        }

        body::after {
            content: '';

            position: fixed;
            inset: 0;

            pointer-events: none;

            opacity: 0.35;

            background-image:
                radial-gradient(
                    rgba(47, 102, 181, 0.12) 1px,
                    transparent 1px
                );

            background-size: 30px 30px;

            mask-image:
                linear-gradient(
                    to bottom,
                    rgba(0, 0, 0, 0.3),
                    transparent 65%
                );
        }


        /* =========================================
            WRAPPER
        ========================================= */

        .error-wrapper {
            position: relative;
            z-index: 2;

            width: 100%;
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;
        }


        /* =========================================
            ERROR CARD
        ========================================= */

        .error-card {
            position: relative;

            width: 100%;
            max-width: 560px;

            padding: 42px 38px;

            text-align: center;

            background: var(--card);

            border: 1px solid var(--border);

            border-radius: 20px;

            box-shadow:
                0 12px 35px rgba(31, 45, 61, 0.08);

            animation:
                cardEnter
                0.6s
                cubic-bezier(0.22, 1, 0.36, 1)
                both;
        }


        /* =========================================
            ERROR CODE
        ========================================= */

        .error-code {
            position: relative;

            display: inline-block;

            margin-bottom: 18px;

            font-size: 72px;
            line-height: 1;

            font-weight: 800;

            letter-spacing: -4px;

            color: var(--primary);

            animation:
                contentEnter
                0.65s
                cubic-bezier(0.22, 1, 0.36, 1)
                0.08s
                both;
        }

        .error-code::after {
            content: '';

            position: absolute;

            width: 42px;
            height: 4px;

            bottom: -10px;
            left: 50%;

            border-radius: 10px;

            background: var(--primary);

            transform: translateX(-50%);
        }


        /* =========================================
            ERROR ICON
        ========================================= */

        .error-icon-wrapper {
            width: 78px;
            height: 78px;

            margin: 0 auto 24px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 20px;

            color: var(--danger);

            background: var(--danger-light);

            border: 1px solid var(--danger-border);

            animation:
                contentEnter
                0.65s
                cubic-bezier(0.22, 1, 0.36, 1)
                0.15s
                both;
        }

        .error-icon-wrapper i {
            font-size: 32px;
        }


        /* =========================================
            TITLE
        ========================================= */

        .error-title {
            margin-bottom: 10px;

            font-size: 25px;
            font-weight: 700;

            letter-spacing: -0.5px;

            color: var(--text-dark);

            animation:
                contentEnter
                0.65s
                cubic-bezier(0.22, 1, 0.36, 1)
                0.22s
                both;
        }


        /* =========================================
            DESCRIPTION
        ========================================= */

        .error-description {
            max-width: 440px;

            margin: 0 auto 28px;

            font-size: 13px;
            line-height: 1.8;

            color: var(--text-secondary);

            animation:
                contentEnter
                0.65s
                cubic-bezier(0.22, 1, 0.36, 1)
                0.28s
                both;
        }


        /* =========================================
            BUTTON GROUP
        ========================================= */

        .button-group {
            animation:
                contentEnter
                0.65s
                cubic-bezier(0.22, 1, 0.36, 1)
                0.34s
                both;
        }

        .btn {
            min-height: 46px;

            padding: 0.6rem 1.2rem;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            font-size: 13px;
            font-weight: 600;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease,
                border-color 0.2s ease;
        }


        /* Tombol utama */

        .btn-primary {
            color: #FFFFFF;

            border: none !important;

            background: var(--primary) !important;

            box-shadow:
                0 7px 18px
                rgba(47, 102, 181, 0.20);
        }

        .btn-primary:hover {
            background: var(--primary-dark) !important;

            transform: translateY(-1px);

            box-shadow:
                0 10px 22px
                rgba(47, 102, 181, 0.25);
        }

        .btn-primary:active {
            transform: translateY(0);
        }


        /* Tombol kembali */

        .btn-outline-secondary {
            color: var(--text-secondary);

            background: #FFFFFF;

            border-color: var(--border);
        }

        .btn-outline-secondary:hover {
            color: var(--text-dark);

            background: #F8FAFC;

            border-color: #D0D8E3;

            transform: translateY(-1px);
        }


        /* =========================================
            FOOTER
        ========================================= */

        .error-footer {
            margin-top: 28px;
            padding-top: 20px;

            border-top: 1px solid var(--border);

            font-size: 11px;

            color: var(--text-light);

            animation:
                contentEnter
                0.65s
                cubic-bezier(0.22, 1, 0.36, 1)
                0.40s
                both;
        }

        .error-footer i {
            color: var(--primary);
        }


        /* =========================================
            ANIMATION
        ========================================= */

        @keyframes cardEnter {
            from {
                opacity: 0;

                transform:
                    translateY(10px)
                    scale(0.99);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);
            }
        }

        @keyframes contentEnter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        /* =========================================
            RESPONSIVE
        ========================================= */

        @media (max-width: 575.98px) {

            body {
                padding: 15px;
            }

            .error-card {
                padding: 35px 22px;

                border-radius: 18px;
            }

            .error-code {
                font-size: 60px;
            }

            .error-icon-wrapper {
                width: 70px;
                height: 70px;

                margin-bottom: 20px;
            }

            .error-icon-wrapper i {
                font-size: 28px;
            }

            .error-title {
                font-size: 22px;
            }

            .error-description {
                font-size: 12px;
            }

            .button-group {
                width: 100%;
            }

            .button-group .btn {
                width: 100%;
            }
        }

    </style>
</head>

<body>

    <div class="error-wrapper">

        <div class="error-card">


            <!-- ==================================
                 KODE ERROR
            =================================== -->
            <div class="error-code">
                403
            </div>


            <!-- ==================================
                 ICON AKSES DITOLAK
            =================================== -->
            <div class="error-icon-wrapper">
                <i class="bi bi-shield-lock"></i>
            </div>


            <!-- ==================================
                 JUDUL
            =================================== -->
            <h2 class="error-title">
                Akses Ditolak
            </h2>


            <!-- ==================================
                 DESKRIPSI
            =================================== -->
            <p class="error-description">
                Maaf, Anda tidak memiliki hak akses atau izin yang cukup
                untuk membuka halaman ini. Silakan kembali ke halaman utama
                atau hubungi Administrator jika Anda merasa ini adalah
                sebuah kesalahan.
            </p>


            <!-- ==================================
                 TOMBOL
            =================================== -->
            <div class="button-group d-flex justify-content-center gap-2 flex-wrap">

                <!-- Tombol Beranda dengan Loading -->
                <a
                    href="{{ route('dashboard') }}"
                    class="btn btn-primary"
                    id="homeBtn"
                    onclick="handleLoading(event, 'homeBtn', 'Memuat Beranda...')"
                >
                    <i class="bi bi-house-door me-2" id="homeIcon"></i>
                    <span id="homeText">Kembali ke Beranda</span>
                </a>


                <!-- Tombol Kembali dengan Loading -->
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    id="backBtn"
                    onclick="handleBackLoading(event)"
                >
                    <i class="bi bi-arrow-left me-2" id="backIcon"></i>
                    <span id="backText">Kembali</span>
                </button>

            </div>


            <!-- ==================================
                 FOOTER
            =================================== -->
            <div class="error-footer">

                <i class="bi bi-shield-check me-1"></i>

                Halaman ini dilindungi oleh sistem hak akses POS SMART.

            </div>

        </div>

    </div>


    <!-- Script Animasi Loading Tombol -->
    <script>
        function handleLoading(event, btnId, loadingText) {
            const btn = document.getElementById(btnId);
            
            // Mencegah klik berulang kali
            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none';

            // Ubah ikon menjadi spinner bootstrap & ganti teks
            if(btnId === 'homeBtn') {
                document.getElementById('homeIcon').className = 'spinner-border spinner-border-sm me-2';
                document.getElementById('homeText').innerText = loadingText;
            }
        }

        function handleBackLoading(event) {
            const btn = document.getElementById('backBtn');
            
            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none';

            document.getElementById('backIcon').className = 'spinner-border spinner-border-sm me-2';
            document.getElementById('backText').innerText = 'Memuat...';

            // Beri jeda sedikit agar animasi terlihat sebelum kembali ke history sebelumnya
            setTimeout(() => {
                window.history.back();
            }, 300);
        }
    </script>

    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>
</html>