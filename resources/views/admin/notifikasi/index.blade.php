@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@push('styles')
<style>
    .notification-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .notification-card.unread {
        border-left: 5px solid #5e72e4;
        background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
    }

    .notification-icon-box {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
        flex-shrink: 0;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-weight: 700;
        color: #2d3748;
        font-size: 1.1rem;
        margin-bottom: 8px;
    }

    .notification-text {
        color: #718096;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 0.85rem;
        color: #a0aec0;
    }

    .notification-meta i {
        margin-right: 5px;
    }

    .notification-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-unread {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(245, 87, 108, 0.3);
    }

    .badge-read {
        background: #e2e8f0;
        color: #718096;
    }

    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .stats-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 14px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .btn-action {
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 5rem;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        color: #4a5568;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #a0aec0;
    }

    /* ✅ TAMBAHAN: Gradient colors untuk icon */
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #a8caba 0%, #5d4e6d 100%); }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-action">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats & Actions -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-box">
                <div class="stats-number">{{ $unreadCount }}</div>
                <div class="stats-label">Notifikasi Belum Dibaca</div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="filter-card">
                <form method="GET" action="{{ route('admin.notifikasi.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-funnel"></i> Status
                        </label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-tag"></i> Tipe
                        </label>
                        <select name="tipe" class="form-select">
                            <option value="">Semua Tipe</option>
                            <option value="peminjaman_baru" {{ request('tipe') == 'peminjaman_baru' ? 'selected' : '' }}>Peminjaman Baru</option>
                            <option value="perpanjangan_baru" {{ request('tipe') == 'perpanjangan_baru' ? 'selected' : '' }}>Perpanjangan Baru</option>
                            <option value="reminder_deadline" {{ request('tipe') == 'reminder_deadline' ? 'selected' : '' }}>Reminder Deadline</option>
                            <option value="terlambat" {{ request('tipe') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="user_baru" {{ request('tipe') == 'user_baru' ? 'selected' : '' }}>User Baru</option>
                            <option value="buku_baru" {{ request('tipe') == 'buku_baru' ? 'selected' : '' }}>Buku Baru</option>
                            <option value="laporan_baru" {{ request('tipe') == 'laporan_baru' ? 'selected' : '' }}>Laporan Baru</option>
                            <option value="sistem" {{ request('tipe') == 'sistem' ? 'selected' : '' }}>Sistem</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-search"></i> Pencarian
                        </label>
                        <input type="text" name="search" class="form-control" placeholder="Cari notifikasi..." value="{{ request('search') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.notifikasi.index') }}" class="btn btn-secondary btn-action">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        @if($unreadCount > 0)
                        <button type="button" class="btn btn-success btn-action" onclick="markAllAsReadConfirm()">
                            <i class="bi bi-check-all"></i> Tandai Semua Dibaca
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    @if($notifikasi->count() > 0)
        @foreach($notifikasi as $n)
        <div class="notification-card {{ !$n->dibaca ? 'unread' : '' }}">
            <div class="card-body p-4">
                <div class="d-flex gap-3">
                    {{-- ✅ PERBAIKAN: Gunakan method dari model --}}
                    <div class="notification-icon-box bg-gradient-{{ $n->getBadgeColor() }}">
                        <i class="bi bi-{{ $n->getIcon() }}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="notification-title">{{ $n->judul }}</h5>
                                <span class="notification-badge {{ !$n->dibaca ? 'badge-unread' : 'badge-read' }}">
                                    {{ !$n->dibaca ? 'Belum Dibaca' : 'Sudah Dibaca' }}
                                </span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.notifikasi.show', $n->id) }}">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    </li>
                                    @if(!$n->dibaca)
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="markAsRead({{ $n->id }})">
                                            <i class="bi bi-check2"></i> Tandai Dibaca
                                        </a>
                                    </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.notifikasi.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <p class="notification-text">{{ Str::limit($n->isi, 200) }}</p>
                        <div class="notification-meta">
                            <span><i class="bi bi-clock"></i> {{ $n->getWaktuRelatif() }}</span>
                            <span><i class="bi bi-tag"></i> {{ ucwords(str_replace('_', ' ', $n->tipe)) }}</span>
                            @if($n->prioritas && $n->prioritas !== 'normal')
                            <span class="badge bg-{{ $n->getPrioritasColor() }}">
                                {{ ucfirst($n->prioritas) }}
                            </span>
                            @endif
                        </div>
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
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <i class="bi bi-bell-slash"></i>
                    <h4>Tidak Ada Notifikasi</h4>
                    <p>Belum ada notifikasi yang tersedia saat ini</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function markAsRead(id) {
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
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsReadConfirm() {
    if (confirm('Tandai semua notifikasi sebagai dibaca?')) {
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
        .catch(error => console.error('Error:', error));
    }
}
</script>
@endpush