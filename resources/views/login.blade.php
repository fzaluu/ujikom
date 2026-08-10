@extends('layouts.app')

@section('title', 'Login - POS SMART')

@section('content')
<!-- Fullscreen SaaS Modern Login Wrapper -->
<div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-3" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); z-index: 2000; overflow-y: auto;">
    
    <!-- Background Decorative Elements -->
    <div class="position-absolute w-100 h-100 opacity-10 pointer-events-none" style="background-image: radial-gradient(#3B82F6 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="position-relative" style="width: 100%; max-width: 440px;">
        
        <!-- Glass Card Modern -->
        <div class="card shadow-lg border-0 rounded-4 p-4 p-sm-5 bg-white bg-opacity-95 backdrop-blur">
            
            <!-- Header / Branding -->
            <div class="text-center mb-4">
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-3 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px;">
                        <i class="bi bi-cart3 fs-3"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-1">POS SMART</h3>
                <p class="text-muted small m-0">Masuk untuk mengelola sistem kasir</p>
            </div>

            <!-- Form Login -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label text-secondary small fw-semibold">Email atau Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="text" class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label text-secondary small fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control bg-light border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                        <button class="btn btn-light border border-start-0 text-muted px-3" type="button" id="togglePassword">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold rounded-3 shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sistem
                    </button>
                </div>
            </form>
            
        </div>
        
        <!-- Footer Info -->
        <div class="text-center mt-4">
            <p class="text-white-50 small mb-0">&copy; {{ date('Y') }} POS SMART — SMKN 4 Kota Tasikmalaya</p>
        </div>

    </div>
</div>

<!-- Script Toggle Password Interaktif -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    });
</script>
@endsection