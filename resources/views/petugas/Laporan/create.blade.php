@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>🆕 Tambah Laporan Baru</h3>

    <form action="{{ route('petugas.laporan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="judul" class="form-label">Judul Laporan</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="isi" class="form-label">Isi Laporan</label>
            <textarea name="isi" rows="5" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('petugas.laporan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
