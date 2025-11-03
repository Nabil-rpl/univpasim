@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<style>
    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .detail-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        color: white;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
    }

    .info-section {
        background: #f8fafc;
        border-radius: 8px;
        padding: 15px;
        border-left: 3px solid #667eea;
    }

    .info-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #e2e8f0;
        align-items: center;
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-row:first-child {
        padding-top: 0;
    }

    .info-label {
        font-weight: 600;
        color: #64748b;
        min-width: 140px;
        flex-shrink: 0;
        font-size: 0.9rem;
    }

    .info-label i {
        margin-right: 6px;
        color: #667eea;
        font-size: 0.9rem;
    }

    .info-value {
        flex: 1;
        color: #1e293b;
        font-size: 0.9rem;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
    }

    .card-title i {
        color: #667eea;
        margin-right: 8px;
    }

    .avatar-circle {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 12px;
    }

    .timeline-item {
        position: relative;
        padding-left: 35px;
        padding-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 13px;
        top: 28px;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .timeline-content h6 {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .timeline-content p {
        font-size: 0.85rem;
        margin-bottom: 4px;
    }

    .timeline-content small {
        font-size: 0.8rem;
    }

    .alert-custom {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .alert-warning-custom {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        color: #92400e;
    }

    .alert-danger-custom {
        background: #fee2e2;
        border-left: 4px solid #ef4444;
        color: #991b1b;
    }

    .btn-back {
        background: white;
        border: 2px solid white;
        padding: 8px 20px;
        border-radius: 8px;
        color: #667eea;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-back:hover {
        background: transparent;
        color: white;
    }

    .badge-custom {
        padding: 5px 12px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .id-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        font-size: 0.95rem;
        display: inline-block;
    }

    .denda-alert {
        background: #fee2e2;
        border-left: 3px solid #ef4444;
        padding: 10px 12px;
        border-radius: 6px;
        margin-top: 8px;
        font-size: 0.85rem;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">
                    <i class="bi bi-file-text-fill me-2"></i>Detail Peminjaman
                </h4>
                <p class="mb-0 opacity-90" style="font-size: 0.9rem;">Informasi lengkap transaksi peminjaman buku</p>
            </div>
            <div>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Status Alert -->
    @if($peminjaman->status == 'dipinjam' && $peminjaman->isTerlambat())
        @php
            $hariTerlambat = $peminjaman->getHariTerlambat();
            $denda = $peminjaman->hitungDenda();
        @endphp
        <div class="alert alert-danger-custom alert-custom">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 1.5rem;"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">Peminjaman Terlambat!</strong>
                    <p class="mb-1" style="font-size: 0.9rem;">
                        Terlambat <strong>{{ $hariTerlambat }} hari</strong> sejak {{ $peminjaman->tanggal_deadline->format('d M Y') }}
                    </p>
                    <div class="badge bg-danger">
                        <i class="bi bi-cash-coin me-1"></i>
                        Denda: Rp {{ number_format($denda, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    @elseif($peminjaman->status == 'dipinjam')
        <div class="alert alert-warning-custom alert-custom">
            <div class="d-flex align-items-start">
                <i class="bi bi-hourglass-split me-3" style="font-size: 1.5rem;"></i>
                <div>
                    <strong class="d-block mb-1">Buku Sedang Dipinjam</strong>
                    <p class="mb-0" style="font-size: 0.9rem;">
                        Deadline: <strong>{{ $peminjaman->tanggal_deadline ? $peminjaman->tanggal_deadline->format('d M Y') : '-' }}</strong>
                        @if($peminjaman->tanggal_deadline)
                            <span class="badge bg-warning text-dark ms-1">{{ $peminjaman->tanggal_deadline->diffForHumans() }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Informasi Peminjam -->
        <div class="col-lg-4 mb-3">
            <div class="detail-card">
                <h6 class="card-title">
                    <i class="bi bi-person-circle"></i>Informasi Peminjam
                </h6>
                
                <div class="text-center mb-3">
                    @if($peminjaman->mahasiswa->role === 'mahasiswa')
                        <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                    @else
                        <div class="avatar-circle bg-info bg-opacity-10 text-info">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif

                    <h5 class="mb-2">{{ $peminjaman->mahasiswa->name }}</h5>
                    
                    @if($peminjaman->mahasiswa->role === 'mahasiswa')
                        <span class="badge badge-custom bg-primary">
                            <i class="bi bi-mortarboard me-1"></i>Mahasiswa
                        </span>
                    @else
                        <span class="badge badge-custom bg-info">
                            <i class="bi bi-person-circle me-1"></i>Pengguna Luar
                        </span>
                    @endif
                </div>

                <div class="info-section">
                    @if($peminjaman->mahasiswa->role === 'mahasiswa')
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-card-text"></i>NIM
                            </div>
                            <div class="info-value">
                                @if($peminjaman->mahasiswa->mahasiswa)
                                    {{ $peminjaman->mahasiswa->mahasiswa->nim }}
                                @elseif($peminjaman->mahasiswa->nim)
                                    {{ $peminjaman->mahasiswa->nim }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-envelope"></i>Email
                        </div>
                        <div class="info-value">
                            {{ $peminjaman->mahasiswa->email ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-telephone"></i>No. Telepon
                        </div>
                        <div class="info-value">
                            {{ $peminjaman->mahasiswa->no_hp ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Buku -->
        <div class="col-lg-4 mb-3">
            <div class="detail-card">
                <h6 class="card-title">
                    <i class="bi bi-book"></i>Informasi Buku
                </h6>

                <div class="text-center mb-3">
                    <div class="avatar-circle bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <h5 class="mb-1">{{ $peminjaman->buku->judul }}</h5>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">{{ $peminjaman->buku->penulis }}</p>
                </div>

                <div class="info-section">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-building"></i>Penerbit
                        </div>
                        <div class="info-value">
                            {{ $peminjaman->buku->penerbit ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-calendar"></i>Tahun
                        </div>
                        <div class="info-value">
                            {{ $peminjaman->buku->tahun_terbit ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-tag"></i>Kategori
                        </div>
                        <div class="info-value">
                            @if($peminjaman->buku->kategori)
                                <span class="badge bg-secondary badge-custom">{{ $peminjaman->buku->kategori }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-hash"></i>ISBN
                        </div>
                        <div class="info-value">
                            {{ $peminjaman->buku->isbn ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-stack"></i>Stok
                        </div>
                        <div class="info-value">
                            <span class="badge badge-custom {{ $peminjaman->buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $peminjaman->buku->stok }} buku
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Peminjaman -->
        <div class="col-lg-4 mb-3">
            <div class="detail-card">
                <h6 class="card-title">
                    <i class="bi bi-clipboard-data"></i>Detail Peminjaman
                </h6>

                <div class="text-center mb-3">
                    <div class="id-badge">
                        #{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}
                    </div>
                </div>

                <div class="info-section">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-calendar-plus"></i>Tanggal Pinjam
                        </div>
                        <div class="info-value">
                            {{ $peminjaman->tanggal_pinjam->format('d M Y, H:i') }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-hourglass"></i>Durasi
                        </div>
                        <div class="info-value">
                            <span class="badge badge-custom bg-info">{{ $peminjaman->durasi_hari }} Hari</span>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-calendar-x"></i>Deadline
                        </div>
                        <div class="info-value">
                            @if($peminjaman->tanggal_deadline)
                                <span class="{{ $peminjaman->isTerlambat() ? 'text-danger fw-bold' : '' }}">
                                    {{ $peminjaman->tanggal_deadline->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-calendar-check"></i>Tgl Kembali
                        </div>
                        <div class="info-value">
                            @if($peminjaman->tanggal_kembali)
                                <span class="text-success">
                                    {{ $peminjaman->tanggal_kembali->format('d M Y, H:i') }}
                                </span>
                            @else
                                <span class="badge badge-custom bg-warning">Belum kembali</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-flag"></i>Status
                        </div>
                        <div class="info-value">
                            @if($peminjaman->status == 'dipinjam')
                                @if($peminjaman->isTerlambat())
                                    <span class="status-badge bg-danger text-white">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Terlambat
                                    </span>
                                @else
                                    <span class="status-badge bg-warning text-white">
                                        <i class="bi bi-hourglass-split me-1"></i>Dipinjam
                                    </span>
                                @endif
                            @else
                                <span class="status-badge bg-success text-white">
                                    <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($peminjaman->status == 'dipinjam' && $peminjaman->isTerlambat())
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-exclamation-circle"></i>Terlambat
                            </div>
                            <div class="info-value">
                                <span class="badge badge-custom bg-danger">{{ $peminjaman->getHariTerlambat() }} Hari</span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-cash-coin"></i>Denda
                            </div>
                            <div class="info-value">
                                <strong class="text-danger">
                                    Rp {{ number_format($peminjaman->hitungDenda(), 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                    @endif

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-person-badge"></i>Petugas
                        </div>
                        <div class="info-value">
                            @if($peminjaman->petugas)
                                {{ $peminjaman->petugas->name }}
                            @else
                                <span class="badge badge-custom bg-secondary">Sistem</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="row">
        <div class="col-12">
            <div class="detail-card">
                <h6 class="card-title">
                    <i class="bi bi-clock-history"></i>Riwayat Timeline
                </h6>

                <div class="timeline">
                    <!-- Peminjaman dibuat -->
                    <div class="timeline-item">
                        <div class="timeline-icon bg-primary">
                            <i class="bi bi-plus-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>Peminjaman Dibuat</h6>
                            <p class="text-muted">{{ $peminjaman->created_at->format('d M Y, H:i') }} WIB</p>
                            <small class="text-muted">
                                Dicatat oleh {{ $peminjaman->petugas ? $peminjaman->petugas->name : 'Sistem' }}
                            </small>
                        </div>
                    </div>

                    <!-- Buku dipinjam -->
                    <div class="timeline-item">
                        <div class="timeline-icon bg-info">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>Buku Dipinjam</h6>
                            <p class="text-muted">{{ $peminjaman->tanggal_pinjam->format('d M Y, H:i') }} WIB</p>
                            <small class="text-muted">
                                {{ $peminjaman->mahasiswa->name }} meminjam "{{ $peminjaman->buku->judul }}"
                            </small>
                        </div>
                    </div>

                    <!-- Deadline -->
                    @if($peminjaman->tanggal_deadline)
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $peminjaman->isTerlambat() ? 'bg-danger' : 'bg-warning' }}">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="{{ $peminjaman->isTerlambat() ? 'text-danger' : '' }}">Batas Pengembalian</h6>
                            <p class="text-muted">{{ $peminjaman->tanggal_deadline->format('d M Y') }}</p>
                            <small class="text-muted">
                                Durasi: {{ $peminjaman->durasi_hari }} hari
                                @if($peminjaman->isTerlambat() && $peminjaman->status == 'dipinjam')
                                    <span class="badge badge-custom bg-danger ms-1">
                                        Terlambat {{ $peminjaman->getHariTerlambat() }} hari
                                    </span>
                                @endif
                            </small>
                        </div>
                    </div>
                    @endif

                    <!-- Pengembalian -->
                    @if($peminjaman->tanggal_kembali)
                    <div class="timeline-item">
                        <div class="timeline-icon bg-success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="text-success">Buku Dikembalikan</h6>
                            <p class="text-muted">{{ $peminjaman->tanggal_kembali->format('d M Y, H:i') }} WIB</p>
                            <small class="text-muted">Buku dikembalikan dalam kondisi baik</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection