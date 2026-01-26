<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk ke Sistem - Perpustakaan Digital</title>
    
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

        /* Loading Overlay - Animasi setelah login berhasil */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loading-overlay.active {
            display: flex;
            animation: overlayFadeIn 0.5s ease;
        }

        @keyframes overlayFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .loading-content {
            text-align: center;
            color: white;
            position: relative;
            z-index: 2;
        }

        .loading-logo {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: logoScale 1.5s ease-in-out infinite;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .loading-logo::before {
            content: '';
            position: absolute;
            width: 140px;
            height: 140px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 35px;
            animation: logoRotate 3s linear infinite;
        }

        .loading-logo i {
            font-size: 60px;
            color: white;
            animation: logoRotate 2s ease-in-out infinite;
        }

        @keyframes logoScale {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes logoRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            animation: textPulse 2s ease-in-out infinite;
        }

        @keyframes textPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .loading-subtext {
            font-size: 14px;
            font-weight: 400;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        /* Animated Progress Bar */
        .loading-progress {
            width: 300px;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
            margin: 0 auto;
            position: relative;
        }

        .loading-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #ffffff, #60a5fa, #ffffff);
            background-size: 200% 100%;
            border-radius: 10px;
            animation: progressMove 1.5s ease-in-out infinite;
            width: 0%;
            transition: width 0.3s ease;
        }

        @keyframes progressMove {
            0% { background-position: 0% 0%; }
            100% { background-position: 200% 0%; }
        }

        /* Book Loading Animation */
        .loading-books {
            margin-top: 40px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .loading-book {
            width: 8px;
            height: 40px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 4px;
            animation: bookJump 1s ease-in-out infinite;
        }

        .loading-book:nth-child(1) { animation-delay: 0s; }
        .loading-book:nth-child(2) { animation-delay: 0.1s; }
        .loading-book:nth-child(3) { animation-delay: 0.2s; }
        .loading-book:nth-child(4) { animation-delay: 0.3s; }
        .loading-book:nth-child(5) { animation-delay: 0.4s; }

        @keyframes bookJump {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Floating Particles in Loading */
        .loading-particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            pointer-events: none;
            animation: particleFloat linear infinite;
        }

        /* Success Checkmark */
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: none;
            position: relative;
            background: rgba(16, 185, 129, 0.2);
        }

        .success-checkmark.active {
            display: block;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .checkmark-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 3;
            stroke: #10b981;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark-check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            stroke: #10b981;
            stroke-width: 3;
            fill: none;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
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
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: backgroundMove 20s ease-in-out infinite;
        }

        @keyframes backgroundMove {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 1; }
            50% { transform: scale(1.15) rotate(8deg); opacity: 0.85; }
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            pointer-events: none;
            animation: particleFloat linear infinite;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.6;
            }
            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }

        .geometric-shape {
            position: absolute;
            opacity: 0.06;
            pointer-events: none;
            filter: blur(50px);
        }

        .shape-1 {
            width: 250px;
            height: 250px;
            background: white;
            border-radius: 50%;
            top: -100px;
            right: 10%;
            animation: float1 18s infinite ease-in-out;
        }

        .shape-2 {
            width: 180px;
            height: 180px;
            background: white;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            bottom: 10%;
            left: 5%;
            animation: float2 22s infinite ease-in-out;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            background: white;
            border-radius: 15px;
            transform: rotate(45deg);
            top: 30%;
            left: 15%;
            animation: float3 20s infinite ease-in-out;
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
            50% { transform: rotate(225deg) translate(30px, -30px); }
        }

        .login-container {
            background: white;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 30px 90px rgba(30, 64, 175, 0.4);
            display: flex;
            max-width: 900px;
            width: 100%;
            min-height: 550px;
            position: relative;
            z-index: 1;
            animation: containerSlideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes containerSlideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-illustration {
            flex: 1;
            position: relative;
            overflow: hidden;
            background: #1e40af;
        }

        .photo-container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 1;
            filter: brightness(0.95);
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.15) 0%, rgba(59, 130, 246, 0.1) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .deco-dots {
            position: absolute;
            width: 100px;
            height: 100px;
            z-index: 2;
            pointer-events: none;
        }

        .deco-dots::before,
        .deco-dots::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
        }

        .deco-top {
            top: 30px;
            right: 40px;
        }

        .deco-bottom {
            bottom: 40px;
            left: 40px;
        }

        .login-form-side {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            position: relative;
        }

        .form-wrapper {
            width: 100%;
            max-width: 400px;
            animation: formFadeIn 1s ease-out 0.3s both;
        }

        @keyframes formFadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
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
            box-shadow: 0 10px 30px rgba(30, 64, 175, 0.3);
            margin-bottom: 15px;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            animation: logoFloat 3s ease-in-out infinite;
            position: relative;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .brand-logo::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 18px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            filter: blur(15px);
            opacity: 0.5;
            z-index: -1;
            animation: glowPulse 2s ease-in-out infinite;
        }

        @keyframes glowPulse {
            0%, 100% { transform: scale(0.9); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .brand-logo:hover {
            transform: translateY(-8px) rotate(5deg) scale(1.1);
            box-shadow: 0 20px 40px rgba(30, 64, 175, 0.4);
        }

        .form-title {
            color: #1e293b;
            font-weight: 800;
            font-size: 26px;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            animation: titleSlide 0.8s ease-out 0.5s both;
        }

        @keyframes titleSlide {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-subtitle {
            color: #64748b;
            font-size: 14px;
            font-weight: 400;
            animation: subtitleSlide 0.8s ease-out 0.6s both;
        }

        @keyframes subtitleSlide {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .custom-alert {
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            margin-bottom: 20px;
            border-left: 4px solid #3b82f6;
            animation: alertSlideDown 0.5s ease-out;
        }

        @keyframes alertSlideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .form-group {
            margin-bottom: 20px;
            animation: inputSlideUp 0.6s ease-out both;
        }

        .form-group:nth-child(1) { animation-delay: 0.7s; }
        .form-group:nth-child(2) { animation-delay: 0.8s; }

        @keyframes inputSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 14px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .form-label i {
            color: #3b82f6;
            margin-right: 8px;
            font-size: 16px;
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
            transition: all 0.3s ease;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            padding: 12px 18px 12px 45px;
            font-size: 14px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f8fafc;
            border-radius: 12px;
            width: 100%;
            font-weight: 500;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: white;
            outline: none;
            transform: translateY(-2px);
        }

        .form-control:focus + .input-icon {
            color: #3b82f6;
            transform: translateY(-50%) scale(1.1);
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
            transform: translateY(-50%) scale(1.1);
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            animation: inputSlideUp 0.6s ease-out 0.9s both;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            border: 2px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
            animation: checkPop 0.3s ease;
        }

        @keyframes checkPop {
            0% { transform: scale(0.8); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .form-check-label {
            color: #475569;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        .forgot-link {
            color: #3b82f6;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .forgot-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background: #3b82f6;
            transition: width 0.4s ease;
        }

        .forgot-link:hover {
            color: #1e40af;
        }

        .forgot-link:hover::after {
            width: 100%;
        }

        .btn-login {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border: none;
            padding: 14px 24px;
            font-size: 15px;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 20px rgba(30, 64, 175, 0.3);
            color: white;
            width: 100%;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
            animation: inputSlideUp 0.6s ease-out 1s both;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.7s ease;
        }

        .btn-login:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(30, 64, 175, 0.4);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-2px) scale(0.98);
        }

        .divider {
            text-align: center;
            margin: 28px 0;
            position: relative;
            animation: inputSlideUp 0.6s ease-out 1.1s both;
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
            padding: 20px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.4s ease;
            animation: inputSlideUp 0.6s ease-out 1.2s both;
        }

        .register-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
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
            transition: transform 0.4s ease;
        }

        .register-link:hover {
            color: #1e40af;
        }

        .register-link:hover::after {
            transform: translateX(8px);
        }

        @media (max-width: 991px) {
            .login-illustration {
                display: none;
            }
            
            .login-container {
                max-width: 450px;
            }
        }

        @media (max-width: 576px) {
            .login-form-side {
                padding: 40px 25px;
            }
            
            .form-title {
                font-size: 24px;
            }
            
            .brand-logo {
                width: 60px;
                height: 60px;
                font-size: 32px;
            }

            .form-control {
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

            .loading-progress {
                width: 250px;
            }

            .loading-text {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-logo">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            
            <div class="success-checkmark" id="successCheckmark">
                <svg width="80" height="80" viewBox="0 0 52 52">
                    <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>

            <h3 class="loading-text" id="loadingText">Memproses Login...</h3>
            <p class="loading-subtext" id="loadingSubtext">Mohon tunggu sebentar</p>
            
            <div class="loading-progress">
                <div class="loading-progress-bar" id="progressBar"></div>
            </div>

            <div class="loading-books">
                <div class="loading-book"></div>
                <div class="loading-book"></div>
                <div class="loading-book"></div>
                <div class="loading-book"></div>
                <div class="loading-book"></div>
            </div>
        </div>
    </div>

    <div class="login-wrapper">
        <div class="geometric-shape shape-1"></div>
        <div class="geometric-shape shape-2"></div>
        <div class="geometric-shape shape-3"></div>

        <div class="login-container">
            <div class="login-illustration">
                <div class="photo-container">
                    <img src="{{ asset('images/pasim1.jpg') }}" alt="Library Background">
                    <div class="photo-overlay"></div>
                </div>
                
                <div class="deco-dots deco-top"></div>
                <div class="deco-dots deco-bottom"></div>
            </div>

            <div class="login-form-side">
                <div class="form-wrapper">
                    <div class="brand-header">
                        <div class="brand-logo">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <h2 class="form-title">Selamat Datang!</h2>
                        <p class="form-subtitle">Silakan masuk ke akun Anda</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

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

                    <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope-fill"></i>Alamat Email
                            </label>
                            <div class="input-wrapper">
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="nama@email.com"
                                       required
                                       autocomplete="email">
                                <i class="bi bi-person-circle input-icon"></i>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock-fill"></i>Password
                            </label>
                            <div class="input-wrapper password-wrapper">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Masukkan password Anda"
                                       required
                                       autocomplete="current-password">
                                <i class="bi bi-shield-lock-fill input-icon"></i>
                                <button class="password-toggle" type="button" id="togglePassword">
                                    <i class="bi bi-eye-fill" id="eyeIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="options-row">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="remember" 
                                       name="remember"
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                            <a href="#" class="forgot-link">Lupa Password?</a>
                        </div>

                        <button type="submit" class="btn-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>MASUK SEKARANG
                        </button>

                        <div class="divider">
                            <span>atau</span>
                        </div>

                        <div class="register-box">
                            <p class="register-text">
                                Belum punya akun? 
                                <a href="{{ route('register') }}" class="register-link">Daftar Sekarang</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Create floating particles
        function createParticles() {
            const wrapper = document.querySelector('.login-wrapper');
            const overlay = document.getElementById('loadingOverlay');
            
            for (let i = 0; i < 10; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                const size = Math.random() * 4 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDuration = (Math.random() * 15 + 15) + 's';
                particle.style.animationDelay = Math.random() * 8 + 's';
                wrapper.appendChild(particle);
            }

            // Particles for loading overlay
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'loading-particle';
                const size = Math.random() * 6 + 3;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particle.style.animationDelay = Math.random() * 5 + 's';
                overlay.appendChild(particle);
            }
        }
        createParticles();

        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const loginForm = document.getElementById('loginForm');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const progressBar = document.getElementById('progressBar');
            const loadingText = document.getElementById('loadingText');
            const loadingSubtext = document.getElementById('loadingSubtext');
            const successCheckmark = document.getElementById('successCheckmark');

            // Password toggle
            if (togglePassword) {
                togglePassword.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    eyeIcon.classList.toggle('bi-eye-fill');
                    eyeIcon.classList.toggle('bi-eye-slash-fill');
                });
            }

            // Auto hide alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Form submission with loading animation
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Jika ada error validation, jangan tampilkan loading
                    const hasErrors = document.querySelector('.is-invalid');
                    if (hasErrors) {
                        return;
                    }

                    // Tampilkan loading overlay
                    loadingOverlay.classList.add('active');
                    
                    // Animate progress bar
                    let progress = 0;
                    const progressInterval = setInterval(function() {
                        progress += Math.random() * 15;
                        if (progress > 90) {
                            progress = 90;
                        }
                        progressBar.style.width = progress + '%';
                    }, 200);

                    // Simulate loading stages
                    setTimeout(function() {
                        loadingText.textContent = 'Memverifikasi Akun...';
                        loadingSubtext.textContent = 'Memeriksa kredensial Anda';
                    }, 1000);

                    setTimeout(function() {
                        loadingText.textContent = 'Menyiapkan Dashboard...';
                        loadingSubtext.textContent = 'Hampir selesai';
                        progressBar.style.width = '100%';
                    }, 2000);

                    // Show success checkmark
                    setTimeout(function() {
                        successCheckmark.classList.add('active');
                        loadingText.textContent = 'Berhasil!';
                        loadingSubtext.textContent = 'Mengalihkan ke dashboard...';
                    }, 2500);
                });
            }

            // Input focus animations
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