@extends('layouts.pengguna-luar')

@section('page-title', 'Dashboard Pengguna')

@section('content')
<div class="container-fluid dashboard-container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="welcome-gradient"></div>
                <div class="welcome-content">
                    <div class="welcome-text">
                        <div class="greeting-badge">
                            <i class="bi bi-stars"></i>
                            <span>Dashboard</span>
                        </div>
                        <h1 class="welcome-title">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
                        <p class="welcome-subtitle">
                            <span class="info-chip">
                                <i class="bi bi-envelope"></i>
                                {{ auth()->user()->email }}
                            </span>
                            @if(auth()->user()->no_hp)
                                <span class="info-chip">
                                    <i class="bi bi-telephone"></i>
                                    {{ auth()->user()->no_hp }}
                                </span>
                            @endif
                            @if(auth()->user()->alamat)
                                <span class="info-chip">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ Str::limit(auth()->user()->alamat, 30) }}
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="welcome-action">
                        <a href="{{ route('pengguna-luar.buku.index') }}" class="btn-primary-custom">
                            <i class="bi bi-book"></i>
                            <span>Pinjam Buku</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success-custom alert-dismissible fade show" role="alert">
            <div class="alert-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="alert-content">
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger-custom alert-dismissible fade show" role="alert">
            <div class="alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="alert-content">
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Account Status Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="status-card status-active">
                <div class="status-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="status-content">
                    <h5 class="status-title">Akun Aktif</h5>
                    <p class="status-text">Akun Anda terdaftar sebagai Pengguna Umum. Batas peminjaman: 2 buku, maksimal 7 hari</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4 g-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-decoration stat-decoration-1"></div>
                <div class="stat-icon-wrapper">
                    <div class="stat-icon stat-icon-1">
                        <i class="bi bi-book-half"></i>
                    </div>
                </div>
                <div class="stat-details">
                    <h3 class="stat-number">{{ $totalBuku ?? 0 }}</h3>
                    <p class="stat-label">Total Buku Tersedia</p>
                </div>
                <div class="stat-bg-icon">
                    <i class="bi bi-book-half"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-decoration stat-decoration-2"></div>
                <div class="stat-icon-wrapper">
                    <div class="stat-icon stat-icon-2">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <div class="stat-details">
                    <h3 class="stat-number">{{ $bukuTersedia ?? 0 }}</h3>
                    <p class="stat-label">Buku Siap Dipinjam</p>
                </div>
                <div class="stat-bg-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-decoration stat-decoration-3"></div>
                <div class="stat-icon-wrapper">
                    <div class="stat-icon stat-icon-3">
                        <i class="bi bi-bookmark-star"></i>
                    </div>
                </div>
                <div class="stat-details">
                    <h3 class="stat-number">{{ $peminjamanAktif ?? 0 }}/2</h3>
                    <p class="stat-label">Sedang Dipinjam</p>
                </div>
                <div class="stat-bg-icon">
                    <i class="bi bi-bookmark-star"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card-custom action-card-custom">
                <div class="card-header-custom">
                    <div class="card-icon-header">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <h5>Aksi Cepat</h5>
                </div>
                <div class="card-body-custom">
                    <div class="action-buttons">
                        <a href="{{ route('pengguna-luar.buku.index') }}" class="action-btn action-btn-primary">
                            <div class="action-btn-icon">
                                <i class="bi bi-book"></i>
                            </div>
                            <div class="action-btn-text">
                                <strong>Pinjam Buku Baru</strong>
                                <small>Jelajahi koleksi buku</small>
                            </div>
                            <i class="bi bi-chevron-right action-btn-arrow"></i>
                        </a>
                        <a href="{{ route('pengguna-luar.peminjaman.riwayat') }}" class="action-btn action-btn-secondary">
                            <div class="action-btn-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="action-btn-text">
                                <strong>Lihat Riwayat</strong>
                                <small>Histori peminjaman Anda</small>
                            </div>
                            <i class="bi bi-chevron-right action-btn-arrow"></i>
                        </a>
                        @if (Route::has('pengguna-luar.qr.scanner'))
                            <a href="{{ route('pengguna-luar.qr.scanner') }}" class="action-btn action-btn-tertiary">
                                <div class="action-btn-icon">
                                    <i class="bi bi-qr-code-scan"></i>
                                </div>
                                <div class="action-btn-text">
                                    <strong>QR Scanner</strong>
                                    <small>Scan kode QR buku</small>
                                </div>
                                <i class="bi bi-chevron-right action-btn-arrow"></i>
                            </a>
                        @endif
                        <a href="{{ route('pengguna-luar.pengaturan.index') }}" class="action-btn action-btn-quaternary">
                            <div class="action-btn-icon">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="action-btn-text">
                                <strong>Pengaturan</strong>
                                <small>Kelola informasi akun</small>
                            </div>
                            <i class="bi bi-chevron-right action-btn-arrow"></i>
                        </a>
                    </div>
                    
                    @if(($peminjamanAktif ?? 0) > 0)
                        <div class="info-box">
                            <div class="info-box-icon">
                                <i class="bi bi-info-circle-fill"></i>
                            </div>
                            <div class="info-box-text">
                                Anda memiliki <strong>{{ $peminjamanAktif }}</strong> buku yang sedang dipinjam. 
                                Sisa kuota: <strong>{{ 2 - $peminjamanAktif }}</strong> buku.
                            </div>
                        </div>
                    @else
                        <div class="info-box info-box-success">
                            <div class="info-box-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="info-box-text">
                                Anda dapat meminjam hingga <strong>2 buku</strong> dengan durasi maksimal <strong>7 hari</strong>.
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profile Summary Card -->
            <div class="card-custom mt-4">
                <div class="card-header-custom">
                    <div class="card-icon-header">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <h5>Informasi Akun</h5>
                </div>
                <div class="card-body-custom">
                    <div class="profile-info">
                        <div class="profile-item">
                            <div class="profile-label">
                                <i class="bi bi-person"></i>
                                <span>Nama</span>
                            </div>
                            <div class="profile-value">{{ auth()->user()->name }}</div>
                        </div>
                        <div class="profile-item">
                            <div class="profile-label">
                                <i class="bi bi-envelope"></i>
                                <span>Email</span>
                            </div>
                            <div class="profile-value">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="profile-item">
                            <div class="profile-label">
                                <i class="bi bi-telephone"></i>
                                <span>Telepon</span>
                            </div>
                            <div class="profile-value">{{ auth()->user()->no_hp ?? '-' }}</div>
                        </div>
                        <div class="profile-item">
                            <div class="profile-label">
                                <i class="bi bi-calendar-check"></i>
                                <span>Bergabung</span>
                            </div>
                            <div class="profile-value">{{ auth()->user()->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-8">
            <div class="card-custom activity-card-custom">
                <div class="card-header-custom">
                    <div class="card-icon-header">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h5>Riwayat Peminjaman Terakhir</h5>
                </div>
                <div class="card-body-custom">
                    @if(isset($riwayatPeminjaman) && $riwayatPeminjaman->count() > 0)
                        <div class="activity-list">
                            @foreach($riwayatPeminjaman as $p)
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6 class="activity-title">{{ $p->buku->judul }}</h6>
                                        <p class="activity-subtitle">{{ $p->buku->penulis }}</p>
                                        <div class="activity-meta">
                                            <span class="meta-item">
                                                <i class="bi bi-calendar3"></i>
                                                {{ $p->tanggal_pinjam->format('d/m/Y') }}
                                            </span>
                                            @if($p->tanggal_deadline)
                                                <span class="meta-item">
                                                    <i class="bi bi-clock"></i>
                                                    Deadline: {{ $p->tanggal_deadline->format('d/m/Y') }}
                                                </span>
                                            @endif
                                            @if($p->status == 'dipinjam')
                                                <span class="badge-custom badge-warning">
                                                    <i class="bi bi-clock"></i>
                                                    Dipinjam
                                                </span>
                                            @else
                                                <span class="badge-custom badge-success">
                                                    <i class="bi bi-check-circle"></i>
                                                    Dikembalikan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('pengguna-luar.peminjaman.show', $p->id) }}" class="activity-action" aria-label="Lihat detail peminjaman">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('pengguna-luar.peminjaman.riwayat') }}" class="btn-outline-custom">
                                Lihat Semua Riwayat
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <h6 class="empty-title">Belum Ada Riwayat</h6>
                            <p class="empty-text">Anda belum melakukan peminjaman buku</p>
                            <a href="{{ route('pengguna-luar.buku.index') }}" class="btn-primary-custom mt-3">
                                <i class="bi bi-book"></i>
                                <span>Pinjam Buku Sekarang</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
