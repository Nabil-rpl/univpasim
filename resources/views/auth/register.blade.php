<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun Baru - Perpustakaan Digital</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background: #f8fafc;
        }

        .login-wrapper {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            animation: backgroundMove 20s ease-in-out infinite;
        }

        @keyframes backgroundMove {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(5deg); }
        }

        .geometric-shape {
            position: absolute;
            opacity: 0.08;
        }

        .shape-1 {
            width: 200px;
            height: 200px;
            background: white;
            border-radius: 50%;
            top: -100px;
            right: 10%;
            animation: float1 15s infinite ease-in-out;
        }

        .shape-2 {
            width: 150px;
            height: 150px;
            background: white;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            bottom: 10%;
            left: 5%;
            animation: float2 20s infinite ease-in-out;
        }

        .shape-3 {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 15px;
            transform: rotate(45deg);
            top: 30%;
            left: 15%;
            animation: float3 18s infinite ease-in-out;
        }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-30px, 30px) rotate(180deg); }
        }

        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -40px) scale(1.1); }
        }

        @keyframes float3 {
            0%, 100% { transform: rotate(45deg) translate(0, 0); }
            50% { transform: rotate(225deg) translate(20px, -20px); }
        }

        .login-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(30, 64, 175, 0.35);
            display: flex;
            max-width: 500px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .login-form-side {
            flex: 1;
            padding: 40px 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .brand-logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            color: white;
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.25);
            margin-bottom: 15px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .brand-logo:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(30, 64, 175, 0.35);
        }

        .form-title {
            color: #1e293b;
            font-weight: 800;
            font-size: 26px;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .form-subtitle {
            color: #64748b;
            font-size: 14px;
            font-weight: 400;
        }

        .custom-alert {
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
            border-left: 4px solid #3b82f6;
        }

        .alert-danger {
            background: #fee2e2;
            border-left-color: #ef4444;
            color: #991b1b;
        }

        .alert-success {
            background: #d1fae5;
            border-left-color: #10b981;
            color: #065f46;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 13px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .form-label i {
            color: #3b82f6;
            margin-right: 8px;
            font-size: 15px;
        }

        .text-danger {
            color: #ef4444;
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
            z-index: 1;
            transition: color 0.3s ease;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            padding: 11px 18px 11px 45px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8fafc;
            border-radius: 12px;
            width: 100%;
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
            background: white;
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-control:focus + .input-icon {
            color: #3b82f6;
        }

        .invalid-feedback {
            display: block;
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 500;
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
            z-index: 2;
            border-radius: 8px;
        }

        .password-toggle:hover {
            color: #3b82f6;
            background: #eff6ff;
        }

        .form-text {
            color: #64748b;
            font-size: 12px;
            font-weight: 400;
            margin-top: 6px;
        }

        .role-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .role-option {
            flex: 1;
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .role-label {
            display: block;
            padding: 14px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .role-option input[type="radio"]:checked + .role-label {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        }

        .role-label i {
            font-size: 26px;
            color: #3b82f6;
            display: block;
            margin-bottom: 6px;
        }

        .role-label span {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }

        .conditional-fields {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .conditional-fields.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        textarea.form-control {
            resize: vertical;
            min-height: 70px;
        }

        .btn-login {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border: none;
            padding: 14px 24px;
            font-size: 15px;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 20px rgba(30, 64, 175, 0.25);
            color: white;
            width: 100%;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(30, 64, 175, 0.35);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading::after {
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

        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0 20%, #e2e8f0 80%, transparent);
        }

        .divider span {
            background: white;
            padding: 0 20px;
            color: #94a3b8;
            font-size: 12px;
            position: relative;
            z-index: 1;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .register-box {
            text-align: center;
            padding: 18px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .register-text {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
            margin: 0;
        }

        .register-link {
            color: #3b82f6;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .register-link::after {
            content: '→';
            margin-left: 5px;
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .register-link:hover {
            color: #1e40af;
        }

        .register-link:hover::after {
            transform: translateX(5px);
        }

        @media (max-width: 576px) {
            .login-form-side {
                padding: 35px 25px;
            }
            
            .form-title {
                font-size: 24px;
            }
            
            .brand-logo {
                width: 60px;
                height: 60px;
                font-size: 32px;
            }

            .form-control, .form-select {
                padding: 10px 16px 10px 40px;
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
</head>
<body>
    <div class="login-wrapper">
        <div class="geometric-shape shape-1"></div>
        <div class="geometric-shape shape-2"></div>
        <div class="geometric-shape shape-3"></div>

        <div class="login-container">
            <div class="login-form-side">
                <div class="form-wrapper">
                    <div class="brand-header">
                        <div class="brand-logo">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <h2 class="form-title">Daftar Akun</h2>
                        <p class="form-subtitle">Buat akun untuk mengakses sistem perpustakaan</p>
                    </div>

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

                    <form method="POST" action="{{ route('register') }}" class="login-form">
                        @csrf

                        <!-- Role Selector -->
                        <div class="role-selector">
                            <div class="role-option">
                                <input type="radio" name="role_type" id="role_mahasiswa" value="mahasiswa" 
                                       {{ old('role_type', 'mahasiswa') == 'mahasiswa' ? 'checked' : '' }}>
                                <label for="role_mahasiswa" class="role-label">
                                    <i class="bi bi-mortarboard-fill"></i>
                                    <span>Mahasiswa</span>
                                </label>
                            </div>
                            <div class="role-option">
                                <input type="radio" name="role_type" id="role_pengguna_luar" value="pengguna_luar"
                                       {{ old('role_type') == 'pengguna_luar' ? 'checked' : '' }}>
                                <label for="role_pengguna_luar" class="role-label">
                                    <i class="bi bi-person-fill"></i>
                                    <span>Umum</span>
                                </label>
                            </div>
                        </div>

                        <!-- Common Fields -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="bi bi-person-fill"></i>Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Masukkan nama lengkap" required>
                                <i class="bi bi-person-circle input-icon"></i>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope-fill"></i>Email <span class="text-danger">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="nama@gmail.com" required>
                                <i class="bi bi-at input-icon"></i>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Mahasiswa Fields -->
                        <div id="mahasiswa-fields" class="conditional-fields {{ old('role_type', 'mahasiswa') == 'mahasiswa' ? 'active' : '' }}">
                            <div class="form-group">
                                <label for="nim" class="form-label">
                                    <i class="bi bi-card-text"></i>NIM <span class="text-danger">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" class="form-control @error('nim') is-invalid @enderror" 
                                           id="nim" name="nim" value="{{ old('nim') }}" 
                                           placeholder="Masukkan NIM">
                                    <i class="bi bi-123 input-icon"></i>
                                    @error('nim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="jurusan" class="form-label">
                                    <i class="bi bi-book-fill"></i>Jurusan <span class="text-danger">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <select class="form-select @error('jurusan') is-invalid @enderror" 
                                            id="jurusan" name="jurusan">
                                        <option value="">Pilih Jurusan</option>
                                        <option value="Teknik Informatika" {{ old('jurusan') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                                        <option value="Sistem Informasi" {{ old('jurusan') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                                        <option value="Teknik Elektro" {{ old('jurusan') == 'Teknik Elektro' ? 'selected' : '' }}>Teknik Elektro</option>
                                        <option value="Manajemen" {{ old('jurusan') == 'Manajemen' ? 'selected' : '' }}>Manajemen</option>
                                        <option value="Akuntansi" {{ old('jurusan') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                                    </select>
                                    <i class="bi bi-mortarboard input-icon"></i>
                                    @error('jurusan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Pengguna Luar Fields -->
                        <div id="pengguna-luar-fields" class="conditional-fields {{ old('role_type') == 'pengguna_luar' ? 'active' : '' }}">
                            <div class="form-group">
                                <label for="no_hp" class="form-label">
                                    <i class="bi bi-telephone-fill"></i>No. HP <span class="text-danger">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" 
                                           id="no_hp" name="no_hp" value="{{ old('no_hp') }}" 
                                           placeholder="08xxxxxxxxxx">
                                    <i class="bi bi-phone input-icon"></i>
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alamat" class="form-label">
                                    <i class="bi bi-geo-alt-fill"></i>Alamat <span class="text-danger">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                              id="alamat" name="alamat" rows="2" 
                                              placeholder="Masukkan alamat lengkap" 
                                              style="padding-left: 45px;">{{ old('alamat') }}</textarea>
                                    <i class="bi bi-house input-icon"></i>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Fields -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock-fill"></i>Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-wrapper password-wrapper">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" 
                                       placeholder="Minimal 6 karakter" required minlength="6">
                                <i class="bi bi-shield-lock-fill input-icon"></i>
                                <button class="password-toggle" type="button" id="togglePassword">
                                    <i class="bi bi-eye-fill" id="eyeIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Password minimal 6 karakter.</div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-fill"></i>Konfirmasi Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-wrapper password-wrapper">
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Ulangi password" required minlength="6">
                                <i class="bi bi-shield-lock-fill input-icon"></i>
                                <button class="password-toggle" type="button" id="togglePasswordConfirm">
                                    <i class="bi bi-eye-fill" id="eyeIconConfirm"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-login">
                            <i class="bi bi-person-plus me-2"></i>DAFTAR AKUN
                        </button>

                        <div class="divider">
                            <span>atau</span>
                        </div>

                        <div class="register-box">
                            <p class="register-text">
                                Sudah punya akun? 
                                <a href="{{ route('login') }}" class="register-link">Login Sekarang</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleMahasiswa = document.getElementById('role_mahasiswa');
            const rolePenggunaLuar = document.getElementById('role_pengguna_luar');
            const mahasiswaFields = document.getElementById('mahasiswa-fields');
            const penggunaLuarFields = document.getElementById('pengguna-luar-fields');

            function toggleFields() {
                if (roleMahasiswa.checked) {
                    mahasiswaFields.classList.add('active');
                    penggunaLuarFields.classList.remove('active');
                    document.getElementById('nim').required = true;
                    document.getElementById('jurusan').required = true;
                    document.getElementById('no_hp').required = false;
                    document.getElementById('alamat').required = false;
                } else {
                    mahasiswaFields.classList.remove('active');
                    penggunaLuarFields.classList.add('active');
                    document.getElementById('nim').required = false;
                    document.getElementById('jurusan').required = false;
                    document.getElementById('no_hp').required = true;
                    document.getElementById('alamat').required = true;
                }
            }

            roleMahasiswa.addEventListener('change', toggleFields);
            rolePenggunaLuar.addEventListener('change', toggleFields);

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

            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            const loginForm = document.querySelector('.login-form');
            const loginBtn = document.querySelector('.btn-login');
            
            if (loginForm) {
                loginForm.addEventListener('submit', function() {
                    loginBtn.classList.add('loading');
                    loginBtn.innerHTML = '<span style="opacity: 0;">Processing...</span>';
                });
            }

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
</body>
</html>