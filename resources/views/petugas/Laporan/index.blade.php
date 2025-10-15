@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>📋 Daftar Laporan Kamu</h3>
    <a href="{{ route('petugas.laporan.create') }}" class="btn btn-primary mb-3">+ Tambah Laporan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Status</th>
                <th>Dibuat Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $laporan->judul }}</td>
                <td>{{ $laporan->status }}</td>
                <td>{{ $laporan->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('petugas.laporan.edit', $laporan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('petugas.laporan.destroy', $laporan->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
