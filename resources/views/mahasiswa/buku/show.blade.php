@extends('layouts.mahasiswa')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-4">
                @if ($buku->foto)
                    <img src="{{ asset('storage/' . $buku->foto) }}" class="img-fluid rounded-start" alt="{{ $buku->judul }}" style="max-height: 500px; object-fit: cover;">
                @else
                    <img src="{{ asset('images/no-image.png') }}" class="img-fluid rounded-start" alt="No Image" style="max-height: 500px; object-fit: cover;">
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

                    <div class="d-flex gap-2">
                        <form action="{{ route('mahasiswa.buku.pinjam', $buku->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin meminjam buku ini?')">
                            @csrf
                            <button type="submit" class="btn btn-primary" {{ $buku->stok <= 0 ? 'disabled' : '' }}>
                                <i class="bi bi-book me-2"></i>Pinjam Buku
                            </button>
                        </form>
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
</div>
@endsection