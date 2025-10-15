@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah QR Code</h2>
    <form action="{{ route('admin.qrcodes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nama File</label>
            <input type="text" name="nama_file" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>User (Opsional)</label>
            <select name="user_id" class="form-control">
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.qrcodes.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
