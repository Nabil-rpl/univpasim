@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white px-4 py-3 rounded-3 shadow-sm border">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.buku.index') }}" class="text-decoration-none text-primary">
                    <i class="bi bi-book-fill"></i> Daftar Buku
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Detail Buku</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-lg overflow-hidden">
        {{-- Header --}}
        <div class="card-header bg-gradient-primary text-white py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 fw-bold">
                        <i class="bi bi-book-half me-2"></i>Detail Buku
                    </h3>
                    <p class="mb-0 opacity-75">Informasi lengkap buku perpustakaan</p>
                </div>
                <a href="{{ route('admin.buku.index') }}" class="btn btn-light shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row g-0">
            {{-- Sidebar: Cover & Stats --}}
            <div class="col-lg-4 bg-light border-end">
                <div class="p-4">
                    {{-- Cover Buku --}}
                    <div class="text-center mb-4">
                        @if ($buku->foto && file_exists(public_path('storage/' . $buku->foto)))
                            <div class="cover-wrapper">
                                <img src="{{ asset('storage/' . $buku->foto) }}" 
                                     alt="{{ $buku->judul }}" 
                                     class="img-fluid rounded-3 shadow-lg"
                                     style="max-height: 420px; width: 100%; object-fit: cover;">
                                
                                {{-- Badge Stok --}}
                                <span class="badge-stok badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }} shadow">
                                    <i class="bi bi-box-seam"></i> {{ $buku->stok }} Unit
                                </span>
                            </div>
                        @else
                            <div class="no-cover bg-white rounded-3 shadow d-flex align-items-center justify-content-center" style="height: 420px;">
                                <div class="text-center">
                                    <i class="bi bi-image text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                                    <p class="text-muted mt-3">Tidak ada cover</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Quick Stats --}}
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-card text-center">
                                <div class="stat-icon bg-primary-subtle">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                </div>
                                <h4 class="stat-value">{{ $buku->tahun_terbit }}</h4>
                                <p class="stat-label">Tahun Terbit</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card text-center">
                                <div class="stat-icon bg-success-subtle">
                                    <i class="bi bi-stack text-success"></i>
                                </div>
                                <h4 class="stat-value">{{ $buku->stok }}</h4>
                                <p class="stat-label">Stok Buku</p>
                            </div>
                        </div>
                    </div>

                    {{-- QR Indicator --}}
                    @if($buku->qrCode)
                    <div class="alert alert-info d-flex align-items-center mt-3 mb-0">
                        <i class="bi bi-qr-code-scan fs-4 me-2"></i>
                        <span class="fw-semibold">QR Code Tersedia</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Content: Detail Information --}}
            <div class="col-lg-8">
                <div class="p-4">
                    {{-- Judul & Badges --}}
                    <div class="mb-4">
                        <h2 class="fw-bold text-dark mb-3">{{ $buku->judul }}</h2>
                        <div class="d-flex gap-2 flex-wrap">
                            @if($buku->kategori)
                                <span class="badge bg-primary px-3 py-2">
                                    <i class="bi bi-tag-fill"></i> {{ $buku->kategori }}
                                </span>
                            @endif
                            <span class="badge bg-info px-3 py-2">
                                <i class="bi bi-calendar-check"></i> {{ $buku->tahun_terbit }}
                            </span>
                            <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                <i class="bi bi-box-seam"></i> Stok: {{ $buku->stok }}
                            </span>
                        </div>
                    </div>

                    {{-- Section: Informasi Detail --}}
                    <section class="mb-4">
                        <h5 class="section-title mb-3">
                            <i class="bi bi-info-circle me-2"></i>Informasi Detail
                        </h5>

                        <div class="row g-3">
                            {{-- Penulis --}}
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-icon bg-primary-subtle">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>Penulis</label>
                                        <p>{{ $buku->penulis }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Penerbit --}}
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-icon bg-success-subtle">
                                        <i class="bi bi-building text-success"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>Penerbit</label>
                                        <p>{{ $buku->penerbit }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Tahun Terbit --}}
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-icon bg-warning-subtle">
                                        <i class="bi bi-calendar-check text-warning"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>Tahun Terbit</label>
                                        <p>{{ $buku->tahun_terbit }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Stok --}}
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-icon bg-info-subtle">
                                        <i class="bi bi-box-seam text-info"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>Stok Tersedia</label>
                                        <p>
                                            <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                                {{ $buku->stok }} Unit
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Section: Informasi Sistem --}}
                    <section class="mb-4">
                        <h5 class="section-title mb-3">
                            <i class="bi bi-clock-history me-2"></i>Informasi Sistem
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="system-info">
                                    <div class="system-icon bg-primary">
                                        <i class="bi bi-plus-circle"></i>
                                    </div>
                                    <div class="system-content">
                                        <label>Ditambahkan</label>
                                        <p class="mb-0">{{ $buku->created_at->translatedFormat('d F Y') }}</p>
                                        <small class="text-muted">{{ $buku->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="system-info">
                                    <div class="system-icon bg-success">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>
                                    <div class="system-content">
                                        <label>Terakhir Diupdate</label>
                                        <p class="mb-0">{{ $buku->updated_at->translatedFormat('d F Y') }}</p>
                                        <small class="text-muted">{{ $buku->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Section: QR Code --}}
                    @if($buku->qrCode)
                    <section class="mb-4">
                        <h5 class="section-title mb-3">
                            <i class="bi bi-qr-code-scan me-2"></i>QR Code Buku
                        </h5>

                        <div class="qr-section">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center">
                                    <div class="qr-container">
                                        <img src="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                             alt="QR Code" 
                                             class="img-fluid rounded shadow">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    {{-- Kode Unik --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-key-fill me-1"></i>Kode Unik
                                        </label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control form-control-lg font-monospace bg-dark text-white border-0" 
                                                   value="{{ $buku->qrCode->kode_unik }}" 
                                                   id="kodeUnik"
                                                   readonly>
                                            <button class="btn btn-outline-secondary" 
                                                    type="button"
                                                    onclick="copyCode()"
                                                    title="Copy kode">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Info Pembuat --}}
                                    <div class="qr-meta">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-person-badge text-primary me-2"></i>
                                            <small>
                                                Dibuat oleh: 
                                                @if($buku->qrCode->petugas)
                                                    <strong>{{ $buku->qrCode->petugas->name }}</strong>
                                                    <span class="badge bg-primary badge-sm ms-1">Petugas</span>
                                                @else
                                                    <strong>System</strong>
                                                @endif
                                            </small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock text-success me-2"></i>
                                            <small class="text-muted">
                                                {{ $buku->qrCode->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-2 flex-wrap justify-content-end mt-4 pt-3 border-top">
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        
                        <button type="button" 
                                class="btn btn-danger btn-lg"
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i> Hapus Buku
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Penghapusan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <i class="bi bi-exclamation-triangle text-warning modal-warning-icon"></i>
                </div>
                <p class="text-center mb-3 fs-5 fw-semibold">Yakin ingin menghapus buku ini?</p>
                <div class="alert alert-light border text-center mb-3">
                    <strong class="text-primary">"{{ $buku->judul }}"</strong>
                </div>
                <div class="alert alert-warning d-flex align-items-start mb-0">
                    <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                    <small>
                        <strong>Perhatian!</strong> Data yang sudah dihapus tidak dapat dikembalikan lagi.
                    </small>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Batal
                </button>
                <form action="{{ route('admin.buku.destroy', $buku->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== VARIABLES ===== */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
    --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
    --radius: 12px;
    --transition: all 0.3s ease;
}

/* ===== HEADER ===== */
.bg-gradient-primary {
    background: var(--primary-gradient);
}

/* ===== BREADCRUMB ===== */
.breadcrumb {
    margin-bottom: 0;
}

/* ===== COVER IMAGE ===== */
.cover-wrapper {
    position: relative;
}

.cover-wrapper img {
    transition: var(--transition);
}

.cover-wrapper:hover img {
    transform: scale(1.02);
}

.badge-stok {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

/* ===== STAT CARDS ===== */
.stat-card {
    background: white;
    border-radius: var(--radius);
    padding: 1.25rem;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 1.5rem;
    margin: 0 auto 0.75rem;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0.5rem 0 0.25rem;
    color: #2d3748;
}

.stat-label {
    font-size: 0.875rem;
    color: #718096;
    margin: 0;
}

/* ===== SECTION TITLE ===== */
.section-title {
    font-weight: 700;
    color: #2d3748;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e2e8f0;
}

/* ===== INFO BOX ===== */
.info-box {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: var(--radius);
    transition: var(--transition);
    height: 100%;
}

.info-box:hover {
    background: #f7fafc;
    box-shadow: var(--shadow-sm);
    transform: translateY(-2px);
}

.info-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-content label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #718096;
    margin-bottom: 0.25rem;
    display: block;
    letter-spacing: 0.5px;
}

.info-content p {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

/* ===== SYSTEM INFO ===== */
.system-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    height: 100%;
}

.system-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.system-content label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #718096;
    margin-bottom: 0.25rem;
    display: block;
    letter-spacing: 0.5px;
}

.system-content p {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.95rem;
}

/* ===== QR CODE SECTION ===== */
.qr-section {
    background: #f7fafc;
    border: 2px dashed #cbd5e0;
    border-radius: var(--radius);
    padding: 1.5rem;
}

.qr-container {
    background: white;
    padding: 1rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.qr-container:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-md);
}

.qr-meta {
    background: white;
    padding: 1rem;
    border-radius: var(--radius);
    border: 1px solid #e2e8f0;
}

/* ===== BUTTONS ===== */
.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: var(--transition);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
}

/* ===== BADGES ===== */
.badge {
    font-weight: 600;
    letter-spacing: 0.3px;
}

.badge-sm {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

/* ===== MODAL ===== */
.modal-warning-icon {
    font-size: 5rem;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(0.95); }
}

/* ===== CARD ===== */
.card {
    border-radius: var(--radius);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 0.75rem;
    }
    
    .info-box {
        margin-bottom: 0.75rem;
    }
    
    .qr-section .col-md-4 {
        margin-bottom: 1.5rem;
    }
}
</style>

<script>
function copyCode() {
    const codeInput = document.getElementById('kodeUnik');
    codeInput.select();
    codeInput.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(codeInput.value).then(() => {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i class="bi bi-check-lg"></i>';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    });
}
</script>
@endsection