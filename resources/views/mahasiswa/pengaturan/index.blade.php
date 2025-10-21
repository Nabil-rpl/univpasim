@extends('layouts.mahasiswa')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Pengaturan Akun</h4>
        </div>

        <div class="card-body">
            {{-- Alert --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(isset($user))
            <form action="{{ route('mahasiswa.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nama --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', $user->name ?? '') }}" 
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" 
                           value="{{ old('email', $user->email ?? '') }}" 
                           class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                {{-- Password Baru --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" name="password" id="password" 
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control" placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
            @else
                <div class="alert alert-warning">
                    Data pengguna tidak ditemukan. Silakan <a href="{{ route('login') }}">login kembali</a>.
                </div>
            @endif
        </div>
    </div>
</div>

{{-- JS Preview Foto --}}
<script>
    function previewFoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-foto').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection