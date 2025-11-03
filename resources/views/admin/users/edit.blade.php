@extends('layouts.app')

@section('page-title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 text-dark fw-bold">Edit User</h2>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Edit Form -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-dark fw-bold">Form Edit User</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="edit-user-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person-fill me-1"></i>Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required>
                                    <i class="bi bi-person-circle input-icon"></i>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-1"></i>Email <span class="text-danger">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    <i class="bi bi-at input-icon"></i>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    <i class="bi bi-person-gear me-1"></i>Role <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                    <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="pengguna_luar" {{ old('role', $user->role) == 'pengguna_luar' ? 'selected' : '' }}>Pengguna Luar</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Fields untuk Mahasiswa -->
                        <div id="mahasiswa-fields" class="role-specific-fields">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nim" class="form-label">
                                        <i class="bi bi-card-text me-1"></i>NIM <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('nim') is-invalid @enderror" 
                                               id="nim" 
                                               name="nim" 
                                               value="{{ old('nim', $user->mahasiswa->nim ?? $user->nim) }}" 
                                               maxlength="20">
                                        <i class="bi bi-card-list input-icon"></i>
                                        @error('nim')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="jurusan" class="form-label">
                                        <i class="bi bi-book me-1"></i>Jurusan <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('jurusan') is-invalid @enderror" 
                                               id="jurusan" 
                                               name="jurusan" 
                                               value="{{ old('jurusan', $user->mahasiswa->jurusan ?? '') }}" 
                                               maxlength="100">
                                        <i class="bi bi-mortarboard input-icon"></i>
                                        @error('jurusan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fields untuk Pengguna Luar -->
                        <div id="pengguna-luar-fields" class="role-specific-fields">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_hp" class="form-label">
                                        <i class="bi bi-telephone me-1"></i>No HP <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('no_hp') is-invalid @enderror" 
                                               id="no_hp" 
                                               name="no_hp" 
                                               value="{{ old('no_hp', $user->no_hp) }}" 
                                               maxlength="15">
                                        <i class="bi bi-phone input-icon"></i>
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="alamat" class="form-label">
                                        <i class="bi bi-geo-alt me-1"></i>Alamat <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                                  id="alamat" 
                                                  name="alamat" 
                                                  rows="3" 
                                                  style="padding-left: 45px;">{{ old('alamat', $user->alamat) }}</textarea>
                                        <i class="bi bi-house input-icon"></i>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Password Baru (Kosongkan jika tidak diganti)
                                </label>
                                <div class="input-wrapper password-wrapper">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password">
                                    <i class="bi bi-shield-lock-fill input-icon"></i>
                                    <button class="password-toggle" type="button" id="togglePassword">
                                        <i class="bi bi-eye-fill" id="eyeIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Konfirmasi Password Baru
                                </label>
                                <div class="input-wrapper password-wrapper">
                                    <input type="password" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation">
                                    <i class="bi bi-shield-lock-fill input-icon"></i>
                                    <button class="password-toggle" type="button" id="togglePasswordConfirm">
                                        <i class="bi bi-eye-fill" id="eyeIconConfirm"></i>
                                    </button>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-submit">
                                <i class="bi bi-check-circle me-1"></i>Update User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-cancel">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

/* General Styles */
body {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
}

/* Container */
.container-fluid {
    padding: 20px;
}

/* Card */
.card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(30, 64, 175, 0.15);
    animation: slideIn 0.6s ease-out;
}

/* Animation for Card */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-header {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    color: white;
    border-radius: 16px 16px 0 0;
    padding: 20px;
}

/* Alerts */
.custom-alert {
    border: none;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    margin-bottom: 20px;
    border-left: 4px solid #3b82f6;
    animation: alertSlide 0.5s ease-out;
}

.alert-success {
    background: #d1fae5;
    border-left-color: #10b981;
    color: #065f46;
}

.alert-danger {
    background: #fee2e2;
    border-left-color: #ef4444;
    color: #991b1b;
}

.alert ul {
    margin: 0;
    padding-left: 18px;
}

/* Role Specific Fields */
.role-specific-fields {
    display: none;
    animation: fadeInDown 0.4s ease-out;
}

.role-specific-fields.show {
    display: block;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: #1e293b;
    font-size: 14px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.form-label i {
    color: #3b82f6;
    margin-right: 8px;
}

.input-wrapper {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 18px;
    transition: all 0.3s ease;
    z-index: 2;
}

.form-control {
    border: 2px solid #e2e8f0;
    padding: 12px 18px 12px 45px;
    font-size: 14px;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: #f8fafc;
    font-weight: 500;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    background: white;
    outline: none;
    transform: translateY(-1px);
}

.form-control:focus + .input-icon {
    color: #3b82f6;
    transform: translateY(-50%) scale(1.1);
}

.form-control.is-invalid {
    border-color: #ef4444;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

textarea.form-control + .input-icon {
    top: 20px;
    transform: translateY(0);
}

.form-select {
    border: 2px solid #e2e8f0;
    padding: 12px 18px;
    font-size: 14px;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: #f8fafc;
    font-weight: 500;
}

.form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    background: white;
    outline: none;
}

.invalid-feedback {
    font-size: 12px;
    color: #ef4444;
    margin-top: 5px;
}

.password-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 18px;
    cursor: pointer;
    padding: 6px;
    transition: all 0.3s ease;
    border-radius: 8px;
    z-index: 3;
}

.password-toggle:hover {
    color: #3b82f6;
    background: #eff6ff;
}

/* Buttons */
.btn {
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    border: none;
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
}

.btn-success:active {
    transform: translateY(0);
}

.btn-outline-secondary {
    border-color: #e2e8f0;
    color: #475569;
}

.btn-outline-secondary:hover {
    background: #f1f5f9;
    color: #1e293b;
}

.btn-submit.loading {
    pointer-events: none;
    opacity: 0.8;
    position: relative;
}

.btn-submit.loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.7s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Animations */
@keyframes alertSlide {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .col-lg-8 {
        max-width: 100%;
    }

    .card-body {
        padding: 20px;
    }

    .btn {
        padding: 10px 16px;
        font-size: 13px;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 15px;
    }

    .card-header {
        padding: 15px;
    }

    .form-control, .form-select {
        padding: 10px 16px 10px 40px;
        font-size: 13px;
    }

    .input-icon {
        left: 12px;
        font-size: 16px;
    }

    .password-toggle {
        right: 12px;
        font-size: 16px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const mahasiswaFields = document.getElementById('mahasiswa-fields');
    const penggunaLuarFields = document.getElementById('pengguna-luar-fields');
    
    const nimInput = document.getElementById('nim');
    const jurusanInput = document.getElementById('jurusan');
    const noHpInput = document.getElementById('no_hp');
    const alamatInput = document.getElementById('alamat');

    function toggleRoleFields() {
        const selectedRole = roleSelect.value;
        
        // Hide all role-specific fields first
        mahasiswaFields.classList.remove('show');
        penggunaLuarFields.classList.remove('show');
        
        // Remove required attributes
        nimInput.removeAttribute('required');
        jurusanInput.removeAttribute('required');
        noHpInput.removeAttribute('required');
        alamatInput.removeAttribute('required');
        
        // Show and set required based on role
        if (selectedRole === 'mahasiswa') {
            mahasiswaFields.classList.add('show');
            nimInput.setAttribute('required', 'required');
            jurusanInput.setAttribute('required', 'required');
        } else if (selectedRole === 'pengguna_luar') {
            penggunaLuarFields.classList.add('show');
            noHpInput.setAttribute('required', 'required');
            alamatInput.setAttribute('required', 'required');
        }
    }

    // Initial check
    toggleRoleFields();

    // Listen for changes
    roleSelect.addEventListener('change', toggleRoleFields);

    // Toggle Password Visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword) {
        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye-fill');
            eyeIcon.classList.toggle('bi-eye-slash-fill');
        });
    }

    // Toggle Password Confirmation Visibility
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirm = document.getElementById('password_confirmation');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');

    if (togglePasswordConfirm) {
        togglePasswordConfirm.addEventListener('click', function(e) {
            e.preventDefault();
            const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', type);
            eyeIconConfirm.classList.toggle('bi-eye-fill');
            eyeIconConfirm.classList.toggle('bi-eye-slash-fill');
        });
    }

    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Loading animation on submit
    const editForm = document.querySelector('.edit-user-form');
    const submitBtn = document.querySelector('.btn-submit');

    if (editForm) {
        editForm.addEventListener('submit', function() {
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<span style="opacity: 0;">Processing...</span>';
        });
    }

    // Input focus effect
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            const icon = this.parentElement.querySelector('.input-icon');
            if (icon) {
                icon.style.color = '#3b82f6';
            }
        });
        
        input.addEventListener('blur', function() {
            const icon = this.parentElement.querySelector('.input-icon');
            if (icon && !this.value) {
                icon.style.color = '#94a3b8';
            }
        });
    });
});
</script>
@endsection