:root {
    --primary-blue: #60A5FA;
    --light-blue: #DBEAFE;
    --extra-light-blue: #EFF6FF;
    --dark-blue: #3B82F6;
    --pure-white: #FFFFFF;
    --text-dark: #1E293B;
    --text-light: #64748B;
    --shadow-sm: 0 1px 3px rgba(96, 165, 250, 0.12);
    --shadow-md: 0 4px 20px rgba(96, 165, 250, 0.15);
    --shadow-lg: 0 10px 40px rgba(96, 165, 250, 0.2);
}

.dashboard-container {
    padding: 2rem 1rem;
    background: linear-gradient(135deg, var(--extra-light-blue) 0%, #F8FAFC 100%);
    min-height: 100vh;
}

/* Welcome Card */
.welcome-card {
    position: relative;
    background: var(--pure-white);
    border-radius: 24px;
    padding: 3rem;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.welcome-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--primary-blue), var(--dark-blue));
}

.welcome-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.greeting-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--light-blue);
    color: var(--dark-blue);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.welcome-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
}

.welcome-subtitle {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin: 0;
}

.info-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--extra-light-blue);
    color: var(--text-light);
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.9rem;
}

.btn-primary-custom {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
    color: var(--pure-white);
    padding: 1rem 2rem;
    border-radius: 16px;
    text-decoration: none;
    font-weight: 600;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.btn-primary-custom:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(96, 165, 250, 0.4);
    color: var(--pure-white);
}

