@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@push('styles')
<style>
    /* ========== ENHANCED MODERN NOTIFICATION STYLES ========== */
    .page-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
        border-radius: 32px;
        padding: 48px;
        margin-bottom: 40px;
        color: white;
        box-shadow: 0 20px 60px rgba(99, 102, 241, 0.25);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .page-header h2 {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 12px;
        position: relative;
        letter-spacing: -0.5px;
    }

    .page-header p {
        font-size: 1.1rem;
        opacity: 0.95;
        margin: 0;
        position: relative;
        font-weight: 500;
    }

    /* Action Bar */
    .action-bar {
        background: white;
        border-radius: 24px;
        padding: 24px 32px;
        margin-bottom: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        border: 2px solid #f1f5f9;
    }

    .action-bar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .action-bar-title i {
        color: #6366f1;
        font-size: 1.3rem;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .stats-card {
        background: white;
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid #f1f5f9;
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        transform: scaleX(0);
        transition: transform 0.4s;
        transform-origin: left;
    }

    .stats-card:hover::before {
        transform: scaleX(1);
    }

    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(99, 102, 241, 0.15);
        border-color: #e0e7ff;
    }

    .stats-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .stats-number {
        font-size: 3rem;
        font-weight: 900;
        line-height: 1;
        margin-bottom: 8px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stats-label {
        font-size: 0.95rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Notification Card - Premium Design */
    .notification-card {
        background: white;
        border-radius: 28px;
        padding: 36px;
        margin-bottom: 24px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        border: 2px solid #f1f5f9;
        position: relative;
        overflow: hidden;
    }

    .notification-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #6366f1, #8b5cf6);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .notification-card:hover::before {
        opacity: 1;
    }

    .notification-card:hover {
        transform: translateX(4px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
        border-color: #e0e7ff;
    }

    .notification-card.unread {
        background: linear-gradient(135deg, #fefefe 0%, #f0f7ff 100%);
        border-color: #c7d2fe;
        box-shadow: 0 4px 24px rgba(99, 102, 241, 0.1);
    }

    .notification-card.unread::before {
        opacity: 1;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-title {
        font-weight: 800;
        color: #0f172a;
        font-size: 1.35rem;
        margin-bottom: 16px;
        line-height: 1.4;
        letter-spacing: -0.3px;
    }

    .notification-text {
        color: #475569;
        font-size: 1.05rem;
        line-height: 1.8;
        margin-bottom: 24px;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 600;
    }

    .notification-meta span {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .notification-meta i {
        color: #6366f1;
        font-size: 1.1rem;
    }

    /* Badges */
    .notification-badges {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .badge-unread {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .type-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 800;
        border: 2px solid #e2e8f0;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    /* Action Buttons */
    .btn-modern {
        padding: 14px 32px;
        border-radius: 16px;
        font-size: 1rem;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-primary.btn-modern {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35);
        color: white;
    }

    .btn-primary.btn-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 36px rgba(99, 102, 241, 0.45);
    }

    .btn-light.btn-modern {
        background: rgba(255, 255, 255, 0.95);
        color: #6366f1;
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    .btn-light.btn-modern:hover {
        background: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(255, 255, 255, 0.4);
    }

    .btn-outline-modern {
        padding: 12px 28px;
        border-radius: 16px;
        font-size: 0.95rem;
        font-weight: 700;
        border: 2px solid #e2e8f0;
        background: white;
        color: #475569;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-outline-modern:hover {
        border-color: #6366f1;
        background: #f0f7ff;
        color: #6366f1;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.15);
    }

    /* Dropdown Menu */
    .dropdown-toggle-modern {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #f1f5f9;
        background: white;
        color: #64748b;
        transition: all 0.3s;
        font-size: 1.2rem;
    }

    .dropdown-toggle-modern:hover {
        border-color: #6366f1;
        background: #f0f7ff;
        color: #6366f1;
        transform: rotate(90deg);
    }

    .dropdown-menu {
        border: none;
        border-radius: 20px;
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
        padding: 12px;
        min-width: 220px;
        margin-top: 10px;
    }

    .dropdown-item {
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.95rem;
        transition: all 0.2s;
        font-weight: 600;
        color: #475569;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dropdown-item:hover {
        background: #f0f7ff;
        color: #6366f1;
        transform: translateX(6px);
    }

    .dropdown-item i {
        font-size: 1.15rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 120px 20px;
    }

    .empty-state i {
        font-size: 7rem;
        color: #e2e8f0;
        margin-bottom: 32px;
        opacity: 0.5;
    }

    .empty-state h4 {
        color: #475569;
        font-weight: 800;
        margin-bottom: 16px;
        font-size: 1.75rem;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Gradient Backgrounds */
    .bg-gradient-primary { 
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); 
    }
    .bg-gradient-success { 
        background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
    }
    .bg-gradient-warning { 
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
    }
    .bg-gradient-info { 
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); 
    }

    /* Alerts */
    .alert {
        border-radius: 20px;
        border: none;
        padding: 18px 24px;
        font-weight: 600;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert i {
        font-size: 1.3rem;
    }

    /* Load More Button */
    .load-more-section {
        text-align: center;
        padding: 50px 0;
    }

    .btn-load-more {
        padding: 18px 48px;
        border-radius: 20px;
        font-size: 1.05rem;
        font-weight: 800;
        border: 2px solid #e2e8f0;
        background: white;
        color: #6366f1;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .btn-load-more:hover {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-color: #6366f1;
        color: white;
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(99, 102, 241, 0.4);
    }

    .btn-load-more i {
        font-size: 1.4rem;
        transition: transform 0.3s;
    }

    .btn-load-more:hover i {
        transform: translateY(4px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 36px 28px;
        }

        .page-header h2 {
            font-size: 2rem;
        }

        .notification-card {
            padding: 28px 24px;
        }

        .stats-number {
            font-size: 2.5rem;
        }

        .action-bar {
            padding: 20px 24px;
        }

        .btn-load-more {
            padding: 16px 36px;
            font-size: 0.95rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="bi bi-bell-fill me-3"></i>Notifikasi</h2>
                <p>Kelola dan pantau semua notifikasi sistem Anda</p>
            </div>
            <a href="{{ route('admin.notifikasi.index') }}" class="btn btn-light btn-modern">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
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

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stats-card">
            <div class="stats-icon bg-gradient-warning text-white">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div class="stats-number">{{ $unreadCount }}</div>
            <div class="stats-label">Belum Dibaca</div>
        </div>

        <div class="stats-card">
            <div class="stats-icon bg-gradient-primary text-white">
                <i class="bi bi-collection-fill"></i>
            </div>
            <div class="stats-number">{{ $notifikasi->total() }}</div>
            <div class="stats-label">Total Notifikasi</div>
        </div>
    </div>

    <!-- Action Bar -->
    @if($unreadCount > 0)
    <div class="action-bar">
        <div class="action-bar-title">
            <i class="bi bi-check-all"></i>
            <span>{{ $unreadCount }} notifikasi belum dibaca</span>
        </div>
        <button type="button" class="btn btn-primary btn-modern" onclick="markAllAsRead()">
            <i class="bi bi-check-circle-fill"></i>
            <span>Tandai Semua Dibaca</span>
        </button>
    </div>
    @endif

    <!-- Notifications List -->
    @forelse($notifikasi as $n)
    <div class="notification-card {{ !$n->dibaca ? 'unread' : '' }}" id="notif-{{ $n->id }}">
        <div class="d-flex justify-content-between align-items-start gap-4">
            <div class="notification-content">
                <h5 class="notification-title">{{ $n->judul }}</h5>
                
                <div class="notification-badges">
                    @if(!$n->dibaca)
                    <span class="badge-modern badge-unread" id="badge-{{ $n->id }}">
                        <i class="bi bi-circle-fill"></i>
                        Belum Dibaca
                    </span>
                    @endif
                    <span class="type-badge">
                        {{ ucwords(str_replace('_', ' ', $n->tipe)) }}
                    </span>
                    @if($n->prioritas && $n->prioritas !== 'normal')
                    <span class="badge bg-{{ $n->getPrioritasColor() }}">
                        {{ ucfirst($n->prioritas) }}
                    </span>
                    @endif
                </div>

                <p class="notification-text">{{ Str::limit($n->isi, 280) }}</p>

                <div class="notification-meta">
                    <span>
                        <i class="bi bi-clock-history"></i>
                        {{ $n->getWaktuRelatif() }}
                    </span>
                    <span>
                        <i class="bi bi-calendar3"></i>
                        {{ $n->created_at->format('d M Y, H:i') }}
                    </span>
                </div>
            </div>
            
            <div class="dropdown">
                <button class="dropdown-toggle-modern" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.notifikasi.show', $n->id) }}">
                            <i class="bi bi-eye-fill"></i>
                            <span>Lihat Detail</span>
                        </a>
                    </li>
                    @if(!$n->dibaca)
                    <li>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="markAsRead({{ $n->id }})">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Tandai Dibaca</span>
                        </a>
                    </li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('admin.notifikasi.destroy', $n->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Hapus notifikasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-trash3-fill"></i>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @empty
    <div class="notification-card">
        <div class="empty-state">
            <i class="bi bi-bell-slash"></i>
            <h4>Tidak Ada Notifikasi</h4>
            <p>Belum ada notifikasi yang tersedia saat ini</p>
        </div>
    </div>
    @endforelse

    <!-- Load More Button -->
    @if($notifikasi->hasMorePages())
    <div class="load-more-section">
        <a href="{{ $notifikasi->nextPageUrl() }}" class="btn btn-load-more">
            <span>Lihat Lebih Banyak</span>
            <i class="bi bi-arrow-down-circle-fill"></i>
        </a>
        <p class="text-muted mt-4 mb-0" style="font-weight: 600; font-size: 0.95rem;">
            Menampilkan {{ $notifikasi->firstItem() }} - {{ $notifikasi->lastItem() }} dari {{ $notifikasi->total() }} notifikasi
        </p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Auto hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Get base URL from meta tag
const getBaseUrl = () => {
    return document.querySelector('meta[name="base-url"]')?.content || '';
};

// Get CSRF token
const getCsrfToken = () => {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
};

// Mark single notification as read
function markAsRead(notifId) {
    if(!confirm('Tandai notifikasi ini sebagai sudah dibaca?')) return;
    
    const baseUrl = getBaseUrl();
    const csrfToken = getCsrfToken();
    
    console.log('🔵 Marking notification as read:', notifId);
    console.log('🔵 URL:', `${baseUrl}/admin/notifikasi/${notifId}/baca`);
    console.log('🔵 CSRF Token:', csrfToken);
    
    fetch(`${baseUrl}/admin/notifikasi/${notifId}/baca`, {
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
            // Remove unread styling
            const card = document.getElementById(`notif-${notifId}`);
            const badge = document.getElementById(`badge-${notifId}`);
            
            if(card) {
                card.classList.remove('unread');
                card.style.transition = 'all 0.5s ease';
            }
            if(badge) {
                badge.style.transition = 'opacity 0.3s ease';
                badge.style.opacity = '0';
                setTimeout(() => badge.remove(), 300);
            }
            
            showAlert('success', 'Notifikasi berhasil ditandai sebagai sudah dibaca');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('danger', data.message || 'Gagal menandai notifikasi');
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        showAlert('danger', 'Terjadi kesalahan: ' + error.message);
    });
}

// Mark all notifications as read
function markAllAsRead() {
    if(!confirm('Tandai semua notifikasi sebagai sudah dibaca?')) return;
    
    const baseUrl = getBaseUrl();
    const csrfToken = getCsrfToken();
    
    console.log('🔵 Marking all notifications as read');
    console.log('🔵 URL:', `${baseUrl}/admin/notifikasi/baca-semua`);
    console.log('🔵 CSRF Token:', csrfToken);
    
    // Disable button to prevent double click
    const button = event.target.closest('button');
    if(button) {
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i> <span>Memproses...</span>';
    }
    
    fetch(`${baseUrl}/admin/notifikasi/baca-semua`, {
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

// Show alert message
function showAlert(type, message) {
    // Remove existing alerts first
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
    
    // Add animation keyframes if not exists
    if (!document.querySelector('#alertAnimation')) {
        const style = document.createElement('style');
        style.id = 'alertAnimation';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(alertDiv);
    
    // Auto dismiss after 5 seconds with animation
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

// Smooth scroll to top when reload
window.addEventListener('beforeunload', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

console.log('✅ Notification page scripts loaded');
</script>
@endpush