@extends('layouts.petugas')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        border-radius: 16px;
        border: none;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(37, 99, 235, 0.25);
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .welcome-card .card-body {
        position: relative;
        z-index: 1;
        padding: 35px;
    }

    .welcome-card .card-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: white;
    }

    .welcome-card .text-muted {
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 1.05rem;
    }

    /* Action Bar */
    .action-bar {
        background: white;
        border-radius: 16px;
        padding: 25px 30px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        border: 2px solid #f1f5f9;
    }

    .action-bar-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .action-bar-title i {
        color: #2563eb;
        font-size: 1.3rem;
    }

    /* Stats Cards */
    .stat-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--card-gradient);
    }

    .stat-card.card-warning {
        --card-gradient: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
    }

    .stat-card.card-primary {
        --card-gradient: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
    }

    .stat-card.card-danger {
        --card-gradient: linear-gradient(90deg, #ef4444 0%, #f87171 100%);
    }

    .stat-card.card-info {
        --card-gradient: linear-gradient(90deg, #06b6d4 0%, #22d3ee 100%);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-card .card-body {
        padding: 30px 25px;
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
    }

    .card-warning .icon-wrapper {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    }

    .card-primary .icon-wrapper {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    }

    .card-danger .icon-wrapper {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    }

    .card-info .icon-wrapper {
        background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
    }

    .icon-wrapper i {
        font-size: 2rem;
    }

    .card-warning .icon-wrapper i {
        color: #f59e0b;
    }

    .card-primary .icon-wrapper i {
        color: #2563eb;
    }

    .card-danger .icon-wrapper i {
        color: #ef4444;
    }

    .card-info .icon-wrapper i {
        color: #06b6d4;
    }

    .stat-card h5 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1e293b;
        margin-top: 15px;
        margin-bottom: 10px;
    }

    .stat-card p {
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .filter-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f5f9;
    }

    .filter-header i {
        font-size: 1.5rem;
        color: #2563eb;
    }

    .filter-header h5 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
    }

    .filter-input {
        padding: 12px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s;
        background: #f8fafc;
    }

    .filter-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        background: white;
    }

    .btn-filter {
        padding: 12px 30px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: white;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #64748b;
        border: 2px solid #e2e8f0;
    }

    .btn-secondary:hover {
        border-color: #2563eb;
        color: #2563eb;
    }

    /* Notification Card */
    .notification-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        border: 2px solid transparent;
        position: relative;
    }

    .notification-card.unread {
        border-left: 5px solid #2563eb;
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.15);
    }

    .notification-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .notification-header {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .notification-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-unread {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
        box-shadow: 0 2px 8px rgba(245, 87, 108, 0.3);
        animation: pulse-badge 2s ease-in-out infinite;
    }

    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .notification-text {
        color: #64748b;
        line-height: 1.7;
        margin-bottom: 15px;
        font-size: 0.95rem;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        font-size: 0.85rem;
        color: #64748b;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .meta-item i {
        color: #2563eb;
    }

    .notification-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f1f5f9;
    }

    .btn-action {
        padding: 8px 16px;
        border-radius: 10px;
        border: none;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-view {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        color: white;
    }

    .btn-delete {
        background: white;
        color: #ef4444;
        border: 2px solid #ef4444;
    }

    .btn-delete:hover {
        background: #ef4444;
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-state-icon {
        font-size: 5rem;
        color: #cbd5e0;
        margin-bottom: 20px;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .empty-state h4 {
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 25px;
    }

    /* Alerts */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 16px 20px;
        font-weight: 600;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert i {
        font-size: 1.2rem;
    }

    @media (max-width: 768px) {
        .welcome-card .card-title {
            font-size: 1.4rem;
        }
        
        .stat-card h5 {
            font-size: 1.8rem;
        }

        .action-bar {
            padding: 20px;
        }

        .notification-header {
            flex-direction: column;
        }

        .notification-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">📬 Notifikasi Petugas</h5>
                    <p class="text-muted">Semua notifikasi peminjaman, perpanjangan, dan pengembalian buku</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card card-warning border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                    <h5 class="mt-3">{{ $belumDibaca }}</h5>
                    <p class="text-muted mb-0">Belum Dibaca</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card card-primary border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <h5 class="mt-3">{{ $stats['peminjaman_baru'] ?? 0 }}</h5>
                    <p class="text-muted mb-0">Peminjaman Baru</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card card-info border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-arrow-clockwise"></i>
                    </div>
                    <h5 class="mt-3">{{ $stats['perpanjangan_baru'] ?? 0 }}</h5>
                    <p class="text-muted mb-0">Perpanjangan</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card card-danger border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-box-arrow-in-left"></i>
                    </div>
                    <h5 class="mt-3">{{ $stats['pengembalian_baru'] ?? 0 }}</h5>
                    <p class="text-muted mb-0">Pengembalian</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    @if($belumDibaca > 0)
    <div class="action-bar">
        <div class="action-bar-title">
            <i class="bi bi-check-all"></i>
            <span>{{ $belumDibaca }} notifikasi belum dibaca</span>
        </div>
        <button type="button" class="btn-filter btn-primary" onclick="markAllAsRead()">
            <i class="bi bi-check-circle-fill"></i>
            <span>Tandai Semua Dibaca</span>
        </button>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="filter-card">
        <div class="filter-header">
            <i class="bi bi-funnel-fill"></i>
            <h5>Filter Notifikasi</h5>
        </div>

        <form method="GET" action="{{ route('petugas.notifikasi.index') }}" class="row g-3">
            <div class="col-md-4">
                <select name="status" class="filter-input form-select">
                    <option value="">Semua Status</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>
            </div>

            <div class="col-md-4">
                <select name="tipe" class="filter-input form-select">
                    <option value="">Semua Tipe</option>
                    <option value="peminjaman_baru" {{ request('tipe') == 'peminjaman_baru' ? 'selected' : '' }}>Peminjaman Baru</option>
                    <option value="perpanjangan_baru" {{ request('tipe') == 'perpanjangan_baru' ? 'selected' : '' }}>Perpanjangan Baru</option>
                    <option value="pengembalian_baru" {{ request('tipe') == 'pengembalian_baru' ? 'selected' : '' }}>Pengembalian Baru</option>
                    <option value="terlambat" {{ request('tipe') == 'terlambat' ? 'selected' : '' }}>Keterlambatan</option>
                    <option value="sistem" {{ request('tipe') == 'sistem' ? 'selected' : '' }}>Sistem</option>
                </select>
            </div>

            <div class="col-md-4">
                <input type="text" name="search" class="filter-input form-control" placeholder="Cari notifikasi..." value="{{ request('search') }}">
            </div>

            <div class="col-12">
                <button type="submit" class="btn-filter btn-primary">
                    <i class="bi bi-search"></i>
                    Cari
                </button>
                <a href="{{ route('petugas.notifikasi.index') }}" class="btn-filter btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Notifications List -->
    @if($notifikasi->count() > 0)
        @foreach($notifikasi as $n)
        <div class="notification-card {{ !$n->dibaca ? 'unread' : '' }}" id="notif-{{ $n->id }}">
            <div class="notification-header">
                <div class="notification-content">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="notification-title">{{ $n->judul }}</h5>
                            @if(!$n->dibaca)
                            <span class="notification-badge badge-unread" id="badge-{{ $n->id }}">
                                ● Belum Dibaca
                            </span>
                            @endif
                        </div>
                    </div>

                    <p class="notification-text">{{ Str::limit($n->isi, 200) }}</p>

                    <div class="notification-meta">
                        <span class="meta-item">
                            <i class="bi bi-clock"></i>
                            {{ $n->getWaktuRelatif() }}
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-tag"></i>
                            {{ ucwords(str_replace('_', ' ', $n->tipe)) }}
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-calendar"></i>
                            {{ $n->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <div class="notification-actions">
                        <a href="{{ route('petugas.notifikasi.show', $n->id) }}" class="btn-action btn-view">
                            <i class="bi bi-eye"></i>
                            Lihat Detail
                        </a>

                        <form action="{{ route('petugas.notifikasi.destroy', $n->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">
                                <i class="bi bi-trash"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $notifikasi->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-bell-slash"></i>
            </div>
            <h4>Tidak Ada Notifikasi</h4>
            <p>Belum ada notifikasi yang tersedia saat ini</p>
            <a href="{{ route('petugas.dashboard') }}" class="btn-filter btn-primary">
                <i class="bi bi-house"></i>
                Kembali ke Dashboard
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Auto dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Get base URL and CSRF token
const getBaseUrl = () => {
    return document.querySelector('meta[name="base-url"]')?.content || '';
};

const getCsrfToken = () => {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
};

// Mark all notifications as read
function markAllAsRead() {
    if(!confirm('Tandai semua notifikasi sebagai sudah dibaca?')) return;
    
    const baseUrl = getBaseUrl();
    const csrfToken = getCsrfToken();
    
    console.log('🔵 Marking all notifications as read');
    console.log('🔵 URL:', `${baseUrl}/petugas/notifikasi/baca-semua`);
    
    const button = event.target.closest('button');
    if(button) {
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i> <span>Memproses...</span>';
    }
    
    fetch(`${baseUrl}/petugas/notifikasi/baca-semua`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('🔵 Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('✅ Response data:', data);
        if(data.success) {
            showAlert('success', 'Semua notifikasi berhasil ditandai sebagai sudah dibaca');
            setTimeout(() => location.reload(), 1000);
        } else {
            if(button) {
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-check-circle-fill"></i> <span>Tandai Semua Dibaca</span>';
            }
            showAlert('danger', data.message || 'Gagal menandai notifikasi');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        if(button) {
            button.disabled = false;
            button.innerHTML = '<i class="bi bi-check-circle-fill"></i> <span>Tandai Semua Dibaca</span>';
        }
        showAlert('danger', 'Terjadi kesalahan: ' + error.message);
    });
}

// Show alert message with animation
function showAlert(type, message) {
    document.querySelectorAll('.alert-custom-js').forEach(alert => alert.remove());
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-custom-js`;
    alertDiv.setAttribute('role', 'alert');
    alertDiv.style.cssText = `
        position: fixed;
        top: 100px;
        right: 30px;
        z-index: 9999;
        min-width: 350px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        animation: slideInRight 0.4s ease;
    `;
    alertDiv.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i>
        <span>${message}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    if (!document.querySelector('#alertAnimation')) {
        const style = document.createElement('style');
        style.id = 'alertAnimation';
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(400px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(400px); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.style.animation = 'slideOutRight 0.4s ease';
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getInstance(alertDiv);
            if (bsAlert) {
                bsAlert.close();
            } else {
                alertDiv.remove();
            }
        }, 400);
    }, 5000);
}

console.log('✅ Petugas notification page scripts loaded');
</script>
@endpush