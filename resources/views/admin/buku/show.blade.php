@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-light px-3 py-2 rounded">
            <li class="breadcrumb-item"><a href="{{ route('admin.buku.index') }}" class="text-decoration-none">📚 Daftar Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Buku</li>
        </ol>
    </nav>

    <div class="card shadow-lg border-0 overflow-hidden">
        {{-- Header dengan Gradient Premium --}}
        <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">
                        <i class="bi bi-book-half"></i> Detail Informasi Buku
                    </h3>
                    <p class="mb-0 opacity-75 small">Informasi lengkap tentang buku perpustakaan</p>
                </div>
                <div>
                    <a href="{{ route('admin.buku.index') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-0">
            {{-- Kolom Gambar & Stats --}}
            <div class="col-lg-4 bg-light border-end">
                <div class="p-4 h-100 d-flex flex-column">
                    {{-- Cover Buku --}}
                    <div class="text-center mb-4">
                        @if ($buku->foto && file_exists(public_path('storage/' . $buku->foto)))
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('storage/' . $buku->foto) }}" 
                                     alt="{{ $buku->judul }}" 
                                     class="img-fluid rounded-3 shadow-lg book-cover"
                                     style="max-height: 400px; object-fit: cover; border: 5px solid white;">
                                
                                {{-- Badge Overlay --}}
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }} px-3 py-2 shadow">
                                        <i class="bi bi-box-seam"></i> Stok: {{ $buku->stok }}
                                    </span>
                                </div>

                                @if($buku->kategori)
                                <div class="position-absolute bottom-0 start-0 end-0 m-2">
                                    <span class="badge bg-primary bg-opacity-90 w-100 py-2 shadow">
                                        <i class="bi bi-tag"></i> {{ $buku->kategori }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-white rounded-3 shadow p-5 text-center">
                                <i class="bi bi-image-fill text-muted" style="font-size: 6rem; opacity: 0.2;"></i>
                                <p class="text-muted fst-italic mt-3 mb-0">Tidak ada cover buku</p>
                            </div>
                        @endif
                    </div>

                    {{-- Quick Stats Cards --}}
                    <div class="mt-auto">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card border-0 shadow-sm text-center stat-card">
                                    <div class="card-body py-3">
                                        <div class="stat-icon bg-primary bg-opacity-10 text-primary mb-2 mx-auto">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <h4 class="mb-0 fw-bold">{{ $buku->tahun_terbit }}</h4>
                                        <small class="text-muted">Tahun Terbit</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 shadow-sm text-center stat-card">
                                    <div class="card-body py-3">
                                        <div class="stat-icon bg-success bg-opacity-10 text-success mb-2 mx-auto">
                                            <i class="bi bi-stack"></i>
                                        </div>
                                        <h4 class="mb-0 fw-bold">{{ $buku->stok }}</h4>
                                        <small class="text-muted">Stok Buku</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- QR Code Indicator --}}
                        @if($buku->qrCode)
                        <div class="card border-0 shadow-sm mt-2 bg-info bg-opacity-10">
                            <div class="card-body py-2 text-center">
                                <i class="bi bi-qr-code-scan text-info"></i>
                                <small class="text-info fw-semibold ms-1">QR Code Tersedia</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Detail Informasi --}}
            <div class="col-lg-8">
                <div class="p-4">
                    {{-- Judul Buku --}}
                    <div class="mb-4">
                        <h2 class="fw-bold text-primary mb-3">{{ $buku->judul }}</h2>
                        <div class="d-flex gap-2 flex-wrap">
                            @if($buku->kategori)
                                <span class="badge bg-primary bg-opacity-75 px-3 py-2">
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

                    <hr class="my-4">

                    {{-- Detail Informasi Grid --}}
                    <h5 class="fw-bold mb-3 text-secondary">
                        <i class="bi bi-info-circle"></i> Informasi Detail
                    </h5>

                    <div class="row g-3 mb-4">
                        {{-- Penulis --}}
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 h-100 border">
                                <div class="d-flex align-items-start">
                                    <div class="icon-box me-3 flex-shrink-0">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-1">Penulis</small>
                                        <h5 class="mb-0 fw-bold">{{ $buku->penulis }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Penerbit --}}
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 h-100 border">
                                <div class="d-flex align-items-start">
                                    <div class="icon-box me-3 flex-shrink-0">
                                        <i class="bi bi-building text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-1">Penerbit</small>
                                        <h5 class="mb-0 fw-bold">{{ $buku->penerbit }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tahun Terbit --}}
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 h-100 border">
                                <div class="d-flex align-items-start">
                                    <div class="icon-box me-3 flex-shrink-0">
                                        <i class="bi bi-calendar-check text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-1">Tahun Terbit</small>
                                        <h5 class="mb-0 fw-bold">{{ $buku->tahun_terbit }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Stok --}}
                        <div class="col-md-6">
                            <div class="info-card p-3 rounded-3 h-100 border">
                                <div class="d-flex align-items-start">
                                    <div class="icon-box me-3 flex-shrink-0">
                                        <i class="bi bi-box-seam text-info"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-1">Stok Tersedia</small>
                                        <h5 class="mb-0">
                                            <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                                {{ $buku->stok }} Unit
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Informasi Sistem --}}
                    <h5 class="fw-bold mb-3 text-secondary">
                        <i class="bi bi-clock-history"></i> Informasi Sistem
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light border-0 h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-primary text-white me-3">
                                            <i class="bi bi-plus-circle"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Ditambahkan</small>
                                            <strong>{{ $buku->created_at->translatedFormat('d F Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $buku->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light border-0 h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-success text-white me-3">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Terakhir Diupdate</small>
                                            <strong>{{ $buku->updated_at->diffForHumans() }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- QR Code Section --}}
                    @if($buku->qrCode)
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3 text-secondary">
                        <i class="bi bi-qr-code-scan"></i> QR Code Buku
                    </h5>
                    
                    <div class="qr-section p-4 bg-light rounded-3 border-2 border-dashed">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <div class="qr-container p-3 bg-white rounded shadow-sm">
                                    <img src="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                         alt="QR Code" 
                                         class="img-fluid"
                                         style="max-width: 180px;">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-2">
                                        <i class="bi bi-key"></i> Kode Unik
                                    </small>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control bg-dark text-white font-monospace" 
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
                                <div class="card bg-white border-0 shadow-sm">
                                    <div class="card-body py-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-badge text-primary me-2"></i>
                                            <small class="text-muted">
                                                Dibuat oleh: 
                                                @if($buku->qrCode->petugas)
                                                    <strong class="text-primary">{{ $buku->qrCode->petugas->name }}</strong>
                                                    <span class="badge bg-info ms-1">Petugas</span>
                                                @else
                                                    <strong class="text-secondary">System</strong>
                                                @endif
                                            </small>
                                        </div>
                                        <div class="d-flex align-items-center mt-1">
                                            <i class="bi bi-clock text-success me-2"></i>
                                            <small class="text-muted">
                                                {{ $buku->qrCode->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <hr class="my-4">

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-2 flex-wrap justify-content-end">
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left-circle"></i> Kembali ke Daftar
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
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Penghapusan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div class="warning-icon">
                        <i class="bi bi-exclamation-triangle text-warning"></i>
                    </div>
                </div>
                <p class="text-center mb-2 fs-5">Yakin ingin menghapus buku ini?</p>
                <div class="text-center mb-3">
                    <div class="alert alert-light border">
                        <strong class="text-primary">"{{ $buku->judul }}"</strong>
                    </div>
                </div>
                <div class="alert alert-warning mb-0">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                        <small>
                            <strong>Perhatian!</strong> Data yang sudah dihapus tidak dapat dikembalikan lagi.
                        </small>
                    </div>
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
                        <i class="bi bi-trash"></i> Ya, Hapus Sekarang!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Gradient Background */
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Book Cover Animation */
.book-cover {
    transition: transform 0.3s ease;
}

.book-cover:hover {
    transform: scale(1.02);
}

/* Icon Boxes */
.icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 12px;
    font-size: 1.4rem;
}

/* Icon Circle */
.icon-circle {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 1.2rem;
    flex-shrink: 0;
}

/* Info Cards */
.info-card {
    background: #ffffff;
    transition: all 0.3s ease;
}

.info-card:hover {
    background: #f8f9fa;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

/* Stat Cards */
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}

.stat-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 1.2rem;
}

/* QR Section */
.qr-section {
    border: 2px dashed #dee2e6;
}

.qr-container {
    transition: transform 0.3s ease;
}

.qr-container:hover {
    transform: scale(1.03);
}

/* Breadcrumb */
.breadcrumb {
    border: 1px solid #e9ecef;
}

/* Modal Warning Icon */
.warning-icon i {
    font-size: 5rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Buttons */
.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Badge */
.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Card */
.card {
    border-radius: 12px;
}

/* Border Dashed */
.border-dashed {
    border-style: dashed !important;
}
</style>

<script>
// Copy kode unik ke clipboard
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