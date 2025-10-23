@extends('layouts.app')

@section('page-title', 'Detail User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-3 btn-back">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <h2 class="mb-0 text-dark fw-bold">Detail User</h2>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit User
                    </a>
                    @if($user->id !== auth()->id())
                    <button type="button" 
                            class="btn btn-danger btn-sm btn-delete" 
                            onclick="confirmDelete({{ $user->id }})">
                        <i class="bi bi-trash me-1"></i>Hapus User
                    </button>
                    @endif
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- User Profile Card -->
                <div class="col-lg-4">
                    <div class="card profile-card shadow-sm border-0 rounded-3">
                        <div class="card-body text-center p-4">
                            <!-- Avatar -->
                            <div class="profile-avatar mx-auto mb-3">
                                {{ strtoupper(substr($user->display_name, 0, 2)) }}
                            </div>
                            
                            <!-- User Name -->
                            <h4 class="fw-bold text-dark mb-1">{{ $user->display_name }}</h4>
                            
                            <!-- Role Badge -->
                            <div class="mb-3">
                                @if($user->role == 'admin')
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="bi bi-shield-check me-1"></i>Administrator
                                    </span>
                                @elseif($user->role == 'petugas')
                                    <span class="badge bg-info fs-6 px-3 py-2">
                                        <i class="bi bi-person-badge me-1"></i>Petugas
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-6 px-3 py-2">
                                        <i class="bi bi-mortarboard me-1"></i>Mahasiswa
                                    </span>
                                @endif
                            </div>

                            <!-- Quick Stats -->
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="quick-stat">
                                        <h6 class="text-muted mb-1">Status</h6>
                                        <span class="badge bg-success">Aktif</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="quick-stat">
                                        <h6 class="text-muted mb-1">Bergabung</h6>
                                        <small class="text-dark fw-semibold">{{ $user->created_at->format('M Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Security Card -->
                    <div class="card security-card shadow-sm border-0 rounded-3 mt-4">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <h6 class="mb-0 fw-bold">
                                <i class="bi bi-shield-lock me-2"></i>Keamanan Akun
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="security-item d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Email Terverifikasi</span>
                                <span class="badge bg-success">Ya</span>
                            </div>
                            <div class="security-item d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Password Terakhir Diubah</span>
                                <span class="text-dark small">{{ $user->updated_at->format('d M Y') }}</span>
                            </div>
                            <div class="security-item d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Login Terakhir</span>
                                <span class="text-dark small">{{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Details Card -->
                <div class="col-lg-8">
                    <div class="card details-card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-person-lines-fill me-2"></i>Informasi Detail
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr class="detail-row">
                                            <td class="detail-label">
                                                <i class="bi bi-person text-primary me-2"></i>
                                                <strong>Nama Lengkap</strong>
                                            </td>
                                            <td class="detail-value">{{ $user->name }}</td>
                                        </tr>
                                        <tr class="detail-row">
                                            <td class="detail-label">
                                                <i class="bi bi-envelope text-primary me-2"></i>
                                                <strong>Email</strong>
                                            </td>
                                            <td class="detail-value">
                                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                    {{ $user->email }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr class="detail-row">
                                            <td class="detail-label">
                                                <i class="bi bi-shield text-primary me-2"></i>
                                                <strong>Role</strong>
                                            </td>
                                            <td class="detail-value">
                                                @if($user->role == 'admin')
                                                    <span class="badge bg-success">Administrator</span>
                                                @elseif($user->role == 'petugas')
                                                    <span class="badge bg-info">Petugas</span>
                                                @else
                                                    <span class="badge bg-warning">Mahasiswa</span>
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        @if($user->role === 'mahasiswa' && $user->mahasiswa)
                                        <tr class="detail-row">
                                            <td class="detail-label">
                                                <i class="bi bi-card-text text-primary me-2"></i>
                                                <strong>NIM</strong>
                                            </td>
                                            <td class="detail-value">
                                                <span class="badge bg-light text-dark fs-6">{{ $user->mahasiswa->nim }}</span>
                                            </td>
                                        </tr>
                                        <tr class="detail-row">
                                            <td class="detail-label">
                                                <i class="bi bi-building text-primary me-2"></i>
                                                <strong>Jurusan</strong>
                                            </td>
                                            <td class="detail-value">{{ $user->mahasiswa->jurusan ?? '-' }}</td>
                                        </tr>
                                        @endif
                                        
                                        <tr class="detail-row">
                                            <td class="detail-label">
                                                <i class="bi bi-calendar-plus text-primary me-2"></i>
                                                <strong>Tanggal Daftar</strong>
                                            </td>
                                            <td class="detail-value">
                                                {{ $user->created_at->format('d F Y, H:i') }} WIB
                                                <small class="text-muted">({{ $user->created_at->diffForHumans() }})</small>
                                            </td>
                                        </tr>
                                        <tr class="detail-row">
                                            <td class="detail-label">
                                                <i class="bi bi-calendar-check text-primary me-2"></i>
                                                <strong>Terakhir Diupdate</strong>
                                            </td>
                                            <td class="detail-value">
                                                {{ $user->updated_at->format('d F Y, H:i') }} WIB
                                                <small class="text-muted">({{ $user->updated_at->diffForHumans() }})</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Timeline Card -->
                    <div class="card timeline-card shadow-sm border-0 rounded-3 mt-4">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <h6 class="mb-0 fw-bold">
                                <i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Akun Dibuat</h6>
                                        <p class="timeline-text text-muted mb-1">
                                            User {{ $user->display_name }} berhasil mendaftar ke sistem
                                        </p>
                                        <small class="timeline-time text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $user->created_at->format('d M Y, H:i') }}
                                        </small>
                                    </div>
                                </div>
                                
                                @if($user->created_at != $user->updated_at)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Profil Diperbarui</h6>
                                        <p class="timeline-text text-muted mb-1">
                                            Informasi profil user telah diperbarui
                                        </p>
                                        <small class="timeline-time text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $user->updated_at->format('d M Y, H:i') }}
                                        </small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="delete-form-{{ $user->id }}" 
      action="{{ route('admin.users.destroy', $user->id) }}" 
      method="POST" 
      class="d-none">
    @csrf
    @method('DELETE')
</form>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

/* General Styles */
body {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
}

.container-fluid {
    padding: 20px;
}

/* Header */
h2 {
    font-size: 24px;
    animation: slideIn 0.6s ease-out;
}

.btn-back {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-back:hover {
    transform: translateX(-2px);
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

/* Cards */
.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    animation: slideIn 0.6s ease-out;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-header {
    border-radius: 12px 12px 0 0;
    padding: 16px;
}

/* Profile Card */
.profile-card {
    position: sticky;
    top: 20px;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 24px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    animation: pulse 2s infinite;
}

.quick-stat {
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.quick-stat:hover {
    background: #f8fafc;
}

/* Security Card */
.security-item {
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
}

.security-item:last-child {
    border-bottom: none;
}

/* Details Card */
.detail-row {
    transition: all 0.3s ease;
}

.detail-row:hover {
    background: #f8fafc;
}

.detail-label {
    width: 200px;
    padding: 16px;
    font-size: 14px;
    color: #475569;
    background: #f8fafc;
    border-right: 1px solid #e2e8f0;
}

.detail-value {
    padding: 16px;
    font-size: 14px;
    color: #1e293b;
    font-weight: 500;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #667eea, #764ba2);
}

.timeline-item {
    position: relative;
    margin-bottom: 24px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 4px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.timeline-content {
    background: white;
    padding: 16px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border-left: 3px solid #667eea;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
}

.timeline-text {
    font-size: 13px;
    line-height: 1.5;
}

.timeline-time {
    font-size: 12px;
}

/* Badges */
.badge {
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
}

/* Buttons */
.btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    padding: 8px 16px;
    transition: all 0.3s ease;
}

.btn-warning {
    background: #f59e0b;
    border: none;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-danger {
    background: #ef4444;
    border: none;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-delete.loading {
    pointer-events: none;
    opacity: 0.8;
    position: relative;
}

.btn-delete.loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.7s linear infinite;
}

/* Animations */
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

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 992px) {
    .profile-card {
        position: static;
        margin-bottom: 20px;
    }
    
    .detail-label {
        width: auto;
        display: block;
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 8px;
    }
    
    .detail-value {
        padding-top: 8px;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 15px;
    }
    
    .profile-avatar {
        width: 60px;
        height: 60px;
        font-size: 20px;
    }
    
    h2 {
        font-size: 20px;
    }
    
    .btn-group {
        flex-direction: column;
        gap: 8px;
    }
    
    .btn-group .btn {
        width: 100%;
    }
    
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -16px;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 16px;
    }
    
    .detail-label,
    .detail-value {
        padding: 12px;
        font-size: 13px;
    }
    
    .timeline-content {
        padding: 12px;
    }
    
    .btn {
        font-size: 12px;
        padding: 6px 12px;
    }
}
</style>

<script>
function confirmDelete(userId) {
    if (confirm('Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.')) {
        const deleteForm = document.getElementById('delete-form-' + userId);
        const deleteBtn = document.querySelector('.btn-delete');
        deleteBtn.classList.add('loading');
        deleteForm.submit();
    }
}

// Auto hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Add smooth scrolling for timeline items
document.addEventListener('DOMContentLoaded', function() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateX(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    timelineItems.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = 'all 0.6s ease';
        observer.observe(item);
    });
});
</script>
@endsection