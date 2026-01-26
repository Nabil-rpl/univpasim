@extends('layouts.mahasiswa')

@section('title', 'Notifikasi Saya')
@section('page-title', 'Notifikasi')

@push('styles')
    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0a58ca;
            --secondary: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
        }

        body {
            background-color: #f5f6fa;
            min-height: 100vh;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 25px;
            color: white;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .page-header p {
            opacity: 0.95;
            font-size: 1rem;
            margin: 0;
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border-left: 3px solid #0d6efd;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-card-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #0d6efd;
        }

        .stat-card-label {
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        .filter-header i {
            font-size: 1.2rem;
            color: #0d6efd;
        }

        .filter-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
            color: #343a40;
        }

        .filter-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-input {
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s;
            background: white;
        }

        .filter-input:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-filter {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: #0d6efd;
            color: white;
        }

        .btn-primary:hover {
            background: #0a58ca;
        }

        .btn-secondary {
            background: #f5f5f5;
            color: #666;
            border: 1px solid #e0e0e0;
        }

        .btn-secondary:hover {
            background: #e8e8e8;
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        /* Notification Card */
        .notification-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s;
            border-left: 4px solid transparent;
            position: relative;
        }

        .notification-card.unread {
            border-left-color: #0d6efd;
            background: #f8fbff;
        }

        .notification-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .notification-header {
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }

        .notification-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .notification-title {
            font-size: 1rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 5px;
        }

        .notification-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 8px;
        }

        .badge-unread {
            background: #0d6efd;
            color: white;
        }

        .badge-read {
            background: #e9ecef;
            color: #6c757d;
        }

        .notification-text {
            color: #333;
            line-height: 1.6;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }

        .notification-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            font-size: 0.8rem;
            color: #333;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            background: white;
            padding: 5px 12px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }

        .meta-item i {
            color: #0d6efd;
            font-size: 0.85rem;
        }

        .notification-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f0f0f0;
        }

        .btn-action {
            padding: 6px 14px;
            border-radius: 8px;
            border: none;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }

        .btn-view {
            background: #0d6efd;
            color: white;
        }

        .btn-view:hover {
            background: #0a58ca;
            color: white;
        }

        .btn-delete {
            background: #f5f5f5;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }

        /* Priority Badge */
        .priority-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 5px;
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
            background: #d1ecf1;
            color: #0c5460;
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
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .empty-state-icon {
            font-size: 5rem;
            color: #cbd5e0;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .empty-state h4 {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #6c757d;
            margin-bottom: 25px;
        }

        /* Gradient backgrounds for icons */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e55353 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #3ab0c3 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ffcd39 100%);
        }

        .bg-gradient-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #8a9199 100%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .page-header-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
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
                width: 45px;
                height: 45px;
                font-size: 1.2rem;
            }

            .notification-top {
                flex-direction: column;
                gap: 8px;
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
            <h1>
                <div class="page-header-icon">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <span>Notifikasi Saya</span>
            </h1>
            <p>Semua notifikasi dan update terbaru untuk Anda</p>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
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
                    <div class="stat-card-icon bg-gradient-primary">
                        <i class="bi bi-bell-fill"></i>
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
                    <div class="stat-card-value">{{ $todayCount ?? 0 }}</div>
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
                    <select name="tipe" class="filter-input">
                        <option value="">Semua Tipe</option>
                        <option value="peminjaman_disetujui"
                            {{ request('tipe') == 'peminjaman_disetujui' ? 'selected' : '' }}>Peminjaman Disetujui</option>
                        <option value="peminjaman_ditolak" {{ request('tipe') == 'peminjaman_ditolak' ? 'selected' : '' }}>
                            Peminjaman Ditolak</option>
                        <option value="perpanjangan_disetujui"
                            {{ request('tipe') == 'perpanjangan_disetujui' ? 'selected' : '' }}>Perpanjangan Disetujui
                        </option>
                        <option value="perpanjangan_ditolak"
                            {{ request('tipe') == 'perpanjangan_ditolak' ? 'selected' : '' }}>Perpanjangan Ditolak</option>
                        <option value="reminder_deadline" {{ request('tipe') == 'reminder_deadline' ? 'selected' : '' }}>
                            Reminder Deadline</option>
                        <option value="terlambat" {{ request('tipe') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="pengembalian_sukses"
                            {{ request('tipe') == 'pengembalian_sukses' ? 'selected' : '' }}>Pengembalian Sukses</option>
                        <option value="buku_tersedia" {{ request('tipe') == 'buku_tersedia' ? 'selected' : '' }}>Buku
                            Tersedia</option>
                        <option value="denda_belum_dibayar"
                            {{ request('tipe') == 'denda_belum_dibayar' ? 'selected' : '' }}>Denda Belum Dibayar</option>
                        <option value="sistem" {{ request('tipe') == 'sistem' ? 'selected' : '' }}>Sistem</option>
                    </select>

                    <input type="text" name="search" class="filter-input" placeholder="Cari notifikasi..."
                        value="{{ request('search') }}">
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
                </div>
            </form>
        </div>

        <!-- Notifications List -->
        @if ($notifikasi->count() > 0)
            @foreach ($notifikasi as $n)
                <div class="notification-card {{ !$n->dibaca ? 'unread' : '' }}">
                    <div class="notification-header">
                        <div class="notification-icon bg-gradient-{{ $n->getBadgeColor() }}">
                            <i class="bi bi-{{ $n->getIcon() }}"></i>
                        </div>

                        <div class="notification-content">
                            <div class="notification-top">
                                <div>
                                    <h5 class="notification-title">{{ $n->judul }}</h5>
                                    @if (!$n->dibaca)
                                        <span class="notification-badge badge-unread">BARU</span>
                                    @else
                                        <span class="notification-badge badge-read">DIBACA</span>
                                    @endif
                                    @if ($n->prioritas && $n->prioritas !== 'normal')
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
                                <a href="{{ route('mahasiswa.notifikasi.show', $n->id) }}" class="btn-action btn-view">
                                    <i class="bi bi-eye"></i>
                                    Lihat Detail
                                </a>

                                <form action="{{ route('mahasiswa.notifikasi.destroy', $n->id) }}" method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
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
