@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
<style>
    .stats-card {
        border-radius: 15px;
        padding: 20px;
        background: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border-left: 4px solid;
        margin-bottom: 20px;
    }
    .stats-card.primary { border-left-color: #2563eb; }
    .stats-card.warning { border-left-color: #f59e0b; }
    .stats-card.success { border-left-color: #10b981; }
    
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
</style>

<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1"><i class="bi bi-journal-bookmark-fill me-2"></i>Data Peminjaman</h3>
            <p class="text-muted mb-0">Daftar semua peminjaman buku perpustakaan</p>
        </div>
    </div>

    <!-- Statistik Cards -->
    @php
        $totalPeminjaman = $peminjamans->count();
        $sedangDipinjam = $peminjamans->where('status', 'dipinjam')->count();
        $sudahDikembalikan = $peminjamans->where('status', 'dikembalikan')->count();
    @endphp

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Peminjaman</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalPeminjaman }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-journal-bookmark-fill" style="font-size: 2.5rem; color: #2563eb;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Sedang Dipinjam</h6>
                        <h2 class="mb-0 fw-bold">{{ $sedangDipinjam }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-hourglass-split" style="font-size: 2.5rem; color: #f59e0b;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Sudah Dikembalikan</h6>
                        <h2 class="mb-0 fw-bold">{{ $sudahDikembalikan }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; color: #10b981;"></i>
                    </div>
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
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="4%">No</th>
                        <th width="18%">Mahasiswa</th>
                        <th width="24%">Buku</th>
                        <th width="11%">Tanggal Pinjam</th>
                        <th width="11%">Tanggal Kembali</th>
                        <th width="8%">Durasi</th>
                        <th width="10%">Status</th>
                        <th width="14%">Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $peminjaman)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $peminjaman->mahasiswa->nama ?? '-' }}</strong><br>
                                <small class="text-muted">NIM: {{ $peminjaman->mahasiswa->nim ?? '-' }}</small>
                            </td>
                            <td>
                                <strong>{{ $peminjaman->buku->judul ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $peminjaman->buku->penulis ?? '-' }}</small>
                            </td>
                            <td>
                                <i class="bi bi-calendar-event text-primary me-1"></i>
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}
                            </td>
                            <td>
                                @if($peminjaman->tanggal_kembali)
                                    <i class="bi bi-calendar-check text-success me-1"></i>
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}
                                @else
                                    <span class="text-muted">Belum dikembalikan</span>
                                @endif
                            </td>
                            <td>
                                @if($peminjaman->tanggal_kembali)
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->diffInDays($peminjaman->tanggal_kembali) }} hari
                                @else
                                    @php
                                        $hariPinjam = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->diffInDays(now());
                                    @endphp
                                    <span class="{{ $hariPinjam > 7 ? 'text-danger' : 'text-warning' }} fw-bold">
                                        {{ $hariPinjam }} hari
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($peminjaman->status == 'dipinjam')
                                    @php
                                        $hariPinjam = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->diffInDays(now());
                                        $badgeClass = $hariPinjam > 7 ? 'bg-danger' : 'bg-warning text-dark';
                                    @endphp
                                    <span class="badge badge-status {{ $badgeClass }}">
                                        <i class="bi bi-hourglass-split me-1"></i>Dipinjam
                                    </span>
                                @else
                                    <span class="badge badge-status bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($peminjaman->petugas)
                                    <div>
                                        <i class="bi bi-person-badge me-1 text-primary"></i>
                                        <strong>{{ $peminjaman->petugas->name }}</strong>
                                    </div>
                                    <small class="text-muted">{{ ucfirst($peminjaman->petugas->role) }}</small>
                                @else
                                    <span class="text-muted">
                                        <i class="bi bi-person-x me-1"></i>Sistem
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada data peminjaman</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($peminjamans->count() > 0)
        <div class="mt-3 pt-3 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Total: <strong>{{ $totalPeminjaman }}</strong> peminjaman
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="bi bi-clock-history me-1"></i>
                        Data diperbarui: {{ now()->format('d M Y, H:i') }} WIB
                    </small>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection