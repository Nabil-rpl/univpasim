@extends('layouts.mahasiswa')

@section('content')
    <h2>📚 Daftar Buku</h2>

    <form action="{{ route('mahasiswa.buku.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari buku..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </div>
    </form>

    <div class="row">
        @forelse ($buku as $item)
            <div class="col-md-3 mb-4">
                <div class="card">
                    @if ($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" class="card-img-top" alt="Foto Buku">
                    @else
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Foto Buku">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->judul }}</h5>
                        <p class="card-text">
                            Penulis: {{ $item->penulis }} <br>
                            Penerbit: {{ $item->penerbit }}
                        </p>
                        <a href="{{ route('mahasiswa.buku.show', $item->id) }}" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Tidak ada buku ditemukan.</p>
        @endforelse
    </div>

    {{ $buku->links() }}
@endsection