/* Status Card */
.status-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    background: var(--pure-white);
    padding: 1.5rem 2rem;
    border-radius: 20px;
    box-shadow: var(--shadow-md);
    border-left: 5px solid #10B981;
}

.status-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
    color: #059669;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    flex-shrink: 0;
}

.status-content {
    flex: 1;
}

.status-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.status-text {
    font-size: 0.9rem;
    color: var(--text-light);
    margin: 0;
}

/* Custom Alerts */
.alert-success-custom,
.alert-danger-custom {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    border-radius: 16px;
    border: none;
    box-shadow: var(--shadow-sm);
}

.alert-success-custom {
    background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
    color: #065F46;
}

.alert-danger-custom {
    background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
    color: #991B1B;
}

.alert-icon {
    font-size: 1.5rem;
}

.alert-content {
    flex: 1;
    font-weight: 500;
}

/* Stat Cards */
.stat-card {
    position: relative;
    background: var(--pure-white);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.stat-decoration {
    position: absolute;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    opacity: 0.1;
}

.stat-decoration-1 {
    background: var(--primary-blue);
    top: -50px;
    right: -50px;
}

.stat-decoration-2 {
    background: var(--dark-blue);
    top: -30px;
    right: -30px;
}

.stat-decoration-3 {
    background: var(--primary-blue);
    bottom: -40px;
    right: -40px;
}

.stat-icon-wrapper {
    margin-bottom: 1.5rem;
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--pure-white);
    box-shadow: var(--shadow-md);
}

.stat-icon-1 {
    background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
}

.stat-icon-2 {
    background: linear-gradient(135deg, #93C5FD, var(--primary-blue));
}

.stat-icon-3 {
    background: linear-gradient(135deg, var(--dark-blue), #2563EB);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.95rem;
    color: var(--text-light);
    font-weight: 600;
    margin: 0;
}

.stat-bg-icon {
    position: absolute;
    bottom: -20px;
    right: -10px;
    font-size: 8rem;
    color: var(--extra-light-blue);
    opacity: 0.3;
}

/* Custom Cards */
.card-custom {
    background: var(--pure-white);
    border-radius: 20px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.card-header-custom {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--extra-light-blue);
}

.card-icon-header {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
    color: var(--pure-white);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    box-shadow: var(--shadow-sm);
}

.card-header-custom h5 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--text-dark);
}

