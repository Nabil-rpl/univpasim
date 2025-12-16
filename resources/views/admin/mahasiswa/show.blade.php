@extends('layouts.app')

@section('page-title', 'Detail Mahasiswa')

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

    .user-card-top {
        border-top: 5px solid #10b981;
    }

    .timeline-card-top {
        border-top: 5px solid #f59e0b;
    }

    .alert-custom {
        padding: 20px;
        border-radius: 14px;
        margin-bottom: 24px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .alert-info-custom {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-left: 5px solid #3b82f6;
    }

    .alert-info-custom strong,
    .alert-info-custom p {
        color: #1e3a8a;
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
                    <i class="bi bi-mortarboard-fill me-2"></i>Detail Mahasiswa
                </h3>
                <p class="mb-0 opacity-90" style="font-size: 0.95rem;">Informasi lengkap data mahasiswa</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- ID Mahasiswa -->
    <div class="detail-card" style="text-align: center; border-top: 5px solid #8b5cf6;">
        <div class="id-badge">
            {{ $mahasiswa->nim }}
        </div>
        <p class="text-muted mb-0 mt-2">Nomor Induk Mahasiswa</p>
    </div>

    <!-- Action Buttons -->
    <div class="detail-card" style="border-top: 5px solid #667eea;">
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-action-custom btn-edit-custom">
                <i class="bi bi-pencil-square me-2"></i>Edit Data Mahasiswa
            </a>
            <button type="button" class="btn btn-action-custom btn-delete-custom" onclick="confirmDelete()">
                <i class="bi bi-trash3 me-2"></i>Hapus Data Mahasiswa
            </button>
        </div>
    </div>

    <!-- Informasi Profil -->
    <div class="detail-card profile-card-top">
        <h6 class="card-title">
            <i class="bi bi-person-circle"></i>
            Informasi Profil Mahasiswa
        </h6>
        
        <div class="avatar-display">
            <div class="avatar-circle">
                {{ strtoupper(substr($mahasiswa->nama, 0, 2)) }}
            </div>
            
            <div class="avatar-info">
                <h5>{{ $mahasiswa->nama }}</h5>
                <p>
                    <i class="bi bi-envelope me-1"></i>{{ $mahasiswa->email }}
                </p>
                <span class="badge badge-custom" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                    <i class="bi bi-mortarboard me-1"></i>Mahasiswa Aktif
                </span>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-person-fill"></i>
                    <span>Nama Lengkap</span>
                </div>
                <div class="info-value">
                    <strong>{{ $mahasiswa->nama }}</strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Alamat Email</span>
                </div>
                <div class="info-value">
                    {{ $mahasiswa->email }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-card-text"></i>
                    <span>Nomor Induk Mahasiswa (NIM)</span>
                </div>
                <div class="info-value">
                    <span class="status-badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="bi bi-hash"></i>
                        {{ $mahasiswa->nim }}
                    </span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-building"></i>
                    <span>Jurusan / Program Studi</span>
                </div>
                <div class="info-value">
                    @if($mahasiswa->jurusan)
                        <span class="badge badge-custom bg-info">
                            <i class="bi bi-book me-1"></i>{{ $mahasiswa->jurusan }}
                        </span>
                    @else
                        <span class="text-muted fst-italic">Belum ditentukan</span>
                    @endif
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
                    <strong>{{ $mahasiswa->created_at->format('d M Y') }}</strong>
                    <span class="text-muted ms-2">{{ $mahasiswa->created_at->format('H:i') }} WIB</span>
                    <br>
                    <small class="text-muted">{{ $mahasiswa->created_at->diffForHumans() }}</small>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-calendar-check"></i>
                    <span>Terakhir Diupdate</span>
                </div>
                <div class="info-value">
                    <strong>{{ $mahasiswa->updated_at->format('d M Y') }}</strong>
                    <span class="text-muted ms-2">{{ $mahasiswa->updated_at->format('H:i') }} WIB</span>
                    <br>
                    <small class="text-muted">{{ $mahasiswa->updated_at->diffForHumans() }}</small>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-flag-fill"></i>
                    <span>Status Mahasiswa</span>
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

    <!-- Informasi User Terkait -->
    @if($mahasiswa->user)
    <div class="detail-card user-card-top">
        <h6 class="card-title">
            <i class="bi bi-person-badge-fill"></i>
            Informasi Akun User Terkait
        </h6>

        <div class="alert-custom alert-info-custom">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-info-circle-fill" style="font-size: 2rem; color: #2563eb;"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-3" style="font-size: 1.1rem;">ℹ️ Akun Sistem Terhubung</strong>
                    <div class="info-section" style="border-left-color: #3b82f6;">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-person-circle"></i>
                                <span>Nama User</span>
                            </div>
                            <div class="info-value">
                                <strong>{{ $mahasiswa->user->name }}</strong>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-envelope-at"></i>
                                <span>Email Login</span>
                            </div>
                            <div class="info-value">
                                {{ $mahasiswa->user->email }}
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-shield-check"></i>
                                <span>Role Akses</span>
                            </div>
                            <div class="info-value">
                                @if($mahasiswa->user->role == 'admin')
                                    <span class="status-badge bg-success text-white">
                                        <i class="bi bi-shield-check"></i>
                                        Administrator
                                    </span>
                                @elseif($mahasiswa->user->role == 'petugas')
                                    <span class="status-badge bg-info text-white">
                                        <i class="bi bi-person-badge"></i>
                                        Petugas
                                    </span>
                                @else
                                    <span class="status-badge bg-primary text-white">
                                        <i class="bi bi-mortarboard"></i>
                                        Mahasiswa
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Timeline -->
    <div class="detail-card timeline-card-top">
        <h6 class="card-title">
            <i class="bi bi-clock-history"></i>
            Riwayat Aktivitas
        </h6>

        <div class="timeline">
            <!-- Data dibuat -->
            <div class="timeline-item">
                <div class="timeline-icon bg-success">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>
                <div class="timeline-content">
                    <h6>🎓 Data Mahasiswa Dibuat</h6>
                    <p>{{ $mahasiswa->created_at->format('d M Y, H:i') }} WIB</p>
                    <small>
                        Mahasiswa <strong>{{ $mahasiswa->nama }}</strong> terdaftar dalam sistem
                    </small>
                </div>
            </div>

            <!-- Update terakhir -->
            @if($mahasiswa->created_at != $mahasiswa->updated_at)
            <div class="timeline-item">
                <div class="timeline-icon bg-info">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div class="timeline-content">
                    <h6>✏️ Data Diperbarui</h6>
                    <p>{{ $mahasiswa->updated_at->format('d M Y, H:i') }} WIB</p>
                    <small>
                        Informasi data mahasiswa telah diperbarui
                    </small>
                </div>
            </div>
            @endif

            <!-- User terhubung -->
            @if($mahasiswa->user)
            <div class="timeline-item">
                <div class="timeline-icon bg-primary">
                    <i class="bi bi-link-45deg"></i>
                </div>
                <div class="timeline-content">
                    <h6>🔗 Akun User Terhubung</h6>
                    <p>Terhubung dengan akun: <strong>{{ $mahasiswa->user->name }}</strong></p>
                    <small>
                        <i class="bi bi-envelope me-1"></i>{{ $mahasiswa->user->email }}
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Form Delete (Hidden) -->
<form id="delete-form" action="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete() {
    if (confirm('⚠️ Apakah Anda yakin ingin menghapus data mahasiswa "{{ $mahasiswa->nama }}"?\n\nData yang dihapus tidak dapat dikembalikan!')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection