@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@push('styles')
<style>
    /* ========== MODERN NOTIFICATION STYLES ========== */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
    }

    .page-header h2 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .page-header p {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0;
    }

    /* Stats Card */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border-color: #667eea;
    }

    .stats-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 12px;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stats-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 600;
    }

    /* Notification Card */
    .notification-card {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
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
        background: #e2e8f0;
        transition: all 0.3s;
    }

    .notification-card:hover {
        transform: translateX(4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    .notification-card:hover::before {
        width: 8px;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    }

    .notification-card.unread {
        background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
        border-color: #667eea;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.15);
    }

    .notification-card.unread::before {
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    }

    .notification-icon-box {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 16px;
        margin-bottom: 12px;
    }

    .notification-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 1.125rem;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .notification-text {
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 16px;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 600;
    }

    .notification-meta i {
        margin-right: 6px;
        color: #667eea;
        font-size: 1rem;
    }

    .notification-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-unread {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.4);
        animation: pulse-glow 2s infinite;
    }

    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 3px 10px rgba(102, 126, 234, 0.4); }
        50% { box-shadow: 0 5px 18px rgba(102, 126, 234, 0.6); }
    }

    .type-badge {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(102, 126, 234, 0.1) 100%);
        color: #4c5fd5;
        padding: 5px 14px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }

    /* Action Buttons */
    .btn-action {
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
    }

    .btn-primary.btn-action {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-primary.btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-success.btn-action {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-success.btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .dropdown-toggle {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 16px;
        background: white;
        transition: all 0.2s;
        color: #475569;
        font-weight: 600;
    }

    .dropdown-toggle:hover {
        border-color: #667eea;
        background: #f8faff;
        color: #667eea;
    }

    .dropdown-menu {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        padding: 8px;
        min-width: 220px;
    }

    .dropdown-item {
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 0.9rem;
        transition: all 0.2s;
        font-weight: 600;
        color: #475569;
    }

    .dropdown-item:hover {
        background: #f8faff;
        color: #667eea;
        transform: translateX(4px);
    }

    .dropdown-item i {
        width: 24px;
        font-size: 1.1rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 5rem;
        color: #cbd5e1;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h4 {
        color: #475569;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 1rem;
    }

    /* Gradient Backgrounds */
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #64748b 0%, #475569 100%); }

    /* Pagination */
    .pagination {
        gap: 8px;
    }

    .pagination .page-link {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 16px;
        color: #475569;
        font-weight: 600;
        transition: all 0.2s;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #8253b1 100%);
        border-color: #667eea;
        color: white;
    }

    /* Loading State */
    .btn-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-loading::after {
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

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header h2 {
            font-size: 1.5rem;
        }

        .stats-number {
            font-size: 2rem;
        }

        .notification-card {
            padding: 16px;
        }

        .notification-icon-box {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }

        .notification-meta {
            font-size: 0.8rem;
        }

        .notification-header {
            flex-direction: column;
            gap: 12px;
        }

        .dropdown {
            align-self: flex-start;
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
                <h2><i class="bi bi-bell-fill me-2"></i>Notifikasi</h2>
                <p>Kelola dan pantau semua notifikasi sistem</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-action">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stats-card">
            <div class="stats-icon bg-gradient-warning text-white">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div class="stats-number text-warning">{{ $unreadCount }}</div>
            <div class="stats-label">Belum Dibaca</div>
        </div>

        <div class="stats-card">
            <div class="stats-icon bg-gradient-primary text-white">
                <i class="bi bi-collection-fill"></i>
            </div>
            <div class="stats-number text-primary">{{ $notifikasi->total() }}</div>
            <div class="stats-label">Total Notifikasi</div>
        </div>
    </div>

    <!-- Mark All as Read Button -->
    @if($unreadCount > 0)
    <div class="mb-4">
        <button type="button" class="btn btn-success btn-action" onclick="markAllAsReadConfirm()">
            <i class="bi bi-check-all me-2"></i>Tandai Semua Dibaca ({{ $unreadCount }})
        </button>
    </div>
    @endif

    <!-- Notifications List -->
    @forelse($notifikasi as $n)
    <div class="notification-card {{ !$n->dibaca ? 'unread' : '' }}" id="notif-{{ $n->id }}">
        <div class="d-flex gap-4">
            <div class="notification-content">
                <div class="notification-header">
                    <div class="flex-grow-1">
                        <h5 class="notification-title">{{ $n->judul }}</h5>
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                            @if(!$n->dibaca)
                            <span class="notification-badge badge-unread" id="badge-{{ $n->id }}">
                                <i class="bi bi-circle-fill" id="icon-{{ $n->id }}"></i>
                                <span id="text-{{ $n->id }}">Belum Dibaca</span>
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
                    </div>
                    
                    <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.notifikasi.show', $n->id) }}">
                                    <i class="bi bi-eye-fill"></i> Lihat Detail
                                </a>
                            </li>
                            @if(!$n->dibaca)
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="markAsReadInline({{ $n->id }})">
                                    <i class="bi bi-check-circle-fill"></i> Tandai Dibaca
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
                                        <i class="bi bi-trash3-fill"></i> Hapus
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <p class="notification-text">{{ Str::limit($n->isi, 250) }}</p>

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
        </div>
    </div>
    @empty
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-bell-slash"></i>
                <h4>Tidak Ada Notifikasi</h4>
                <p>Belum ada notifikasi yang tersedia saat ini</p>
            </div>
        </div>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($notifikasi->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $notifikasi->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Mark single notification as read WITHOUT reload
function markAsReadInline(id) {
    fetch(`/admin/notifikasi/${id}/baca`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update card appearance
            const card = document.getElementById(`notif-${id}`);
            const badge = document.getElementById(`badge-${id}`);
            
            // Remove unread class
            card.classList.remove('unread');
            
            // Remove badge completely
            if (badge) {
                badge.remove();
            }
            
            // Remove menu item "Tandai Dibaca"
            const menuItem = event.target.closest('li');
            if (menuItem) {
                menuItem.remove();
            }
            
            // Update counter stats
            updateStatsCounter();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Mark all as read WITH reload (because of pagination)
function markAllAsReadConfirm() {
    if (confirm('Tandai semua notifikasi sebagai dibaca?')) {
        const btn = event.target;
        btn.classList.add('btn-loading');
        btn.disabled = true;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
        
        fetch('/admin/notifikasi/baca-semua', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.classList.remove('btn-loading');
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    }
}

// Update stats counter
function updateStatsCounter() {
    const unreadCards = document.querySelectorAll('.notification-card.unread').length;
    const totalCards = document.querySelectorAll('.notification-card').length;
    
    // Update stats numbers
    document.querySelectorAll('.stats-number')[0].textContent = unreadCards;
    document.querySelectorAll('.stats-number')[1].textContent = totalCards;
    
    // Hide "Tandai Semua Dibaca" button if no unread
    if (unreadCards === 0) {
        const markAllBtn = document.querySelector('button[onclick="markAllAsReadConfirm()"]');
        if (markAllBtn && markAllBtn.parentElement) {
            markAllBtn.parentElement.remove();
        }
    }
}

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
</script>
@endpush