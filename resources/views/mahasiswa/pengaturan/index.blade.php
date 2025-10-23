@extends('layouts.mahasiswa')

@section('title', 'Pengaturan Akun')

@section('content')
<style>
    .settings-container {
        background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }

    .breadcrumb-custom {
        background: transparent;
        padding: 0;
        margin-bottom: 2rem;
    }

    .breadcrumb-custom .breadcrumb-item {
        color: #64748B;
        font-weight: 600;
    }

    .breadcrumb-custom .breadcrumb-item.active {
        color: #3B82F6;
    }

    .breadcrumb-custom a {
        color: #60A5FA;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-custom a:hover {
        color: #3B82F6;
    }

    .settings-card {
        background: #FFFFFF;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(96, 165, 250, 0.15);
        border: 1px solid #E0E7FF;
        animation: fadeInUp 0.6s ease;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        padding: 2rem 2.5rem;
        border: none;
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon {
        width: 55px;
        height: 55px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .header-text h4 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .header-text p {
        margin: 0;
        font-size: 0.95rem;
        opacity: 0.9;
        font-weight: 500;
    }

    .card-body-custom {
        padding: 2.5rem;
    }

    /* Alert Styles */
    .alert-custom {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        border: none;
        margin-bottom: 2rem;
        animation: slideInDown 0.4s ease;
    }

    .alert-success-custom {
        background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color: #065F46;
        border-left: 4px solid #10B981;
    }

    .alert-danger-custom {
        background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
        color: #991B1B;
        border-left: 4px solid #EF4444;
    }

    .alert-warning-custom {
        background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
        color: #92400E;
        border-left: 4px solid #F59E0B;
    }

    .alert-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
        font-weight: 600;
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #E0E7FF;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
        color: #3B82F6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .section-title h5 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #1E293B;
    }

    /* Form Groups */
    .form-group-custom {
        margin-bottom: 1.75rem;
    }

    .form-label-custom {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .form-label-custom i {
        color: #60A5FA;
        font-size: 1rem;
    }

    .form-control-custom {
        border: 2px solid #E0E7FF;
        border-radius: 14px;
        padding: 0.875rem 1.25rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #F8FAFC;
        color: #1E293B;
        font-weight: 500;
    }

    .form-control-custom:focus {
        border-color: #60A5FA;
        box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.1);
        outline: none;
        background: #FFFFFF;
    }

    .form-control-custom.is-invalid {
        border-color: #EF4444;
    }

    .form-control-custom.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #DC2626;
        font-weight: 600;
    }

    .form-text-custom {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: #64748B;
        font-weight: 500;
    }

    .form-text-custom i {
        color: #60A5FA;
    }

    /* Password Divider */
    .password-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 2.5rem 0;
    }

    .divider-line {
        flex: 1;
        height: 2px;
        background: linear-gradient(90deg, transparent, #E0E7FF, transparent);
    }

    .divider-text {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #EFF6FF;
        color: #3B82F6;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Submit Button */
    .btn-submit-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 14px;
        border: none;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        box-shadow: 0 8px 25px rgba(96, 165, 250, 0.4);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-submit-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(96, 165, 250, 0.5);
        color: white;
        background: linear-gradient(135deg, #3B82F6, #2563EB);
    }

    .btn-submit-custom:active {
        transform: translateY(-1px);
    }

    .btn-submit-custom i {
        font-size: 1.1rem;
    }

    /* Info Box */
    .info-box {
        display: flex;
        align-items: start;
        gap: 1rem;
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        padding: 1.5rem;
        border-radius: 16px;
        margin-top: 2rem;
        border: 1px solid #BFDBFE;
    }

    .info-box-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .info-box-content {
        flex: 1;
        padding-top: 0.25rem;
    }

    .info-box-content h6 {
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
        font-weight: 700;
        color: #1E293B;
    }

    .info-box-content p {
        margin: 0;
        font-size: 0.9rem;
        color: #475569;
        line-height: 1.6;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .card-body-custom {
            padding: 1.75rem;
        }

        .card-header-custom {
            padding: 1.5rem;
        }

        .header-text h4 {
            font-size: 1.4rem;
        }

        .btn-submit-custom {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="settings-container">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item">
                    <a href="{{ route('mahasiswa.dashboard') }}">
                        <i class="bi bi-house-door-fill"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active">Pengaturan Akun</li>
            </ol>
        </nav>

        <!-- Settings Card -->
        <div class="settings-card">
            <div class="card-header-custom">
                <div class="header-content">
                    <div class="header-icon">
                        <i class="bi bi-gear-fill"></i>
                    </div>
                    <div class="header-text">
                        <h4>Pengaturan Akun</h4>
                        <p>Kelola informasi profil dan keamanan akun Anda</p>
                    </div>
                </div>
            </div>

            <div class="card-body-custom">
                {{-- Alert --}}
                @if(session('success'))
                    <div class="alert-custom alert-success-custom">
                        <i class="bi bi-check-circle-fill alert-icon"></i>
                        <div class="alert-content">{{ session('success') }}</div>
                    </div>
                @elseif(session('error'))
                    <div class="alert-custom alert-danger-custom">
                        <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                        <div class="alert-content">{{ session('error') }}</div>
                    </div>
                @endif

                @if(isset($user))
                <form action="{{ route('mahasiswa.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Informasi Pribadi Section --}}
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h5>Informasi Pribadi</h5>
                        </div>

                        {{-- Nama --}}
                        <div class="form-group-custom">
                            <label for="name" class="form-label-custom">
                                <i class="bi bi-person-badge-fill"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $user->name ?? '') }}" 
                                   class="form-control form-control-custom @error('name') is-invalid @enderror" 
                                   required
                                   placeholder="Masukkan nama lengkap Anda">
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group-custom">
                            <label for="email" class="form-label-custom">
                                <i class="bi bi-envelope-fill"></i>
                                Alamat Email
                            </label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $user->email ?? '') }}" 
                                   class="form-control form-control-custom @error('email') is-invalid @enderror" 
                                   required
                                   placeholder="contoh@email.com">
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text-custom">
                                <i class="bi bi-info-circle"></i>
                                Email digunakan untuk login dan notifikasi
                            </small>
                        </div>
                    </div>

                    {{-- Password Divider --}}
                    <div class="password-divider">
                        <div class="divider-line"></div>
                        <div class="divider-text">
                            <i class="bi bi-shield-lock-fill"></i>
                            Keamanan
                        </div>
                        <div class="divider-line"></div>
                    </div>

                    {{-- Keamanan Section --}}
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <h5>Ubah Password</h5>
                        </div>

                        {{-- Password Baru --}}
                        <div class="form-group-custom">
                            <label for="password" class="form-label-custom">
                                <i class="bi bi-key-fill"></i>
                                Password Baru
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="form-control form-control-custom @error('password') is-invalid @enderror"
                                   placeholder="Masukkan password baru">
                            @error('password')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text-custom">
                                <i class="bi bi-info-circle"></i>
                                Kosongkan jika tidak ingin mengubah password
                            </small>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="form-group-custom">
                            <label for="password_confirmation" class="form-label-custom">
                                <i class="bi bi-shield-check"></i>
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control form-control-custom" 
                                   placeholder="Ulangi password baru">
                            <small class="form-text-custom">
                                <i class="bi bi-info-circle"></i>
                                Masukkan kembali password baru untuk konfirmasi
                            </small>
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="info-box">
                        <div class="info-box-icon">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                        <div class="info-box-content">
                            <h6>Tips Keamanan</h6>
                            <p>Gunakan password yang kuat dengan kombinasi huruf besar, huruf kecil, angka, dan simbol. Minimal 8 karakter untuk keamanan maksimal.</p>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div style="margin-top: 2rem;">
                        <button type="submit" class="btn-submit-custom">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
                @else
                    <div class="alert-custom alert-warning-custom">
                        <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                        <div class="alert-content">
                            Data pengguna tidak ditemukan. Silakan <a href="{{ route('login') }}" style="color: #92400E; font-weight: 700; text-decoration: underline;">login kembali</a>.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- JS Preview Foto --}}
<script>
    function previewFoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-foto').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection