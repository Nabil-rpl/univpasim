@extends('layouts.mahasiswa')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-center">📚 Daftar Buku</h3>

    <form action="{{ route('mahasiswa.buku.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari buku...">
            <button class="btn btn-primary">Cari</button>
        </div>
    </form>

    <div class="row">
        @forelse ($buku as $item)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    @if ($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" class="card-img-top" alt="{{ $item->judul }}">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No Image">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->judul }}</h5>
                        <p class="card-text text-muted">
                            <strong>Penulis:</strong> {{ $item->penulis }} <br>
                            <strong>Penerbit:</strong> {{ $item->penerbit }}
                        </p>
                        <a href="{{ route('mahasiswa.buku.show', $item->id) }}" class="btn btn-sm btn-outline-primary w-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Tidak ada buku ditemukan.</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $buku->links() }}
    </div>
</div>
@endsection
