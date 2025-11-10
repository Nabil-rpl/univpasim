@extends('layouts.petugas')

@section('content')
<div class="container-fluid py-4 px-3">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-11 col-xl-10">
            <!-- Breadcrumb Modern -->
            <nav class="breadcrumb-modern mb-4" aria-label="breadcrumb">
                <ol class="breadcrumb-list">
                    <li class="breadcrumb-item">
                        <a href="{{ route('petugas.buku.index') }}">
                            <i class="bi bi-house-door"></i>
                            <span>Daftar Buku</span>
                        </a>
                    </li>
                    <li class="breadcrumb-separator">
                        <i class="bi bi-chevron-right"></i>
                    </li>
                    <li class="breadcrumb-item active">
                        <span>Detail Buku</span>
                    </li>
                </ol>
            </nav>

            <!-- Main Detail Card -->
            <div class="detail-card">
                <!-- Hero Section -->
                <div class="hero-banner">
                    <div class="hero-pattern"></div>
                    <div class="hero-content">
                        <div class="hero-badge">
                            <i class="bi bi-book-fill"></i>
                            Detail Buku
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="detail-content">
                    <div class="row g-4">
                        <!-- Left Column - Image & Quick Stats -->
                        <div class="col-lg-4 col-md-5">
                            <div class="book-showcase">
                                <!-- Book Cover -->
                                <div class="book-cover-wrapper">
                                    @if ($buku->foto && file_exists(public_path('storage/' . $buku->foto)))
                                        <div class="book-cover">
                                            <img src="{{ asset('storage/' . $buku->foto) }}" 
                                                 alt="{{ $buku->judul }}"
                                                 class="cover-image">
                                            <div class="cover-overlay">
                                                <button class="zoom-btn" onclick="openImageModal()">
                                                    <i class="bi bi-zoom-in"></i>
                                                    Lihat Ukuran Penuh
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="book-cover no-image">
                                            <div class="no-image-content">
                                                <i class="bi bi-image"></i>
                                                <span>Tidak ada cover</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Quick Stats -->
                                <div class="quick-stats">
                                    <div class="stat-card">
                                        <div class="stat-icon stock">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div class="stat-info">
                                            <span class="stat-label">Stok Tersedia</span>
                                            <span class="stat-value">{{ $buku->stok }} Unit</span>
                                        </div>
                                    </div>

                                    <div class="stat-card">
                                        <div class="stat-icon year">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div class="stat-info">
                                            <span class="stat-label">Tahun Terbit</span>
                                            <span class="stat-value">{{ $buku->tahun_terbit }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Category Badge -->
                                @if($buku->kategori)
                                <div class="category-display">
                                    <div class="category-icon">
                                        @switch($buku->kategori)
                                            @case('Teknik Informatika') 💻 @break
                                            @case('Sistem Informasi') 🖥️ @break
                                            @case('Manajemen') 📊 @break
                                            @case('Akuntansi') 💰 @break
                                            @case('Hukum') ⚖️ @break
                                            @case('Kedokteran') 🏥 @break
                                            @case('Teknik Sipil') 🏗️ @break
                                            @case('Arsitektur') 📐 @break
                                            @case('Psikologi') 🧠 @break
                                            @case('Sastra') 📚 @break
                                            @default 📖 @break
                                        @endswitch
                                    </div>
                                    <div class="category-text">
                                        <span class="category-label">Kategori</span>
                                        <span class="category-name">{{ $buku->kategori }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column - Book Details -->
                        <div class="col-lg-8 col-md-7">
                            <div class="book-details">
                                <!-- Title Section -->
                                <div class="title-section">
                                    <h1 class="book-title">{{ $buku->judul }}</h1>
                                    <div class="action-buttons-top">
                                        <a href="{{ route('petugas.buku.edit', $buku->id) }}" 
                                           class="btn-action edit"
                                           title="Edit Buku">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('petugas.buku.destroy', $buku->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action delete" title="Hapus Buku">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Info Grid -->
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-icon author">
                                            <i class="bi bi-person-circle"></i>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">Penulis</span>
                                            <span class="info-value">{{ $buku->penulis }}</span>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon publisher">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">Penerbit</span>
                                            <span class="info-value">{{ $buku->penerbit }}</span>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon date">
                                            <i class="bi bi-clock-history"></i>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">Ditambahkan</span>
                                            <span class="info-value">{{ $buku->created_at->translatedFormat('d F Y') }}</span>
                                            <span class="info-sub">{{ $buku->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon update">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">Terakhir Update</span>
                                            <span class="info-value">{{ $buku->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Code Section -->
                                <div class="qr-section">
                                    <div class="section-header">
                                        <div class="section-icon">
                                            <i class="bi bi-qr-code-scan"></i>
                                        </div>
                                        <h3 class="section-title">QR Code Buku</h3>
                                    </div>

                                    @if ($buku->qrCode)
                                        <div class="qr-content">
                                            <div class="qr-display">
                                                <div class="qr-image-wrapper">
                                                    <img src="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                                         alt="QR Code {{ $buku->judul }}"
                                                         class="qr-image">
                                                </div>
                                            </div>

                                            <div class="qr-info-panel">
                                                <div class="qr-code-field">
                                                    <label class="field-label">
                                                        <i class="bi bi-key"></i>
                                                        Kode Unik
                                                    </label>
                                                    <div class="code-display">
                                                        <input type="text" 
                                                               class="code-input" 
                                                               value="{{ $buku->qrCode->kode_unik }}" 
                                                               id="kodeUnik"
                                                               readonly>
                                                        <button class="copy-btn" onclick="copyCode()">
                                                            <i class="bi bi-clipboard"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="qr-meta">
                                                    <div class="meta-row">
                                                        <i class="bi bi-person-badge"></i>
                                                        <span>Dibuat oleh: <strong>{{ $buku->qrCode->user->name ?? 'System' }}</strong></span>
                                                    </div>
                                                    <div class="meta-row">
                                                        <i class="bi bi-clock"></i>
                                                        <span>{{ $buku->qrCode->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>

                                                <div class="qr-actions">
                                                    <a href="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                                       download="QR-{{ Str::slug($buku->judul) }}.svg"
                                                       class="qr-btn download">
                                                        <i class="bi bi-download"></i>
                                                        <span>Download</span>
                                                    </a>

                                                    <form action="{{ route('petugas.buku.regenerateQR', $buku->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Regenerate QR Code? QR lama akan dihapus.')">
                                                        @csrf
                                                        <button class="qr-btn regenerate">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                            <span>Regenerate</span>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('petugas.qrcode.destroy', $buku->qrCode->id) }}" 
                                                          method="POST" 
                                                          class="d-inline" 
                                                          onsubmit="return confirm('Hapus QR Code ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="qr-btn delete">
                                                            <i class="bi bi-trash"></i>
                                                            <span>Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="qr-empty">
                                            <div class="empty-icon">
                                                <i class="bi bi-qr-code"></i>
                                            </div>
                                            <h4>Belum Ada QR Code</h4>
                                            <p>Buat QR Code untuk memudahkan akses informasi buku ini</p>
                                            <a href="{{ route('petugas.qrcode.generate', ['type' => 'buku', 'id' => $buku->id]) }}" 
                                               class="btn-generate-qr">
                                                <i class="bi bi-plus-circle"></i>
                                                Buat QR Code
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <!-- Bottom Actions -->
                                <div class="bottom-actions">
                                    <a href="{{ route('petugas.buku.index') }}" class="btn-bottom back">
                                        <i class="bi bi-arrow-left"></i>
                                        Kembali
                                    </a>
                                    <a href="{{ route('petugas.buku.edit', $buku->id) }}" class="btn-bottom primary">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit Buku
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
@if ($buku->foto && file_exists(public_path('storage/' . $buku->foto)))
<div class="image-modal" id="imageModal" onclick="closeImageModal()">
    <div class="modal-content-wrapper" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeImageModal()">
            <i class="bi bi-x-lg"></i>
        </button>
        <img src="{{ asset('storage/' . $buku->foto) }}" alt="{{ $buku->judul }}" class="modal-image">
    </div>
</div>
@endif

<!-- Toast Notification -->
@if(session('success'))
<div class="toast-notification show">
    <div class="toast-icon success">
        <i class="bi bi-check-circle-fill"></i>
    </div>
    <div class="toast-content">
        <h6 class="toast-title">Berhasil!</h6>
        <p class="toast-message">{{ session('success') }}</p>
    </div>
    <button class="toast-close" onclick="this.parentElement.remove()">
        <i class="bi bi-x"></i>
    </button>
</div>
@endif

<style>
    :root {
        --primary: #667eea;
        --primary-dark: #5568d3;
        --secondary: #764ba2;
        --success: #48bb78;
        --danger: #f56565;
        --warning: #ed8936;
        --info: #4299e1;
        --light: #f7fafc;
        --dark: #2d3748;
        --gray: #718096;
        --border: #e2e8f0;
    }

    body {
        background: linear-gradient(135deg, #667eea08 0%, #764ba208 100%);
        overflow-x: hidden;
    }

    /* Breadcrumb Modern */
    .breadcrumb-modern {
        background: white;
        padding: 12px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .breadcrumb-list {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        padding: 0;
        list-style: none;
        flex-wrap: wrap;
    }

    .breadcrumb-item a {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--gray);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: color 0.3s;
    }

    .breadcrumb-item a:hover {
        color: var(--primary);
    }

    .breadcrumb-item.active span {
        color: var(--primary);
        font-weight: 600;
        font-size: 14px;
    }

    .breadcrumb-separator {
        color: var(--border);
        font-size: 10px;
    }

    /* Detail Card */
    .detail-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    /* Hero Banner */
    .hero-banner {
        position: relative;
        height: 140px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        overflow: hidden;
    }

    .hero-pattern {
        position: absolute;
        inset: 0;
        background-image: 
            repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.05) 35px, rgba(255,255,255,.05) 70px);
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 28px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50px;
        color: white;
        font-size: 18px;
        font-weight: 700;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* Detail Content */
    .detail-content {
        padding: 30px;
    }

    /* Book Showcase */
    .book-showcase {
        position: sticky;
        top: 20px;
    }

    .book-cover-wrapper {
        margin-bottom: 20px;
    }

    .book-cover {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
        aspect-ratio: 3/4;
    }

    .cover-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .cover-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .book-cover:hover .cover-overlay {
        opacity: 1;
    }

    .zoom-btn {
        padding: 10px 20px;
        background: white;
        color: var(--dark);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .zoom-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .book-cover.no-image {
        background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .no-image-content {
        text-align: center;
        color: var(--gray);
    }

    .no-image-content i {
        font-size: 48px;
        margin-bottom: 10px;
        display: block;
    }

    .no-image-content span {
        font-size: 14px;
    }

    /* Quick Stats */
    .quick-stats {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border-radius: 14px;
        border: 1px solid var(--border);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.stock {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .stat-icon.year {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    .stat-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .stat-label {
        font-size: 12px;
        color: var(--gray);
        font-weight: 500;
    }

    .stat-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
    }

    /* Category Display */
    .category-display {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.12), rgba(118, 75, 162, 0.12));
        border: 2px solid rgba(102, 126, 234, 0.3);
        border-radius: 14px;
    }

    .category-icon {
        font-size: 32px;
        flex-shrink: 0;
    }

    .category-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .category-label {
        font-size: 11px;
        color: var(--gray);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .category-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Book Details */
    .book-details {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Title Section */
    .title-section {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--border);
    }

    .book-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--dark);
        line-height: 1.3;
        flex: 1;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .action-buttons-top {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .btn-action {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        color: white;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-action.edit {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
    }

    .btn-action.delete {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
    }

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }

    .info-item {
        display: flex;
        gap: 12px;
        padding: 18px;
        background: var(--light);
        border-radius: 14px;
        border: 1px solid var(--border);
        transition: all 0.3s;
    }

    .info-item:hover {
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
    }

    .info-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 18px;
        color: white;
        flex-shrink: 0;
    }

    .info-icon.author {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .info-icon.publisher {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    .info-icon.date {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .info-icon.update {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .info-label {
        font-size: 11px;
        color: var(--gray);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--dark);
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .info-sub {
        font-size: 12px;
        color: var(--gray);
    }

    /* --- Updated QR Section Styles --- */
    .qr-section {
        padding: 24px;
        background: #f7fafc;
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .section-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 12px;
        font-size: 22px;
        color: white;
        flex-shrink: 0;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .qr-content {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 24px;
        align-items: start;
    }

    .qr-display {
        width: 180px;
        flex-shrink: 0;
    }

    .qr-image-wrapper {
        position: relative;
        padding: 20px;
        background: white;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .qr-image {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 8px;
    }

    .qr-info-panel {
        display: flex;
        flex-direction: column;
        gap: 18px;
        min-width: 0;
    }

    .qr-code-field {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .field-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--gray);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .code-display {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #edf2f7;
        border-radius: 8px;
        padding: 8px 12px;
        border: 1px solid var(--border);
    }

    .code-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        font-size: 14px;
        color: var(--dark);
        padding: 0;
        margin: 0;
        font-family: inherit;
    }

    .copy-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        color: var(--gray);
        font-size: 14px;
        transition: color 0.2s;
    }

    .copy-btn:hover {
        color: var(--primary);
    }

    .qr-meta {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border);
    }

    .meta-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--gray);
    }

    .meta-row i {
        font-size: 14px;
        color: var(--primary);
    }

    .qr-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .qr-btn {
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
        text-decoration: none;
        white-space: nowrap;
    }

    .qr-btn.download {
        background: #1768ff; 0%, #f5576c 100%);
        color: white;
    }

    .qr-btn.regenerate {
        background: #12e6f9; 0%, #f5576c 100%);
        color: white;
    }

    .qr-btn.delete {
        background: #ec2416; 0%, #f5576c 100%);
        color: white;
    }

    .qr-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* QR Empty State */
    .qr-empty {
        text-align: center;
        padding: 30px;
        color: var(--gray);
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        color: #cbd5e0;
    }

    .qr-empty h4 {
        margin: 0 0 10px;
        color: var(--dark);
        font-weight: 600;
    }

    .btn-generate-qr {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 12px;
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-generate-qr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Bottom Actions */
    .bottom-actions {
        display: flex;
        gap: 12px;
        padding-top: 20px;
        border-top: 2px solid var(--border);
    }

    .btn-bottom {
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-bottom.back {
        background: #edf2f7;
        color: var(--gray);
    }

    .btn-bottom.primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .btn-bottom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.9);
        z-index: 1050;
        align-items: center;
        justify-content: center;
    }

    .image-modal.show {
        display: flex;
    }

    .modal-content-wrapper {
        position: relative;
        max-width: 90vw;
        max-height: 90vh;
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }

    .modal-close {
        position: absolute;
        top: 12px;
        right: 12px;
        background: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        z-index: 10;
    }

    .modal-image {
        max-width: 100%;
        max-height: 80vh;
        display: block;
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: white;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 1060;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.4s ease;
    }

    .toast-notification.show {
        opacity: 1;
        transform: translateY(0);
    }

    .toast-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-size: 18px;
    }

    .toast-icon.success {
        background: linear-gradient(135deg, var(--success), #38a169);
    }

    .toast-content h6 {
        margin: 0;
        font-size: 14px;
        color: var(--dark);
    }

    .toast-message {
        margin: 0;
        font-size: 13px;
        color: var(--gray);
    }

    .toast-close {
        background: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
        color: var(--gray);
        margin-left: auto;
    }
</style>

<script>
    // Image Modal
    function openImageModal() {
        document.getElementById('imageModal').style.display = 'flex';
    }

    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    // Copy Code
    function copyCode() {
        const codeInput = document.getElementById('kodeUnik');
        codeInput.select();
        document.execCommand('copy');
        alert('Kode unik disalin ke clipboard!');
    }

    // Close toast on click (optional)
    document.querySelectorAll('.toast-close').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.toast-notification').remove();
        });
    });
</script>
@endsection