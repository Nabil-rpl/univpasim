@extends('layouts.petugas')

@section('page-title', 'Riwayat Pengembalian')

@section('content')
<style>
    :root {
        --primary: #667eea;
        --success: #48bb78;
        --danger: #f56565;
        --warning: #ed8936;
        --info: #4299e1;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-500: #6b7280;
        --dark: #2d3748;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .page-header {
        background: linear-gradient(135deg, #48bb78, #38a169);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .stats-card {
        border-radius: 15px;
        padding: 25px;
        background: white;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s;
        border-left: 5px solid;
        margin-bottom: 20px;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .stats-card.success { border-left-color: #10b981; }
    .stats-card.danger { border-left-color: #ef4444; }
    .stats-card.warning { border-left-color: #f59e0b; }
    .stats-card.info { border-left-color: #3b82f6; }

    .filter-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 25px;
    }

    .table-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: var(--shadow-sm);
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
        border: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-action.btn-warning {
        background: linear-gradient(135deg, var(--warning), #dd6b20);
        color: white;
    }

    .btn-action.btn-info {
        background: linear-gradient(135deg, var(--info), #3182ce);
        color: white;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1>
        <i class="bi bi-clock-history me-2"></i>
        Riwayat Pengembalian
    </h1>
    <p class="mb-0">Semua data pengembalian buku yang telah diproses</p>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Pengembalian</h6>
                    <h2 class="mb-0 fw-bold">{{ $stats['total'] }}</h2>
                </div>
                <div style="font-size: 2.5rem; opacity: 0.3;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Bulan Ini</h6>
                    <h2 class="mb-0 fw-bold">{{ $stats['bulan_ini'] }}</h2>
                </div>
                <div style="font-size: 2.5rem; opacity: 0.3;">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Denda</h6>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</h3>
                </div>
                <div style="font-size: 2.5rem; opacity: 0.3;">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Denda Belum Lunas</h6>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($stats['denda_belum_lunas'], 0, ',', '.') }}</h3>
                </div>
                <div style="font-size: 2.5rem; opacity: 0.3;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="filter-card">
    <form method="GET" action="{{ route('petugas.pengembalian.riwayat') }}">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Pencarian</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Nama, NIM, atau judul buku..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tipe Peminjam</label>
                <select name="role" class="form-select">
                    <option value="">Semua</option>
                    <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="pengguna_luar" {{ request('role') == 'pengguna_luar' ? 'selected' : '' }}>Pengguna Luar</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status Denda</label>
                <select name="status_denda" class="form-select">
                    <option value="">Semua</option>
                    <option value="lunas" {{ request('status_denda') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="belum_lunas" {{ request('status_denda') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            
            <!-- ✅ TOMBOL DENGAN LAYOUT BARU -->
            <div class="col-md-12 mt-2">
                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('petugas.pengembalian.riwayat') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                    
                    <!-- ✅ TOMBOL KEMBALI KE PENGEMBALIAN AKTIF -->
                    <a href="{{ route('petugas.pengembalian.index') }}" class="btn btn-success">
                        <i class="bi bi-arrow-left"></i> Kembali ke Pengembalian Aktif
                    </a>
                </div>
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

<!-- Table -->
<div class="table-card">
    <h5 class="mb-3">
        <i class="bi bi-list-check me-2"></i>Daftar Riwayat Pengembalian
    </h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Denda</th>
                    <th>Status Pembayaran</th>
                    <th>Petugas</th>
                    <th width="10%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalian as $index => $item)
                <tr>
                    <td>{{ $pengembalian->firstItem() + $index }}</td>
                    <td>
                        <strong>{{ $item->peminjaman->mahasiswa->name }}</strong><br>
                        @if($item->peminjaman->mahasiswa->role == 'mahasiswa')
                            <small class="text-muted">NIM: {{ $item->peminjaman->mahasiswa->nim }}</small>
                        @else
                            <small class="text-muted">{{ $item->peminjaman->mahasiswa->no_hp }}</small>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $item->peminjaman->buku->judul }}</strong><br>
                        <small class="text-muted">{{ $item->peminjaman->buku->penulis }}</small>
                    </td>
                    <td>
                        <strong>{{ $item->peminjaman->tanggal_pinjam->format('d M Y') }}</strong><br>
                        <small class="text-muted">{{ $item->peminjaman->tanggal_pinjam->format('H:i') }}</small>
                    </td>
                    <td>
                        <strong>{{ $item->tanggal_pengembalian->format('d M Y') }}</strong><br>
                        <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                    </td>
                    <td>
                        @if($item->denda > 0)
                            <strong class="text-danger">Rp {{ number_format($item->denda, 0, ',', '.') }}</strong>
                        @else
                            <span class="badge bg-success">Rp 0</span>
                        @endif
                    </td>
                    <td>
                        @if($item->denda > 0)
                            @if($item->denda_dibayar)
                                <span class="badge badge-status bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Lunas
                                </span>
                                @if($item->denda_dibayar_pada)
                                    <br><small class="text-muted">{{ $item->denda_dibayar_pada->format('d M Y H:i') }}</small>
                                @endif
                            @else
                                <span class="badge badge-status bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>Belum Lunas
                                </span>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($item->petugas)
                            <strong>{{ $item->petugas->name }}</strong>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('petugas.peminjaman.show', $item->peminjaman_id) }}" 
                               class="btn btn-sm btn-action btn-info" 
                               title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            @if($item->denda > 0 && !$item->denda_dibayar)
                                <a href="{{ route('petugas.pengembalian.edit-denda', $item->id) }}" 
                                   class="btn btn-sm btn-action btn-warning" 
                                   title="Konfirmasi Pembayaran">
                                    <i class="bi bi-cash-coin"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada riwayat pengembalian</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pengembalian->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted">
            Menampilkan {{ $pengembalian->firstItem() ?? 0 }} - {{ $pengembalian->lastItem() ?? 0 }} 
            dari {{ $pengembalian->total() }} data
        </div>
        <div>
            {{ $pengembalian->links() }}
        </div>
    </div>
    @endif
</div>
@endsection