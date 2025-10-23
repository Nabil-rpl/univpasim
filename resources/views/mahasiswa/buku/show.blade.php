@extends('layouts.mahasiswa')

@section('content')
<style>
    .detail-container {
        background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }

    .breadcrumb-custom {
        background: transparent;
        padding: 0;
        margin-bottom: 2rem;
    }

    .breadcrumb-custom .breadcrumb-item {
        color: #64748B;
        font-weight: 600;
    }

    .breadcrumb-custom .breadcrumb-item.active {
        color: #3B82F6;
    }

    .breadcrumb-custom a {
        color: #60A5FA;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-custom a:hover {
        color: #3B82F6;
    }

    .detail-card {
        background: #FFFFFF;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(96, 165, 250, 0.15);
        border: 1px solid #E0E7FF;
        animation: fadeInUp 0.6s ease;
    }

    .book-image-section {
        position: relative;
        background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
        padding: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 600px;
    }

    .book-image-wrapper {
        position: relative;
        max-width: 100%;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(59, 130, 246, 0.3);
        transition: transform 0.3s ease;
    }

    .book-image-wrapper:hover {
        transform: scale(1.05);
    }

    .book-image-wrapper img {
        width: 100%;
        height: auto;
        max-height: 550px;
        object-fit: cover;
        display: block;
    }

    .no-image-placeholder {
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        padding: 4rem 2rem;
        box-shadow: 0 20px 60px rgba(59, 130, 246, 0.2);
    }

    .no-image-icon {
        width: 120px;
        height: 120px;
        background: #FFFFFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: #60A5FA;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 30px rgba(96, 165, 250, 0.2);
    }

    .book-info-section {
        padding: 3rem;
    }

    .book-category-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #DBEAFE;
        color: #3B82F6;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .book-title-main {
        font-size: 2.25rem;
        font-weight: 800;
        color: #1E293B;
        margin-bottom: 2rem;
        line-height: 1.3;
        letter-spacing: -0.5px;
    }

    .book-details-grid {
        display: grid;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .detail-item {
        display: flex;
        align-items: start;
        gap: 1rem;
        padding: 1.25rem;
        background: #F8FAFC;
        border-radius: 16px;
        transition: all 0.3s ease;
        border: 1px solid #E0E7FF;
    }

    .detail-item:hover {
        background: #EFF6FF;
        transform: translateX(5px);
    }

    .detail-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.3);
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #60A5FA;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1E293B;
    }

    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .stock-available {
        background: #D1FAE5;
        color: #065F46;
    }

    .stock-unavailable {
        background: #FEE2E2;
        color: #991B1B;
    }

    /* Alert Styles */
    .alert-custom {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        border-radius: 16px;
        border: none;
        margin-bottom: 2rem;
    }

    .alert-success-custom {
        background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color: #065F46;
        border-left: 4px solid #10B981;
    }

    .alert-danger-custom {
        background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
        color: #991B1B;
        border-left: 4px solid #EF4444;
    }

    .alert-warning-custom {
        background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
        color: #92400E;
        border-left: 4px solid #F59E0B;
    }

    .alert-info-custom {
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        color: #1E40AF;
        border-left: 4px solid #3B82F6;
    }

    /* QR Code Section */
    .qr-section {
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        border-radius: 20px;
        padding: 2.5rem;
        text-align: center;
        margin-bottom: 2rem;
        border: 2px solid #BFDBFE;
    }

    .qr-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 2rem;
    }

    .qr-header-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.3);
    }

    .qr-header h5 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 800;
        color: #1E293B;
    }

    .qr-code-display {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        display: inline-block;
        box-shadow: 0 10px 40px rgba(59, 130, 246, 0.2);
        margin-bottom: 1.5rem;
        border: 3px solid #FFFFFF;
        transition: transform 0.3s ease;
    }

    .qr-code-display:hover {
        transform: scale(1.05);
    }

    .qr-code-display img {
        max-width: 280px;
        width: 100%;
        height: auto;
        display: block;
    }

    .qr-code-info {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.15);
    }

    .qr-code-info code {
        background: #EFF6FF;
        color: #3B82F6;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .btn-scanner {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        padding: 1.25rem 2.5rem;
        border-radius: 16px;
        border: none;
        font-weight: 700;
        font-size: 1.05rem;
        text-decoration: none;
        box-shadow: 0 8px 25px rgba(96, 165, 250, 0.4);
        transition: all 0.3s ease;
    }

    .btn-scanner:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(96, 165, 250, 0.5);
        color: white;
        background: linear-gradient(135deg, #3B82F6, #2563EB);
    }

    .btn-scanner:disabled {
        background: #CBD5E1;
        color: #94A3B8;
        cursor: not-allowed;
        box-shadow: none;
    }

    .btn-scanner:disabled:hover {
        transform: none;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        color: #3B82F6;
        border: 2px solid #BFDBFE;
        padding: 0.875rem 1.75rem;
        border-radius: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #EFF6FF;
        border-color: #60A5FA;
        color: #3B82F6;
        transform: translateY(-2px);
    }

    /* Instructions Card */
    .instructions-card {
        background: #FFFFFF;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(96, 165, 250, 0.15);
        border: 1px solid #E0E7FF;
        margin-top: 2rem;
        animation: fadeInUp 0.8s ease;
    }

    .instructions-header {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        padding: 1.75rem 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .instructions-header-icon {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .instructions-header h5 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 800;
    }

    .instructions-body {
        padding: 2.5rem;
    }

    .instruction-steps {
        display: grid;
        gap: 1.5rem;
    }

    .instruction-step {
        display: flex;
        align-items: start;
        gap: 1.25rem;
        padding: 1.5rem;
        background: #F8FAFC;
        border-radius: 16px;
        transition: all 0.3s ease;
        border: 1px solid #E0E7FF;
    }

    .instruction-step:hover {
        background: #EFF6FF;
        transform: translateX(5px);
        border-color: #BFDBFE;
    }

    .step-number {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.3);
    }

    .step-content {
        flex: 1;
        padding-top: 0.5rem;
    }

    .step-content p {
        margin: 0;
        color: #1E293B;
        font-size: 1.05rem;
        font-weight: 600;
        line-height: 1.6;
    }

    .step-content strong {
        color: #3B82F6;
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
        .book-info-section {
            padding: 2rem;
        }

        .book-title-main {
            font-size: 1.75rem;
        }

        .book-image-section {
            min-height: 400px;
        }

        .qr-section {
            padding: 1.75rem;
        }

        .instructions-body {
            padding: 1.75rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-back,
        .btn-scanner {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="detail-container">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item">
                    <a href="{{ route('mahasiswa.dashboard') }}">
                        <i class="bi bi-house-door-fill"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('mahasiswa.buku.index') }}">Daftar Buku</a>
                </li>
                <li class="breadcrumb-item active">Detail Buku</li>
            </ol>
        </nav>

        <!-- Main Detail Card -->
        <div class="detail-card">
            <div class="row g-0">
                <!-- Book Image Section -->
                <div class="col-lg-5">
                    <div class="book-image-section">
                        @if ($buku->foto)
                            <div class="book-image-wrapper">
                                <img src="{{ asset('storage/' . $buku->foto) }}" alt="{{ $buku->judul }}">
                            </div>
                        @else
                            <div class="no-image-placeholder">
                                <div class="no-image-icon">
                                    <i class="bi bi-book-fill"></i>
                                </div>
                                <p style="color: #64748B; font-weight: 600; font-size: 1.1rem;">Tidak Ada Gambar</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Book Info Section -->
                <div class="col-lg-7">
                    <div class="book-info-section">
                        @if($buku->kategori)
                            <div class="book-category-badge">
                                <i class="bi bi-tag-fill"></i>
                                <span>{{ $buku->kategori }}</span>
                            </div>
                        @endif

                        <h1 class="book-title-main">{{ $buku->judul }}</h1>

                        <!-- Alerts -->
                        @if (session('success'))
                            <div class="alert-custom alert-success-custom">
                                <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
                                <div style="flex: 1;">{{ session('success') }}</div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert-custom alert-danger-custom">
                                <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem;"></i>
                                <div style="flex: 1;">{{ session('error') }}</div>
                            </div>
                        @endif

                        <!-- Book Details Grid -->
                        <div class="book-details-grid">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Penulis</div>
                                    <div class="detail-value">{{ $buku->penulis }}</div>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Penerbit</div>
                                    <div class="detail-value">{{ $buku->penerbit }}</div>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Tahun Terbit</div>
                                    <div class="detail-value">{{ $buku->tahun_terbit }}</div>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Ketersediaan Stok</div>
                                    <div class="detail-value">
                                        <span class="stock-badge {{ $buku->stok > 0 ? 'stock-available' : 'stock-unavailable' }}">
                                            @if($buku->stok > 0)
                                                <i class="bi bi-check-circle-fill"></i>
                                                {{ $buku->stok }} Buku Tersedia
                                            @else
                                                <i class="bi bi-x-circle-fill"></i>
                                                Stok Habis
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Section -->
                        @if ($buku->qrCode)
                            <div class="qr-section">
                                <div class="qr-header">
                                    <div class="qr-header-icon">
                                        <i class="bi bi-qr-code"></i>
                                    </div>
                                    <h5>Scan untuk Meminjam</h5>
                                </div>

                                <div class="qr-code-display">
                                    <img src="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                         alt="QR Code {{ $buku->judul }}">
                                </div>

                                <div class="qr-code-info">
                                    <i class="bi bi-key-fill" style="color: #60A5FA;"></i>
                                    <span style="color: #64748B; font-weight: 600;">Kode:</span>
                                    <code>{{ $buku->qrCode->kode_unik }}</code>
                                </div>

                                <div class="alert-custom alert-info-custom" style="text-align: left; margin-bottom: 1.5rem;">
                                    <i class="bi bi-info-circle-fill" style="font-size: 1.5rem;"></i>
                                    <div style="flex: 1;">
                                        Gunakan <strong>aplikasi scanner QR</strong> atau klik tombol di bawah untuk membuka scanner dan pinjam buku ini
                                    </div>
                                </div>

                                @if ($buku->stok > 0)
                                    <a href="{{ route('mahasiswa.qr.scanner') }}" class="btn-scanner">
                                        <i class="bi bi-camera-fill"></i>
                                        <span>Buka QR Scanner</span>
                                    </a>
                                @else
                                    <button class="btn-scanner" disabled>
                                        <i class="bi bi-x-circle-fill"></i>
                                        <span>Stok Tidak Tersedia</span>
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="alert-custom alert-warning-custom">
                                <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem;"></i>
                                <div style="flex: 1;">
                                    <strong>QR Code belum tersedia!</strong> Silakan hubungi petugas perpustakaan untuk informasi lebih lanjut.
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('mahasiswa.buku.index') }}" class="btn-back">
                                <i class="bi bi-arrow-left"></i>
                                <span>Kembali ke Daftar</span>
                            </a>
                        </div>

                        @if ($buku->stok <= 0)
                            <div class="alert-custom alert-warning-custom" style="margin-top: 1.5rem;">
                                <i class="bi bi-info-circle-fill" style="font-size: 1.5rem;"></i>
                                <div style="flex: 1;">
                                    Stok buku sedang habis. Silakan coba lagi nanti atau hubungi petugas perpustakaan.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="instructions-card">
            <div class="instructions-header">
                <div class="instructions-header-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <h5>Cara Meminjam Buku</h5>
            </div>
            <div class="instructions-body">
                <div class="instruction-steps">
                    <div class="instruction-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <p>Klik tombol <strong>"Buka QR Scanner"</strong> di atas untuk mengaktifkan kamera</p>
                        </div>
                    </div>
                    <div class="instruction-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <p>Arahkan kamera smartphone Anda ke <strong>QR Code buku</strong> yang ingin dipinjam</p>
                        </div>
                    </div>
                    <div class="instruction-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <p>Sistem akan menampilkan <strong>konfirmasi peminjaman</strong> dengan detail buku</p>
                        </div>
                    </div>
                    <div class="instruction-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <p>Klik <strong>"Ya, Pinjam Buku"</strong> untuk menyelesaikan proses peminjaman</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection