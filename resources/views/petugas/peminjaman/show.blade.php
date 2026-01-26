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

    .role-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.875rem;
        font-weight: 600;
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

    .timeline-item.danger::before {
        border-color: #ef4444;
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

    .riwayat-denda-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .denda-stat-box {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: transform 0.3s;
    }

    .denda-stat-box:hover {
        transform: translateY(-5px);
    }

    .denda-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 1.5rem;
    }

    .denda-amount {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 10px 0 5px;
    }

    .denda-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 600;
    }

    .denda-count {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 5px;
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
                        @if($peminjaman->isTerlambat())
                            @php
                                $hariTerlambat = $peminjaman->getHariTerlambat();
                                $denda = $peminjaman->hitungDenda();
                            @endphp
                            <span class="status-badge bg-danger">
                                <i class="bi bi-exclamation-triangle me-1"></i>Terlambat {{ $hariTerlambat }} hari
                            </span>
                        @else
                            <span class="status-badge bg-warning text-dark">
                                <i class="bi bi-hourglass-split me-1"></i>Sedang Dipinjam
                            </span>
                        @endif
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

            <!-- Ringkasan Riwayat Denda Buku Ini -->
            @php
                // Cek apakah peminjaman ini sudah ada data pengembalian
                $pengembalianBukuIni = \App\Models\Pengembalian::where('peminjaman_id', $peminjaman->id)->first();
                
                $dendaBukuIni = 0;
                $statusDenda = null;
                
                if($pengembalianBukuIni && $pengembalianBukuIni->denda > 0) {
                    $dendaBukuIni = $pengembalianBukuIni->denda;
                    $statusDenda = $pengembalianBukuIni->denda_dibayar ? 'lunas' : 'belum_bayar';
                }
            @endphp

            @if($dendaBukuIni > 0)
            <div class="riwayat-denda-card">
                <h5 class="text-white mb-4">
                    <i class="bi bi-wallet2 me-2"></i>Informasi Denda Peminjaman Buku Ini
                </h5>
                <div class="row g-3">
                    <!-- Info Denda -->
                    <div class="col-md-6">
                        <div class="denda-stat-box">
                            <div class="denda-icon" style="background: linear-gradient(135deg, {{ $statusDenda == 'lunas' ? '#d1fae5, #a7f3d0' : '#fef3c7, #fde68a' }});">
                                <i class="bi bi-{{ $statusDenda == 'lunas' ? 'check-circle-fill text-success' : 'exclamation-triangle-fill text-warning' }}"></i>
                            </div>
                            <div class="denda-label">Total Denda</div>
                            <div class="denda-amount {{ $statusDenda == 'lunas' ? 'text-success' : 'text-warning' }}">
                                Rp {{ number_format($dendaBukuIni, 0, ',', '.') }}
                            </div>
                            <div class="denda-count">
                                @if($statusDenda == 'lunas')
                                    <span class="badge bg-success">Sudah Dibayar</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Dibayar</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info Tanggal -->
                    <div class="col-md-6">
                        <div class="denda-stat-box">
                            <div class="denda-icon" style="background: linear-gradient(135deg, #ddd6fe, #c4b5fd);">
                                <i class="bi bi-calendar-event text-primary"></i>
                            </div>
                            <div class="denda-label">
                                @if($statusDenda == 'lunas')
                                    Dibayar Pada
                                @else
                                    Tanggal Pengembalian
                                @endif
                            </div>
                            <div class="denda-amount text-primary" style="font-size: 1.2rem;">
                                @if($statusDenda == 'lunas' && $pengembalianBukuIni->denda_dibayar_pada)
                                    {{ \Carbon\Carbon::parse($pengembalianBukuIni->denda_dibayar_pada)->format('d M Y') }}
                                @else
                                    {{ $pengembalianBukuIni->tanggal_pengembalian->format('d M Y') }}
                                @endif
                            </div>
                            <div class="denda-count">
                                @if($statusDenda == 'lunas' && $pengembalianBukuIni->catatan_pembayaran)
                                    {{ Str::limit($pengembalianBukuIni->catatan_pembayaran, 30) }}
                                @else
                                    Hari keterlambatan: {{ $pengembalianBukuIni->peminjaman->getHariTerlambat() ?? 0 }} hari
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Data Peminjam -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-primary mb-0">
                    <i class="bi bi-person-badge me-2"></i>Data Peminjam
                </h6>
                @if($peminjaman->mahasiswa->role == 'mahasiswa')
                    <span class="role-badge bg-primary text-white">
                        <i class="bi bi-mortarboard-fill me-1"></i>Mahasiswa
                    </span>
                @else
                    <span class="role-badge bg-info text-white">
                        <i class="bi bi-person-fill me-1"></i>Pengguna Luar
                    </span>
                @endif
            </div>

            <div class="info-row">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">{{ $peminjaman->mahasiswa->name }}</div>
            </div>

            @if($peminjaman->mahasiswa->role == 'mahasiswa')
                <div class="info-row">
                    <div class="info-label">NIM</div>
                    <div class="info-value">{{ $peminjaman->mahasiswa->nim ?? '-' }}</div>
                </div>
            @else
                <div class="info-row">
                    <div class="info-label">No. Handphone</div>
                    <div class="info-value">
                        @if($peminjaman->mahasiswa->no_hp)
                            <i class="bi bi-telephone me-2"></i>{{ $peminjaman->mahasiswa->no_hp }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat</div>
                    <div class="info-value">
                        @if($peminjaman->mahasiswa->alamat)
                            <i class="bi bi-geo-alt me-2"></i>{{ $peminjaman->mahasiswa->alamat }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
            @endif

            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">
                    <i class="bi bi-envelope me-2"></i>{{ $peminjaman->mahasiswa->email }}
                </div>
            </div>

            <!-- Data Peminjaman -->
            <h6 class="text-primary mb-3 mt-4"><i class="bi bi-calendar-check me-2"></i>Data Peminjaman</h6>
            <div class="info-row">
                <div class="info-label">Tanggal Pinjam</div>
                <div class="info-value">
                    <i class="bi bi-calendar3 me-2"></i>
                    {{ $peminjaman->tanggal_pinjam->format('d F Y, H:i') }}
                    <span class="text-muted">({{ $peminjaman->tanggal_pinjam->diffForHumans() }})</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Durasi Pinjam</div>
                <div class="info-value">
                    <span class="badge bg-info">{{ $peminjaman->durasi_hari }} Hari</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Batas Pengembalian</div>
                <div class="info-value">
                    @if($peminjaman->tanggal_deadline)
                        <i class="bi bi-calendar-x me-2"></i>
                        {{ $peminjaman->tanggal_deadline->format('d F Y') }}
                        @if($peminjaman->status == 'dipinjam')
                            @if($peminjaman->isTerlambat())
                                <span class="badge bg-danger ms-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Lewat Deadline
                                </span>
                            @else
                                <span class="badge bg-success ms-2">
                                    <i class="bi bi-check-circle me-1"></i>Masih Dalam Waktu
                                </span>
                            @endif
                        @endif
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Kembali</div>
                <div class="info-value">
                    @if($peminjaman->tanggal_kembali)
                        <i class="bi bi-calendar-check me-2"></i>
                        {{ $peminjaman->tanggal_kembali->format('d F Y, H:i') }}
                        <span class="text-muted">({{ $peminjaman->tanggal_kembali->diffForHumans() }})</span>
                    @else
                        <span class="text-muted">Belum dikembalikan</span>
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Lama Pinjam</div>
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
                        <span class="badge bg-secondary">Sistem</span>
                    @endif
                </div>
            </div>

            <!-- Box Denda (jika terlambat) -->
            @if($peminjaman->status == 'dipinjam' && $peminjaman->isTerlambat())
            <div class="denda-box">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-danger mb-1">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Keterlambatan Pengembalian
                        </h6>
                        <p class="mb-0 text-dark">
                            Peminjaman telah melewati batas waktu <strong>{{ $peminjaman->getHariTerlambat() }} hari</strong>
                        </p>
                    </div>
                    <div class="text-end">
                        <small class="d-block text-muted">Denda Peminjaman Ini</small>
                        <h4 class="text-danger mb-0">
                            Rp {{ number_format($peminjaman->hitungDenda(), 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            @if($peminjaman->status == 'dikembalikan')
            <h6 class="text-primary mb-3 mt-4"><i class="bi bi-clock-history me-2"></i>Timeline</h6>
            <div class="timeline">
                <div class="timeline-item">
                    <strong>Buku Dipinjam</strong>
                    <p class="text-muted mb-0">{{ $peminjaman->tanggal_pinjam->format('d F Y, H:i') }}</p>
                    <small class="text-muted">
                        Oleh: {{ $peminjaman->petugas->name ?? 'Sistem' }}
                    </small>
                </div>
                <div class="timeline-item {{ $peminjaman->tanggal_kembali > $peminjaman->tanggal_deadline ? 'danger' : 'success' }}">
                    <strong>Buku Dikembalikan</strong>
                    <p class="text-muted mb-0">{{ $peminjaman->tanggal_kembali->format('d F Y, H:i') }}</p>
                    @if($peminjaman->tanggal_kembali > $peminjaman->tanggal_deadline)
                        <span class="badge bg-danger">
                            Terlambat {{ $peminjaman->tanggal_deadline->diffInDays($peminjaman->tanggal_kembali) }} hari
                        </span>
                    @else
                        <span class="badge bg-success">Tepat Waktu</span>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons mt-4">
                <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary btn-custom">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>

                @if($peminjaman->status == 'dipinjam')
                <a href="{{ route('petugas.pengembalian.show', $peminjaman->id) }}" class="btn btn-success btn-custom">
                    <i class="bi bi-box-arrow-in-down me-2"></i>Proses Pengembalian
                </a>
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