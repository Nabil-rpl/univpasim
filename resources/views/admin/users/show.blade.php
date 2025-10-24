@extends('layouts.app')

@section('page-title', 'Detail User')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-back">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h1 class="page-title mb-0">Detail User</h1>
                    <p class="page-subtitle mb-0">Informasi lengkap pengguna sistem</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                @if($user->id !== auth()->id())
                <button type="button" 
                        class="btn btn-danger btn-delete" 
                        onclick="confirmDelete({{ $user->id }})">
                    <i class="bi bi-trash me-1"></i>Hapus
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column - Profile Card -->
        <div class="col-lg-4">
            <div class="card profile-card">
                <div class="card-body text-center p-4">
                    <!-- Avatar with gradient border -->
                    <div class="avatar-wrapper mb-3">
                        <div class="profile-avatar">
                            {{ strtoupper(substr($user->display_name, 0, 2)) }}
                        </div>
                    </div>
                    
                    <!-- User Info -->
                    <h4 class="user-name mb-2">{{ $user->display_name }}</h4>
                    <p class="user-email text-muted mb-3">{{ $user->email }}</p>
                    
                    <!-- Role Badge -->
                    @if($user->role == 'admin')
                        <span class="role-badge badge-admin">
                            <i class="bi bi-shield-check"></i>
                            Administrator
                        </span>
                    @elseif($user->role == 'petugas')
                        <span class="role-badge badge-petugas">
                            <i class="bi bi-person-badge"></i>
                            Petugas
                        </span>
                    @else
                        <span class="role-badge badge-mahasiswa">
                            <i class="bi bi-mortarboard"></i>
                            Mahasiswa
                        </span>
                    @endif

                    <div class="divider my-4"></div>

                    <!-- Quick Stats -->
                    <div class="quick-stats">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-check-circle-fill text-success"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Status Akun</span>
                                <span class="stat-value text-success">Aktif</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-calendar-event text-primary"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Bergabung Sejak</span>
                                <span class="stat-value">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <div class="card security-card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Keamanan Akun
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="security-list">
                        <div class="security-item">
                            <div class="security-info">
                                <i class="bi bi-envelope-check text-success"></i>
                                <span>Email Terverifikasi</span>
                            </div>
                            <span class="badge bg-success-subtle text-success">Aktif</span>
                        </div>
                        <div class="security-item">
                            <div class="security-info">
                                <i class="bi bi-key text-warning"></i>
                                <span>Password Diubah</span>
                            </div>
                            <span class="text-muted small">{{ $user->updated_at->format('d M Y') }}</span>
                        </div>
                        <div class="security-item">
                            <div class="security-info">
                                <i class="bi bi-clock-history text-info"></i>
                                <span>Login Terakhir</span>
                            </div>
                            <span class="text-muted small">{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Details & Activity -->
        <div class="col-lg-8">
            <!-- User Details Card -->
            <div class="card details-card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-person-lines-fill me-2"></i>
                        Informasi Detail
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Basic Info -->
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="bi bi-person-circle"></i>
                                    Nama Lengkap
                                </label>
                                <div class="info-value">{{ $user->name }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="bi bi-envelope"></i>
                                    Email
                                </label>
                                <div class="info-value">
                                    <a href="mailto:{{ $user->email }}" class="text-primary text-decoration-none">
                                        {{ $user->email }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="bi bi-shield-check"></i>
                                    Role Pengguna
                                </label>
                                <div class="info-value">
                                    @if($user->role == 'admin')
                                        <span class="badge bg-success-subtle text-success">Administrator</span>
                                    @elseif($user->role == 'petugas')
                                        <span class="badge bg-info-subtle text-info">Petugas</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning">Mahasiswa</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($user->role === 'mahasiswa' && $user->mahasiswa)
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="bi bi-card-text"></i>
                                    NIM
                                </label>
                                <div class="info-value">
                                    <span class="badge bg-primary-subtle text-primary">{{ $user->mahasiswa->nim }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="bi bi-building"></i>
                                    Jurusan
                                </label>
                                <div class="info-value">{{ $user->mahasiswa->jurusan ?? '-' }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="bi bi-calendar-plus"></i>
                                    Tanggal Daftar
                                </label>
                                <div class="info-value">
                                    {{ $user->created_at->format('d F Y, H:i') }} WIB
                                    <small class="text-muted d-block mt-1">{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="bi bi-calendar-check"></i>
                                    Terakhir Diupdate
                                </label>
                                <div class="info-value">
                                    {{ $user->updated_at->format('d F Y, H:i') }} WIB
                                    <small class="text-muted d-block mt-1">{{ $user->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline Card -->
            <div class="card timeline-card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Riwayat Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="timeline-title mb-0">Akun Dibuat</h6>
                                    <span class="timeline-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $user->created_at->format('d M Y') }}
                                    </span>
                                </div>
                                <p class="timeline-text mb-2">
                                    User <strong>{{ $user->display_name }}</strong> berhasil mendaftar ke sistem
                                </p>
                                <span class="timeline-time">
                                    <i class="bi bi-clock"></i>
                                    {{ $user->created_at->format('H:i') }} WIB
                                </span>
                            </div>
                        </div>
                        
                        @if($user->created_at != $user->updated_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="timeline-title mb-0">Profil Diperbarui</h6>
                                    <span class="timeline-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $user->updated_at->format('d M Y') }}
                                    </span>
                                </div>
                                <p class="timeline-text mb-2">
                                    Informasi profil user telah diperbarui
                                </p>
                                <span class="timeline-time">
                                    <i class="bi bi-clock"></i>
                                    {{ $user->updated_at->format('H:i') }} WIB
                                </span>
                            </div>
                        </div>
                        @endif
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
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #06b6d4;
    --dark: #1e293b;
    --light: #f8fafc;
    --border: #e2e8f0;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
    --radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--light);
    color: var(--dark);
    line-height: 1.6;
}

/* Page Header */
.page-header {
    animation: fadeInDown 0.5s ease;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.page-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin-top: 2px;
}

.btn-back {
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: white;
    transition: var(--transition);
}

.btn-back:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: translateX(-3px);
}

/* Alerts */
.custom-alert {
    border: none;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    animation: slideInDown 0.4s ease;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border-left: 4px solid var(--success);
    color: #065f46;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-left: 4px solid var(--danger);
    color: #991b1b;
}

/* Cards */
.card {
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: white;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    animation: fadeInUp 0.5s ease;
}

.card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid var(--border);
    padding: 1rem 1.25rem;
    border-radius: var(--radius) var(--radius) 0 0;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
    display: flex;
    align-items: center;
}

.card-body {
    padding: 1.5rem;
}

/* Profile Card */
.profile-card {
    position: sticky;
    top: 20px;
}

.avatar-wrapper {
    position: relative;
    display: inline-block;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 2rem;
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
    border: 4px solid white;
    animation: scaleIn 0.6s ease;
}

.user-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.user-email {
    font-size: 0.875rem;
    word-break: break-all;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.badge-admin {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.badge-petugas {
    background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
    color: #0e7490;
}

.badge-mahasiswa {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border), transparent);
}

/* Quick Stats */
.quick-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: var(--light);
    border-radius: 10px;
    transition: var(--transition);
}

.stat-item:hover {
    background: #f1f5f9;
    transform: translateX(5px);
}

.stat-icon {
    font-size: 1.5rem;
}

.stat-content {
    display: flex;
    flex-direction: column;
    text-align: left;
}

.stat-label {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 500;
}

.stat-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
}

/* Security Card */
.security-list {
    display: flex;
    flex-direction: column;
}

.security-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
    transition: var(--transition);
}

.security-item:last-child {
    border-bottom: none;
}

.security-item:hover {
    background: var(--light);
}

.security-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    color: var(--dark);
    font-weight: 500;
}

.security-info i {
    font-size: 1.125rem;
}

/* Details Card */
.info-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.info-label i {
    font-size: 1rem;
    color: var(--primary);
}

.info-value {
    font-size: 0.9375rem;
    font-weight: 500;
    color: var(--dark);
    padding: 0.75rem;
    background: var(--light);
    border-radius: 8px;
    border-left: 3px solid var(--primary);
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, var(--primary), var(--info));
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
    padding-left: 1rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -1.5rem;
    top: 6px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 2px currentColor;
}

.timeline-content {
    background: white;
    padding: 1.25rem;
    border-radius: 10px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.timeline-content:hover {
    box-shadow: var(--shadow-md);
    transform: translateX(5px);
}

.timeline-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--dark);
}

.timeline-date {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 500;
}

.timeline-text {
    font-size: 0.875rem;
    color: #64748b;
    line-height: 1.6;
}

.timeline-time {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Buttons */
.btn {
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.625rem 1.25rem;
    border: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.btn-delete.loading {
    pointer-events: none;
    opacity: 0.7;
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
    animation: spin 0.6s linear infinite;
}

/* Badge Utilities */
.badge {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.bg-success-subtle {
    background: #d1fae5 !important;
}

.bg-info-subtle {
    background: #cffafe !important;
}

.bg-warning-subtle {
    background: #fef3c7 !important;
}

.bg-primary-subtle {
    background: #dbeafe !important;
}

/* Animations */
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

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 991px) {
    .profile-card {
        position: static;
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 1.25rem;
    }
    
    .page-subtitle {
        display: none;
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        font-size: 1.5rem;
    }
    
    .user-name {
        font-size: 1.25rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline-marker {
        width: 16px;
        height: 16px;
        left: -1.25rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .btn {
        font-size: 0.8125rem;
        padding: 0.5rem 1rem;
    }
    
    .info-value {
        font-size: 0.875rem;
        padding: 0.625rem;
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

// Smooth scroll animation for timeline
document.addEventListener('DOMContentLoaded', function() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateX(0)';
                }, index * 100);
            }
        });
    }, {
        threshold: 0.1
    });
    
    timelineItems.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = 'all 0.5s ease';
        observer.observe(item);
    });
});

// Add stagger animation to info groups
document.addEventListener('DOMContentLoaded', function() {
    const infoGroups = document.querySelectorAll('.info-group');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 50);
            }
        });
    }, {
        threshold: 0.1
    });
    
    infoGroups.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'all 0.4s ease';
        observer.observe(item);
    });
});
</script>
@endsection