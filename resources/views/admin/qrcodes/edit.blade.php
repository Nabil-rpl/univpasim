@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit QR Code</h2>
    <form action="{{ route('admin.qrcodes.update', $qrcode->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control" value="{{ $qrcode->kode }}" required>
        </div>

        <div class="mb-3">
            <label>Nama File</label>
            <input type="text" name="nama_file" class="form-control" value="{{ $qrcode->nama_file }}" required>
        </div>

        <div class="mb-3">
            <label>User (Opsional)</label>
            <select name="user_id" class="form-control">
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected($qrcode->user_id == $user->id)>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.qrcodes.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
