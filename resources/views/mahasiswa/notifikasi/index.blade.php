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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
        }

        .page-header-icon {
            width: 55px;
            height: 55px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            backdrop-filter: blur(10px);
        }

        .page-header p {
            opacity: 0.95;
            font-size: 1.05rem;
            margin: 0;
            position: relative;
            font-weight: 500;
        }

        /* Action Bar */
        .action-bar {
            background: white;
            border-radius: 20px;
            padding: 24px 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
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
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-bar-title i {
            color: #667eea;
            font-size: 1.2rem;
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
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 2px solid #f1f5f9;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s;
            transform-origin: left;
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
            border-color: #e0e7ff;
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
            font-size: 1.4rem;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-card-value {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card-label {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 2px solid #f1f5f9;
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .filter-header i {
            font-size: 1.25rem;
            color: #667eea;
        }

        .filter-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.05rem;
            color: #0f172a;
        }

        .filter-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-input {
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: white;
            font-weight: 500;
        }

        .filter-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-filter {
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #475569;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            border-color: #667eea;
            background: #f8fbff;
            color: #667eea;
            transform: translateY(-2px);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }

        /* Notification Card */
        .notification-card {
            background: white;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            transition: all 0.3s;
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
            background: linear-gradient(180deg, #667eea, #764ba2);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .notification-card:hover::before {
            opacity: 1;
        }

        .notification-card.unread {
            border-color: #c7d2fe;
            background: linear-gradient(135deg, #fefefe 0%, #f0f7ff 100%);
        }

        .notification-card.unread::before {
            opacity: 1;
        }

        .notification-card:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            border-color: #e0e7ff;
        }

        .notification-header {
            display: flex;
            gap: 18px;
            align-items: flex-start;
        }

        .notification-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            letter-spacing: -0.2px;
        }

        .notification-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-left: 10px;
            letter-spacing: 0.5px;
        }

        .badge-unread {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .badge-read {
            background: #e2e8f0;
            color: #64748b;
        }

        .notification-text {
            color: #475569;
            line-height: 1.7;
            margin-bottom: 16px;
            font-size: 0.95rem;
        }

        .notification-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .meta-item i {
            color: #667eea;
            font-size: 0.9rem;
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px solid #f1f5f9;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 12px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-delete {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-2px);
        }

        /* Priority Badge */
        .priority-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 800;
            margin-left: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .priority-mendesak {
            background: #fee2e2;
            color: #dc2626;
        }

        .priority-tinggi {
            background: #fed7aa;
            color: #ea580c;
        }

        .priority-normal {
            background: #dbeafe;
            color: #2563eb;
        }

        .priority-rendah {
            background: #e2e8f0;
            color: #64748b;
        }

        /* Load More Section */
        .load-more-section {
            text-align: center;
            padding: 50px 0;
        }

        .btn-load-more {
            padding: 18px 48px;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 800;
            border: 2px solid #e2e8f0;
            background: white;
            color: #667eea;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            letter-spacing: 0.3px;
            text-transform: uppercase;
            text-decoration: none;
        }

        .btn-load-more:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(102, 126, 234, 0.4);
        }

        .btn-load-more i {
            font-size: 1.3rem;
            transition: transform 0.3s;
        }

        .btn-load-more:hover i {
            transform: translateY(3px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 100px 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 2px solid #f1f5f9;
        }

        .empty-state-icon {
            font-size: 6rem;
            color: #e2e8f0;
            margin-bottom: 24px;
            opacity: 0.6;
        }

        .empty-state h4 {
            color: #0f172a;
            font-weight: 800;
            margin-bottom: 12px;
            font-size: 1.5rem;
        }

        .empty-state p {
            color: #64748b;
            margin-bottom: 28px;
            font-size: 1.05rem;
        }

        /* Alerts */
        .alert {
            border-radius: 16px;
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

        /* Gradient backgrounds for icons */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e55353 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .bg-gradient-secondary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.6rem;
            }

            .page-header-icon {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .filter-group {
                grid-template-columns: 1fr;
            }

            .action-bar {
                padding: 20px;
            }

            .notification-header {
                flex-direction: column;
            }

            .notification-icon {
                width: 48px;
                height: 48px;
                font-size: 1.3rem;
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

            .btn-load-more {
                padding: 16px 36px;
                font-size: 0.9rem;
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
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('error') }}</span>
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

        <!-- Action Bar -->
        @if($unreadCount > 0)
        <div class="action-bar">
            <div class="action-bar-title">
                <i class="bi bi-check-all"></i>
                <span>{{ $unreadCount }} notifikasi belum dibaca</span>
            </div>
            <button type="button" class="btn-filter btn-primary" onclick="markAllAsRead()">
                <i class="bi bi-check-circle-fill"></i>
                <span>Tandai Semua Dibaca</span>
            </button>
        </div>
        @endif

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
                </div>
            </form>
        </div>

        <!-- Notifications List -->
        @if ($notifikasi->count() > 0)
            @foreach ($notifikasi as $n)
                <div class="notification-card {{ !$n->dibaca ? 'unread' : '' }}" id="notif-{{ $n->id }}">
                    <div class="notification-header">
                        <div class="notification-icon bg-gradient-{{ $n->getBadgeColor() }}">
                            <i class="bi bi-{{ $n->getIcon() }}"></i>
                        </div>

                        <div class="notification-content">
                            <div class="notification-top">
                                <div>
                                    <h5 class="notification-title">{{ $n->judul }}</h5>
                                    @if (!$n->dibaca)
                                        <span class="notification-badge badge-unread" id="badge-{{ $n->id }}">BARU</span>
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

           <!-- Load More Button -->
           @if($notifikasi->hasMorePages())
           <div class="load-more-section">
               <a href="{{ $notifikasi->nextPageUrl() }}" class="btn-load-more">
                   <span>Lihat Notifikasi Selanjutnya</span>
                   <i class="bi bi-arrow-down-circle-fill"></i>
               </a>
               <p class="text-muted mt-4 mb-0" style="font-weight: 600; font-size: 0.95rem;">
                   Menampilkan {{ $notifikasi->firstItem() }} - {{ $notifikasi->lastItem() }} dari {{ $notifikasi->total() }} notifikasi
               </p>
           </div>
           @endif
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
           console.log('🔵 URL:', `${baseUrl}/mahasiswa/notifikasi/baca-semua`);
           
           const button = event.target.closest('button');
           if(button) {
               button.disabled = true;
               button.innerHTML = '<i class="bi bi-hourglass-split"></i> <span>Memproses...</span>';
           }
           
           fetch(`${baseUrl}/mahasiswa/notifikasi/baca-semua`, {
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

       console.log('✅ Mahasiswa notification page scripts loaded');
   </script>
@endpush