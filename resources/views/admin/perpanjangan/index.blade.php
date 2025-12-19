@extends('layouts.app')

@section('title', 'Data Perpanjangan Buku')

@section('content')
<style>
    /* Modern Card Design */
    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stats-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        transform: translateY(-4px);
    }

    .stats-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 12px 0 8px 0;
        line-height: 1;
    }

    .stats-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .stats-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }

    /* Page Header */
    .page-header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
        margin-bottom: 28px;
    }

    /* Main Content Card */
    .main-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    /* Filter Section */
    .filter-section {
        background: #f8fafc;
        padding: 24px;
        border-bottom: 2px solid #e2e8f0;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Table Styles - COMPACT VERSION */
    .custom-table {
        margin: 0;
        font-size: 0.875rem;
    }

    .custom-table thead th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 12px 16px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #64748b;
        white-space: nowrap;
    }

    .custom-table tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .custom-table tbody tr {
        transition: all 0.2s;
    }

    .custom-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Compact User Info */
    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 180px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.875rem;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-meta {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Compact Book Info */
    .book-info {
        min-width: 160px;
    }

    .book-title {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.875rem;
        margin-bottom: 2px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-code {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
        font-weight: 600;
    }

    /* Compact Timeline */
    .timeline-compact {
        min-width: 140px;
    }

    .date-item {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
        font-size: 0.75rem;
    }

    .date-icon {
        font-size: 0.875rem;
    }

    .duration-text {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
        margin-top: 4px;
    }

    /* Compact Status */
    .status-compact {
        min-width: 100px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
        white-space: nowrap;
    }

    .status-date {
        font-size: 0.7rem;
        color: #64748b;
        margin-top: 4px;
    }

    /* Compact Petugas */
    .petugas-compact {
        min-width: 120px;
    }

    .petugas-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.8rem;
        margin-bottom: 2px;
    }

    .petugas-time {
        font-size: 0.7rem;
        color: #64748b;
    }

    /* Button Styles */
    .btn-action {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        white-space: nowrap;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-export {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        transition: all 0.3s;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        color: white;
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 8px;
    }

    .empty-text {
        font-size: 0.875rem;
        color: #94a3b8;
    }

    /* Pagination */
    .pagination-info {
        font-size: 0.875rem;
        color: #64748b;
    }

    /* Section Header */
    .section-header {
        background: white;
        padding: 16px 24px;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #475569;
        margin: 0;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-number {
            font-size: 1.5rem;
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .custom-table {
            font-size: 0.8rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-2 fw-bold">
                    <i class="bi bi-arrow-repeat me-2"></i>Perpanjangan Buku
                </h3>
                <p class="mb-0 opacity-90" style="font-size: 0.95rem;">
                    Monitoring dan pengelolaan perpanjangan peminjaman buku perpustakaan
                </p>
            </div>
            <a href="{{ route('admin.perpanjangan.export', request()->query()) }}" 
               class="btn btn-export">
                <i class="bi bi-file-earmark-arrow-down me-2"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Menunggu -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stats-label">Menunggu Persetujuan</div>
                        <div class="stats-number text-warning">{{ $stats['menunggu'] }}</div>
                        <span class="stats-status bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-hourglass-split me-1"></i>Pending
                        </span>
                    </div>
                    <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disetujui -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stats-label">Disetujui</div>
                        <div class="stats-number text-success">{{ $stats['disetujui'] }}</div>
                        <span class="stats-status bg-success bg-opacity-10 text-success">
                            <i class="bi bi-check-circle-fill me-1"></i>Approved
                        </span>
                    </div>
                    <div class="stats-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stats-label">Ditolak</div>
                        <div class="stats-number text-danger">{{ $stats['ditolak'] }}</div>
                        <span class="stats-status bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-x-circle-fill me-1"></i>Rejected
                        </span>
                    </div>
                    <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stats-label">Total Perpanjangan</div>
                        <div class="stats-number text-primary">{{ $stats['total'] }}</div>
                        <span class="stats-status bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-collection me-1"></i>All Records
                        </span>
                    </div>
                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-card">
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-funnel-fill me-2 text-primary"></i>
                <h6 class="mb-0 fw-bold">Filter & Pencarian</h6>
            </div>

            <form method="GET" action="{{ route('admin.perpanjangan.index') }}">
                <div class="row g-3">
                    <!-- Status -->
                    <div class="col-lg-2 col-md-6">
                        <label class="filter-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>
                                Menunggu
                            </option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                Disetujui
                            </option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                                Ditolak
                            </option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan
                            </option>
                        </select>
                    </div>

                    <!-- Tanggal Dari -->
                    <div class="col-lg-2 col-md-6">
                        <label class="filter-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" class="form-control" 
                               value="{{ request('tanggal_dari') }}">
                    </div>

                    <!-- Tanggal Sampai -->
                    <div class="col-lg-2 col-md-6">
                        <label class="filter-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" class="form-control" 
                               value="{{ request('tanggal_sampai') }}">
                    </div>

                    <!-- Search -->
                    <div class="col-lg-4 col-md-6">
                        <label class="filter-label">Pencarian</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nama, NIM, atau judul buku..." 
                               value="{{ request('search') }}">
                    </div>

                    <!-- Buttons -->
                    <div class="col-lg-2 col-md-12">
                        <label class="filter-label d-none d-lg-block">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-action flex-grow-1">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            @if(request()->anyFilled(['status', 'tanggal_dari', 'tanggal_sampai', 'search']))
                                <a href="{{ route('admin.perpanjangan.index') }}" 
                                   class="btn btn-outline-secondary btn-action"
                                   title="Reset">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Header -->
        <div class="section-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="section-title mb-0">
                    <i class="bi bi-table me-2"></i>Daftar Perpanjangan
                </h6>
                <span class="badge bg-secondary px-3 py-2">
                    {{ $perpanjangan->total() }} Data
                </span>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Timeline</th>
                        <th class="text-center">Status</th>
                        <th>Petugas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perpanjangan as $item)
                    <tr>
                        <!-- Peminjam -->
                        <td>
                            <div class="user-info">
                                <div class="user-avatar bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="user-details">
                                    <div class="user-name" title="{{ $item->peminjaman->mahasiswa->name ?? '-' }}">
                                        {{ $item->peminjaman->mahasiswa->name ?? '-' }}
                                    </div>
                                    <div class="user-meta">
                                        {{ $item->peminjaman->mahasiswa->nim ?? $item->peminjaman->mahasiswa->nik ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Buku -->
                        <td>
                            <div class="book-info">
                                <div class="book-title" title="{{ $item->peminjaman->buku->judul ?? '-' }}">
                                    {{ $item->peminjaman->buku->judul ?? '-' }}
                                </div>
                                <span class="book-code bg-dark bg-opacity-10 text-dark">
                                    {{ $item->peminjaman->buku->kode_buku ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <!-- Timeline -->
                        <td>
                            <div class="timeline-compact">
                                <div class="date-item text-danger">
                                    <i class="bi bi-calendar-x date-icon"></i>
                                    <span>{{ $item->tanggal_deadline_lama->format('d M Y') }}</span>
                                </div>
                                <div class="date-item text-success">
                                    <i class="bi bi-calendar-check date-icon"></i>
                                    <span>{{ $item->tanggal_deadline_baru->format('d M Y') }}</span>
                                </div>
                                <span class="duration-text bg-primary text-white">
                                    +{{ $item->durasi_tambahan }} Hari
                                </span>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            <div class="status-compact">
                                <span class="status-badge bg-{{ $item->getStatusBadgeClass() }}">
                                    <i class="bi bi-{{ $item->getStatusIcon() }} me-1"></i>
                                    {{ $item->getStatusLabel() }}
                                </span>
                                <div class="status-date">
                                    {{ $item->tanggal_perpanjangan->format('d M Y') }}
                                </div>
                            </div>
                        </td>

                        <!-- Petugas -->
                        <td>
                            @if($item->petugas)
                            <div class="petugas-compact">
                                <div class="petugas-name">
                                    <i class="bi bi-person-badge me-1 text-primary"></i>
                                    {{ $item->petugas->name }}
                                </div>
                                <div class="petugas-time">
                                    {{ $item->updated_at->format('d/m H:i') }}
                                </div>
                            </div>
                            @else
                            <span class="text-muted small">
                                <i class="bi bi-dash-circle me-1"></i>Belum diproses
                            </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="text-center">
                            <a href="{{ route('admin.perpanjangan.show', $item->id) }}" 
                               class="btn btn-primary btn-action btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <h5 class="empty-title">Tidak Ada Data</h5>
                                <p class="empty-text mb-0">
                                    @if(request()->anyFilled(['status', 'tanggal_dari', 'tanggal_sampai', 'search']))
                                        Tidak ada perpanjangan yang sesuai dengan filter pencarian
                                    @else
                                        Belum ada perpanjangan yang diajukan
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($perpanjangan->hasPages())
        <div class="section-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="pagination-info">
                    Menampilkan <strong>{{ $perpanjangan->firstItem() ?? 0 }}</strong> 
                    hingga <strong>{{ $perpanjangan->lastItem() ?? 0 }}</strong> 
                    dari <strong>{{ $perpanjangan->total() }}</strong> data
                </div>
                <div>
                    {{ $perpanjangan->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection