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
    }

    body {
        background: linear-gradient(135deg, #667eea08 0%, #764ba208 100%);
    }

    /* Animated Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 25px;
        padding: 40px;
        margin-bottom: 30px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .page-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 10px 0 0;
        position: relative;
        z-index: 1;
    }

    /* Modern Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color), var(--card-color-dark));
    }

    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .stats-card.primary {
        --card-color: #667eea;
        --card-color-dark: #5568d3;
    }

    .stats-card.warning {
        --card-color: #ed8936;
        --card-color-dark: #dd6b20;
    }

    .stats-card.success {
        --card-color: #48bb78;
        --card-color-dark: #38a169;
    }

    .stats-card.danger {
        --card-color: #f56565;
        --card-color-dark: #e53e3e;
    }

    .stats-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        background: linear-gradient(135deg, var(--card-color), var(--card-color-dark));
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    .stats-icon::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--card-color), var(--card-color-dark));
        z-index: -1;
        opacity: 0.3;
        filter: blur(10px);
    }

    .stats-content h6 {
        color: #718096;
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0 0 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-content h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
        background: linear-gradient(135deg, var(--card-color), var(--card-color-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .filter-card .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s;
    }

    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .filter-card .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .table {
        margin: 0;
    }

    .table thead th {
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        color: var(--dark);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px;
        border: none;
    }

    .table tbody td {
        padding: 20px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tbody tr {
        transition: all 0.3s;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9ff, #f5f7ff);
        transform: scale(1.01);
    }

    /* User Card in Table */
    .user-card {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .user-info strong {
        display: block;
        color: var(--dark);
        font-size: 0.95rem;
    }

    .user-info small {
        color: #718096;
        font-size: 0.8rem;
    }

    /* Book Card in Table */
    .book-info {
        display: flex;
        align-items: start;
        gap: 12px;
    }

    .book-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .book-details strong {
        display: block;
        color: var(--dark);
        font-size: 0.95rem;
        margin-bottom: 2px;
    }

    .book-details small {
        color: #718096;
        font-size: 0.8rem;
    }

    /* Badges */
    .badge-role {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-status {
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .badge-duration {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #4c51bf;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }

    /* Date Info */
    .date-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .date-info strong {
        color: var(--dark);
        font-size: 0.95rem;
    }

    .date-info small {
        color: #718096;
        font-size: 0.75rem;
    }

    .deadline-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 4px;
    }

    .deadline-badge.normal {
        background: #e6fffa;
        color: #047857;
    }

    .deadline-badge.warning {
        background: #fef3c7;
        color: #d97706;
    }

    .deadline-badge.danger {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Action Buttons */
    .btn-action-group {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    .btn-action {
        width: 38px;
        height: 38px;
        padding: 0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.3s;
        font-size: 1rem;
    }

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-action.btn-info {
        background: linear-gradient(135deg, #4299e1, #3182ce);
        color: white;
    }

    .btn-action.btn-success {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }

    .btn-action.btn-danger {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: #cbd5e0;
    }

    .empty-state h4 {
        color: var(--dark);
        font-weight: 700;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #718096;
        margin: 0;
    }

    /* Alerts */
    .alert {
        border-radius: 15px;
        border: none;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .alert i {
        font-size: 1.3rem;
    }

    /* Pagination */
    .pagination {
        gap: 6px;
    }

    .pagination .page-link {
        border: none;
        border-radius: 10px;
        padding: 10px 16px;
        color: var(--dark);
        font-weight: 600;
        transition: all 0.3s;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 25px;
        }

        .page-header h1 {
            font-size: 1.8rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-card .row > div {
            margin-bottom: 15px;
        }

        .table-card {
            padding: 15px;
            overflow-x: auto;
        }

        .btn-action-group {
            flex-direction: column;
        }
    }

    /* Loading Animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .loading {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>
                <i class="bi bi-journal-bookmark-fill me-3"></i>
                Manajemen Peminjaman
            </h1>
            <p class="mb-0">Kelola dan pantau seluruh aktivitas peminjaman buku perpustakaan</p>
        </div>
        <div class="d-none d-md-block">
            <div style="font-size: 4rem; opacity: 0.2;">📚</div>
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
            <div class="col-md-3">
                <label class="form-label">
                    <i class="bi bi-search me-2"></i>Pencarian
                </label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Nama peminjam atau judul buku..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">
                    <i class="bi bi-funnel me-2"></i>Status
                </label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">
                    <i class="bi bi-person me-2"></i>Tipe Peminjam
                </label>
                <select name="role" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="pengguna_luar" {{ request('role') == 'pengguna_luar' ? 'selected' : '' }}>Pengguna Luar</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">
                    <i class="bi bi-calendar me-2"></i>Dari Tanggal
                </label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-2"></i>Filter
                </button>
                <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
                <a href="{{ route('petugas.peminjaman.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Tambah
                </a>
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
                            <i class="bi bi-clock-fill me-1"></i>{{ $item->durasi_hari }} Hari
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
                                <small class="text-muted d-block">Petugas</small>
                            </div>
                        @else
                            <span class="badge bg-secondary">
                                <i class="bi bi-cpu"></i> Sistem
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
                            <p class="text-muted">Gunakan filter atau tambah data peminjaman baru</p>
                            <a href="{{ route('petugas.peminjaman.create') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Peminjaman Baru
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
    <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 2px solid #f1f5f9;">
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

<!-- Quick Actions Floating Button -->
<div class="quick-actions" style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">
    <a href="{{ route('petugas.peminjaman.create') }}" 
       class="btn btn-lg shadow-lg"
       style="
           width: 60px; 
           height: 60px; 
           border-radius: 50%; 
           background: linear-gradient(135deg, var(--primary), var(--secondary));
           display: flex;
           align-items: center;
           justify-content: center;
           color: white;
           text-decoration: none;
           transition: all 0.3s;
           box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
       "
       onmouseover="this.style.transform='scale(1.1) rotate(90deg)'"
       onmouseout="this.style.transform='scale(1) rotate(0deg)'"
       title="Tambah Peminjaman">
        <i class="bi bi-plus-lg" style="font-size: 1.8rem;"></i>
    </a>
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
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.classList.contains('btn-danger')) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                submitBtn.disabled = true;
            }
        });
    });

    // Smooth scroll for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add animation to stats cards on load
    window.addEventListener('load', function() {
        const statsCards = document.querySelectorAll('.stats-card');
        statsCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }, index * 100);
        });
    });

    // Highlight row on hover with subtle animation
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
    });

    // Add confirmation dialog with better styling
    function confirmDelete(event, message) {
        event.preventDefault();
        if (confirm(message || 'Apakah Anda yakin ingin menghapus data ini?')) {
            event.target.closest('form').submit();
        }
    }

    // Toast notification function (optional enhancement)
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <i class="bi bi-check-circle-fill me-2"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>

<style>
    /* Additional hover effects for better interactivity */
    .btn {
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    /* Skeleton loading for better perceived performance */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Print styles */
    @media print {
        .page-header,
        .filter-card,
        .btn-action-group,
        .quick-actions,
        .pagination {
            display: none !important;
        }

        .table-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }

        body {
            background: white;
        }
    }

    /* Dark mode support (optional) */
    @media (prefers-color-scheme: dark) {
        :root {
            --light: #1a202c;
            --dark: #f7fafc;
        }
    }

    /* Accessibility improvements */
    .btn-action:focus,
    .form-control:focus,
    .form-select:focus {
        outline: 3px solid rgba(102, 126, 234, 0.5);
        outline-offset: 2px;
    }

    /* Reduced motion for users who prefer it */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* Custom scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
    }

    /* Tooltip enhancement */
    [title] {
        position: relative;
    }

    /* Badge pulse animation for late returns */
    .badge-status.bg-danger {
        animation: pulse-danger 2s infinite;
    }

    @keyframes pulse-danger {
        0%, 100% {
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }
        50% {
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.6);
        }
    }

    /* Success animation for returned books */
    .badge-status.bg-success {
        animation: success-pop 0.5s ease-out;
    }

    @keyframes success-pop {
        0% {
            transform: scale(0.8);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Filter card expand animation */
    .filter-card {
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Table row stagger animation */
    tbody tr {
        animation: fadeInRow 0.5s ease-out backwards;
    }

    tbody tr:nth-child(1) { animation-delay: 0.05s; }
    tbody tr:nth-child(2) { animation-delay: 0.1s; }
    tbody tr:nth-child(3) { animation-delay: 0.15s; }
    tbody tr:nth-child(4) { animation-delay: 0.2s; }
    tbody tr:nth-child(5) { animation-delay: 0.25s; }

    @keyframes fadeInRow {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Status indicator pulse */
    .badge-status::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 10px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        transform: translateY(-50%);
        opacity: 0.8;
    }

    .badge-status.bg-warning::before {
        animation: pulse-warning 2s infinite;
    }

    @keyframes pulse-warning {
        0%, 100% {
            opacity: 0.8;
            transform: translateY(-50%) scale(1);
        }
        50% {
            opacity: 1;
            transform: translateY(-50%) scale(1.3);
        }
    }
</style>
@endsection