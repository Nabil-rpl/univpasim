@extends('layouts.petugas')

@section('page-title', 'Detail Peminjaman')

@section('content')
<style>
    .detail-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .detail-header {
        padding-bottom: 20px;
        margin-bottom: 25px;
        border-bottom: 2px solid #e2e8f0;
    }

    .info-row {
        display: flex;
        padding: 15px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        width: 200px;
        font-weight: 600;
        color: #64748b;
    }

    .info-value {
        flex: 1;
        color: #1e293b;
        font-weight: 500;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
    }

    .book-cover {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 5px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: white;
        border: 3px solid #2563eb;
    }

    .timeline-item.success::before {
        border-color: #10b981;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-custom {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="row">
    <!-- Informasi Buku -->
    <div class="col-lg-4 mb-4">
        <div class="detail-card">
            <h5 class="mb-3"><i class="bi bi-book me-2"></i>Informasi Buku</h5>
            @if($peminjaman->buku->foto)
                <img src="{{ asset('storage/' . $peminjaman->buku->foto) }}" 
                     alt="{{ $peminjaman->buku->judul }}" 
                     class="book-cover mb-3">
            @else
                <div class="book-cover mb-3 d-flex align-items-center justify-content-center bg-light">
                    <i class="bi bi-book" style="font-size: 4rem; color: #cbd5e1;"></i>
                </div>
            @endif
            
            <h5 class="mb-2">{{ $peminjaman->buku->judul }}</h5>
            <p class="text-muted mb-1">
                <i class="bi bi-person me-2"></i>{{ $peminjaman->buku->penulis }}
            </p>
            <p class="text-muted mb-1">
                <i class="bi bi-building me-2"></i>{{ $peminjaman->buku->penerbit }}
            </p>
            <p class="text-muted mb-0">
                <i class="bi bi-calendar me-2"></i>{{ $peminjaman->buku->tahun_terbit }}
            </p>
        </div>
    </div>

    <!-- Detail Peminjaman -->
    <div class="col-lg-8">
        <div class="detail-card">
            <div class="detail-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-file-text me-2"></i>Detail Peminjaman</h4>
                    @if($peminjaman->status == 'dipinjam')
                        @php
                            $hariPinjam = $peminjaman->tanggal_pinjam->diffInDays(now());
                            $badgeClass = $hariPinjam > 7 ? 'bg-danger' : 'bg-warning text-dark';
                        @endphp
                        <span class="status-badge {{ $badgeClass }}">
                            <i class="bi bi-hourglass-split me-1"></i>Sedang Dipinjam ({{ $hariPinjam }} hari)
                        </span>
                    @else
                        <span class="status-badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Sudah Dikembalikan
                        </span>
                    @endif
                </div>
            </div>

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

            <!-- Data Mahasiswa -->
            <h6 class="text-primary mb-3"><i class="bi bi-person-badge me-2"></i>Data Peminjam</h6>
            <div class="info-row">
                <div class="info-label">Nama Mahasiswa</div>
                <div class="info-value">{{ $peminjaman->mahasiswa->nama }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NIM</div>
                <div class="info-value">{{ $peminjaman->mahasiswa->nim }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jurusan</div>
                <div class="info-value">{{ $peminjaman->mahasiswa->jurusan ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $peminjaman->mahasiswa->email }}</div>
            </div>

            <!-- Data Peminjaman -->
            <h6 class="text-primary mb-3 mt-4"><i class="bi bi-calendar-check me-2"></i>Data Peminjaman</h6>
            <div class="info-row">
                <div class="info-label">Tanggal Pinjam</div>
                <div class="info-value">
                    <i class="bi bi-calendar3 me-2"></i>
                    {{ $peminjaman->tanggal_pinjam->format('d F Y') }}
                    <span class="text-muted">({{ $peminjaman->tanggal_pinjam->diffForHumans() }})</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Kembali</div>
                <div class="info-value">
                    @if($peminjaman->tanggal_kembali)
                        <i class="bi bi-calendar-check me-2"></i>
                        {{ $peminjaman->tanggal_kembali->format('d F Y') }}
                        <span class="text-muted">({{ $peminjaman->tanggal_kembali->diffForHumans() }})</span>
                    @else
                        <span class="text-muted">Belum dikembalikan</span>
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Durasi Pinjam</div>
                <div class="info-value">
                    @if($peminjaman->status == 'dipinjam')
                        {{ $peminjaman->tanggal_pinjam->diffInDays(now()) }} hari
                    @else
                        {{ $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali) }} hari
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Petugas</div>
                <div class="info-value">
                    @if($peminjaman->petugas)
                        <i class="bi bi-person-check me-2"></i>{{ $peminjaman->petugas->name }}
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            @if($peminjaman->status == 'dikembalikan')
            <h6 class="text-primary mb-3 mt-4"><i class="bi bi-clock-history me-2"></i>Timeline</h6>
            <div class="timeline">
                <div class="timeline-item">
                    <strong>Buku Dipinjam</strong>
                    <p class="text-muted mb-0">{{ $peminjaman->tanggal_pinjam->format('d F Y, H:i') }}</p>
                </div>
                <div class="timeline-item success">
                    <strong>Buku Dikembalikan</strong>
                    <p class="text-muted mb-0">{{ $peminjaman->tanggal_kembali->format('d F Y, H:i') }}</p>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons mt-4">
                <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary btn-custom">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>

                @if($peminjaman->status == 'dipinjam')
                <form action="{{ route('petugas.peminjaman.kembalikan', $peminjaman->id) }}" 
                      method="POST" class="d-inline"
                      onsubmit="return confirm('Konfirmasi pengembalian buku oleh {{ $peminjaman->mahasiswa->nama }}?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success btn-custom">
                        <i class="bi bi-check-circle me-2"></i>Kembalikan Buku
                    </button>
                </form>
                @endif

                <form action="{{ route('petugas.peminjaman.destroy', $peminjaman->id) }}" 
                      method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-custom">
                        <i class="bi bi-trash me-2"></i>Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection