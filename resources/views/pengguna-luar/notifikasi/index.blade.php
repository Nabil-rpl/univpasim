@extends('layouts.pengguna-luar')

@section('page-title', 'Notifikasi')

@push('styles')
<style>
    .notification-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border-left: 4px solid #e2e8f0;
        position: relative;
    }

    .notification-card.unread {
        background: linear-gradient(to right, #eff6ff, #ffffff);
        border-left-color: #3B82F6;
    }

    .notification-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .notification-card.highlighted {
        animation: highlight 2s ease-in-out;
    }

    @keyframes highlight {
        0%, 100% { background: white; }
        50% { background: #fef3c7; }
    }

    .notif-header {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 12px;
    }

    .notif-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 22px;
    }

    .notif-icon.terlambat {
        background: linear-gradient(135deg, #fee2e2, #fca5a5);
        color: #dc2626;
    }

    .notif-icon.reminder_deadline {
        background: linear-gradient(135deg, #fef3c7, #fcd34d);
        color: #f59e0b;
    }

    .notif-icon.denda_belum_dibayar {
        background: linear-gradient(135deg, #fce7f3, #f9a8d4);
        color: #ec4899;
    }

    .notif-icon.peminjaman_disetujui {
        background: linear-gradient(135deg, #dcfce7, #86efac);
        color: #16a34a;
    }

    .notif-icon.peminjaman_ditolak {
        background: linear-gradient(135deg, #fee2e2, #fca5a5);
        color: #dc2626;
    }

    .notif-icon.default {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #6366f1;
    }

    .notif-content {
        flex: 1;
    }

    .notif-title {
        font-weight: 600;
        font-size: 16px;
        color: #1e293b;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .unread-dot {
        width: 8px;
        height: 8px;
        background: #3B82F6;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .notif-text {
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 8px;
    }

    .notif-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 13px;
        color: #94a3b8;
    }

    .notif-meta i {
        margin-right: 4px;
    }

    .notif-actions {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        gap: 8px;
    }

    .notif-actions .btn {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 8px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border-left: 4px solid;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-card.danger {
        border-left-color: #dc2626;
    }

    .stat-card.warning {
        border-left-color: #f59e0b;
    }

    .stat-card.info {
        border-left-color: #ec4899;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
    }

    .stat-card.danger .stat-icon {
        background: linear-gradient(135deg, #fee2e2, #fca5a5);
        color: #dc2626;
    }

    .stat-card.warning .stat-icon {
        background: linear-gradient(135deg, #fef3c7, #fcd34d);
        color: #f59e0b;
    }

    .stat-card.info .stat-icon {
        background: linear-gradient(135deg, #fce7f3, #f9a8d4);
        color: #ec4899;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 13px;
        color: #64748b;
    }

    .filter-bar {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 20px;
    }

    .filter-bar .form-select,
    .filter-bar .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 10px 16px;
    }

    .filter-bar .form-select:focus,
    .filter-bar .form-control:focus {
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .empty-state i {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        color: #64748b;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 14px;
    }

    .pagination {
        justify-content: center;
        margin-top: 30px;
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 4px;
        border: 1px solid #e2e8f0;
        color: #3B82F6;
    }

    .pagination .page-link:hover {
        background: #eff6ff;
        border-color: #3B82F6;
    }

    .pagination .page-item.active .page-link {
        background: #3B82F6;
        border-color: #3B82F6;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="stats-grid">
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="stat-value">{{ $stats['terlambat'] }}</div>
            <div class="stat-label">Keterlambatan</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="bi bi-clock-fill"></i>
            </div>
            <div class="stat-value">{{ $stats['reminder_deadline'] }}</div>
            <div class="stat-label">Reminder Deadline</div>
        </div>

        <div class="stat-card info">
            <div class="stat-icon">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div class="stat-value">{{ $stats['denda_belum_dibayar'] }}</div>
            <div class="stat-label">Denda Belum Dibayar</div>
        </div>

        <div class="stat-card" style="border-left-color: #3B82F6;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #dbeafe, #93c5fd); color: #3B82F6;">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div class="stat-value">{{ $unreadCount }}</div>
            <div class="stat-label">Belum Dibaca</div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('pengguna-luar.notifikasi.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted">Tipe</label>
                    <select name="tipe" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="terlambat" {{ request('tipe') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="reminder_deadline" {{ request('tipe') == 'reminder_deadline' ? 'selected' : '' }}>Reminder Deadline</option>
                        <option value="denda_belum_dibayar" {{ request('tipe') == 'denda_belum_dibayar' ? 'selected' : '' }}>Denda Belum Dibayar</option>
                        <option value="peminjaman_disetujui" {{ request('tipe') == 'peminjaman_disetujui' ? 'selected' : '' }}>Peminjaman Disetujui</option>
                        <option value="peminjaman_ditolak" {{ request('tipe') == 'peminjaman_ditolak' ? 'selected' : '' }}>Peminjaman Ditolak</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small text-muted">Pencarian</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari notifikasi..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </div>

            @if(request()->hasAny(['status', 'tipe', 'search']))
            <div class="mt-3">
                <a href="{{ route('pengguna-luar.notifikasi.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i> Reset Filter
                </a>
            </div>
            @endif
        </form>
    </div>

    {{-- Action Bar --}}
    @if($unreadCount > 0)
    <div class="mb-3">
        <button type="button" class="btn btn-outline-primary" id="markAllReadBtn">
            <i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca ({{ $unreadCount }})
        </button>
    </div>
    @endif

    {{-- Notifications List --}}
    @forelse($notifikasi as $notif)
    <div class="notification-card {{ !$notif->dibaca ? 'unread' : '' }} {{ request('highlight') == $notif->id ? 'highlighted' : '' }}" 
         id="notif-{{ $notif->id }}">
        <div class="notif-header">
            <div class="notif-icon {{ $notif->tipe ?: 'default' }}">
                <i class="bi bi-{{ $notif->getIcon() }}"></i>
            </div>
            <div class="notif-content">
                <div class="notif-title">
                    @if(!$notif->dibaca)
                    <span class="unread-dot"></span>
                    @endif
                    {{ $notif->judul }}
                </div>
                <div class="notif-text">{{ $notif->isi }}</div>
                <div class="notif-meta">
                    <span>
                        <i class="bi bi-clock"></i>
                        {{ $notif->getWaktuRelatif() }}
                    </span>
                    <span>
                        <i class="bi bi-tag"></i>
                        {{ ucfirst(str_replace('_', ' ', $notif->tipe)) }}
                    </span>
                    @if($notif->dibaca)
                    <span class="text-success">
                        <i class="bi bi-check-circle"></i>
                        Dibaca
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="notif-actions">
            @if(!$notif->dibaca)
            <button type="button" class="btn btn-sm btn-outline-primary mark-read-btn" data-id="{{ $notif->id }}">
                <i class="bi bi-check"></i> Tandai Dibaca
            </button>
            @endif
            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $notif->id }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="bi bi-bell-slash"></i>
        <h5>Tidak Ada Notifikasi</h5>
        <p>Anda belum memiliki notifikasi{{ request()->hasAny(['status', 'tipe', 'search']) ? ' yang sesuai dengan filter' : '' }}</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($notifikasi->hasPages())
    <div class="mt-4">
        {{ $notifikasi->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Mark as read
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notifId = this.getAttribute('data-id');
            markAsRead(notifId);
        });
    });

    // Mark all as read
    document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
        if (confirm('Tandai semua notifikasi sebagai dibaca?')) {
            fetch('{{ route("pengguna-luar.notifikasi.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

    // Delete notification
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notifId = this.getAttribute('data-id');
            if (confirm('Hapus notifikasi ini?')) {
                fetch(`/pengguna-luar/notifikasi/${notifId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`notif-${notifId}`).remove();
                        
                        // Check if no more notifications
                        if (document.querySelectorAll('.notification-card').length === 0) {
                            location.reload();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });

    function markAsRead(notifId) {
        fetch(`/pengguna-luar/notifikasi/${notifId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById(`notif-${notifId}`);
                card.classList.remove('unread');
                card.querySelector('.unread-dot')?.remove();
                card.querySelector('.mark-read-btn')?.remove();
                
                // Update unread count
                const badge = document.getElementById('notificationBadge');
                const currentCount = parseInt(badge.textContent) || 0;
                if (currentCount > 1) {
                    badge.textContent = currentCount - 1;
                } else {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Auto scroll to highlighted notification
    @if(request('highlight'))
    document.addEventListener('DOMContentLoaded', function() {
        const highlighted = document.querySelector('.highlighted');
        if (highlighted) {
            highlighted.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
    @endif
</script>
@endpush