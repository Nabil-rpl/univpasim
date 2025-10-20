@extends('layouts.mahasiswa')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-4">
                @if ($buku->foto)
                    <img src="{{ asset('storage/' . $buku->foto) }}" class="img-fluid rounded-start" alt="{{ $buku->judul }}">
                @else
                    <img src="{{ asset('images/no-image.png') }}" class="img-fluid rounded-start" alt="No Image">
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title">{{ $buku->judul }}</h3>
                    <p class="card-text">
                        <strong>Penulis:</strong> {{ $buku->penulis }}<br>
                        <strong>Penerbit:</strong> {{ $buku->penerbit }}<br>
                        <strong>Tahun:</strong> {{ $buku->tahun_terbit }}<br>
                        <strong>Stok:</strong> {{ $buku->stok }}
                    </p>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @elseif (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('mahasiswa.buku.pinjam', $buku->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" {{ $buku->stok <= 0 ? 'disabled' : '' }}>
                            📘 Pinjam Buku
                        </button>
                        <a href="{{ route('mahasiswa.buku.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
