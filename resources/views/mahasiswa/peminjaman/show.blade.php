@extends('layouts.mahasiswa')

@section('page-title', 'Detail Peminjaman')

@section('content')
<style>
.detail-container {
    background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 100%);
    min-height: 100vh;
    padding: 3rem 0;
}

.card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
    border: 1px solid #E0E7FF;
    margin-bottom: 1.5rem;
    animation: fadeInUp 0.6s ease;
}

.card-header {
    background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
    border-bottom: 1px solid #E0E7FF;
    padding: 1.5rem;
    border-radius: 20px 20px 0 0 !important;
}

.card-header h5 {
    font-weight: 700;
    color: #1E293B;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-body {
    padding: 2rem;
}

.status-badge {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.badge-dipinjam {
    background: linear-gradient(135deg, #FEF3C7, #FDE68A);
    color: #92400E;
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
}

.badge-dikembalikan {
    background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
    color: #065F46;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.book-cover-detail {
    width: 140px;
    height: 190px;
    background: linear-gradient(135deg, #60A5FA, #3B82F6);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 4.5rem;
    margin: 0 auto;
    box-shadow: 0 8px 25px rgba(96, 165, 250, 0.4);
    overflow: hidden;
}

.book-cover-detail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.info-group {
    background: #F8FAFC;
    padding: 1.5rem;
    border-radius: 16px;
    border: 1px solid #E2E8F0;
}

.info-item {
    margin-bottom: 1.5rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item label {
    font-weight: 700;
    color: #64748B;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item p {
    color: #1E293B;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0;
}

.detail-box {
    display: flex;
    align-items: start;
    gap: 1rem;
    padding: 1.25rem;
    background: #F8FAFC;
    border-radius: 16px;
    margin-bottom: 1rem;
    border: 1px solid #E2E8F0;
    transition: all 0.3s ease;
}

.detail-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(96, 165, 250, 0.15);
}

.detail-icon {
    font-size: 2rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 14px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.detail-box label {
    font-weight: 700;
    color: #64748B;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-box p {
    color: #1E293B;
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.detail-box small {
    color: #64748B;
    font-weight: 500;
}

.avatar-circle {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #60A5FA, #3B82F6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    flex-shrink: 0;
    box-shadow: 0 4px 15px rgba(96, 165, 250, 0.4);
}

.petugas-info h6 {
    margin: 0 0 0.25rem 0;
    color: #1E293B;
    font-size: 1.1rem;
    font-weight: 700;
}

.petugas-info p {
    margin: 0;
    color: #64748B;
    font-size: 0.95rem;
}

.alert-custom {
    border: none;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0;
}

.alert-info-custom {
    background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
    color: #1E40AF;
    border-left: 4px solid #3B82F6;
}

.alert-warning-custom {
    background: linear-gradient(135deg, #FEF3C7, #FDE68A);
    color: #92400E;
    border-left: 4px solid #F59E0B;
}

.alert-danger-custom {
    background: linear-gradient(135deg, #FEE2E2, #FECACA);
    color: #991B1B;
    border-left: 4px solid #EF4444;
}

.alert-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.btn-back {
    background: linear-gradient(135deg, #60A5FA, #3B82F6);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 700;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-back:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(96, 165, 250, 0.4);
    color: white;
}

.denda-box {
    background: linear-gradient(135deg, #FEE2E2, #FECACA);
    border: 2px solid #EF4444;
    border-radius: 16px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.denda-box .amount {
    font-size: 2rem;
    font-weight: 800;
    color: #DC2626;
    margin: 0.5rem 0;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .detail-box {
        flex-direction: column;
        text-align: center;
    }
    
    .detail-icon {
        margin: 0 auto;
    }
}
</style>

<div class="detail-container">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="mb-1" style="color: #1E293B; font-weight: 800;">Detail Peminjaman</h2>
                        <p class="text-muted mb-0">Informasi lengkap peminjaman buku Anda</p>
                    </div>
                    <a href="{{ route('mahasiswa.peminjaman.riwayat') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Status Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="mb-1" style="color: #1E293B; font-weight: 700;">Status Peminjaman</h5>
                                <p class="text-muted mb-0">ID: <strong>#{{ $peminjaman->id }}</strong></p>
                            </div>
                            <div>
                                @if($peminjaman->status == 'dipinjam')
                                    <span class="status-badge badge-dipinjam">
                                        <i class="bi bi-hourglass-split"></i> Sedang Dipinjam
                                    </span>
                                @else
                                    <span class="status-badge badge-dikembalikan">
                                        <i class="bi bi-check-circle-fill"></i> Sudah Dikembalikan
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Book Info -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-book-fill"></i> Informasi Buku</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3 mb-md-0">
                                <div class="book-cover-detail">
                                    @if($peminjaman->buku->foto)
                                        <img src="{{ asset('storage/' . $peminjaman->buku->foto) }}" alt="{{ $peminjaman->buku->judul }}">
                                    @else
                                        <i class="bi bi-book-fill"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="info-group">
                                    <div class="info-item">
                                        <label>Judul Buku</label>
                                        <p>{{ $peminjaman->buku->judul }}</p>
                                    </div>
                                    <div class="info-item">
                                        <label>Penulis</label>
                                        <p>{{ $peminjaman->buku->penulis }}</p>
                                    </div>
                                    <div class="info-item">
                                        <label>Penerbit</label>
                                        <p>{{ $peminjaman->buku->penerbit }}</p>
                                    </div>
                                    <div class="info-item">
                                        <label>Tahun Terbit</label>
                                        <p>{{ $peminjaman->buku->tahun_terbit }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Borrowing Details -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-calendar-range"></i> Detail Peminjaman</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-box">
                                    <i class="bi bi-calendar-plus detail-icon text-primary"></i>
                                    <div class="flex-grow-1">
                                        <label>Tanggal Pinjam</label>
                                        <p>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                                        <small>{{ $peminjaman->tanggal_pinjam->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-box">
                                    <i class="bi bi-calendar-date detail-icon text-warning"></i>
                                    <div class="flex-grow-1">
                                        <label>Tanggal Deadline</label>
                                        <p>{{ $peminjaman->tanggal_deadline->format('d F Y') }}</p>
                                        <small>{{ $peminjaman->tanggal_deadline->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-box">
                                    @if($peminjaman->tanggal_kembali)
                                        <i class="bi bi-calendar-check detail-icon text-success"></i>
                                        <div class="flex-grow-1">
                                            <label>Tanggal Kembali</label>
                                            <p>{{ $peminjaman->tanggal_kembali->format('d F Y') }}</p>
                                            <small>{{ $peminjaman->tanggal_kembali->diffForHumans() }}</small>
                                        </div>
                                    @else
                                        <i class="bi bi-calendar-x detail-icon text-danger"></i>
                                        <div class="flex-grow-1">
                                            <label>Tanggal Kembali</label>
                                            <p>Belum Dikembalikan</p>
                                            <small>Sudah {{ $peminjaman->tanggal_pinjam->diffInDays(now()) }} hari dipinjam</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-box">
                                    <i class="bi bi-clock-history detail-icon text-info"></i>
                                    <div class="flex-grow-1">
                                        <label>Durasi Pinjam</label>
                                        <p>{{ $peminjaman->durasi_hari }} Hari</p>
                                        <small>Batas waktu peminjaman</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Alert Info Durasi --}}
                        @if($peminjaman->tanggal_kembali)
                            <div class="alert-custom alert-info-custom">
                                <i class="bi bi-info-circle-fill alert-icon"></i>
                                <div>
                                    <strong>Durasi Peminjaman Aktual:</strong> 
                                    {{ $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali) }} hari
                                </div>
                            </div>
                        @elseif($peminjaman->isTerlambat())
                            <div class="alert-custom alert-danger-custom">
                                <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                                <div>
                                    <strong>Terlambat {{ $peminjaman->getHariTerlambat() }} Hari!</strong><br>
                                    <small>Harap segera kembalikan buku ke perpustakaan</small>
                                </div>
                            </div>
                        @else
                            <div class="alert-custom alert-warning-custom">
                                <i class="bi bi-clock-fill alert-icon"></i>
                                <div>
                                    <strong>Sisa Waktu:</strong> 
                                    {{ now()->diffInDays($peminjaman->tanggal_deadline) }} hari lagi
                                </div>
                            </div>
                        @endif

                        {{-- Info Denda --}}
                        @if($peminjaman->status == 'dikembalikan' && $peminjaman->pengembalian)
                            @if($peminjaman->pengembalian->denda > 0)
                                <div class="denda-box">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-cash-coin" style="font-size: 3rem; color: #DC2626;"></i>
                                        <div>
                                            <label style="margin: 0; color: #991B1B;">Total Denda</label>
                                            <div class="amount">Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</div>
                                            <small style="color: #991B1B;">Denda keterlambatan pengembalian</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif($peminjaman->status == 'dipinjam' && $peminjaman->isTerlambat())
                            <div class="denda-box">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem; color: #DC2626;"></i>
                                    <div>
                                        <label style="margin: 0; color: #991B1B;">Estimasi Denda</label>
                                        <div class="amount">Rp {{ number_format($peminjaman->hitungDenda(), 0, ',', '.') }}</div>
                                        <small style="color: #991B1B;">Denda akan bertambah Rp 2.000 per hari</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div> 
                </div>

                <!-- Petugas Info -->
                @if($peminjaman->status == 'dikembalikan' && $peminjaman->pengembalian && $peminjaman->pengembalian->petugas)
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="bi bi-person-badge-fill"></i> Petugas Penerima Pengembalian</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="petugas-info">
                                    <h6>{{ $peminjaman->pengembalian->petugas->name }}</h6>
                                    <p><i class="bi bi-envelope-fill"></i> {{ $peminjaman->pengembalian->petugas->email }}</p>
                                    <p style="margin-top: 0.5rem; color: #3B82F6; font-weight: 600;">
                                        <i class="bi bi-calendar-check"></i> 
                                        Diterima: {{ $peminjaman->pengembalian->tanggal_pengembalian->format('d F Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($peminjaman->petugas)
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="bi bi-person-badge-fill"></i> Petugas yang Memproses</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="petugas-info">
                                    <h6>{{ $peminjaman->petugas->name }}</h6>
                                    <p><i class="bi bi-envelope-fill"></i> {{ $peminjaman->petugas->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection