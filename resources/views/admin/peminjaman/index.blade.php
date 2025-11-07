@extends('layouts.app')

@section('title', 'Data Peminjaman Buku')

@section('content')
<style>
    .stats-card {
        border-radius: 15px;
        padding: 25px;
        background: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        border-left: 5px solid;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .stats-card.primary { border-left-color: #2563eb; }
    .stats-card.success { border-left-color: #10b981; }
    .stats-card.warning { border-left-color: #f59e0b; }
    .stats-card.danger { border-left-color: #ef4444; }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
    }

    .bg-primary-gradient { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); }
    .bg-success-gradient { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .bg-warning-gradient { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .bg-danger-gradient { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

    .filter-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .table-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .deadline-info {
        font-size: 0.75rem;
        margin-top: 2px;
        display: block;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transition: background-color 0.3s;
    }

    .alert {
        border-radius: 12px;
        border: none;
    }

    .user-type-badge {
        font-size: 0.65rem;
        padding: 2px 6px;
        border-radius: 8px;
        margin-left: 5px;
        white-space: nowrap;
    }

    .btn-detail {
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 5px 12px;
        transition: all 0.3s;
        border: 1px solid #667eea;
        color: #667eea;
        background: white;
        white-space: nowrap;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        background: #667eea;
        color: white;
    }

    /* Table styling improvements */
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    .table thead th {
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .info-row {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }

    .info-value {
        font-size: 0.9rem;
        color: #1e293b;
        font-weight: 600;
    }

    .user-info-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-details {
        display: flex;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .user-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    .user-meta {
        font-size: 0.75rem;
        color: #64748b;
    }

    .book-info-container {
        display: flex;
        align-items: start;
        gap: 10px;
    }

    .book-details {
        display: flex;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .book-title {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
        line-height: 1.3;
    }

    .book-author {
        font-size: 0.75rem;
        color: #64748b;
    }

    .date-display {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .date-main {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }

    .date-time {
        font-size: 0.75rem;
        color: #64748b;
    }

    .duration-badge {
        display: inline-block;
        margin-bottom: 4px;
    }

    .status-container {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: flex-start;
    }

    .denda-info {
        font-size: 0.75rem;
        font-weight: 600;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .petugas-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .petugas-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .petugas-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }

    .petugas-role {
        font-size: 0.75rem;
        color: #64748b;
    }
</style>

<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-journal-bookmark-fill me-2"></i>Data Peminjaman Buku
                </h3>
                <p class="mb-0 opacity-75">Monitoring semua aktivitas peminjaman buku perpustakaan</p>
            </div>
            <div>
                <span class="badge bg-light text-dark px-3 py-2">
                    <i class="bi bi-eye me-1"></i>Mode: Read Only
                </span>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    @php
        $stats = [
            'total' => $peminjamans->count(),
            'dipinjam' => $peminjamans->where('status', 'dipinjam')->count(),
            'dikembalikan' => $peminjamans->where('status', 'dikembalikan')->count(),
            'terlambat' => $peminjamans->where('status', 'dipinjam')->filter(function($item) {
                return $item->isTerlambat();
            })->count()
        ];
    @endphp

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Peminjaman</h6>
                        <h2 class="mb-0 fw-bold">{{ $stats['total'] }}</h2>
                        <small class="text-muted">Seluruh data</small>
                    </div>
                    <div class="stats-icon bg-primary-gradient">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Sedang Dipinjam</h6>
                        <h2 class="mb-0 fw-bold">{{ $stats['dipinjam'] }}</h2>
                        <small class="text-muted">Aktif saat ini</small>
                    </div>
                    <div class="stats-icon bg-warning-gradient">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Sudah Dikembalikan</h6>
                        <h2 class="mb-0 fw-bold">{{ $stats['dikembalikan'] }}</h2>
                        <small class="text-muted">Transaksi selesai</small>
                    </div>
                    <div class="stats-icon bg-success-gradient">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Terlambat</h6>
                        <h2 class="mb-0 fw-bold">{{ $stats['terlambat'] }}</h2>
                        <small class="text-muted">Perlu perhatian</small>
                    </div>
                    <div class="stats-icon bg-danger-gradient">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filter & Info -->
    <div class="filter-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <i class="bi bi-funnel text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Filter Data</h5>
                        <p class="text-muted mb-0 small">Menampilkan semua data peminjaman yang tercatat</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="badge bg-info px-3 py-2">
                    <i class="bi bi-clock-history me-1"></i>
                    Update: {{ now()->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="bi bi-table me-2"></i>Daftar Peminjaman
            </h5>
            <span class="text-muted small">
                Total: <strong>{{ $stats['total'] }}</strong> data
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="3%" class="text-center">No</th>
                        <th width="15%">Peminjam</th>
                        <th width="17%">Buku</th>
                        <th width="11%">Tgl Pinjam</th>
                        <th width="11%">Durasi</th>
                        <th width="11%">Tgl Kembali</th>
                        <th width="13%">Status</th>
                        <th width="12%">Petugas</th>
                        <th width="7%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $index => $item)
                    <tr>
                        <td class="text-center">
                            <span class="fw-bold text-muted">{{ $index + 1 }}</span>
                        </td>
                        
                        <!-- Peminjam -->
                        <td>
                            <div class="user-info-container">
                                @if($item->mahasiswa->role === 'mahasiswa')
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                @else
                                    <div class="avatar-circle bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif
                                <div class="user-details">
                                    <div class="user-name">
                                        <span>{{ $item->mahasiswa->name }}</span>
                                        @if($item->mahasiswa->role === 'mahasiswa')
                                            <span class="user-type-badge bg-primary bg-opacity-10 text-primary">
                                                <i class="bi bi-mortarboard"></i> MHS
                                            </span>
                                        @else
                                            <span class="user-type-badge bg-info bg-opacity-10 text-info">
                                                <i class="bi bi-person-circle"></i> Luar
                                            </span>
                                        @endif
                                    </div>
                                    @if($item->mahasiswa->role === 'mahasiswa')
                                        @if($item->mahasiswa->mahasiswa)
                                            <div class="user-meta">{{ $item->mahasiswa->mahasiswa->nim }}</div>
                                        @elseif($item->mahasiswa->nim)
                                            <div class="user-meta">{{ $item->mahasiswa->nim }}</div>
                                        @else
                                            <div class="user-meta text-muted fst-italic">-</div>
                                        @endif
                                    @else
                                        <div class="user-meta">
                                            <i class="bi bi-telephone-fill me-1"></i>
                                            {{ $item->mahasiswa->no_hp ?? '-' }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Buku -->
                        <td>
                            <div class="book-info-container">
                                <div class="avatar-circle bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-book-fill"></i>
                                </div>
                                <div class="book-details">
                                    <div class="book-title">{{ $item->buku->judul }}</div>
                                    <div class="book-author">{{ $item->buku->penulis }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Tanggal Pinjam -->
                        <td>
                            <div class="date-display">
                                <div class="date-main">
                                    <i class="bi bi-calendar-event text-primary me-1"></i>
                                    {{ $item->tanggal_pinjam->format('d M Y') }}
                                </div>
                                <div class="date-time">{{ $item->tanggal_pinjam->format('H:i') }} WIB</div>
                            </div>
                        </td>

                        <!-- Durasi & Deadline -->
                        <td>
                            <div>
                                @if($item->durasi_hari)
                                    <span class="badge bg-info duration-badge">
                                        <i class="bi bi-clock me-1"></i>{{ $item->durasi_hari }} Hari
                                    </span>
                                @endif
                                @if($item->tanggal_deadline)
                                    <div class="deadline-info {{ $item->isTerlambat() ? 'text-danger fw-bold' : 'text-muted' }}">
                                        <i class="bi bi-calendar-x me-1"></i>
                                        {{ $item->tanggal_deadline->format('d M Y') }}
                                    </div>
                                @else
                                    <div class="text-muted small">-</div>
                                @endif
                            </div>
                        </td>

                        <!-- Tanggal Kembali -->
                        <td>
                            @if($item->tanggal_kembali)
                                <div class="date-display">
                                    <div class="date-main">
                                        <i class="bi bi-calendar-check text-success me-1"></i>
                                        {{ $item->tanggal_kembali->format('d M Y') }}
                                    </div>
                                    <div class="date-time">{{ $item->tanggal_kembali->format('H:i') }} WIB</div>
                                </div>
                            @else
                                <span class="text-muted fst-italic small">
                                    <i class="bi bi-dash-circle me-1"></i>Belum kembali
                                </span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td>
                            <div class="status-container">
                                @if($item->status == 'dipinjam')
                                    @if($item->isTerlambat())
                                        @php
                                            $hariTerlambat = $item->getHariTerlambat();
                                            $denda = $item->hitungDenda();
                                        @endphp
                                        <span class="badge badge-status bg-danger">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Terlambat {{ $hariTerlambat }}h
                                        </span>
                                        <span class="denda-info">
                                            <i class="bi bi-cash-coin"></i>
                                            Rp {{ number_format($denda, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="badge badge-status bg-warning text-dark">
                                            <i class="bi bi-hourglass-split me-1"></i>Dipinjam
                                        </span>
                                    @endif
                                @else
                                    <span class="badge badge-status bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Petugas -->
                        <td>
                            @if($item->petugas)
                                <div class="petugas-container">
                                    <div class="avatar-circle bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </div>
                                    <div class="petugas-details">
                                        <div class="petugas-name">{{ $item->petugas->name }}</div>
                                        <div class="petugas-role">{{ ucfirst($item->petugas->role) }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-gear me-1"></i>Sistem
                                </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="text-center">
                            <a href="{{ route('admin.peminjaman.show', $item->id) }}" 
                               class="btn btn-detail btn-sm"
                               title="Lihat Detail">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 4rem; color: #cbd5e1;"></i>
                            <p class="text-muted mt-3 mb-0 fw-semibold">Tidak ada data peminjaman</p>
                            <small class="text-muted">Belum ada transaksi peminjaman buku yang tercatat</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        @if($peminjamans->count() > 0)
        <div class="mt-4 pt-3 border-top">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center text-muted">
                        <i class="bi bi-info-circle me-2"></i>
                        <span>
                            Menampilkan <strong>{{ $peminjamans->count() }}</strong> dari 
                            <strong>{{ $stats['total'] }}</strong> total peminjaman
                        </span>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="bi bi-shield-check me-1"></i>
                        Data di-monitoring oleh Admin
                    </small>
                </div>
            </div>
        </div>
        @endif
    </div>
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
</script>
@endsection