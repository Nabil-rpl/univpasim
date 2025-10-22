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
    }

    .deadline-info {
        font-size: 0.75rem;
        margin-top: 2px;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Mahasiswa</th>
                        <th width="20%">Buku</th>
                        <th width="12%">Tanggal Pinjam</th>
                        <th width="12%">Durasi & Deadline</th>
                        <th width="12%">Tanggal Kembali</th>
                        <th width="12%">Status</th>
                        <th width="12%">Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $index => $item)
                    <tr>
                        <td class="text-center fw-bold">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <strong>{{ $item->mahasiswa->name }}</strong><br>
                                    <small class="text-muted">NIM: {{ $item->mahasiswa->nim ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-start">
                                <div class="avatar-circle bg-info bg-opacity-10 text-info me-2">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div>
                                    <strong>{{ $item->buku->judul }}</strong><br>
                                    <small class="text-muted">{{ $item->buku->penulis }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <i class="bi bi-calendar-event text-primary me-1"></i>
                            <strong>{{ $item->tanggal_pinjam->format('d M Y') }}</strong><br>
                            <small class="text-muted">{{ $item->tanggal_pinjam->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            @if($item->durasi_hari)
                                <span class="badge bg-info">{{ $item->durasi_hari }} Hari</span><br>
                            @endif
                            @if($item->tanggal_deadline)
                                <small class="deadline-info {{ $item->isTerlambat() ? 'text-danger fw-bold' : 'text-muted' }}">
                                    <i class="bi bi-calendar-x me-1"></i>
                                    {{ $item->tanggal_deadline->format('d M Y') }}
                                </small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            @if($item->tanggal_kembali)
                                <i class="bi bi-calendar-check text-success me-1"></i>
                                <strong>{{ $item->tanggal_kembali->format('d M Y') }}</strong><br>
                                <small class="text-muted">{{ $item->tanggal_kembali->format('H:i') }} WIB</small>
                            @else
                                <span class="text-muted fst-italic">
                                    <i class="bi bi-dash-circle me-1"></i>Belum dikembalikan
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
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Terlambat {{ $hariTerlambat }} hari
                                    </span>
                                    <br>
                                    <small class="text-danger fw-bold mt-1 d-block">
                                        <i class="bi bi-cash-coin me-1"></i>
                                        Denda: Rp {{ number_format($denda, 0, ',', '.') }}
                                    </small>
                                @else
                                    <span class="badge badge-status bg-warning">
                                        <i class="bi bi-hourglass-split me-1"></i>Sedang Dipinjam
                                    </span>
                                @endif
                            @else
                                <span class="badge badge-status bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->petugas)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-success bg-opacity-10 text-success me-2">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $item->petugas->name }}</strong><br>
                                        <small class="text-muted">{{ ucfirst($item->petugas->role) }}</small>
                                    </div>
                                </div>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-gear me-1"></i>Sistem
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
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

<style>
.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transition: background-color 0.3s;
}

.alert {
    border-radius: 12px;
    border: none;
}
</style>

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