@extends('layouts.petugas')

@section('page-title', 'Pengembalian Buku')

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

    .stats-card.warning { border-left-color: #f59e0b; }
    .stats-card.danger { border-left-color: #ef4444; }
    .stats-card.success { border-left-color: #10b981; }

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

    .bg-warning-gradient { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .bg-danger-gradient { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .bg-success-gradient { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }

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

    .btn-action {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.3s;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .deadline-info {
        font-size: 0.75rem;
        margin-top: 2px;
    }

    .book-thumb {
        width: 50px;
        height: 65px;
        object-fit: cover;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .book-placeholder-small {
        width: 50px;
        height: 65px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .priority-high {
        background: #fee2e2;
        border-left: 4px solid #ef4444;
    }

    .priority-medium {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
    }

    .tab-custom {
        border-bottom: 3px solid transparent;
        color: #6b7280;
        padding: 12px 24px;
        transition: all 0.3s;
    }

    .tab-custom.active {
        border-bottom-color: #2563eb;
        color: #2563eb;
        font-weight: 600;
    }

    .tab-custom:hover {
        color: #2563eb;
        background: #eff6ff;
    }
</style>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Menunggu Pengembalian</h6>
                    <h2 class="mb-0 fw-bold">{{ $stats['aktif'] }}</h2>
                    <small class="text-muted">Buku sedang dipinjam</small>
                </div>
                <div class="stats-icon bg-warning-gradient">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="stats-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Terlambat</h6>
                    <h2 class="mb-0 fw-bold">{{ $stats['terlambat'] }}</h2>
                    <small class="text-muted">Melewati deadline</small>
                </div>
                <div class="stats-icon bg-danger-gradient">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Denda</h6>
                    <h2 class="mb-0 fw-bold">Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</h2>
                    <small class="text-muted">Denda tertunggak</small>
                </div>
                <div class="stats-icon bg-success-gradient">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="mb-3">
    <ul class="nav nav-tabs border-0">
        <li class="nav-item">
            <a class="nav-link tab-custom active" href="#semua" data-bs-toggle="tab">
                <i class="bi bi-list-ul me-2"></i>Semua ({{ $stats['aktif'] }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link tab-custom" href="#terlambat" data-bs-toggle="tab">
                <i class="bi bi-exclamation-triangle me-2"></i>Terlambat ({{ $stats['terlambat'] }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link tab-custom" href="#tepat-waktu" data-bs-toggle="tab">
                <i class="bi bi-check-circle me-2"></i>Tepat Waktu
            </a>
        </li>
    </ul>
</div>

<!-- Filter & Search -->
<div class="filter-card">
    <form method="GET" action="{{ route('petugas.pengembalian.index') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Pencarian</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nama mahasiswa, NIM, atau judul buku..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Filter Status</label>
                <select name="filter" class="form-select">
                    <option value="">Semua</option>
                    <option value="terlambat" {{ request('filter') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="tepat_waktu" {{ request('filter') == 'tepat_waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                    <option value="hari_ini" {{ request('filter') == 'hari_ini' ? 'selected' : '' }}>Deadline Hari Ini</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Urutkan</label>
                <select name="sort" class="form-select">
                    <option value="deadline_asc" {{ request('sort') == 'deadline_asc' ? 'selected' : '' }}>Deadline (Terlama)</option>
                    <option value="deadline_desc" {{ request('sort') == 'deadline_desc' ? 'selected' : '' }}>Deadline (Terbaru)</option>
                    <option value="denda_desc" {{ request('sort') == 'denda_desc' ? 'selected' : '' }}>Denda (Tertinggi)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <a href="{{ route('petugas.pengembalian.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                </a>
            </div>
        </div>
    </form>
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
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Table -->
<div class="table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            <i class="bi bi-list-check me-2"></i>Daftar Buku yang Perlu Dikembalikan
        </h5>
        <a href="{{ route('petugas.pengembalian.riwayat') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-clock-history me-2"></i>Lihat Riwayat
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Buku</th>
                    <th>Judul & Mahasiswa</th>
                    <th>Tanggal Pinjam</th>
                    <th>Deadline</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th width="12%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $index => $item)
                <tr class="{{ $item->isTerlambat() ? ($item->getHariTerlambat() > 3 ? 'priority-high' : 'priority-medium') : '' }}">
                    <td>{{ $peminjaman->firstItem() + $index }}</td>
                    <td>
                        @if($item->buku->foto)
                            <img src="{{ asset('storage/' . $item->buku->foto) }}" 
                                 alt="{{ $item->buku->judul }}" 
                                 class="book-thumb">
                        @else
                            <div class="book-placeholder-small">
                                <i class="bi bi-book-fill"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong class="d-block">{{ $item->buku->judul }}</strong>
                        <small class="text-muted">{{ Str::limit($item->buku->penulis, 30) }}</small>
                        <hr class="my-1">
                        <div class="mt-1">
                            <i class="bi bi-person text-primary me-1"></i>
                            <strong>{{ $item->mahasiswa->name }}</strong><br>
                            <small class="text-muted ms-3">NIM: {{ $item->mahasiswa->nim ?? '-' }}</small>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $item->tanggal_pinjam->format('d M Y') }}</strong><br>
                        <small class="text-muted">{{ $item->tanggal_pinjam->diffForHumans() }}</small>
                    </td>
                    <td>
                        @if($item->tanggal_deadline)
                            <strong class="{{ $item->isTerlambat() ? 'text-danger' : 'text-muted' }}">
                                {{ $item->tanggal_deadline->format('d M Y') }}
                            </strong><br>
                            @if($item->isTerlambat())
                                <small class="text-danger">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    Lewat {{ $item->getHariTerlambat() }} hari
                                </small>
                            @else
                                <small class="text-muted">
                                    Sisa {{ $item->tanggal_deadline->diffInDays(now()) }} hari
                                </small>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $item->durasi_hari }} hari</span>
                    </td>
                    <td>
                        @if($item->isTerlambat())
                            @php
                                $hariTerlambat = $item->getHariTerlambat();
                                $badgeClass = $hariTerlambat > 3 ? 'bg-danger' : 'bg-warning';
                            @endphp
                            <span class="badge badge-status {{ $badgeClass }}">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Terlambat
                            </span>
                        @else
                            <span class="badge badge-status bg-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Normal
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($item->isTerlambat())
                            @php
                                $denda = $item->hitungDenda();
                            @endphp
                            <strong class="text-danger d-block">
                                Rp {{ number_format($denda, 0, ',', '.') }}
                            </strong>
                            <small class="text-muted">
                                ({{ $item->getHariTerlambat() }} hari)
                            </small>
                        @else
                            <span class="badge bg-success">Rp 0</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('petugas.pengembalian.show', $item->id) }}" 
                               class="btn btn-sm btn-success btn-action" 
                               title="Proses Pengembalian">
                                <i class="bi bi-box-arrow-in-down me-1"></i>
                                Proses
                            </a>
                            <a href="{{ route('petugas.peminjaman.show', $item->id) }}" 
                               class="btn btn-sm btn-info btn-action" 
                               title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
                        <p class="text-muted mt-3 mb-0">Tidak ada buku yang perlu dikembalikan</p>
                        <small class="text-muted">Semua peminjaman sudah dikembalikan atau belum ada peminjaman aktif</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($peminjaman->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted">
            Menampilkan {{ $peminjaman->firstItem() ?? 0 }} - {{ $peminjaman->lastItem() ?? 0 }} 
            dari {{ $peminjaman->total() }} data
        </div>
        <div>
            {{ $peminjaman->links() }}
        </div>
    </div>
    @endif
</div>

<script>
    // Auto refresh setiap 5 menit untuk update status real-time
    setTimeout(function() {
        location.reload();
    }, 300000); // 5 menit
</script>
@endsection