.card-body-custom {
    padding: 2rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    border-radius: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-btn-primary {
    background: linear-gradient(135deg, var(--light-blue), var(--extra-light-blue));
    color: var(--dark-blue);
}

.action-btn-secondary {
    background: var(--extra-light-blue);
    color: var(--primary-blue);
}

.action-btn-tertiary {
    background: linear-gradient(135deg, #FEF3C7, #FEF9C3);
    color: #92400E;
}

.action-btn-quaternary {
    background: linear-gradient(135deg, #FCE7F3, #FBE4E8);
    color: #9F1239;
}

.action-btn:hover {
    transform: translateX(8px);
    box-shadow: var(--shadow-md);
}

.action-btn-icon {
    width: 50px;
    height: 50px;
    background: var(--pure-white);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: var(--shadow-sm);
    flex-shrink: 0;
}

.action-btn-text {
    flex: 1;
}

.action-btn-text strong {
    display: block;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.action-btn-text small {
    color: var(--text-light);
}

.action-btn-arrow {
    font-size: 1.25rem;
    transition: transform 0.3s ease;
}

.action-btn:hover .action-btn-arrow {
    transform: translateX(5px);
}

/* Info Box */
.info-box {
    display: flex;
    gap: 1rem;
    background: var(--extra-light-blue);
    padding: 1.25rem;
    border-radius: 16px;
    margin-top: 1.5rem;
    border-left: 4px solid var(--primary-blue);
}

.info-box-success {
    background: #ECFDF5;
    border-left-color: #10B981;
}

.info-box-icon {
    font-size: 1.5rem;
    color: var(--primary-blue);
}

.info-box-success .info-box-icon {
    color: #10B981;
}

.info-box-text {
    color: var(--text-dark);
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Profile Info */
.profile-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.profile-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--extra-light-blue);
}

.profile-item:last-child {
    border-bottom: none;
}

.profile-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-light);
    font-size: 0.9rem;
}

.profile-label i {
    font-size: 1.25rem;
    color: var(--primary-blue);
}

.profile-value {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.95rem;
}

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    padding: 1.25rem;
    background: var(--extra-light-blue);
    border-radius: 16px;
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: var(--light-blue);
    transform: translateX(5px);
}

.activity-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
    color: var(--pure-white);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.activity-subtitle {
    font-size: 0.85rem;
    color: var(--text-light);
    margin-bottom: 0.5rem;
}

.activity-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--text-light);
}

.activity-action {
    width: 40px;
    height: 40px;
    background: var(--pure-white);
    color: var(--primary-blue);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.activity-action:hover {
    background: var(--primary-blue);
    color: var(--pure-white);
    transform: scale(1.1);
}

/* Badges */
.badge-custom {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.75rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-warning {
    background: #FEF3C7;
    color: #92400E;
}

.badge-success {
    background: #D1FAE5;
    color: #065F46;
}

/* Outline Button */
.btn-outline-custom {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: transparent;
    border: 2px solid var(--primary-blue);
    color: var(--primary-blue);
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-custom:hover {
    background: var(--primary-blue);
    color: var(--pure-white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 5rem;
    color: var(--light-blue);
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.empty-text {
    color: var(--text-light);
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }

    .welcome-card {
        padding: 2rem 1.5rem;
    }

    .welcome-content {
        flex-direction: column;
        text-align: center;
    }
    
    .welcome-title {
        font-size: 1.5rem;
    }

    .welcome-subtitle {
        justify-content: center;
    }
    
    .stat-card {
        padding: 1.5rem;
    }

    .stat-number {
        font-size: 2rem;
    }
    
    .activity-item {
        flex-wrap: wrap;
    }

    .status-card {
        flex-direction: column;
        text-align: center;
    }

    .profile-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    .action-btn {
        padding: 1rem;
    }

    .info-chip {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }
}

@media (max-width: 576px) {
    .welcome-title {
        font-size: 1.25rem;
    }

    .stat-number {
        font-size: 1.75rem;
    }

    .stat-label {
        font-size: 0.85rem;
    }

    .activity-action {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto hide alerts after 5 seconds
setTimeout(function() {
    var alerts = document.querySelectorAll('.alert-success-custom, .alert-danger-custom');
    alerts.forEach(function(alert) {
        var bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
@endpush
@endsection