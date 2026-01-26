@extends('layouts.pengguna-luar')

@section('title', 'Notifikasi')

@push('styles')
<style>
    :root {
        --primary: #6366F1;
        --primary-dark: #4F46E5;
        --primary-light: #818CF8;
        --secondary: #8B5CF6;
        --success: #10B981;
        --danger: #EF4444;
        --warning: #F59E0B;
        --info: #3B82F6;
        --light: #F9FAFB;
        --dark: #1F2937;
        --gray: #6B7280;
        --border: #E5E7EB;
    }

    body {
        background: #F3F4F6;
        min-height: 100vh;
    }

    /* Page Header - White Design */
    .page-header {
        background: white;
        border-radius: 20px;
        padding: 32px 36px;
        margin-bottom: 28px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 25px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--border);
    }

    .page-header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }

    .page-header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .page-header-icon {
        width: 64px;
        height: 64px;
        background: #F3F4F6;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #6366F1;
        border: 2px solid #E5E7EB;
    }

    .page-header-text h1 {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 6px 0;
        letter-spacing: -0.02em;
    }

    .page-header-text p {
        font-size: 0.9375rem;
        color: var(--gray);
        margin: 0;
        font-weight: 400;
    }

    .page-header-actions {
        display: flex;
        gap: 12px;
    }

    /* Stats Cards - Redesigned */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color) 0%, var(--card-color-light) 100%);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        border-color: var(--card-color);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card.warning-card {
        --card-color: #F59E0B;
        --card-color-light: #FCD34D;
    }

    .stat-card.primary-card {
        --card-color: #6366F1;
        --card-color-light: #818CF8;
    }

    .stat-card.success-card {
        --card-color: #10B981;
        --card-color-light: #34D399;
    }

    .stat-card.info-card {
        --card-color: #3B82F6;
        --card-color-light: #60A5FA;
    }

    .stat-card-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .stat-card-info {
        flex: 1;
    }

    .stat-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .stat-card-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 6px;
        line-height: 1;
    }

    .stat-card-label {
        color: var(--gray);
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Filter Section - Modern Design */
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
    }

    .filter-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #F3F4F6;
    }

    .filter-header-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .filter-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.125rem;
        color: var(--dark);
        letter-spacing: -0.01em;
    }

    .filter-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .filter-input {
        padding: 12px 16px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.9375rem;
        transition: all 0.2s;
        background: white;
        color: var(--dark);
        font-weight: 500;
    }

    .filter-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .filter-input::placeholder {
        color: #9CA3AF;
    }

    .filter-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-filter {
        padding: 12px 24px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
    }

    .btn-secondary {
        background: #F3F4F6;
        color: var(--gray);
        border: 2px solid var(--border);
    }

    .btn-secondary:hover {
        background: white;
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-success {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    /* Notification Card - Premium Design */
    .notification-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    .notification-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: transparent;
        transition: background 0.3s;
    }

    .notification-card.unread {
        background: linear-gradient(to right, #F0F4FF 0%, white 100%);
        border-left: 4px solid #6366F1;
    }

    .notification-card.unread::before {
        background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
    }

    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .notification-header {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        padding: 0;
        background: transparent;
        border: none;
    }

    .notification-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        gap: 16px;
    }

    .notification-title {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
        line-height: 1.4;
        letter-spacing: -0.01em;
    }

    .notification-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-left: 10px;
        letter-spacing: 0.03em;
    }

    .badge-unread {
        background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    }

    .badge-read {
        background: #F3F4F6;
        color: #6B7280;
    }

    .notification-text {
        color: #4B5563;
        line-height: 1.7;
        margin-bottom: 16px;
        font-size: 0.9375rem;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        font-size: 0.8125rem;
        margin-bottom: 16px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #F9FAFB;
        padding: 6px 14px;
        border-radius: 8px;
        color: var(--gray);
        font-weight: 500;
        border: 1px solid #F3F4F6;
    }

    .meta-item i {
        color: var(--primary);
        font-size: 0.875rem;
    }

    .notification-actions {
        display: flex;
        gap: 10px;
        padding-top: 16px;
        border-top: 1px solid #F3F4F6;
    }

    .btn-action {
        padding: 8px 18px;
        border-radius: 10px;
        border: none;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-view {
        background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
    }

    .btn-view:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
        color: white;
    }

    .btn-delete-action {
        background: white;
        color: #EF4444;
        border: 2px solid #FEE2E2;
    }

    .btn-delete-action:hover {
        background: #EF4444;
        color: white;
        border-color: #EF4444;
    }

    /* Priority Badge */
    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.6875rem;
        font-weight: 700;
        margin-left: 8px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .priority-mendesak {
        background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
        color: #DC2626;
        border: 1px solid #FCA5A5;
    }

    .priority-tinggi {
        background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        color: #D97706;
        border: 1px solid #FCD34D;
    }

    .priority-normal {
        background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
        color: #2563EB;
        border: 1px solid #93C5FD;
    }

    .priority-rendah {
        background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%);
        color: #6B7280;
        border: 1px solid #D1D5DB;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
    }

    .empty-state-icon {
        font-size: 5rem;
        background: linear-gradient(135deg, #E5E7EB 0%, #D1D5DB 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 24px;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state h4 {
        color: var(--dark);
        font-weight: 700;
        margin-bottom: 12px;
        font-size: 1.5rem;
    }

    .empty-state p {
        color: var(--gray);
        margin-bottom: 28px;
        font-size: 1rem;
    }

    /* Gradient backgrounds for icons */
    .bg-gradient-primary { background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); }

    /* Alert Styles */
    .alert {
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 24px;
        }

        .page-header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header-left {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header-icon {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }

        .page-header-text h1 {
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
            width: 48px;
            height: 48px;
        }

        .notification-top {
            flex-direction: column;
            gap: 12px;
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
<div class="container-fluid px-4">
    <!-- Page Header - White Design -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div class="page-header-text">
                    <h1>Notifikasi Saya</h1>
                    <p>Semua notifikasi dan update terbaru untuk Anda</p>
                </div>
            </div>
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
        <div class="stat-card warning-card">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $stats['belum_dibaca'] }}</div>
                    <div class="stat-card-label">Belum Dibaca</div>
                </div>
                <div class="stat-card-icon bg-gradient-warning">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
            </div>
        </div>

        <div class="stat-card primary-card">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $stats['total'] }}</div>
                    <div class="stat-card-label">Total Notifikasi</div>
                </div>
                <div class="stat-card-icon bg-gradient-primary">
                    <i class="bi bi-bell-fill"></i>
                </div>
            </div>
        </div>

        <div class="stat-card success-card">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $stats['sudah_dibaca'] }}</div>
                    <div class="stat-card-label">Sudah Dibaca</div>
                </div>
                <div class="stat-card-icon bg-gradient-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>

        <div class="stat-card info-card">
            <div class="stat-card-content">
                <div class="stat-card-info">
                    <div class="stat-card-value">{{ $stats['hari_ini'] }}</div>
                    <div class="stat-card-label">Hari Ini</div>
                </div>
                <div class="stat-card-icon bg-gradient-info">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-header">
            <div class="filter-header-icon">
                <i class="bi bi-funnel-fill"></i>
            </div>
            <h5>Filter Notifikasi</h5>
        </div>

        <form method="GET" action="{{ route('pengguna-luar.notifikasi.index') }}">
            <div class="filter-group">
                <select name="tipe" class="filter-input">
                    <option value="">Semua Tipe</option>
                    @foreach($tipeNotifikasi as $key => $label)
                        <option value="{{ $key }}" {{ request('tipe') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="search" class="filter-input" placeholder="Cari notifikasi..." value="{{ request('search') }}">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-primary">
                    <i class="bi bi-search"></i>
                    Cari
                </button>
                <a href="{{ route('pengguna-luar.notifikasi.index') }}" class="btn-filter btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset Filter
                </a>
                @if($stats['belum_dibaca'] > 0)
                <button type="button" class="btn-filter btn-success" onclick="tandaiSemuaDibaca()">
                    <i class="bi bi-check-all"></i>
                    Tandai Semua Dibaca
                </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Notifications List -->
    @if($notifikasi->count() > 0)
        @foreach($notifikasi as $notif)
        <div class="notification-card {{ !$notif->dibaca ? 'unread' : '' }}">
            <div class="notification-header">
                <div class="notification-icon bg-gradient-{{ $notif->getBadgeColor() }}">
                    <i class="bi bi-{{ $notif->getIcon() }}"></i>
                </div>

                <div class="notification-content">
                    <div class="notification-top">
                        <div>
                            <h5 class="notification-title">
                                {{ $notif->judul }}
                                @if(!$notif->dibaca)
                                <span class="notification-badge badge-unread">Baru</span>
                                @endif
                                @if($notif->prioritas && $notif->prioritas !== 'normal')
                                <span class="priority-badge priority-{{ $notif->prioritas }}">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    {{ ucfirst($notif->prioritas) }}
                                </span>
                                @endif
                            </h5>
                        </div>
                    </div>

                    <p class="notification-text">{{ Str::limit($notif->isi, 200) }}</p>

                    <div class="notification-meta">
                        <span class="meta-item">
                            <i class="bi bi-clock"></i>
                            {{ $notif->getWaktuRelatif() }}
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-tag"></i>
                            {{ $tipeNotifikasi[$notif->tipe] ?? ucwords(str_replace('_', ' ', $notif->tipe)) }}
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-calendar"></i>
                            {{ $notif->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <div class="notification-actions">
                        <a href="{{ route('pengguna-luar.notifikasi.show', $notif->id) }}" class="btn-action btn-view">
                            <i class="bi bi-eye"></i>
                            Lihat Detail
                        </a>

                        <button type="button" class="btn-action btn-delete-action" onclick="hapusNotifikasi({{ $notif->id }})">
                            <i class="bi bi-trash"></i>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        @if($notifikasi->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $notifikasi->links() }}
        </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-bell-slash"></i>
            </div>
            <h4>Tidak Ada Notifikasi</h4>
            <p>Belum ada notifikasi yang tersedia saat ini</p>
            <a href="{{ route('pengguna-luar.dashboard') }}" class="btn-filter btn-primary">
                <i class="bi bi-house"></i>
                Kembali ke Dashboard
            </a>
        </div>
    @endif
</div>

<!-- Form untuk Hapus Notifikasi -->
<form id="form-hapus-notifikasi" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<!-- Form untuk Tandai Semua Dibaca -->
<form id="form-tandai-semua-dibaca" 
      action="{{ route('pengguna-luar.notifikasi.index') }}" 
      method="POST" class="d-none">
    @csrf
</form>

<!-- Form untuk Hapus Sudah Dibaca -->
<form id="form-hapus-sudah-dibaca" 
      action="{{ route('pengguna-luar.notifikasi.index') }}" 
      method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function hapusNotifikasi(id) {
    if (confirm('Yakin ingin menghapus notifikasi ini?')) {
        const form = document.getElementById('form-hapus-notifikasi');
        form.action = '{{ url("pengguna-luar/notifikasi") }}/' + id;
        form.submit();
    }
}

function tandaiSemuaDibaca() {
    if (confirm('Tandai semua notifikasi sebagai dibaca?')) {
        document.getElementById('form-tandai-semua-dibaca').submit();
    }
}

function hapusSudahDibaca() {
    if (confirm('Yakin ingin menghapus semua notifikasi yang sudah dibaca?')) {
        document.getElementById('form-hapus-sudah-dibaca').submit();
    }
}

// Auto dismiss alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
@endpush