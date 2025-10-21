@extends('layouts.mahasiswa')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-4">
                @if ($buku->foto)
                    <img src="{{ asset('storage/' . $buku->foto) }}" class="img-fluid rounded-start" alt="{{ $buku->judul }}" style="max-height: 500px; object-fit: cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded-start" style="height: 500px;">
                        <div class="text-center">
                            <i class="bi bi-book-fill text-muted" style="font-size: 5rem;"></i>
                            <p class="text-muted mt-3">No Image</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title mb-3">{{ $buku->judul }}</h3>
                    
                    <div class="mb-4">
                        <p class="mb-2"><strong>Penulis:</strong> {{ $buku->penulis }}</p>
                        <p class="mb-2"><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                        <p class="mb-2"><strong>Tahun Terbit:</strong> {{ $buku->tahun_terbit }}</p>
                        <p class="mb-2">
                            <strong>Stok:</strong> 
                            <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $buku->stok }} tersedia
                            </span>
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- ✅ QR Code Section - Untuk Meminjam Buku --}}
                    @if ($buku->qrCode)
                        <div class="card bg-light mb-3">
                            <div class="card-body text-center">
                                <h5 class="mb-3"><i class="bi bi-qr-code me-2"></i>Scan QR Code untuk Meminjam</h5>
                                
                                {{-- Tampilkan QR Code --}}
                                <div class="qr-code-container mb-3">
                                    <img src="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                         alt="QR Code {{ $buku->judul }}" 
                                         class="img-fluid border rounded p-3 bg-white shadow-sm"
                                         style="max-width: 250px;">
                                </div>

                                <p class="text-muted mb-2">
                                    <small>Kode: <code>{{ $buku->qrCode->kode_unik }}</code></small>
                                </p>

                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Scan QR Code ini menggunakan <strong>aplikasi scanner</strong> atau 
                                    <a href="{{ route('mahasiswa.qr.scanner') }}" class="alert-link">klik di sini</a> 
                                    untuk membuka scanner
                                </div>

                                {{-- Tombol Buka Scanner --}}
                                @if ($buku->stok > 0)
                                    <a href="{{ route('mahasiswa.qr.scanner') }}" class="btn btn-primary btn-lg">
                                        <i class="bi bi-camera me-2"></i>Buka QR Scanner
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-lg" disabled>
                                        <i class="bi bi-camera me-2"></i>Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            QR Code untuk buku ini belum tersedia. Silakan hubungi petugas.
                        </div>
                    @endif

                    {{-- Tombol Kembali --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('mahasiswa.buku.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>

                    @if ($buku->stok <= 0)
                        <div class="alert alert-warning mt-3" role="alert">
                            <i class="bi bi-info-circle me-2"></i>Stok buku sedang habis. Silakan coba lagi nanti.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Instruksi Cara Meminjam --}}
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Cara Meminjam Buku</h5>
        </div>
        <div class="card-body">
            <ol class="mb-0">
                <li class="mb-2">Klik tombol <strong>"Buka QR Scanner"</strong> di atas</li>
                <li class="mb-2">Arahkan kamera ke <strong>QR Code buku</strong></li>
                <li class="mb-2">Sistem akan menampilkan <strong>konfirmasi peminjaman</strong></li>
                <li class="mb-0">Klik <strong>"Ya, Pinjam Buku"</strong> untuk menyelesaikan peminjaman</li>
            </ol>
        </div>
    </div>
</div>

<style>
.qr-code-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.qr-code-container img {
    transition: transform 0.3s ease;
}

.qr-code-container img:hover {
    transform: scale(1.05);
}

code {
    background-color: #f8f9fa;
    padding: 2px 6px;
    border-radius: 4px;
    color: #d63384;
}
</style>
@endsection