@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<style>
    .detail-card {
        background: white;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
        transition: all 0.3s ease;
        border: 1px solid rgba(102, 126, 234, 0.08);
    }

    .detail-card:hover {
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.12);
        transform: translateY(-2px);
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 28px;
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
    }

    .info-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 20px;
        border-left: 4px solid #667eea;
    }

    .info-row {
        display: flex;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        gap: 16px;
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-row:first-child {
        padding-top: 0;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #64748b;
        min-width: 200px;
        flex-shrink: 0;
        font-size: 0.95rem;
    }

    .info-label i {
        color: #667eea;
        font-size: 1.1rem;
        width: 24px;
        text-align: center;
    }

    .info-value {
        flex: 1;
        color: #1e293b;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 3px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: #667eea;
        font-size: 1.3rem;
    }

    .avatar-display {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        flex-shrink: 0;
    }

    .avatar-info h5 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .avatar-info p {
        margin-bottom: 0;
        color: #64748b;
    }

    .timeline-item {
        position: relative;
        padding-left: 45px;
        padding-bottom: 28px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 16px;
        top: 36px;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #667eea 0%, #e2e8f0 100%);
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
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border: 3px solid white;
    }

    .timeline-content h6 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: #1e293b;
    }

    .timeline-content p {
        font-size: 0.9rem;
        margin-bottom: 6px;
        color: #64748b;
        font-weight: 500;
    }

    .timeline-content small {
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .alert-custom {
        padding: 20px;
        border-radius: 14px;
        margin-bottom: 24px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .alert-warning-custom {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 5px solid #f59e0b;
    }

    .alert-warning-custom strong,
    .alert-warning-custom p {
        color: #92400e;
    }

    .alert-danger-custom {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 5px solid #ef4444;
    }

    .alert-danger-custom strong,
    .alert-danger-custom p {
        color: #991b1b;
    }

    .btn-back {
        background: white;
        border: 2px solid white;
        padding: 10px 24px;
        border-radius: 10px;
        color: #667eea;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        transform: translateX(-4px);
    }

    .badge-custom {
        padding: 7px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .id-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 28px;
        border-radius: 25px;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        font-size: 1.2rem;
        display: inline-block;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        letter-spacing: 1px;
    }

    .status-badge {
        padding: 8px 18px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .denda-display {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 4px solid #ef4444;
        padding: 12px 16px;
        border-radius: 10px;
        display: inline-block;
    }

    .denda-display strong {
        color: #991b1b;
        font-size: 1.1rem;
    }

    /* Card specific styling */
    .peminjam-card {
        border-top: 5px solid #3b82f6;
    }

    .buku-card {
        border-top: 5px solid #f59e0b;
    }

    .detail-card-main {
        border-top: 5px solid #8b5cf6;
    }

    .timeline-card {
        border-top: 5px solid #10b981;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .info-label {
            min-width: 150px;
            font-size: 0.9rem;
        }
        
        .avatar-display {
            flex-direction: column;
            text-align: center;
        }
        
        .avatar-circle {
            margin: 0 auto;
        }
    }
</style>

<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-2 fw-bold">
                    <i class="bi bi-file-text-fill me-2"></i>Detail Peminjaman
                </h3>
                <p class="mb-0 opacity-90" style="font-size: 0.95rem;">Informasi lengkap transaksi peminjaman buku perpustakaan</p>
            </div>
            <div>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Data Buku
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
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem; color: #dc2626;"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-2" style="font-size: 1.1rem;">⚠️ Peminjaman Terlambat!</strong>
                    <p class="mb-2" style="font-size: 0.95rem;">
                        Terlambat <strong>{{ $hariTerlambat }} hari</strong> sejak batas pengembalian {{ $peminjaman->tanggal_deadline->format('d M Y') }}
                    </p>
                    <div class="badge bg-danger badge-custom">
                        <i class="bi bi-cash-coin me-2"></i>
                        Total Denda: Rp {{ number_format($denda, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    @elseif($peminjaman->status == 'dipinjam')
        <div class="alert alert-warning-custom alert-custom">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-hourglass-split" style="font-size: 2rem; color: #d97706;"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-2" style="font-size: 1.1rem;">📚 Buku Sedang Dipinjam</strong>
                    <p class="mb-2" style="font-size: 0.95rem;">
                        Batas pengembalian: <strong>{{ $peminjaman->tanggal_deadline ? $peminjaman->tanggal_deadline->format('d M Y') : '-' }}</strong>
                        @if($peminjaman->tanggal_deadline)
                            <span class="badge bg-warning text-dark ms-2">{{ $peminjaman->tanggal_deadline->diffForHumans() }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- ID Transaksi -->
    <div class="detail-card" style="text-align: center; border-top: 5px solid #8b5cf6;">
        <div class="id-badge">
            #{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}
        </div>
        <p class="text-muted mb-0 mt-2">ID Transaksi Peminjaman</p>
    </div>

    <!-- Informasi Peminjam -->
    <div class="detail-card peminjam-card">
        <h6 class="card-title">
            <i class="bi bi-person-circle"></i>
            Informasi Peminjam
        </h6>
        
        <div class="avatar-display">
            @if($peminjaman->mahasiswa->role === 'mahasiswa')
                <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
            @else
                <div class="avatar-circle bg-info bg-opacity-10 text-info">
                    <i class="bi bi-person-fill"></i>
                </div>
            @endif
            
            <div class="avatar-info">
                <h5>{{ $peminjaman->mahasiswa->name }}</h5>
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
        </div>

        <div class="info-section">
            @if($peminjaman->mahasiswa->role === 'mahasiswa')
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-card-text"></i>
                        <span>Nomor Induk Mahasiswa (NIM)</span>
                    </div>
                    <div class="info-value">
                        <strong>
                            @if($peminjaman->mahasiswa->mahasiswa)
                                {{ $peminjaman->mahasiswa->mahasiswa->nim }}
                            @elseif($peminjaman->mahasiswa->nim)
                                {{ $peminjaman->mahasiswa->nim }}
                            @else
                                <span class="text-muted fst-italic">Tidak ada</span>
                            @endif
                        </strong>
                    </div>
                </div>
            @endif

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Alamat Email</span>
                </div>
                <div class="info-value">
                    {{ $peminjaman->mahasiswa->email ?? '-' }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-telephone-fill"></i>
                    <span>Nomor Telepon / HP</span>
                </div>
                <div class="info-value">
                    <strong>{{ $peminjaman->mahasiswa->no_hp ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Buku -->
    <div class="detail-card buku-card">
        <h6 class="card-title">
            <i class="bi bi-book-fill"></i>
            Informasi Buku yang Dipinjam
        </h6>

        <div class="avatar-display">
            <div class="avatar-circle bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-book-fill"></i>
            </div>
            <div class="avatar-info">
                <h5>{{ $peminjaman->buku->judul }}</h5>
                <p>
                    <i class="bi bi-pen me-1"></i>{{ $peminjaman->buku->penulis }}
                </p>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-building"></i>
                    <span>Penerbit</span>
                </div>
                <div class="info-value">
                    {{ $peminjaman->buku->penerbit ?? '-' }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-calendar-event"></i>
                    <span>Tahun Terbit</span>
                </div>
                <div class="info-value">
                    <strong>{{ $peminjaman->buku->tahun_terbit ?? '-' }}</strong>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-tag-fill"></i>
                    <span>Kategori Buku</span>
                </div>
                <div class="info-value">
                    @if($peminjaman->buku->kategori)
                        <span class="badge bg-secondary badge-custom">{{ $peminjaman->buku->kategori }}</span>
                    @else
                        <span class="text-muted fst-italic">-</span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-upc-scan"></i>
                    <span>ISBN (International Standard Book Number)</span>
                </div>
                <div class="info-value">
                    <code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">
                        {{ $peminjaman->buku->isbn ?? '-' }}
                    </code>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-stack"></i>
                    <span>Stok Buku Tersedia</span>
                </div>
                <div class="info-value">
                    <span class="badge badge-custom {{ $peminjaman->buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                        <i class="bi bi-book me-1"></i>{{ $peminjaman->buku->stok }} buku tersedia
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Peminjaman -->
    <div class="detail-card detail-card-main">
        <h6 class="card-title">
            <i class="bi bi-clipboard-data-fill"></i>
            Detail Transaksi Peminjaman
        </h6>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-calendar-plus"></i>
                    <span>Tanggal Peminjaman</span>
                </div>
                <div class="info-value">
                    <strong>{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</strong>
                    <span class="text-muted ms-2">{{ $peminjaman->tanggal_pinjam->format('H:i') }} WIB</span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-hourglass-split"></i>
                    <span>Durasi Peminjaman</span>
                </div>
                <div class="info-value">
                    <span class="badge badge-custom bg-info">
                        <i class="bi bi-clock me-1"></i>{{ $peminjaman->durasi_hari }} Hari
                    </span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-calendar-x"></i>
                    <span>Batas Pengembalian (Deadline)</span>
                </div>
                <div class="info-value">
                    @if($peminjaman->tanggal_deadline)
                        <strong class="{{ $peminjaman->isTerlambat() ? 'text-danger' : 'text-success' }}">
                            {{ $peminjaman->tanggal_deadline->format('d M Y') }}
                        </strong>
                        @if($peminjaman->isTerlambat())
                            <span class="badge badge-custom bg-danger ms-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>Terlewat
                            </span>
                        @endif
                    @else
                        <span class="text-muted fst-italic">-</span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-calendar-check"></i>
                    <span>Tanggal Pengembalian</span>
                </div>
                <div class="info-value">
                    @if($peminjaman->tanggal_kembali)
                        <strong class="text-success">
                            {{ $peminjaman->tanggal_kembali->format('d M Y') }}
                        </strong>
                        <span class="text-muted ms-2">{{ $peminjaman->tanggal_kembali->format('H:i') }} WIB</span>
                    @else
                        <span class="badge badge-custom bg-warning text-dark">
                            <i class="bi bi-dash-circle me-1"></i>Belum dikembalikan
                        </span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-flag-fill"></i>
                    <span>Status Peminjaman</span>
                </div>
                <div class="info-value">
                    @if($peminjaman->status == 'dipinjam')
                        @if($peminjaman->isTerlambat())
                            <span class="status-badge bg-danger text-white">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Terlambat
                            </span>
                        @else
                            <span class="status-badge bg-warning text-white">
                                <i class="bi bi-hourglass-split"></i>
                                Sedang Dipinjam
                            </span>
                        @endif
                    @else
                        <span class="status-badge bg-success text-white">
                            <i class="bi bi-check-circle-fill"></i>
                            Dikembalikan
                        </span>
                    @endif
                </div>
            </div>

            @if($peminjaman->status == 'dipinjam' && $peminjaman->isTerlambat())
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span>Jumlah Hari Keterlambatan</span>
                    </div>
                    <div class="info-value">
                        <span class="badge badge-custom bg-danger">
                            <i class="bi bi-clock-history me-1"></i>{{ $peminjaman->getHariTerlambat() }} Hari Terlambat
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-cash-coin"></i>
                        <span>Total Denda yang Harus Dibayar</span>
                    </div>
                    <div class="info-value">
                        <div class="denda-display">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Rp {{ number_format($peminjaman->hitungDenda(), 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            @endif

            <div class="info-row">
                <div class="info-label">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Petugas yang Melayani</span>
                </div>
                <div class="info-value">
                    @if($peminjaman->petugas)
                        <strong>{{ $peminjaman->petugas->name }}</strong>
                        <span class="text-muted ms-2">({{ ucfirst($peminjaman->petugas->role) }})</span>
                    @else
                        <span class="badge badge-custom bg-secondary">
                            <i class="bi bi-gear-fill me-1"></i>Sistem Otomatis
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="detail-card timeline-card">
        <h6 class="card-title">
            <i class="bi bi-clock-history"></i>
            Riwayat Timeline Peminjaman
        </h6>

        <div class="timeline">
            <!-- Peminjaman dibuat -->
            <div class="timeline-item">
                <div class="timeline-icon bg-primary">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>
                <div class="timeline-content">
                    <h6>📝 Peminjaman Dibuat</h6>
                    <p>{{ $peminjaman->created_at->format('d M Y, H:i') }} WIB</p>
                    <small>
                        <i class="bi bi-person-badge me-1"></i>
                        Dicatat oleh: <strong>{{ $peminjaman->petugas ? $peminjaman->petugas->name : 'Sistem' }}</strong>
                    </small>
                </div>
            </div>

            <!-- Buku dipinjam -->
            <div class="timeline-item">
                <div class="timeline-icon bg-info">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="timeline-content">
                    <h6>📚 Buku Dipinjam</h6>
                    <p>{{ $peminjaman->tanggal_pinjam->format('d M Y, H:i') }} WIB</p>
                    <small>
                        <strong>{{ $peminjaman->mahasiswa->name }}</strong> meminjam 
                        "<em>{{ $peminjaman->buku->judul }}</em>"
                    </small>
                </div>
            </div>

            <!-- Deadline -->
            @if($peminjaman->tanggal_deadline)
            <div class="timeline-item">
                <div class="timeline-icon {{ $peminjaman->isTerlambat() ? 'bg-danger' : 'bg-warning' }}">
                    <i class="bi bi-calendar-x-fill"></i>
                </div>
                <div class="timeline-content">
                    <h6 class="{{ $peminjaman->isTerlambat() ? 'text-danger' : '' }}">
                        ⏰ Batas Pengembalian
                    </h6>
                    <p>{{ $peminjaman->tanggal_deadline->format('d M Y') }}</p>
                    <small>
                        Durasi peminjaman: <strong>{{ $peminjaman->durasi_hari }} hari</strong>
                        @if($peminjaman->isTerlambat() && $peminjaman->status == 'dipinjam')
                            <span class="badge badge-custom bg-danger ms-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>
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
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="timeline-content">
                    <h6 class="text-success">✅ Buku Dikembalikan</h6>
                    <p>{{ $peminjaman->tanggal_kembali->format('d M Y, H:i') }} WIB</p>
                    <small>
                        <i class="bi bi-check2-circle me-1"></i>
                        Buku dikembalikan dalam kondisi baik
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection