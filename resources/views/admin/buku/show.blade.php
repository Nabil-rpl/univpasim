@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📖 Detail Buku</h4>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Gambar Buku --}}
                <div class="col-md-4 text-center">
                    @if ($buku->foto && file_exists(public_path('storage/' . $buku->foto)))
                        <img src="{{ asset('storage/' . $buku->foto) }}" class="img-fluid rounded shadow" alt="Foto Buku">
                    @else
                        <p class="text-muted fst-italic">Tidak ada foto.</p>
                    @endif
                </div>

                {{-- Informasi Buku --}}
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr><th>Judul</th><td>{{ $buku->judul }}</td></tr>
                        <tr><th>Penulis</th><td>{{ $buku->penulis }}</td></tr>
                        <tr><th>Penerbit</th><td>{{ $buku->penerbit }}</td></tr>
                        <tr><th>Tahun Terbit</th><td>{{ $buku->tahun_terbit }}</td></tr>
                        <tr><th>Stok</th><td>{{ $buku->stok }}</td></tr>
                    </table>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">⬅ Kembali</a>
                <form action="{{ route('admin.buku.destroy', $buku->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">🗑 Hapus Buku</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
