@extends('layouts.petugas')

@section('page-title', 'Proses Pengembalian Buku')

@section('content')
<style>
    .detail-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .info-section {
        border-left: 4px solid #2563eb;
        padding-left: 20px;
        margin-bottom: 25px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6b7280;
        min-width: 180px;
    }

    .info-value {
        color: #1f2937;
        font-weight: 500;
        text-align: right;
        flex: 1;
    }

    .book-cover {
        width: 150px;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .book-placeholder {
        width: 150px;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }

    .status-badge-large {
        padding: 12px 24px;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
        display: inline-block;
    }

    .denda-card {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 5px solid #f59e0b;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
    }

    .denda-amount {
        font-size: 2rem;
        font-weight: bold;
        color: #d97706;
    }

    .summary-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }

    .confirm-button {
        padding: 12px 40px;
        font-size: 1.1rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .confirm-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .alert-warning-custom {
        background: #fef3c7;
        border-left: 5px solid #f59e0b;
        border-radius: 10px;
        padding: 15px 20px;
    }

    .alert-danger-custom {
        background: #fee2e2;
        border-left: 5px solid #ef4444;
        border-radius: 10px;
        padding: 15px 20px;
    }

    .timeline-item {
        position: relative;
        padding-left: 30px;
        padding-bottom: 15px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 8px;
        width: 2px;
        height: calc(100% - 8px);
        background: #e5e7eb;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-dot {
        position: absolute;
        left: 0;
        top: 5px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 3px solid #2563eb;
        background: white;
    }
</style>

<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Peminjaman
        </a>
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

    <div class="row">
        <!-- Left Column - Book & Student Info -->
        <div class="col-lg-8">
            <!-- Book Information -->
            <div class="detail-card">
                <h5 class="mb-4">
                    <i class="bi bi-book-fill text-primary me-2"></i>Informasi Buku
                </h5>
                
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        @if($peminjaman->buku->foto)
                            <img src="{{ asset('storage/' . $peminjaman->buku->foto) }}" 
                                 alt="{{ $peminjaman->buku->judul }}" 
                                 class="book-cover">
                        @else
                            <div class="book-placeholder">
                                <i class="bi bi-book-fill"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <div class="info-section">
                            <div class="info-item">
                                <span class="info-label">Judul Buku</span>
                                <span class="info-value"><strong>{{ $peminjaman->buku->judul }}</strong></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Penulis</span>
                                <span class="info-value">{{ $peminjaman->buku->penulis }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Penerbit</span>
                                <span class="info-value">{{ $peminjaman->buku->penerbit }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tahun Terbit</span>
                                <span class="info-value">{{ $peminjaman->buku->tahun_terbit }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Stok Saat Ini</span>
                                <span class="info-value">
                                    <span class="badge bg-info">{{ $peminjaman->buku->stok }} buku</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Information -->
            <div class="detail-card">
                <h5 class="mb-4">
                    <i class="bi bi-person-fill text-success me-2"></i>Informasi Peminjam
                </h5>
                
                <div class="info-section">
                    <div class="info-item">
                        <span class="info-label">Nama Mahasiswa</span>
                        <span class="info-value"><strong>{{ $peminjaman->mahasiswa->name }}</strong></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NIM</span>
                        <span class="info-value">{{ $peminjaman->mahasiswa->nim ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $peminjaman->mahasiswa->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Borrowing History Timeline -->
            <div class="detail-card">
                <h5 class="mb-4">
                    <i class="bi bi-clock-history text-info me-2"></i>Riwayat Peminjaman
                </h5>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div>
                        <strong>Tanggal Pinjam</strong><br>
                        <span class="text-muted">{{ $peminjaman->tanggal_pinjam->format('l, d F Y H:i') }}</span>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot" style="border-color: #f59e0b;"></div>
                    <div>
                        <strong>Deadline Pengembalian</strong><br>
                        <span class="text-muted">{{ $peminjaman->tanggal_deadline->format('l, d F Y') }}</span>
                        <span class="badge bg-warning ms-2">{{ $peminjaman->durasi_hari }} hari</span>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot" style="border-color: #10b981;"></div>
                    <div>
                        <strong>Tanggal Pengembalian (Hari ini)</strong><br>
                        <span class="text-muted">{{ now()->format('l, d F Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Return Summary -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="detail-card text-center">
                <h5 class="mb-3">Status Peminjaman</h5>
                <span class="status-badge-large bg-warning">
                    <i class="bi bi-hourglass-split me-2"></i>Sedang Dipinjam
                </span>
                
                <div class="mt-4">
                    <p class="text-muted mb-1">ID Peminjaman</p>
                    <h4>#{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</h4>
                </div>
            </div>

            <!-- Calculation Summary -->
            <div class="detail-card">
                <h5 class="mb-4">
                    <i class="bi bi-calculator text-warning me-2"></i>Ringkasan Perhitungan
                </h5>

                <div class="summary-box">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Durasi Peminjaman:</span>
                        <strong>{{ $peminjaman->durasi_hari }} hari</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tanggal Deadline:</span>
                        <strong>{{ $peminjaman->tanggal_deadline->format('d M Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Hari Dipinjam:</span>
                        <strong>{{ $peminjaman->tanggal_pinjam->diffInDays(now()) }} hari</strong>
                    </div>

                    <hr>

                    @if($hariTerlambat > 0)
                        <div class="alert-danger-custom mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-exclamation-triangle me-2"></i>Keterlambatan:</span>
                                <strong class="text-danger">{{ $hariTerlambat }} hari</strong>
                            </div>
                        </div>

                        <div class="denda-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="d-block text-muted">Total Denda</small>
                                    <span class="denda-amount">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                                </div>
                                <i class="bi bi-exclamation-circle" style="font-size: 3rem; color: #f59e0b;"></i>
                            </div>
                            <hr>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Denda: Rp 5.000/hari × {{ $hariTerlambat }} hari
                            </small>
                        </div>
                    @else
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Tidak ada denda</strong><br>
                            <small>Buku dikembalikan tepat waktu</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Confirmation Form -->
            <div class="detail-card">
                <h5 class="mb-4 text-center">
                    <i class="bi bi-shield-check text-success me-2"></i>Konfirmasi Pengembalian
                </h5>

                @if($hariTerlambat > 0)
                <div class="alert-warning-custom mb-3">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>Perhatian!</strong><br>
                    <small>Mahasiswa harus membayar denda sebesar <strong>Rp {{ number_format($denda, 0, ',', '.') }}</strong></small>
                </div>
                @endif

                <form action="{{ route('petugas.pengembalian.store', $peminjaman->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin memproses pengembalian buku ini?{{ $hariTerlambat > 0 ? '\n\nDenda: Rp ' . number_format($denda, 0, ',', '.') : '' }}')">
                    @csrf

    

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success confirm-button">
                            <i class="bi bi-check-circle me-2"></i>
                            Konfirmasi Pengembalian
                        </button>
                        <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection