@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <!-- Sidebar Profil -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar-placeholder mb-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small mb-2">{{ $user->email }}</p>
                        <span class="badge bg-primary mb-3">{{ ucfirst($user->role) }}</span>

                        @if ($user->role === 'mahasiswa' && $user->nim)
                            <div class="mt-3 p-3 bg-light rounded">
                                <small class="text-muted d-block">Nomor Induk Mahasiswa</small>
                                <strong>{{ $user->nim }}</strong>
                            </div>
                        @endif

                        <hr class="my-3">

                        <div class="d-grid gap-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> Bergabung sejak<br>
                                <strong>{{ $user->created_at->format('d F Y') }}</strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Profil & Password -->
            <div class="col-lg-8">
                <!-- Alert Success -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form Update Profil -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-person-fill"></i> Informasi Profil</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <i class="bi bi-info-circle text-muted" title="Nama tidak dapat diubah"></i>
                                    <label for="name" class="form-label fw-bold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" placeholder="Nama lengkap" disabled>
                                    <small class="text-muted">Nama tidak dapat diubah</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <i class="bi bi-info-circle text-muted" title="Email tidak dapat diubah"></i>
                                    <label for="email" class="form-label fw-bold">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" placeholder="nama@email.com" disabled>
                                    <small class="text-muted">Email tidak dapat diubah</small>
                                </div>

                                @if ($user->role === 'mahasiswa')
                                    <div class="col-md-6 mb-3">
                                        <label for="nim" class="form-label fw-bold">NIM</label>
                                        <input type="text" class="form-control @error('nim') is-invalid @enderror"
                                            id="nim" name="nim" value="{{ old('nim', $user->nim) }}"
                                            placeholder="Nomor Induk Mahasiswa">
                                        @error('nim')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                <div class="col-md-6 mb-3">
                                    <i class="bi bi-info-circle text-muted" title="Role tidak dapat diubah"></i>
                                    <label class="form-label fw-bold">Role</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                                    <small class="text-muted">Role tidak dapat diubah</small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Form Ubah Password -->
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-shield-lock-fill"></i> Ubah Password</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill"></i>
                            <strong>Tips Keamanan:</strong> Gunakan kombinasi huruf besar, huruf kecil, angka dan simbol
                            untuk password yang kuat.
                        </div>

                        <form action="{{ route('admin.profile.update-password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="current_password" class="form-label fw-bold">
                                        Password Saat Ini <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password"
                                            placeholder="Masukkan password saat ini" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('current_password')">
                                            <i class="bi bi-eye" id="current_password-icon"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-bold">
                                        Password Baru <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-key"></i>
                                        </span>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password" placeholder="Password baru" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i> Minimal 8 karakter
                                    </small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-bold">
                                        Konfirmasi Password Baru <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-key-fill"></i>
                                        </span>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Ulangi password baru" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password_confirmation')">
                                            <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-shield-check"></i> Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .avatar-placeholder {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 3rem;
                font-weight: bold;
                margin: 0 auto;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            }

            .card {
                border: none;
                border-radius: 12px;
                overflow: hidden;
            }

            .card-header {
                border-bottom: 2px solid rgba(0, 0, 0, 0.05);
                padding: 1.25rem;
                font-weight: 600;
            }

            .input-group .btn-outline-secondary {
                border-left: none;
            }

            .input-group .form-control {
                border-right: none;
            }

            .input-group .form-control:focus {
                border-color: #ced4da;
                box-shadow: none;
            }

            .input-group .form-control:focus+.btn-outline-secondary {
                border-color: #ced4da;
            }

            .input-group-text {
                background-color: #f8f9fc;
                border-right: none;
            }

            .badge {
                padding: 8px 16px;
                font-size: 0.85rem;
                font-weight: 600;
            }

            .form-label.fw-bold {
                color: #2d3748;
                margin-bottom: 0.5rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function togglePassword(fieldId) {
                const field = document.getElementById(fieldId);
                const icon = document.getElementById(fieldId + '-icon');

                if (field.type === 'password') {
                    field.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    field.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }

            // Auto-hide alert after 5 seconds
            document.addEventListener('DOMContentLoaded', function() {
                const alert = document.querySelector('.alert');
                if (alert) {
                    setTimeout(() => {
                        alert.classList.remove('show');
                        setTimeout(() => alert.remove(), 150);
                    }, 5000);
                }
            });
        </script>
    @endpush
@endsection
