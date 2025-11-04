@extends('layouts.petugas')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">
            <!-- Decorative Header -->
            <div class="header-section text-center mb-5">
                <div class="floating-icon mb-4">
                    <div class="icon-wrapper">
                        📚
                    </div>
                </div>
                <h1 class="display-5 fw-bold mb-3 gradient-text">Tambah Buku Baru</h1>
                <p class="lead text-muted">Lengkapi informasi buku yang akan ditambahkan ke perpustakaan</p>
                
                <!-- Progress Steps -->
                <div class="steps-container mt-4">
                    <div class="step active">
                        <div class="step-circle">1</div>
                        <span class="step-label">Informasi Dasar</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step">
                        <div class="step-circle">2</div>
                        <span class="step-label">Detail Buku</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step">
                        <div class="step-circle">3</div>
                        <span class="step-label">Foto & Selesai</span>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="form-card">
                <form action="{{ route('petugas.buku.store') }}" method="POST" enctype="multipart/form-data" id="bookForm">
                    @csrf

                    <!-- Section 1: Informasi Dasar -->
                    <div class="form-section active" data-section="1">
                        <div class="section-header">
                            <div class="section-icon">📖</div>
                            <div>
                                <h4 class="section-title">Informasi Dasar Buku</h4>
                                <p class="section-subtitle">Masukkan judul dan identitas penulis</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Judul Buku -->
                            <div class="col-12">
                                <div class="form-group-modern">
                                    <label class="modern-label">
                                        <span class="label-icon">📝</span>
                                        <span class="label-text">Judul Buku</span>
                                        <span class="required-badge">Wajib</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               name="judul" 
                                               class="modern-input @error('judul') is-invalid @enderror" 
                                               value="{{ old('judul') }}"
                                               placeholder="Contoh: Pemrograman Web Modern dengan Laravel"
                                               required>
                                        <div class="input-border"></div>
                                    </div>
                                    @error('judul')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Penulis -->
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="modern-label">
                                        <span class="label-icon">👤</span>
                                        <span class="label-text">Nama Penulis</span>
                                        <span class="required-badge">Wajib</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               name="penulis" 
                                               class="modern-input @error('penulis') is-invalid @enderror" 
                                               value="{{ old('penulis') }}"
                                               placeholder="Contoh: John Doe"
                                               required>
                                        <div class="input-border"></div>
                                    </div>
                                    @error('penulis')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Penerbit -->
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="modern-label">
                                        <span class="label-icon">🏢</span>
                                        <span class="label-text">Penerbit</span>
                                        <span class="required-badge">Wajib</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               name="penerbit" 
                                               class="modern-input @error('penerbit') is-invalid @enderror" 
                                               value="{{ old('penerbit') }}"
                                               placeholder="Contoh: Gramedia Pustaka"
                                               required>
                                        <div class="input-border"></div>
                                    </div>
                                    @error('penerbit')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="section-footer">
                            <button type="button" class="btn-next" onclick="nextSection(2)">
                                Selanjutnya
                                <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Section 2: Detail Buku -->
                    <div class="form-section" data-section="2">
                        <div class="section-header">
                            <div class="section-icon">📊</div>
                            <div>
                                <h4 class="section-title">Detail & Kategori Buku</h4>
                                <p class="section-subtitle">Informasi tambahan tentang buku</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Tahun Terbit -->
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="modern-label">
                                        <span class="label-icon">📅</span>
                                        <span class="label-text">Tahun Terbit</span>
                                        <span class="required-badge">Wajib</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="number" 
                                               name="tahun_terbit" 
                                               class="modern-input @error('tahun_terbit') is-invalid @enderror" 
                                               value="{{ old('tahun_terbit') }}"
                                               placeholder="2024"
                                               min="1900"
                                               max="2100"
                                               required>
                                        <div class="input-border"></div>
                                    </div>
                                    @error('tahun_terbit')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Stok Buku -->
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="modern-label">
                                        <span class="label-icon">📦</span>
                                        <span class="label-text">Stok Buku</span>
                                        <span class="required-badge">Wajib</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <div class="input-with-controls">
                                            <button type="button" class="qty-btn" onclick="decrementStock()">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" 
                                                   name="stok" 
                                                   id="stokInput"
                                                   class="modern-input text-center @error('stok') is-invalid @enderror" 
                                                   value="{{ old('stok', 1) }}"
                                                   min="0"
                                                   required>
                                            <button type="button" class="qty-btn" onclick="incrementStock()">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <div class="input-border"></div>
                                    </div>
                                    @error('stok')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kategori -->
                            <div class="col-12">
                                <div class="form-group-modern">
                                    <label class="modern-label">
                                        <span class="label-icon">🏷️</span>
                                        <span class="label-text">Kategori (Jurusan)</span>
                                        <span class="required-badge">Wajib</span>
                                    </label>
                                    <div class="categories-grid">
                                        @foreach ($categories as $category)
                                            <label class="category-card">
                                                <input type="radio" 
                                                       name="kategori" 
                                                       value="{{ $category }}" 
                                                       {{ old('kategori') == $category ? 'checked' : '' }}
                                                       required>
                                                <div class="category-content">
                                                    <span class="category-emoji">
                                                        @switch($category)
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
                                                    </span>
                                                    <span class="category-name">{{ $category }}</span>
                                                    <div class="category-check">
                                                        <i class="bi bi-check-lg"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('kategori')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="section-footer">
                            <button type="button" class="btn-prev" onclick="prevSection(1)">
                                <i class="bi bi-arrow-left me-2"></i>
                                Kembali
                            </button>
                            <button type="button" class="btn-next" onclick="nextSection(3)">
                                Selanjutnya
                                <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Section 3: Upload Foto -->
                    <div class="form-section" data-section="3">
                        <div class="section-header">
                            <div class="section-icon">🖼️</div>
                            <div>
                                <h4 class="section-title">Upload Foto Buku</h4>
                                <p class="section-subtitle">Tambahkan foto cover untuk mempercantik katalog</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-12">
                                <div class="upload-zone" id="uploadZone">
                                    <input type="file" 
                                           name="foto" 
                                           class="upload-input @error('foto') is-invalid @enderror"
                                           accept="image/*"
                                           id="fotoInput"
                                           onchange="handleFileSelect(event)">
                                    
                                    <div class="upload-content" id="uploadContent">
                                        <div class="upload-icon">
                                            <i class="bi bi-cloud-arrow-up"></i>
                                        </div>
                                        <h5 class="upload-title">Klik atau Drag & Drop</h5>
                                        <p class="upload-subtitle">untuk mengupload foto buku</p>
                                        <div class="upload-specs">
                                            <span class="spec-item">
                                                <i class="bi bi-file-earmark-image"></i>
                                                JPG, PNG, JPEG
                                            </span>
                                            <span class="spec-divider">•</span>
                                            <span class="spec-item">
                                                <i class="bi bi-hdd"></i>
                                                Max 2MB
                                            </span>
                                        </div>
                                    </div>

                                    <div class="preview-container" id="previewContainer" style="display: none;">
                                        <div class="preview-wrapper">
                                            <img id="imagePreview" src="" alt="Preview">
                                            <div class="preview-overlay">
                                                <button type="button" class="btn-change" onclick="document.getElementById('fotoInput').click()">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                    Ganti Foto
                                                </button>
                                                <button type="button" class="btn-remove" onclick="removeImage()">
                                                    <i class="bi bi-trash"></i>
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                        <div class="preview-info">
                                            <div class="info-item">
                                                <i class="bi bi-file-earmark"></i>
                                                <span id="fileName"></span>
                                            </div>
                                            <div class="info-item">
                                                <i class="bi bi-hdd"></i>
                                                <span id="fileSize"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('foto')
                                    <div class="error-message mt-2">
                                        <i class="bi bi-exclamation-circle"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="section-footer">
                            <button type="button" class="btn-prev" onclick="prevSection(2)">
                                <i class="bi bi-arrow-left me-2"></i>
                                Kembali
                            </button>
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-check-circle me-2"></i>
                                Simpan Buku
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Quick Actions Card -->
            <div class="quick-actions-card mt-4">
                <div class="action-item">
                    <i class="bi bi-list-ul"></i>
                    <div>
                        <h6>Lihat Semua Buku</h6>
                        <p>Kembali ke daftar katalog</p>
                    </div>
                    <a href="{{ route('petugas.buku.index') }}" class="action-btn">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #667eea;
        --primary-dark: #5568d3;
        --secondary: #764ba2;
        --success: #48bb78;
        --danger: #f56565;
        --warning: #ed8936;
        --light: #f7fafc;
        --dark: #2d3748;
        --gray: #718096;
    }

    body {
        background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);
        min-height: 100vh;
    }

    /* Header Section */
    .header-section {
        position: relative;
    }

    .floating-icon {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 120px;
        height: 120px;
        font-size: 60px;
        background: white;
        border-radius: 30px;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
        position: relative;
    }

    .icon-wrapper::before {
        content: '';
        position: absolute;
        inset: -3px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 30px;
        z-index: -1;
        opacity: 0.3;
        filter: blur(10px);
    }

    .gradient-text {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Progress Steps */
    .steps-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        max-width: 600px;
        margin: 0 auto;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        opacity: 0.4;
        transition: all 0.3s;
    }

    .step.active {
        opacity: 1;
    }

    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--gray);
        transition: all 0.3s;
    }

    .step.active .step-circle {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-color: var(--primary);
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
    }

    .step-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--gray);
        white-space: nowrap;
    }

    .step.active .step-label {
        color: var(--primary);
    }

    .step-line {
        width: 80px;
        height: 3px;
        background: #e2e8f0;
        margin: 0 -10px;
        margin-bottom: 24px;
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 30px;
        padding: 50px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    /* Form Sections */
    .form-section {
        display: none;
        animation: fadeInUp 0.5s ease;
    }

    .form-section.active {
        display: block;
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

    .section-header {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 2px solid #f7fafc;
    }

    .section-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        background: linear-gradient(135deg, var(--primary)15, var(--secondary)15);
        border-radius: 20px;
        flex-shrink: 0;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .section-subtitle {
        color: var(--gray);
        margin: 4px 0 0;
        font-size: 15px;
    }

    /* Modern Form Group */
    .form-group-modern {
        margin-bottom: 30px;
    }

    .modern-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-weight: 600;
        color: var(--dark);
    }

    .label-icon {
        font-size: 20px;
    }

    .label-text {
        flex: 1;
    }

    .required-badge {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .input-wrapper {
        position: relative;
    }

    .modern-input {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s;
        background: white;
    }

    .modern-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .input-border {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        transform: scaleX(0);
        transition: transform 0.3s;
        border-radius: 0 0 12px 12px;
    }

    .modern-input:focus + .input-border {
        transform: scaleX(1);
    }

    /* Stock Controls */
    .input-with-controls {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .qty-btn {
        width: 45px;
        height: 45px;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 18px;
        color: var(--primary);
    }

    .qty-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: scale(1.05);
    }

    /* Categories Grid */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 16px;
    }

    .category-card {
        position: relative;
        cursor: pointer;
    }

    .category-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .category-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 24px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        background: white;
        transition: all 0.3s;
        position: relative;
    }

    .category-card:hover .category-content {
        border-color: var(--primary);
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
    }

    .category-card input[type="radio"]:checked + .category-content {
        background: linear-gradient(135deg, var(--primary)15, var(--secondary)15);
        border-color: var(--primary);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
    }

    .category-emoji {
        font-size: 36px;
    }

    .category-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
        text-align: center;
    }

    .category-check {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        background: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s;
    }

    .category-card input[type="radio"]:checked + .category-content .category-check {
        opacity: 1;
        transform: scale(1);
    }

    /* Upload Zone */
    .upload-zone {
        position: relative;
        border: 3px dashed #cbd5e0;
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        background: #f7fafc;
        transition: all 0.3s;
        cursor: pointer;
    }

    .upload-zone:hover {
        border-color: var(--primary);
        background: rgba(102, 126, 234, 0.05);
    }

    .upload-input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        font-size: 64px;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .upload-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .upload-subtitle {
        color: var(--gray);
        margin-bottom: 20px;
    }

    .upload-specs {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        font-size: 13px;
        color: var(--gray);
    }

    .spec-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .spec-divider {
        color: #cbd5e0;
    }

    /* Preview Container */
    .preview-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .preview-wrapper {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .preview-wrapper img {
        width: 100%;
        height: 400px;
        object-fit: contain;
        background: white;
        display: block;
    }

    .preview-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .preview-wrapper:hover .preview-overlay {
        opacity: 1;
    }

    .btn-change, .btn-remove {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-change {
        background: white;
        color: var(--primary);
    }

    .btn-remove {
        background: var(--danger);
        color: white;
    }

    .btn-change:hover, .btn-remove:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .preview-info {
        display: flex;
        justify-content: center;
        gap: 32px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--gray);
        font-weight: 500;
    }

    .info-item i {
        color: var(--primary);
        font-size: 18px;
    }

    /* Section Footer */
    .section-footer {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-top: 50px;
        padding-top: 30px;
        border-top: 2px solid #f7fafc;
    }

    .btn-prev, .btn-next, .btn-submit {
        padding: 16px 40px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-prev {
        background: white;
        color: var(--gray);
        border: 2px solid #e2e8f0;
    }

    .btn-prev:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateX(-4px);
    }

    .btn-next, .btn-submit {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        margin-left: auto;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }

    .btn-next:hover, .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
    }

    .btn-submit {
        padding: 16px 60px;
    }

    /* Quick Actions Card */
    .quick-actions-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .action-item {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .action-item > i {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: linear-gradient(135deg, var(--primary)15, var(--secondary)15);
        border-radius: 12px;
        color: var(--primary);
    }

    .action-item h6 {
        margin: 0;
        font-weight: 700;
        color: var(--dark);
    }

    .action-item p {
        margin: 0;
        font-size: 13px;
        color: var(--gray);
    }

    .action-item > div {
        flex: 1;
    }

    .action-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .action-btn:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }

    /* Error Message */
    .error-message {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--danger);
        font-size: 13px;
        margin-top: 8px;
        padding: 8px 12px;
        background: rgba(245, 101, 101, 0.1);
        border-radius: 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-card {
            padding: 30px 20px;
            border-radius: 20px;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            font-size: 40px;
        }

        .gradient-text {
            font-size: 2rem;
        }

        .steps-container {
            gap: 0;
        }

        .step-label {
            font-size: 11px;
        }

        .step-line {
            width: 40px;
        }

        .section-header {
            flex-direction: column;
            text-align: center;
        }

        .section-title {
            font-size: 20px;
        }

        .categories-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
        }

        .category-content {
            padding: 20px 12px;
        }

        .category-emoji {
            font-size: 28px;
        }

        .category-name {
            font-size: 12px;
        }

        .upload-zone {
            padding: 40px 20px;
        }

        .preview-wrapper img {
            height: 300px;
        }

        .preview-info {
            flex-direction: column;
            gap: 12px;
        }

        .section-footer {
            flex-direction: column;
        }

        .btn-prev, .btn-next, .btn-submit {
            width: 100%;
            justify-content: center;
        }

        .btn-next, .btn-submit {
            margin-left: 0;
        }
    }
</style>

<script>
    // Section Navigation
    function nextSection(sectionNum) {
        // Validate current section
        const currentSection = document.querySelector('.form-section.active');
        const inputs = currentSection.querySelectorAll('input[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value || (input.type === 'radio' && !document.querySelector(`input[name="${input.name}"]:checked`))) {
                isValid = false;
                input.focus();
                
                // Add shake animation to invalid inputs
                input.style.animation = 'shake 0.5s';
                setTimeout(() => {
                    input.style.animation = '';
                }, 500);
            }
        });

        if (!isValid) {
            return;
        }

        // Switch sections
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.remove('active');
        });
        document.querySelector(`.form-section[data-section="${sectionNum}"]`).classList.add('active');

        // Update steps
        document.querySelectorAll('.step').forEach((step, index) => {
            if (index < sectionNum) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function prevSection(sectionNum) {
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.remove('active');
        });
        document.querySelector(`.form-section[data-section="${sectionNum}"]`).classList.add('active');

        // Update steps
        document.querySelectorAll('.step').forEach((step, index) => {
            if (index < sectionNum) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Stock Controls
    function incrementStock() {
        const input = document.getElementById('stokInput');
        input.value = parseInt(input.value || 0) + 1;
    }

    function decrementStock() {
        const input = document.getElementById('stokInput');
        const currentValue = parseInt(input.value || 0);
        if (currentValue > 0) {
            input.value = currentValue - 1;
        }
    }

    // File Upload Handler
    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            event.target.value = '';
            return;
        }

        // Validate file type
        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar!');
            event.target.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('uploadContent').style.display = 'none';
            document.getElementById('previewContainer').style.display = 'flex';
            
            // Set file info
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
        };
        reader.readAsDataURL(file);
    }

    function removeImage() {
        document.getElementById('fotoInput').value = '';
        document.getElementById('uploadContent').style.display = 'block';
        document.getElementById('previewContainer').style.display = 'none';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Drag and drop functionality
    const uploadZone = document.getElementById('uploadZone');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadZone.addEventListener(eventName, () => {
            uploadZone.style.borderColor = 'var(--primary)';
            uploadZone.style.background = 'rgba(102, 126, 234, 0.1)';
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, () => {
            uploadZone.style.borderColor = '#cbd5e0';
            uploadZone.style.background = '#f7fafc';
        });
    });

    uploadZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('fotoInput').files = files;
        handleFileSelect({ target: { files: files } });
    });

    // Shake animation for validation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection