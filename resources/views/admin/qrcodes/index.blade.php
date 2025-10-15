@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar QR Code</h2>
    <a href="{{ route('admin.qrcodes.create') }}" class="btn btn-primary mb-3">Tambah QR Code</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama File</th>
                <th>Dibuat Oleh</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($qrcodes as $qr)
            <tr>
                <td>{{ $qr->id }}</td>
                <td>{{ $qr->kode }}</td>
                <td>{{ $qr->nama_file }}</td>
                <td>{{ $qr->user?->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.qrcodes.edit', $qr->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.qrcodes.destroy', $qr->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus data ini?')" class="btn btn-danger btn-sm">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
