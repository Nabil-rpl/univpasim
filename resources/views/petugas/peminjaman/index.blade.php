@extends('layouts.petugas')

@section('page-title', 'Manajemen Peminjaman')

@section('content')
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
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-500: #6b7280;
        --gray-700: #374151;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }

    body {
        background: linear-gradient(135deg, #667eea08 0%, #764ba208 100%);
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-xl), 0 0 0 1px rgba(255,255,255,0.1) inset;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .page-header p {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0.5rem 0 0;
        position: relative;
        z-index: 1;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--card-color), var(--card-color-dark));
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stats-card.primary { --card-color: #667eea; --card-color-dark: #5568d3; }
    .stats-card.warning { --card-color: #ed8936; --card-color-dark: #dd6b20; }
    .stats-card.success { --card-color: #48bb78; --card-color-dark: #38a169; }
    .stats-card.danger { --card-color: #f56565; --card-color-dark: #e53e3e; }

    .stats-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, var(--card-color), var(--card-color-dark));
        box-shadow: var(--shadow-md);
    }

    .stats-content h6 {
        color: var(--gray-500);
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0 0 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .stats-content h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: var(--dark);
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
        border: 1px solid var(--gray-200);
    }

    .filter-card .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        padding: 0.625rem 0.875rem;
        transition: all 0.2s;
        font-size: 0.9375rem;
    }

    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success), #38a169);
        color: white;
    }

    .btn-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .table {
        margin: 0;
    }

    .table thead th {
        background: var(--gray-50);
        color: var(--dark);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border: none;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--gray-100);
    }

    .table tbody tr {
        transition: all 0.2s;
    }

    .table tbody tr:hover {
        background: var(--gray-50);
    }

    /* User Card */
    .user-card {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .user-info strong {
        display: block;
        color: var(--dark);
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }

    .user-info small {
        color: var(--gray-500);
        font-size: 0.8125rem;
    }

    /* Book Info */
    .book-info {
        display: flex;
        align-items: start;
        gap: 0.75rem;
    }

    .book-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .book-details strong {
        display: block;
        color: var(--dark);
        font-size: 0.9375rem;
        margin-bottom: 0.125rem;
    }

    .book-details small {
        color: var(--gray-500);
        font-size: 0.8125rem;
    }

    /* Badges */
    .badge-role {
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        font-size: 0.6875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .badge-status {
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        box-shadow: var(--shadow-sm);
    }

    .badge-duration {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #4c51bf;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Date Info */
    .date-info strong {
        display: block;
        color: var(--dark);
        font-size: 0.9375rem;
        margin-bottom: 0.125rem;
    }

    .date-info small {
        color: var(--gray-500);
        font-size: 0.75rem;
    }

    .deadline-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.375rem;
    }

    .deadline-badge.normal {
        background: #d1fae5;
        color: #065f46;
    }

    .deadline-badge.warning {
        background: #fef3c7;
        color: #92400e;
    }

    .deadline-badge.danger {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Action Buttons */
    .btn-action-group {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.2s;
        font-size: 0.9375rem;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-action.btn-info {
        background: linear-gradient(135deg, var(--info), #3182ce);
        color: white;
    }

    .btn-action.btn-success {
        background: linear-gradient(135deg, var(--success), #38a169);
        color: white;
    }

    .btn-action.btn-danger {
        background: linear-gradient(135deg, var(--danger), #e53e3e);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 1.25rem;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.25rem;
        background: var(--gray-100);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--gray-500);
    }

    .empty-state h4 {
        color: var(--dark);
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }

    .empty-state p {
        color: var(--gray-500);
        margin: 0 0 1.5rem;
    }

    /* Alerts */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: var(--shadow-sm);
    }

    .alert i {
        font-size: 1.25rem;
    }

    /* Pagination */
    .pagination {
        gap: 0.375rem;
    }

    .pagination .page-link {
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 0.5rem 0.875rem;
        color: var(--dark);
        font-weight: 600;
        transition: all 0.2s;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-color: transparent;
        box-shadow: var(--shadow-md);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-card .row > div {
            margin-bottom: 1rem;
        }

        .table-card {
            padding: 1rem;
            overflow-x: auto;
        }

        .btn-action-group {
            flex-direction: column;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stats-card {
        animation: fadeIn 0.4s ease-out backwards;
    }

    .stats-card:nth-child(1) { animation-delay: 0.05s; }
    .stats-card:nth-child(2) { animation-delay: 0.1s; }
    .stats-card:nth-child(3) { animation-delay: 0.15s; }
    .stats-card:nth-child(4) { animation-delay: 0.2s; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>
                <i class="bi bi-journal-bookmark-fill me-2"></i>
                Manajemen Peminjaman
            </h1>
            <p class="mb-0">Kelola dan pantau seluruh aktivitas peminjaman buku perpustakaan</p>
        </div>
        <div class="d-none d-md-block">
            <div style="font-size: 3.5rem; opacity: 0.15;">📚</div>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="stats-grid">
    <div class="stats-card primary">
        <div class="d-flex justify-content-between align-items-center">
            <div class="stats-content">
                <h6>Total Peminjaman</h6>
                <h2>{{ $stats['total'] }}</h2>
            </div>
            <div class="stats-icon">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
        </div>
    </div>

    <div class="stats-card warning">
        <div class="d-flex justify-content-between align-items-center">
            <div class="stats-content">
                <h6>Sedang Dipinjam</h6>
                <h2>{{ $stats['dipinjam'] }}</h2>
            </div>
            <div class="stats-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
    </div>

    <div class="stats-card success">
        <div class="d-flex justify-content-between align-items-center">
            <div class="stats-content">
                <h6>Dikembalikan</h6>
                <h2>{{ $stats['dikembalikan'] }}</h2>
            </div>
            <div class="stats-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>
    </div>

    <div class="stats-card danger">
        <div class="d-flex justify-content-between align-items-center">
            <div class="stats-content">
                <h6>Terlambat</h6>
                <h2>{{ $stats['terlambat'] }}</h2>
            </div>
            <div class="stats-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="filter-card">
    <form method="GET" action="{{ route('petugas.peminjaman.index') }}">
        <div class="row g-3">
            <div class="col-xl-4 col-lg-6 col-md-6">
                <label class="form-label">
                    <i class="bi bi-search"></i>Pencarian
                </label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Nama peminjam atau judul buku..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-xl-2 col-lg-3 col-md-6">
                <label class="form-label">
                    <i class="bi bi-funnel"></i>Status
                </label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-6">
                <label class="form-label">
                    <i class="bi bi-person"></i>Tipe Peminjam
                </label>
                <select name="role" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="pengguna_luar" {{ request('role') == 'pengguna_luar' ? 'selected' : '' }}>Pengguna Luar</option>
                </select>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-6">
                <label class="form-label">
                    <i class="bi bi-calendar"></i>Dari Tanggal
                </label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>Filter
                    </button>
                    <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                    <a href="{{ route('petugas.peminjaman.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i>Tambah Peminjaman
                    </a>
                </div>
            </div>
        </div>
    </form>
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

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="bi bi-info-circle-fill"></i>
    <span>{{ session('info') }}</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Table -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Durasi & Deadline</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Petugas</th>
                    <th width="12%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $index => $item)
                <tr>
                    <td><strong>{{ $peminjamans->firstItem() + $index }}</strong></td>
                    <td>
                        <div class="user-card">
                            <div class="user-avatar">
                                {{ strtoupper(substr($item->mahasiswa->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <strong>{{ $item->mahasiswa->name }}</strong>
                                @if($item->mahasiswa->role == 'mahasiswa')
                                    <span class="badge badge-role bg-primary">
                                        <i class="bi bi-mortarboard-fill"></i>Mahasiswa
                                    </span>
                                    <small class="d-block mt-1">NIM: {{ $item->mahasiswa->nim ?? '-' }}</small>
                                @elseif($item->mahasiswa->role == 'pengguna_luar')
                                    <span class="badge badge-role bg-info">
                                        <i class="bi bi-person-fill"></i>Pengguna Luar
                                    </span>
                                    <small class="d-block mt-1">
                                        <i class="bi bi-telephone"></i> {{ $item->mahasiswa->no_hp ?? '-' }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="book-info">
                            <div class="book-icon">
                                <i class="bi bi-book-fill"></i>
                            </div>
                            <div class="book-details">
                                <strong>{{ $item->buku->judul }}</strong>
                                <small>{{ $item->buku->penulis }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="date-info">
                            <strong>{{ $item->tanggal_pinjam->format('d M Y') }}</strong>
                            <small>{{ $item->tanggal_pinjam->format('H:i') }} WIB</small>
                        </div>
                    </td>
                    <td>
                        <span class="badge-duration">
                            <i class="bi bi-clock-fill"></i>{{ $item->durasi_hari }} Hari
                        </span>
                        @if($item->tanggal_deadline)
                            @php
                                $now = now();
                                $deadline = $item->tanggal_deadline;
                                $diff = $now->diffInDays($deadline, false);
                                
                                if ($diff < 0) {
                                    $badgeClass = 'danger';
                                } elseif ($diff <= 2) {
                                    $badgeClass = 'warning';
                                } else {
                                    $badgeClass = 'normal';
                                }
                            @endphp
                            <div class="deadline-badge {{ $badgeClass }}">
                                <i class="bi bi-calendar-x"></i>
                                {{ $deadline->format('d M Y') }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($item->tanggal_kembali)
                            <div class="date-info">
                                <strong>{{ $item->tanggal_kembali->format('d M Y') }}</strong>
                                <small>{{ $item->tanggal_kembali->format('H:i') }} WIB</small>
                            </div>
                        @else
                            <span class="text-muted">
                                <i class="bi bi-dash-circle"></i> Belum dikembalikan
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($item->status == 'dipinjam')
                            @if($item->isTerlambat())
                                @php
                                    $hariTerlambat = $item->getHariTerlambat();
                                    $denda = $item->hitungDenda();
                                @endphp
                                <span class="badge badge-status bg-danger">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    Terlambat {{ $hariTerlambat }} hari
                                </span>
                                <div class="mt-2">
                                    <small class="text-danger fw-bold">
                                        💰 Rp {{ number_format($denda, 0, ',', '.') }}
                                    </small>
                                </div>
                            @else
                                <span class="badge badge-status bg-warning text-white">
                                    <i class="bi bi-hourglass-split"></i>Dipinjam
                                </span>
                            @endif
                        @else
                            <span class="badge badge-status bg-success">
                                <i class="bi bi-check-circle-fill"></i>Dikembalikan
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($item->petugas)
                            <div class="user-info">
                                <strong>{{ $item->petugas->name }}</strong>
                                <small class="text-muted d-block">{{ ucfirst($item->petugas->role) }}</small>
                            </div>
                        @else
                            <span class="text-muted">
                                <i class="bi bi-dash-circle"></i> -
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-action-group">
                            <a href="{{ route('petugas.peminjaman.show', $item->id) }}" 
                               class="btn-action btn-info" 
                               title="Detail">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            
                            @if($item->status == 'dipinjam')
                            <a href="{{ route('petugas.pengembalian.show', $item->id) }}" 
                               class="btn-action btn-success" 
                               title="Proses Pengembalian">
                                <i class="bi bi-box-arrow-in-down-fill"></i>
                            </a>
                            @endif
                            
                            <form action="{{ route('petugas.peminjaman.destroy', $item->id) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-danger" title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="p-0">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <h4>Tidak Ada Data Peminjaman</h4>
                            <p>Gunakan filter atau tambah data peminjaman baru</p>
                            <a href="{{ route('petugas.peminjaman.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i>Tambah Peminjaman Baru
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($peminjamans->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 1px solid var(--gray-200);">
        <div class="text-muted">
            <i class="bi bi-file-earmark-text me-2"></i>
            Menampilkan <strong>{{ $peminjamans->firstItem() ?? 0 }}</strong> - 
            <strong>{{ $peminjamans->lastItem() ?? 0 }}</strong> 
            dari <strong>{{ $peminjamans->total() }}</strong> data
        </div>
        <div>
            {{ $peminjamans->links() }}
        </div>
    </div>
    @endif
</div>

<script>
    // Auto hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });

    // Add loading animation on form submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.classList.contains('btn-danger')) {
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                submitBtn.disabled = true;
                
                // Reset badge peminjaman jika form berhasil
                if (typeof resetPeminjamanBadge === 'function') {
                    resetPeminjamanBadge();
                }
                
                // Reset if form submission fails
                setTimeout(() => {
                    submitBtn.innerHTML = originalContent;
                    submitBtn.disabled = false;
                }, 10000);
            }
        });
    });
</script>
@endsection