@csrf

<div class="mb-3">
    <label class="form-label fw-semibold text-secondary small">Nama Lengkap</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" placeholder="Masukkan nama lengkap..." required>
    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold text-secondary small">Alamat Email</label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" placeholder="contoh@email.com" required>
    @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold text-secondary small">Password</label>
    <div class="input-group">
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter...">
        <button class="btn btn-outline-secondary border-secondary-subtle" type="button" id="togglePassword" title="Lihat/Sembunyikan Password">
            <i class="bi bi-eye" id="toggleIcon"></i>
        </button>
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <small class="text-muted" style="font-size: 0.75rem;">Kosongkan jika tidak ingin mengubah password (pada mode edit).</small>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold text-secondary small">Hak Akses (Role)</label>
    <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
        <option value="">-- Pilih Role --</option>
        @foreach($roles as $role)
            <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id ?? '') == $role->id)>
                {{ ucfirst($role->name) }}
            </option>
        @endforeach
    </select>
    @error('role_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="d-flex flex-column flex-md-row gap-3 pt-2">
    <button type="submit" class="btn btn-primary py-2.5 px-4 shadow-sm rounded-3 fw-semibold">
        <i class="bi bi-save me-2"></i> Simpan User
    </button>
    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary py-2.5 px-4 shadow-sm rounded-3 fw-semibold">
        <i class="bi bi-arrow-left-circle me-2"></i> Kembali
    </a>
</div>

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