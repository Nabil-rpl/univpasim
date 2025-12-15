@extends('layouts.mahasiswa')

@section('title', 'Notifikasi Saya')
@section('page-title', 'Notifikasi')

@push('styles')
<style>
    :root {
        --primary: #667eea;
        --primary-dark: #5568d3;
        --secondary: #764ba2;
        --success: #48bb78;
        --danger: #f56565;
        --warning: #ed8936;
        --info: #4299e1;
        --light: #f7fafc;
        --dark: #2d3748;
        --gray: #718096;
    }

    body {
        background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);
        min-height: 100vh;
    }

    /* Header Section */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 24px;
        padding: 40px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.1); opacity: 0.5; }
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        backdrop-filter: blur(10px);
    }

    .page-header p {
        opacity: 0.95;
        font-size: 1.1rem;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(102, 126, 234, 0.1);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.2);
    }

    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card-value {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-card-label {
        color: var(--gray);
        font-size: 0.95rem;
        font-weight: 600;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .filter-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--light);
    }

    .filter-header i {
        font-size: 1.5rem;
        color: var(--primary);
    }

    .filter-header h5 {
        margin: 0;
        font-weight: 700;
        color: var(--dark);
    }

    .filter-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .filter-input {
        padding: 12px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s;
        background: var(--light);
    }

    .filter-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        background: white;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
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
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: white;
        color: var(--gray);
        border: 2px solid #e2e8f0;
    }

    .btn-secondary:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #38a169;
        transform: translateY(-2px);
    }

    /* Notification Card */
    .notification-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        border: 2px solid transparent;
        position: relative;
    }

    .notification-card.unread {
        border-left: 5px solid var(--primary);
        background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
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

    .notification-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .notification-content {
        flex: 1;
    }

    .notification-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .notification-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark);
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

    .badge-read {
        background: #e2e8f0;
        color: var(--gray);
    }

    .notification-text {
        color: var(--gray);
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
        color: var(--gray);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .meta-item i {
        color: var(--primary);
    }

    .notification-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f7fafc;
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
    }

    .btn-view {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-mark {
        background: white;
        color: var(--success);
        border: 2px solid var(--success);
    }

    .btn-mark:hover {
        background: var(--success);
        color: white;
    }

    .btn-delete {
        background: white;
        color: var(--danger);
        border: 2px solid var(--danger);
    }

    .btn-delete:hover {
        background: var(--danger);
        color: white;
    }

    /* Priority Badge */
    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .priority-mendesak {
        background: #fed7d7;
        color: #c53030;
    }

    .priority-tinggi {
        background: #feebc8;
        color: #c05621;
    }

    .priority-normal {
        background: #bee3f8;
        color: #2c5282;
    }

    .priority-rendah {
        background: #e2e8f0;
        color: #718096;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-state-icon {
        font-size: 5rem;
        color: #cbd5e0;
        margin-bottom: 20px;
        animation: float 3s ease-in-out infinite;
    }

    .empty-state h4 {
        color: var(--dark);
        font-weight: 700;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: var(--gray);
        margin-bottom: 25px;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 30px;
    }

    .page-link {
        padding: 10px 16px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        background: white;
        color: var(--gray);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .page-link:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-2px);
    }

    .page-link.active {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-color: var(--primary);
    }

    /* Gradient backgrounds for icons */
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #a8caba 0%, #5d4e6d 100%); }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .page-header-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }

        .filter-group {
            grid-template-columns: 1fr;
        }

        .notification-header {
            flex-direction: column;
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .notification-top {
            flex-direction: column;
            gap: 10px;
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
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1>
                <div class="page-header-icon">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <span>Notifikasi Saya</span>
            </h1>
            <p>Semua notifikasi dan update terbaru untuk Anda</p>
        </div>
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

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon bg-gradient-warning">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
                <div class="stat-card-value">{{ $unreadCount }}</div>
            </div>
            <div class="stat-card-label">Belum Dibaca</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon bg-gradient-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-card-value">{{ $notifikasi->total() }}</div>
            </div>
            <div class="stat-card-label">Total Notifikasi</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon bg-gradient-info">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-card-value">{{ $notifikasi->where('created_at', '>=', now()->subDay())->count() }}</div>
            </div>
            <div class="stat-card-label">Hari Ini</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-header">
            <i class="bi bi-funnel-fill"></i>
            <h5>Filter Notifikasi</h5>
        </div>

        <form method="GET" action="{{ route('mahasiswa.notifikasi.index') }}">
            <div class="filter-group">
                <select name="status" class="filter-input">
                    <option value="">Semua Status</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>

                <select name="tipe" class="filter-input">
                    <option value="">Semua Tipe</option>
                    <option value="peminjaman_disetujui" {{ request('tipe') == 'peminjaman_disetujui' ? 'selected' : '' }}>Peminjaman Disetujui</option>
                    <option value="peminjaman_ditolak" {{ request('tipe') == 'peminjaman_ditolak' ? 'selected' : '' }}>Peminjaman Ditolak</option>
                    <option value="perpanjangan_disetujui" {{ request('tipe') == 'perpanjangan_disetujui' ? 'selected' : '' }}>Perpanjangan Disetujui</option>
                    <option value="perpanjangan_ditolak" {{ request('tipe') == 'perpanjangan_ditolak' ? 'selected' : '' }}>Perpanjangan Ditolak</option>
                    <option value="reminder_deadline" {{ request('tipe') == 'reminder_deadline' ? 'selected' : '' }}>Reminder Deadline</option>
                    <option value="terlambat" {{ request('tipe') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="pengembalian_sukses" {{ request('tipe') == 'pengembalian_sukses' ? 'selected' : '' }}>Pengembalian Sukses</option>
                    <option value="buku_tersedia" {{ request('tipe') == 'buku_tersedia' ? 'selected' : '' }}>Buku Tersedia</option>
                    <option value="denda_belum_dibayar" {{ request('tipe') == 'denda_belum_dibayar' ? 'selected' : '' }}>Denda Belum Dibayar</option>
                    <option value="sistem" {{ request('tipe') == 'sistem' ? 'selected' : '' }}>Sistem</option>
                </select>

                <input type="text" name="search" class="filter-input" placeholder="Cari notifikasi..." value="{{ request('search') }}">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-primary">
                    <i class="bi bi-search"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route('mahasiswa.notifikasi.index') }}" class="btn-filter btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset
                </a>
                @if($unreadCount > 0)
                <button type="button" class="btn-filter btn-success" onclick="markAllAsReadConfirm()">
                    <i class="bi bi-check-all"></i>
                    Tandai Semua Dibaca
                </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Notifications List -->
    @if($notifikasi->count() > 0)
        @foreach($notifikasi as $n)
        <div class="notification-card {{ !$n->dibaca ? 'unread' : '' }}">
            <div class="notification-header">
                <div class="notification-icon bg-gradient-{{ $n->getBadgeColor() }}">
                    <i class="bi bi-{{ $n->getIcon() }}"></i>
                </div>

                <div class="notification-content">
                    <div class="notification-top">
                        <div>
                            <h5 class="notification-title">{{ $n->judul }}</h5>
                            <span class="notification-badge {{ !$n->dibaca ? 'badge-unread' : 'badge-read' }}">
                                {{ !$n->dibaca ? '● Belum Dibaca' : '✓ Sudah Dibaca' }}
                            </span>
                            @if($n->prioritas && $n->prioritas !== 'normal')
                            <span class="priority-badge priority-{{ $n->prioritas }}">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                {{ ucfirst($n->prioritas) }}
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
                    
                        
                        @if(!$n->dibaca)
                        <button type="button" class="btn-action btn-mark" onclick="markAsRead({{ $n->id }})">
                            <i class="bi bi-check-circle"></i>
                            Tandai Dibaca
                        </button>
                        @endif

                        <form action="{{ route('mahasiswa.notifikasi.destroy', $n->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
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
            <a href="{{ route('mahasiswa.dashboard') }}" class="btn-filter btn-primary">
                <i class="bi bi-house"></i>
                Kembali ke Dashboard
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Mark single notification as read
function markAsRead(id) {
    fetch(`/mahasiswa/notifikasi/${id}/baca`, {
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
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}

// Mark all notifications as read
function markAllAsReadConfirm() {
    if (confirm('Tandai semua notifikasi sebagai dibaca?')) {
        fetch('/mahasiswa/notifikasi/baca-semua', {
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
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    }
}

// Auto dismiss alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
@endpush