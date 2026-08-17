<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>404 - Halaman Tidak Ditemukan - POS SMART</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

        /* Background dekorasi halus seperti halaman login */
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

        /* Pola titik halus */
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
            ERROR CARD
        ========================================= */

        .error-card {
            position: relative;
            z-index: 2;

            max-width: 560px;
            width: 100%;

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
            ERROR NUMBER
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
                0.1s
                both;
        }

        .error-code::after {
            content: '';

            position: absolute;

            width: 42px;
            height: 4px;

            border-radius: 10px;

            background: var(--primary);

            bottom: -10px;
            left: 50%;

            transform: translateX(-50%);
        }


        /* =========================================
            ERROR ICON
        ========================================= */

        .error-icon-wrapper {
            position: relative;

            width: 78px;
            height: 78px;

            margin: 0 auto 24px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 20px;

            color: var(--primary);

            background: var(--primary-light);

            border:
                1px solid
                rgba(47, 102, 181, 0.10);

            animation:
                contentEnter
                0.65s
                cubic-bezier(0.22, 1, 0.36, 1)
                0.16s
                both;
        }

        .error-icon-wrapper i {
            font-size: 32px;
        }


        /* =========================================
            TEXT
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

        .error-description {
            max-width: 440px;

            margin:
                0 auto 28px;

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
            BUTTON
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

        .btn-primary {
            border: none !important;

            color: #FFFFFF;

            background: var(--primary) !important;

            box-shadow:
                0 7px 18px
                rgba(47, 102, 181, 0.20);
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--primary-dark) !important;

            transform: translateY(-1px);

            box-shadow:
                0 10px 22px
                rgba(47, 102, 181, 0.25);
        }

        .btn-outline-secondary {
            color: var(--text-secondary);

            background: #FFFFFF;

            border-color: var(--border);
        }

        .btn-outline-secondary:hover:not(:disabled) {
            color: var(--text-dark);

            background: #F8FAFC;

            border-color: #D0D8E3;

            transform: translateY(-1px);
        }


        /* =========================================
            FOOTER INFO
        ========================================= */

        .error-footer {
            margin-top: 28px;
            padding-top: 20px;

            border-top:
                1px solid var(--border);

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

    <div class="container d-flex justify-content-center align-items-center">

        <div class="error-card">

            <!-- KODE ERROR -->
            <div class="error-code">
                404
            </div>


            <!-- ICON -->
            <div class="error-icon-wrapper">
                <i class="bi bi-exclamation-octagon"></i>
            </div>


            <!-- JUDUL -->
            <h2 class="error-title">
                Halaman Tidak Ditemukan
            </h2>


            <!-- DESKRIPSI -->
            <p class="error-description">
                Maaf, halaman atau tautan yang Anda akses tidak ditemukan.
                Mungkin halaman sudah dipindahkan, dihapus, atau alamat URL
                yang Anda masukkan tidak sesuai.
            </p>


            <!-- TOMBOL -->
            <div class="button-group d-flex justify-content-center gap-2 flex-wrap">

                <!-- Tombol Beranda dengan Loading State -->
                <a
                    href="{{ route('dashboard') }}"
                    class="btn btn-primary"
                    id="btnDashboard"
                >
                    <span class="btn-text">
                        <i class="bi bi-house-door me-2"></i>
                        Kembali ke Beranda
                    </span>
                    <span class="btn-loader d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Memuat...
                    </span>
                </a>


                <!-- Tombol Kembali dengan Loading State -->
                <button
                    class="btn btn-outline-secondary"
                    type="button"
                    id="btnBack"
                >
                    <span class="btn-text">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali
                    </span>
                    <span class="btn-loader d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Memuat...
                    </span>
                </button>

            </div>


            <!-- FOOTER -->
            <div class="error-footer">

                <i class="bi bi-info-circle me-1"></i>

                Jika masalah berlanjut, silakan kembali ke halaman utama POS SMART.

            </div>

        </div>

    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Animasi Loading Tombol -->
    <script>
        // Handler untuk tombol Beranda
        document.getElementById('btnDashboard').addEventListener('click', function (e) {
            e.preventDefault();
            const btn = this;
            
            btn.classList.add('disabled');
            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.btn-loader').classList.remove('d-none');

            setTimeout(() => {
                window.location.href = "{{ route('dashboard') }}";
            }, 300);
        });

        // Handler untuk tombol Kembali
        document.getElementById('btnBack').addEventListener('click', function (e) {
            e.preventDefault();
            const btn = this;

            btn.classList.add('disabled');
            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.btn-loader').classList.remove('d-none');

            setTimeout(() => {
                window.history.back();
            }, 300);
        });
    </script>

</body>
</html>