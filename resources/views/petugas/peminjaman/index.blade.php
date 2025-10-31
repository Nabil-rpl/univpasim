@extends('layouts.petugas')

@section('page-title', 'Manajemen Peminjaman')

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

    .badge-role {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
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
</style>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Peminjaman</h6>
                    <h2 class="mb-0 fw-bold">{{ $stats['total'] }}</h2>
                </div>
                <div class="stats-icon bg-primary-gradient">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Sedang Dipinjam</h6>
                    <h2 class="mb-0 fw-bold">{{ $stats['dipinjam'] }}</h2>
                </div>
                <div class="stats-icon bg-warning-gradient">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Sudah Dikembalikan</h6>
                    <h2 class="mb-0 fw-bold">{{ $stats['dikembalikan'] }}</h2>
                </div>
                <div class="stats-icon bg-success-gradient">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Terlambat</h6>
                    <h2 class="mb-0 fw-bold">{{ $stats['terlambat'] }}</h2>
                </div>
                <div class="stats-icon bg-danger-gradient">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="filter-card">
    <form method="GET" action="{{ route('petugas.peminjaman.index') }}">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Pencarian</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Nama peminjam atau judul buku..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tipe Peminjam</label>
                <select name="role" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="pengguna_luar" {{ request('role') == 'pengguna_luar' ? 'selected' : '' }}>Pengguna Luar</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-2"></i>Filter
                </button>
                <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
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

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Table -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Durasi & Deadline</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Petugas</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $index => $item)
                <tr>
                    <td>{{ $peminjamans->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <strong>{{ $item->mahasiswa->name }}</strong>
                                
                                @if($item->mahasiswa->role == 'mahasiswa')
                                    <span class="badge badge-role bg-primary">
                                        <i class="bi bi-mortarboard-fill me-1"></i>Mahasiswa
                                    </span>
                                    <br>
                                    <small class="text-muted">NIM: {{ $item->mahasiswa->nim ?? '-' }}</small>
                                @elseif($item->mahasiswa->role == 'pengguna_luar')
                                    <span class="badge badge-role bg-info">
                                        <i class="bi bi-person-fill me-1"></i>Pengguna Luar
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-telephone me-1"></i>{{ $item->mahasiswa->no_hp ?? '-' }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $item->buku->judul }}</strong><br>
                        <small class="text-muted">{{ $item->buku->penulis }}</small>
                    </td>
                    <td>
                        <strong>{{ $item->tanggal_pinjam->format('d M Y') }}</strong><br>
                        <small class="text-muted">{{ $item->tanggal_pinjam->format('H:i') }}</small>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $item->durasi_hari }} Hari</span><br>
                        @if($item->tanggal_deadline)
                            <small class="deadline-info {{ $item->isTerlambat() ? 'text-danger' : 'text-muted' }}">
                                <i class="bi bi-calendar-x me-1"></i>
                                {{ $item->tanggal_deadline->format('d M Y') }}
                            </small>
                        @endif
                    </td>
                    <td>
                        @if($item->tanggal_kembali)
                            <strong>{{ $item->tanggal_kembali->format('d M Y') }}</strong><br>
                            <small class="text-muted">{{ $item->tanggal_kembali->format('H:i') }}</small>
                        @else
                            <span class="text-muted">Belum dikembalikan</span>
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
                                <small class="text-danger fw-bold">
                                    Denda: Rp {{ number_format($denda, 0, ',', '.') }}
                                </small>
                            @else
                                <span class="badge badge-status bg-warning">
                                    <i class="bi bi-hourglass-split me-1"></i>Dipinjam
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
                            <strong>{{ $item->petugas->name }}</strong>
                        @else
                            <span class="badge bg-secondary">Sistem</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('petugas.peminjaman.show', $item->id) }}" 
                               class="btn btn-sm btn-info btn-action" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            @if($item->status == 'dipinjam')
                            <a href="{{ route('petugas.pengembalian.show', $item->id) }}" 
                               class="btn btn-sm btn-success btn-action" title="Proses Pengembalian">
                                <i class="bi bi-box-arrow-in-down"></i>
                            </a>
                            @endif
                            
                            <form action="{{ route('petugas.peminjaman.destroy', $item->id) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-action" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
                        <p class="text-muted mt-2 mb-0">Tidak ada data peminjaman</p>
                        <small class="text-muted">Gunakan filter atau tambah data peminjaman baru</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($peminjamans->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted">
            Menampilkan {{ $peminjamans->firstItem() ?? 0 }} - {{ $peminjamans->lastItem() ?? 0 }} 
            dari {{ $peminjamans->total() }} data
        </div>
        <div>
            {{ $peminjamans->links() }}
        </div>
    </div>
    @endif
</div>
@endsection