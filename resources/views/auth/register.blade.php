<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun Baru - Perpustakaan Digital</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
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

        /* Animated Background Pattern */
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

        /* Floating geometric shapes */
        .geometric-shape {
            position: absolute;
            opacity: 0.08;
        }

        .shape-1 {
            width: 200px; /* Dikurangi dari 300px */
            height: 200px; /* Dikurangi dari 300px */
            background: white;
            border-radius: 50%;
            top: -100px; /* Disesuaikan */
            right: 10%;
            animation: float1 15s infinite ease-in-out;
        }

        .shape-2 {
            width: 150px; /* Dikurangi dari 200px */
            height: 150px; /* Dikurangi dari 200px */
            background: white;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            bottom: 10%;
            left: 5%;
            animation: float2 20s infinite ease-in-out;
        }

        .shape-3 {
            width: 120px; /* Dikurangi dari 150px */
            height: 120px; /* Dikurangi dari 150px */
            background: white;
            border-radius: 15px; /* Dikurangi dari 20px */
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
            border-radius: 20px; /* Dikurangi dari 24px */
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(30, 64, 175, 0.35); /* Dikurangi shadow */
            display: flex;
            max-width: 450px; /* Dikurangi dari 550px */
            width: 100%;
            min-height: 600px; /* Dikurangi dari 700px */
            position: relative;
            z-index: 1;
        }

        /* Form Side */
        .login-form-side {
            flex: 1;
            padding: 50px 40px; /* Dikurangi dari 70px 60px */
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }

        .form-wrapper {
            width: 100%;
            max-width: 400px; /* Dikurangi dari 440px */
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px; /* Dikurangi dari 40px */
        }

        .brand-logo {
            width: 70px; /* Dikurangi dari 90px */
            height: 70px; /* Dikurangi dari 90px */
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border-radius: 18px; /* Dikurangi dari 22px */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 35px; /* Dikurangi dari 45px */
            color: white;
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.25); /* Dikurangi shadow */
            margin-bottom: 15px; /* Dikurangi dari 20px */
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .brand-logo:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(30, 64, 175, 0.35); /* Dikurangi shadow */
        }

        .form-title {
            color: #1e293b;
            font-weight: 800;
            font-size: 26px; /* Dikurangi dari 32px */
            margin-bottom: 8px; /* Dikurangi dari 10px */
            letter-spacing: -0.5px;
        }

        .form-subtitle {
            color: #64748b;
            font-size: 14px; /* Dikurangi dari 15px */
            font-weight: 400;
        }

        /* Alerts */
        .custom-alert {
            border: none;
            border-radius: 12px; /* Dikurangi dari 14px */
            padding: 12px 16px; /* Dikurangi dari 14px 18px */
            font-size: 13px; /* Dikurangi dari 14px */
            margin-bottom: 20px; /* Dikurangi dari 25px */
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
            padding-left: 18px; /* Dikurangi dari 20px */
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px; /* Dikurangi dari 24px */
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 13px; /* Dikurangi dari 14px */
            margin-bottom: 8px; /* Dikurangi dari 10px */
            display: flex;
            align-items: center;
        }

        .form-label i {
            color: #3b82f6;
            margin-right: 8px;
            font-size: 15px; /* Dikurangi dari 16px */
        }

        .text-danger {
            color: #ef4444;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px; /* Dikurangi dari 20px */
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px; /* Dikurangi dari 20px */
            z-index: 1;
            transition: color 0.3s ease;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            padding: 12px 18px 12px 45px; /* Dikurangi dari 16px 20px 16px 55px */
            font-size: 14px; /* Dikurangi dari 15px */
            transition: all 0.3s ease;
            background: #f8fafc;
            border-radius: 12px; /* Dikurangi dari 14px */
            width: 100%;
            font-weight: 500;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08); /* Dikurangi shadow */
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
            font-size: 12px; /* Dikurangi dari 13px */
            margin-top: 5px; /* Dikurangi dari 6px */
            font-weight: 500;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px; /* Dikurangi dari 18px */
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 18px; /* Dikurangi dari 20px */
            cursor: pointer;
            padding: 6px; /* Dikurangi dari 8px */
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
            font-size: 12px; /* Dikurangi dari 13px */
            font-weight: 400;
            margin-top: 6px; /* Dikurangi dari 8px */
        }

        /* Register Button */
        .btn-login {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border: none;
            padding: 14px 24px; /* Dikurangi dari 18px 28px */
            font-size: 15px; /* Dikurangi dari 16px */
            font-weight: 700;
            border-radius: 12px; /* Dikurangi dari 14px */
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 20px rgba(30, 64, 175, 0.25); /* Dikurangi shadow */
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
            box-shadow: 0 12px 30px rgba(30, 64, 175, 0.35); /* Dikurangi shadow */
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        /* Divider */
        .divider {
            text-align: center;
            margin: 28px 0; /* Dikurangi dari 32px */
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
            padding: 0 20px; /* Dikurangi dari 24px */
            color: #94a3b8;
            font-size: 12px; /* Dikurangi dari 13px */
            position: relative;
            z-index: 1;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Register Section */
        .register-box {
            text-align: center;
            padding: 20px; /* Dikurangi dari 24px */
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 12px; /* Dikurangi dari 14px */
            border: 1px solid #e2e8f0;
        }

        .register-text {
            color: #64748b;
            font-size: 14px; /* Dikurangi dari 15px */
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

        /* Loading Animation */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px; /* Dikurangi dari 22px */
            height: 20px; /* Dikurangi dari 22px */
            top: 50%;
            left: 50%;
            margin-left: -10px; /* Disesuaikan */
            margin-top: -10px; /* Disesuaikan */
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-form-side {
                padding: 40px 25px; /* Dikurangi dari 50px 30px */
            }
            
            .form-title {
                font-size: 24px; /* Dikurangi dari 28px */
            }
            
            .brand-logo {
                width: 60px; /* Dikurangi dari 75px */
                height: 60px; /* Dikurangi dari 75px */
                font-size: 32px; /* Dikurangi dari 38px */
            }

            .form-control {
                padding: 10px 16px 10px 40px; /* Dikurangi dari 14px 18px 14px 50px */
            }

            .input-icon {
                left: 12px; /* Disesuaikan */
                font-size: 16px; /* Dikurangi dari 18px */
            }

            .password-toggle {
                right: 12px; /* Disesuaikan */
                font-size: 16px; /* Dikurangi dari 18px */
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Geometric floating shapes -->
        <div class="geometric-shape shape-1"></div>
        <div class="geometric-shape shape-2"></div>
        <div class="geometric-shape shape-3"></div>

        <div class="login-container">
            <!-- Form Side -->
            <div class="login-form-side">
                <div class="form-wrapper">
                    <!-- Brand Header -->
                    <div class="brand-header">
                        <div class="brand-logo">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <h2 class="form-title">Daftar Akun</h2>
                        <p class="form-subtitle">Buat akun untuk mengakses sistem perpustakaan</p>
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

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" class="login-form">
                        @csrf

                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="bi bi-person-fill"></i>Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Masukkan nama lengkap"
                                       required>
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
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="nama@gmail.com"
                                       required>
                                <i class="bi bi-at input-icon"></i>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock-fill"></i>Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-wrapper password-wrapper">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Masukkan password (min. 6 karakter)"
                                       required
                                       minlength="6">
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
                                <input type="password" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Konfirmasi password Anda"
                                       required
                                       minlength="6">
                                <i class="bi bi-shield-lock-fill input-icon"></i>
                                <button class="password-toggle" type="button" id="togglePasswordConfirm">
                                    <i class="bi bi-eye-fill" id="eyeIconConfirm"></i>
                                </button>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Auto dismiss alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Loading animation on submit
            const loginForm = document.querySelector('.login-form');
            const loginBtn = document.querySelector('.btn-login');
            
            if (loginForm) {
                loginForm.addEventListener('submit', function() {
                    loginBtn.classList.add('loading');
                    loginBtn.innerHTML = '<span style="opacity: 0;">Processing...</span>';
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
</body>
</html>