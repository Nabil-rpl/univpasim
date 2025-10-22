@extends('layouts.petugas')

@section('content')
<div class="container mt-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('petugas.buku.index') }}">Daftar Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Buku</li>
        </ol>
    </nav>

    <div class="card shadow-lg border-0 overflow-hidden">
        {{-- Header Card dengan Gradient --}}
        <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-book-fill"></i> Detail Buku
                </h4>
                <div>
                    <a href="{{ route('petugas.buku.edit', $buku->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-0">
            {{-- Kolom Gambar Buku --}}
            <div class="col-lg-4">
                <div class="p-4 bg-light h-100 d-flex flex-column align-items-center justify-content-center">
                    @if ($buku->foto && file_exists(public_path('storage/' . $buku->foto)))
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $buku->foto) }}" 
                                 alt="{{ $buku->judul }}" 
                                 class="img-fluid rounded shadow-lg"
                                 style="max-height: 400px; object-fit: cover; border: 5px solid white;">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-dark bg-opacity-75">
                                    <i class="bi bi-image"></i> Cover Buku
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-center p-5">
                            <div class="mb-3">
                                <i class="bi bi-image text-muted" style="font-size: 6rem;"></i>
                            </div>
                            <p class="text-muted fst-italic mb-0">Tidak ada cover buku</p>
                        </div>
                    @endif

                    {{-- Statistik Singkat --}}
                    <div class="w-100 mt-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card text-center shadow-sm border-0">
                                    <div class="card-body py-2">
                                        <i class="bi bi-box-seam text-primary" style="font-size: 1.5rem;"></i>
                                        <h5 class="mb-0 mt-2">{{ $buku->stok }}</h5>
                                        <small class="text-muted">Stok</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card text-center shadow-sm border-0">
                                    <div class="card-body py-2">
                                        <i class="bi bi-calendar text-success" style="font-size: 1.5rem;"></i>
                                        <h5 class="mb-0 mt-2">{{ $buku->tahun_terbit }}</h5>
                                        <small class="text-muted">Tahun</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Detail Buku --}}
            <div class="col-lg-8">
                <div class="card-body p-4">
                    {{-- Judul Buku --}}
                    <div class="mb-4">
                        <h2 class="fw-bold text-primary mb-2">{{ $buku->judul }}</h2>
                        @if($buku->kategori)
                            <span class="badge bg-primary bg-opacity-75 px-3 py-2">
                                <i class="bi bi-tag"></i> {{ $buku->kategori }}
                            </span>
                        @endif
                    </div>

                    {{-- Informasi Detail --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="detail-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Penulis</small>
                                        <strong>{{ $buku->penulis }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-building text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Penerbit</small>
                                        <strong>{{ $buku->penerbit }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-calendar-event text-warning"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tahun Terbit</small>
                                        <strong>{{ $buku->tahun_terbit }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-box-seam text-info"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Stok Tersedia</small>
                                        <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                            {{ $buku->stok }} Unit
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-clock-history text-secondary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Ditambahkan</small>
                                        <strong>{{ $buku->created_at->translatedFormat('d F Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $buku->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-arrow-repeat text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Terakhir Diupdate</small>
                                        <strong>{{ $buku->updated_at->diffForHumans() }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Bagian QR Code --}}
                    <div class="qr-section">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-qr-code-scan"></i> QR Code Buku
                        </h5>

                        @if ($buku->qrCode)
                            <div class="row align-items-center">
                                <div class="col-md-5 text-center">
                                    <div class="qr-container p-3 bg-light rounded shadow-sm">
                                        <img src="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                             alt="QR Code {{ $buku->judul }}" 
                                             class="img-fluid border rounded p-2 bg-white"
                                             style="max-width: 200px;">
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1">Kode Unik</small>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control bg-dark text-white font-monospace" 
                                                   value="{{ $buku->qrCode->kode_unik }}" 
                                                   id="kodeUnik"
                                                   readonly>
                                            <button class="btn btn-outline-secondary" 
                                                    type="button"
                                                    onclick="copyCode()">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="card bg-light border-0">
                                            <div class="card-body py-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-person-badge me-1"></i>
                                                    Dibuat oleh: <strong>{{ $buku->qrCode->user->name ?? 'System' }}</strong>
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $buku->qrCode->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                           download="QR-{{ $buku->judul }}.svg"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-download"></i> Download
                                        </a>

                                        <form action="{{ route('petugas.buku.regenerateQR', $buku->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Regenerate QR Code? QR lama akan dihapus.')">
                                            @csrf
                                            <button class="btn btn-warning btn-sm">
                                                <i class="bi bi-arrow-repeat"></i> Regenerate
                                            </button>
                                        </form>

                                        <form action="{{ route('petugas.qrcode.destroy', $buku->qrCode->id) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Hapus QR Code ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div>
                                    Belum ada QR Code untuk buku ini.
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('petugas.qrcode.generate', ['type' => 'buku', 'id' => $buku->id]) }}" 
                                   class="btn btn-success">
                                    <i class="bi bi-qr-code"></i> Buat QR Code
                                </a>
                            </div>
                        @endif
                    </div>

                    <hr class="my-4">

                    {{-- Tombol Aksi --}}
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('petugas.buku.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('petugas.buku.edit', $buku->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Buku
                        </a>
                        <form action="{{ route('petugas.buku.destroy', $buku->id) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Hapus Buku
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast Notification untuk Success Message --}}
@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong class="me-auto">Berhasil</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.icon-box {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 10px;
    font-size: 1.2rem;
}

.detail-item {
    padding: 10px;
    border-radius: 8px;
    transition: background-color 0.3s;
}

.detail-item:hover {
    background-color: #f8f9fa;
}

.qr-container {
    transition: transform 0.3s;
}

.qr-container:hover {
    transform: scale(1.05);
}

.breadcrumb {
    background-color: #f8f9fa;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
}

.card {
    border-radius: 15px;
}

.btn {
    border-radius: 8px;
    transition: all 0.3s;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

.toast {
    min-width: 300px;
}

/* Animation untuk toast */
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.toast.show {
    animation: slideIn 0.3s ease-out;
}
</style>

<script>
// Copy kode unik ke clipboard
function copyCode() {
    const codeInput = document.getElementById('kodeUnik');
    codeInput.select();
    codeInput.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(codeInput.value).then(() => {
        // Tampilkan notifikasi sukses
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i>';
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
        }, 2000);
    });
}

// Auto hide toast after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const toast = document.querySelector('.toast');
    if (toast) {
        setTimeout(() => {
            toast.classList.remove('show');
        }, 5000);
    }
});
</script>
@endsection