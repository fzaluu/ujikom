<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Masuk - POS SMART</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
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

            --success: #267B5B;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--background);
            color: var(--text-dark);

            overflow-x: hidden;
        }

        /* Background dekorasi halus */
        body::before {
            content: '';

            position: fixed;
            top: 0;
            left: 0;

            width: 100%;
            height: 100%;

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

        .container {
            position: relative;
            z-index: 1;
        }


        /* ==========================================
           LOGIN CARD
        ========================================== */

        .login-card {
            width: 100%;
            max-width: 880px;
            min-height: 480px;

            overflow: hidden;

            border-radius: 20px;

            background: var(--card);

            border: 1px solid var(--border);

            box-shadow:
                0 12px 35px rgba(31, 45, 61, 0.08);

            animation: loginCardEnter 0.6s ease-out both;
        }

        @keyframes loginCardEnter {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        /* ==========================================
           PANEL KIRI
        ========================================== */

        .login-left-pane {
            position: relative;

            padding: 42px !important;

            overflow: hidden;

            background:
                linear-gradient(
                    135deg,
                    #2F66B5 0%,
                    #24559C 100%
                );

            color: white;
        }

        /* Pattern halus */
        .login-left-pane::before {
            content: '';

            position: absolute;

            width: 300px;
            height: 300px;

            border-radius: 50%;

            border: 1px solid rgba(255, 255, 255, 0.10);

            top: -160px;
            right: -130px;
        }

        .login-left-pane::after {
            content: '';

            position: absolute;

            width: 220px;
            height: 220px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.04);

            bottom: -120px;
            left: -100px;
        }

        .left-content {
            position: relative;
            z-index: 2;

            animation: contentEnter 0.7s ease-out both;
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


        /* Brand */

        .brand-icon {
            width: 46px;
            height: 46px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;

            background: rgba(255, 255, 255, 0.15);

            border: 1px solid rgba(255, 255, 255, 0.18);

            font-size: 21px;
        }

        .brand-title {
            font-size: 21px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .brand-subtitle {
            font-size: 12px;
            opacity: 0.7;
        }


        /* Main text */

        .left-title {
            font-size: 31px;
            line-height: 1.25;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .left-description {
            font-size: 13px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.75);
        }


        /* Features */

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 11px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;

            font-size: 12px;

            color: rgba(255, 255, 255, 0.85);
        }

        .feature-check {
            width: 25px;
            height: 25px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            border-radius: 8px;

            background: rgba(255, 255, 255, 0.13);
        }


        /* ==========================================
           PANEL KANAN
        ========================================== */

        .login-right-pane {
            background: #FFFFFF;
            padding: 42px !important;
        }

        .form-wrapper {
            width: 100%;
            max-width: 350px;

            animation:
                formEnter
                0.65s
                ease-out
                0.08s
                both;
        }

        @keyframes formEnter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        /* Mobile logo */

        .mobile-logo {
            width: 48px;
            height: 48px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;

            background: var(--primary);
            color: white;

            box-shadow:
                0 8px 20px rgba(47, 102, 181, 0.20);
        }


        /* Header */

        .login-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            padding: 7px 11px;

            border-radius: 8px;

            background: var(--primary-light);

            color: var(--primary);

            font-size: 11px;
            font-weight: 700;
        }

        .login-heading {
            font-size: 27px;
            font-weight: 700;

            color: var(--text-dark);

            letter-spacing: -0.6px;
        }

        .login-description {
            font-size: 13px;

            color: var(--text-secondary);

            line-height: 1.7;
        }


        /* ==========================================
           FORM
        ========================================== */

        .form-label {
            font-size: 12px;
            font-weight: 600;

            color: var(--text-secondary);
        }

        .input-group {
            border-radius: 11px;
        }

        .input-group-text,
        .form-control {
            height: 47px;

            background: #F8FAFC !important;

            border-color: var(--border) !important;
        }

        .input-group-text {
            padding-left: 15px;

            color: var(--text-light);

            transition: 0.2s ease;
        }

        .form-control {
            font-size: 13px;

            color: var(--text-dark);

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .form-control::placeholder {
            color: #AAB4C0;
        }

        .form-control:focus {
            background: #FFFFFF !important;

            border-color: var(--primary) !important;

            box-shadow:
                0 0 0 4px
                rgba(47, 102, 181, 0.10)
                !important;
        }

        .input-group:focus-within .input-group-text {
            background: #FFFFFF !important;

            border-color: var(--primary) !important;

            color: var(--primary);
        }


        /* Password button */

        .password-toggle {
            height: 47px;

            background: #F8FAFC !important;

            border-color: var(--border) !important;

            color: var(--text-light) !important;

            transition: 0.2s ease;
        }

        .input-group:focus-within .password-toggle {
            background: #FFFFFF !important;

            border-color: var(--primary) !important;
        }

        .password-toggle:hover {
            color: var(--primary) !important;
        }

        #toggleIcon {
            transition: transform 0.18s ease;
        }


        /* ==========================================
           BUTTON
        ========================================== */

        .btn-primary {
            height: 48px;

            border: none !important;

            border-radius: 11px !important;

            background: var(--primary) !important;

            font-size: 13px;

            box-shadow:
                0 7px 18px
                rgba(47, 102, 181, 0.20);

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--primary-dark) !important;

            transform: translateY(-1px);

            box-shadow:
                0 10px 22px
                rgba(47, 102, 181, 0.25);
        }

        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.8;
            cursor: not-allowed;
        }


        /* Footer security */

        .security-text {
            color: var(--text-light);
            font-size: 11px;
        }


        /* ==========================================
           RESPONSIVE
        ========================================== */

        @media (max-width: 991.98px) {

            .login-card {
                max-width: 470px;
                min-height: auto;
            }

            .login-right-pane {
                padding: 38px !important;
            }

        }


        @media (max-width: 575.98px) {

            body {
                padding: 15px;
            }

            .login-card {
                border-radius: 18px;
            }

            .login-right-pane {
                padding: 30px 23px !important;
            }

            .login-heading {
                font-size: 24px;
            }

        }
    </style>
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center">

        <div class="row g-0 login-card">


            <!-- ==================================
                 PANEL KIRI
            =================================== -->
            <div class="col-lg-6 login-left-pane d-none d-lg-flex flex-column justify-content-between">

                <!-- BRAND -->
                <div class="left-content d-flex align-items-center gap-3">

                    <div class="brand-icon">
                        <i class="bi bi-cart3"></i>
                    </div>

                    <div>
                        <div class="brand-title">
                            POS SMART
                        </div>

                        <div class="brand-subtitle">
                            Modern Point Of Sale
                        </div>
                    </div>

                </div>


                <!-- CONTENT -->
                <div class="left-content my-auto py-4">

                    <h2 class="left-title mb-3">
                        Kelola Bisnis Anda
                        <br>
                        Dengan Lebih Mudah.
                    </h2>

                    <p class="left-description mb-4">
                        Sistem Point of Sale modern untuk membantu mengelola
                        transaksi, produk, stok, dan aktivitas bisnis Anda
                        dengan lebih cepat dan efisien.
                    </p>


                    <div class="feature-list">

                        <div class="feature-item">
                            <div class="feature-check">
                                <i class="bi bi-check-lg"></i>
                            </div>

                            <span>
                                Manajemen Penjualan Terintegrasi
                            </span>
                        </div>


                        <div class="feature-item">
                            <div class="feature-check">
                                <i class="bi bi-check-lg"></i>
                            </div>

                            <span>
                                Pemantauan Produk dan Stok
                            </span>
                        </div>


                        <div class="feature-item">
                            <div class="feature-check">
                                <i class="bi bi-check-lg"></i>
                            </div>

                            <span>
                                Sistem Aman dan Mudah Digunakan
                            </span>
                        </div>

                    </div>

                </div>


                <!-- FOOTER -->
                <div class="left-content">

                    <small style="color: rgba(255,255,255,0.65);">
                        &copy; {{ date('Y') }} POS SMART
                    </small>

                </div>

            </div>


            <!-- ==================================
                 PANEL KANAN
            =================================== -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center login-right-pane">

                <div class="form-wrapper">


                    <!-- MOBILE LOGO -->
                    <div class="d-flex d-lg-none justify-content-center mb-4">

                        <div class="mobile-logo">
                            <i class="bi bi-cart3 fs-5"></i>
                        </div>

                    </div>


                    <!-- HEADER -->
                    <div class="text-center text-lg-start mb-4">

                        <div class="login-label mb-3 d-none d-lg-inline-flex">
                            <i class="bi bi-shield-check"></i>
                            SISTEM POS SMART
                        </div>

                        <h2 class="login-heading mb-2">
                            Selamat Datang! 👋
                        </h2>

                        <p class="login-description mb-0">
                            Silakan masuk menggunakan akun Anda untuk
                            melanjutkan ke dashboard.
                        </p>

                    </div>


                    <!-- ==================================
                         FORM LOGIN
                    =================================== -->
                    <form
                        action="{{ route('login') }}"
                        method="POST"
                        id="loginForm"
                    >

                        @csrf


                        <!-- EMAIL -->
                        <div class="mb-3">

                            <label
                                for="email"
                                class="form-label"
                            >
                                Email atau Username
                            </label>


                            <div class="input-group">

                                <span class="input-group-text border-end-0 rounded-start-3">
                                    <i class="bi bi-envelope"></i>
                                </span>


                                <input
                                    type="text"
                                    class="form-control border-start-0 ps-0 shadow-none @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    required
                                    autofocus
                                >

                            </div>


                            @error('email')

                                <div class="text-danger small mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- PASSWORD -->
                        <div class="mb-4">

                            <label
                                for="password"
                                class="form-label"
                            >
                                Password
                            </label>


                            <div class="input-group">

                                <span class="input-group-text border-end-0 rounded-start-3">
                                    <i class="bi bi-lock"></i>
                                </span>


                                <input
                                    type="password"
                                    class="form-control border-start-0 border-end-0 ps-0 shadow-none @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan password"
                                    required
                                >


                                <button
                                    class="btn password-toggle border-start-0 px-3 rounded-end-3 shadow-none"
                                    type="button"
                                    id="togglePassword"
                                    aria-label="Tampilkan password"
                                >
                                    <i
                                        class="bi bi-eye"
                                        id="toggleIcon"
                                    ></i>
                                </button>

                            </div>


                            @error('password')

                                <div class="text-danger small mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- BUTTON -->
                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-primary fw-semibold"
                                id="submitBtn"
                            >

                                <span id="btnText">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Masuk Sistem
                                </span>


                                <span
                                    id="btnLoader"
                                    class="d-none"
                                >

                                    <span
                                        class="spinner-border spinner-border-sm me-2"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>

                                    Memproses...

                                </span>

                            </button>

                        </div>

                    </form>


                    <!-- SECURITY -->
                    <div class="text-center mt-4">

                        <span class="security-text">
                            <i class="bi bi-shield-lock me-1 text-primary"></i>
                            Sistem login aman dan terlindungi
                        </span>

                    </div>


                    <!-- MOBILE FOOTER -->
                    <div class="text-center d-lg-none mt-4 pt-3 border-top">

                        <small class="text-muted">
                            &copy; {{ date('Y') }} POS SMART
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ==================================
         JAVASCRIPT
    =================================== -->
    <script>

        /* TOGGLE PASSWORD */
        document
            .getElementById('togglePassword')
            .addEventListener('click', function () {

                const passwordInput =
                    document.getElementById('password');

                const toggleIcon =
                    document.getElementById('toggleIcon');


                toggleIcon.style.transform = 'scale(0.8)';


                setTimeout(() => {

                    if (passwordInput.type === 'password') {

                        passwordInput.type = 'text';

                        toggleIcon.classList.remove('bi-eye');
                        toggleIcon.classList.add('bi-eye-slash');

                        this.setAttribute(
                            'aria-label',
                            'Sembunyikan password'
                        );

                    } else {

                        passwordInput.type = 'password';

                        toggleIcon.classList.remove('bi-eye-slash');
                        toggleIcon.classList.add('bi-eye');

                        this.setAttribute(
                            'aria-label',
                            'Tampilkan password'
                        );

                    }


                    toggleIcon.style.transform = 'scale(1)';

                }, 100);

            });


        /* LOADING LOGIN */
        document
            .getElementById('loginForm')
            .addEventListener('submit', function () {

                const emailInput =
                    document
                        .getElementById('email')
                        .value
                        .trim();

                const passwordInput =
                    document
                        .getElementById('password')
                        .value
                        .trim();


                if (emailInput && passwordInput) {

                    const submitBtn =
                        document.getElementById('submitBtn');

                    const btnText =
                        document.getElementById('btnText');

                    const btnLoader =
                        document.getElementById('btnLoader');


                    submitBtn.disabled = true;

                    btnText.classList.add('d-none');

                    btnLoader.classList.remove('d-none');

                }

            });

    </script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>