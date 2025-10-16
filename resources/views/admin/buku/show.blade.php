@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Detail Buku</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Judul:</strong> {{ $buku->judul }}</p>
            <p><strong>Penulis:</strong> {{ $buku->penulis }}</p>
            <p><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
            <p><strong>Tahun Terbit:</strong> {{ $buku->tahun_terbit }}</p>

            <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">Kembali</a>

            <form action="{{ route('admin.buku.destroy', $buku->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection
