@extends('layouts.app')

@section('page-title', 'Detail User')

@section('content')
<style>
    .detail-card {
        background: white;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
        transition: all 0.3s ease;
        border: 1px solid rgba(102, 126, 234, 0.08);
    }

    .detail-card:hover {
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.12);
        transform: translateY(-2px);
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 28px;
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
    }

    .info-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 20px;
        border-left: 4px solid #667eea;
    }

    .info-row {
        display: flex;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        gap: 16px;
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-row:first-child {
        padding-top: 0;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #64748b;
        min-width: 200px;
        flex-shrink: 0;
        font-size: 0.95rem;
    }

    .info-label i {
        color: #667eea;
        font-size: 1.1rem;
        width: 24px;
        text-align: center;
    }

    .info-value {
        flex: 1;
        color: #1e293b;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 3px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: #667eea;
        font-size: 1.3rem;
    }

    .avatar-display {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        flex-shrink: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 700;
        position: relative;
    }

    .online-status {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 20px;
        height: 20px;
        background: #22c55e;
        border: 3px solid white;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.4);
    }

    .avatar-info h5 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .avatar-info p {
        margin-bottom: 0;
        color: #64748b;
    }

    .timeline-item {
        position: relative;
        padding-left: 45px;
        padding-bottom: 28px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 16px;
        top: 36px;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #667eea 0%, #e2e8f0 100%);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border: 3px solid white;
    }

    .timeline-content h6 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: #1e293b;
    }

    .timeline-content p {
        font-size: 0.9rem;
        margin-bottom: 6px;
        color: #64748b;
        font-weight: 500;
    }

    .timeline-content small {
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .btn-back {
        background: white;
        border: 2px solid white;
        padding: 10px 24px;
        border-radius: 10px;
        color: #667eea;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        transform: translateX(-4px);
    }

    .btn-action-custom {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-edit-custom {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .btn-edit-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
    }

    .btn-delete-custom {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-delete-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
    }

    .badge-custom {
        padding: 7px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .id-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 28px;
        border-radius: 25px;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        font-size: 1.2rem;
        display: inline-block;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        letter-spacing: 1px;
    }

    .status-badge {
        padding: 8px 18px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Card specific styling */
    .profile-card-top {
        border-top: 5px solid #3b82f6;
    }

    .info-card-top {
        border-top: 5px solid #8b5cf6;
    }

    .security-card-top {
        border-top: 5px solid #f59e0b;
    }

    .timeline-card-top {
        border-top: 5px solid #10b981;
    }

    .security-item {
        background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
        border-left: 4px solid #a855f7;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 16px;
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .security-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 15px rgba(168, 85, 247, 0.15);
    }

    .security-item:last-child {
        margin-bottom: 0;
    }

    .security-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .security-left i {
        font-size: 1.2rem;
    }

    .security-badge {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
    }

    .alert-custom {
        padding: 20px;
        border-radius: 14px;
        margin-bottom: 24px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .alert-success-custom {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-left: 5px solid #22c55e;
    }

    .alert-success-custom strong,
    .alert-success-custom p {
        color: #065f46;
    }

    .alert-danger-custom {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 5px solid #ef4444;
    }

    .alert-danger-custom strong,
    .alert-danger-custom p {
        color: #991b1b;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .info-label {
            min-width: 150px;
            font-size: 0.9rem;
        }
        
        .avatar-display {
            flex-direction: column;
            text-align: center;
        }
        
        .avatar-circle {
            margin: 0 auto;
        }

        .page-header {
            padding: 20px;
        }

        .detail-card {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-2 fw-bold">
                    <i class="bi bi-person-circle me-2"></i>Detail User
                </h3>
                <p class="mb-0 opacity-90" style="font-size: 0.95rem;">Informasi lengkap pengguna sistem perpustakaan</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success-custom alert-custom">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-check-circle-fill" style="font-size: 2rem; color: #16a34a;"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-2" style="font-size: 1.1rem;">✅ Berhasil!</strong>
                    <p class="mb-0" style="font-size: 0.95rem;">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger-custom alert-custom">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem; color: #dc2626;"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-2" style="font-size: 1.1rem;">⚠️ Error!</strong>
                    <p class="mb-0" style="font-size: 0.95rem;">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- ID User -->
    <div class="detail-card" style="text-align: center; border-top: 5px solid #8b5cf6;">
        <div class="id-badge">
            #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
        </div>
        <p class="text-muted mb-0 mt-2">ID User</p>
    </div>

    <!-- Action Buttons -->
    <div class="detail-card" style="border-top: 5px solid #667eea;">
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-action-custom btn-edit-custom">
                <i class="bi bi-pencil-square me-2"></i>Edit User
            </a>
            @if($user->id !== auth()->id())
            <button type="button" class="btn btn-action-custom btn-delete-custom" onclick="confirmDelete({{ $user->id }})">
                <i class="bi bi-trash3 me-2"></i>Hapus User
            </button>
            @endif
        </div>
    </div>

    <!-- Informasi Profil -->
    <div class="detail-card profile-card-top">
        <h6 class="card-title">
            <i class="bi bi-person-circle"></i>
            Informasi Profil User
        </h6>
        
        <div class="avatar-display">
            <div class="avatar-circle">
                {{ strtoupper(substr($user->display_name, 0, 2)) }}
                <span class="online-status"></span>
            </div>
            
            <div class="avatar-info">
                <h5>{{ $user->display_name }}</h5>
                <p>
                    <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                </p>
                @if($user->role == 'admin')
                    <span class="badge badge-custom" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white;">
                        <i class="bi bi-shield-check me-1"></i>Administrator
                    </span>
                @elseif($user->role == 'petugas')
                    <span class="badge badge-custom" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                        <i class="bi bi-person-badge me-1"></i>Petugas
                    </span>
                @else
                    <span class="badge badge-custom" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                        <i class="bi bi-mortarboard me-1"></i>Mahasiswa
                    </span>
                @endif
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-person-fill"></i>
                    <span>Nama Lengkap</span>
                </div>
                <div class="info-value">
                    <strong>{{ $user->name }}</strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Alamat Email</span>
                </div>
                <div class="info-value">
                    {{ $user->email }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-shield-check"></i>
                    <span>Role Pengguna</span>
                </div>
                <div class="info-value">
                    @if($user->role == 'admin')
                        <span class="status-badge" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white;">
                            <i class="bi bi-shield-check"></i>
                            Administrator
                        </span>
                    @elseif($user->role == 'petugas')
                        <span class="status-badge" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                            <i class="bi bi-person-badge"></i>
                            Petugas
                        </span>
                    @else
                        <span class="status-badge" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                            <i class="bi bi-mortarboard"></i>
                            Mahasiswa
                        </span>
                    @endif
                </div>
            </div>

            @if($user->role === 'mahasiswa' && $user->mahasiswa)
            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-card-text"></i>
                    <span>Nomor Induk Mahasiswa (NIM)</span>
                </div>
                <div class="info-value">
                    <strong>{{ $user->mahasiswa->nim }}</strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-building"></i>
                    <span>Jurusan</span>
                </div>
                <div class="info-value">
                    {{ $user->mahasiswa->jurusan ?? '-' }}
                </div>
            </div>
            @endif

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-flag-fill"></i>
                    <span>Status Akun</span>
                </div>
                <div class="info-value">
                    <span class="status-badge bg-success text-white">
                        <i class="bi bi-check-circle-fill"></i>
                        Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Informasi -->
    <div class="detail-card info-card-top">
        <h6 class="card-title">
            <i class="bi bi-clipboard-data-fill"></i>
            Detail Informasi
        </h6>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-calendar-plus"></i>
                    <span>Tanggal Daftar</span>
                </div>
                <div class="info-value">
                    <strong>{{ $user->created_at->format('d M Y') }}</strong>
                    <span class="text-muted ms-2">{{ $user->created_at->format('H:i') }} WIB</span>
                    <br>
                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-calendar-check"></i>
                    <span>Terakhir Diupdate</span>
                </div>
                <div class="info-value">
                    <strong>{{ $user->updated_at->format('d M Y') }}</strong>
                    <span class="text-muted ms-2">{{ $user->updated_at->format('H:i') }} WIB</span>
                    <br>
                    <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-calendar-event"></i>
                    <span>Bergabung Sejak</span>
                </div>
                <div class="info-value">
                    <span class="badge badge-custom bg-primary">
                        <i class="bi bi-clock me-1"></i>{{ $user->created_at->format('M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Keamanan Akun -->
    <div class="detail-card security-card-top">
        <h6 class="card-title">
            <i class="bi bi-shield-lock"></i>
            Keamanan Akun
        </h6>

        <div class="security-item">
            <div class="security-left">
                <i class="bi bi-envelope-check text-success"></i>
                <div>
                    <strong style="color: #1e293b; font-size: 0.95rem;">Email Terverifikasi</strong>
                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">Email aktif dan terverifikasi</p>
                </div>
            </div>
            <span class="security-badge">Aktif</span>
        </div>

        <div class="security-item">
            <div class="security-left">
                <i class="bi bi-key text-warning"></i>
                <div>
                    <strong style="color: #1e293b; font-size: 0.95rem;">Password</strong>
                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">Terakhir diubah: {{ $user->updated_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="security-item">
            <div class="security-left">
                <i class="bi bi-clock-history text-info"></i>
                <div>
                    <strong style="color: #1e293b; font-size: 0.95rem;">Login Terakhir</strong>
                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="detail-card timeline-card-top">
        <h6 class="card-title">
            <i class="bi bi-clock-history"></i>
            Riwayat Aktivitas
        </h6>

        <div class="timeline">
            <!-- Akun dibuat -->
            <div class="timeline-item">
                <div class="timeline-icon bg-success">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>
                <div class="timeline-content">
                    <h6>👤 Akun Dibuat</h6>
                    <p>{{ $user->created_at->format('d M Y, H:i') }} WIB</p>
                    <small>
                        User <strong>{{ $user->display_name }}</strong> berhasil mendaftar ke sistem
                    </small>
                </div>
            </div>

            <!-- Update terakhir -->
            @if($user->created_at != $user->updated_at)
            <div class="timeline-item">
                <div class="timeline-icon bg-info">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div class="timeline-content">
                    <h6>✏️ Profil Diperbarui</h6>
                    <p>{{ $user->updated_at->format('d M Y, H:i') }} WIB</p>
                    <small>
                        Informasi profil user telah diperbarui
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(userId) {
    if (confirm('⚠️ Apakah Anda yakin ingin menghapus user ini?\n\nTindakan ini tidak dapat dibatalkan!')) {
        document.getElementById('delete-form-' + userId).submit();
    }
}

// Auto hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-custom');
        alerts.forEach(function(alert) {
            alert.style.transition = 'all 0.3s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
});
</script>
@endsection