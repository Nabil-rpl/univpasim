@extends('layouts.petugas')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Tambah Peminjaman Baru</h2>
                    <p class="text-muted mb-0">Input data peminjaman buku</p>
                </div>
                <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading mb-2">
                                <i class="bi bi-exclamation-triangle"></i> Terdapat kesalahan:
                            </h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('petugas.peminjaman.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="mahasiswa_id" class="form-label">
                                Mahasiswa <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('mahasiswa_id') is-invalid @enderror" 
                                    id="mahasiswa_id" 
                                    name="mahasiswa_id" 
                                    required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $mhs)
                                    <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                        {{ $mhs->nim }} - {{ $mhs->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mahasiswa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih mahasiswa yang akan meminjam buku</small>
                        </div>

                        <div class="mb-4">
                            <label for="buku_id" class="form-label">
                                Buku <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('buku_id') is-invalid @enderror" 
                                    id="buku_id" 
                                    name="buku_id" 
                                    required>
                                <option value="">-- Pilih Buku --</option>
                                @foreach($bukus as $buku)
                                    <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                        {{ $buku->judul }} - {{ $buku->penulis }} (Stok: {{ $buku->stok }})
                                    </option>
                                @endforeach
                            </select>
                            @error('buku_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Hanya menampilkan buku dengan stok tersedia</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Catatan:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Tanggal peminjaman akan otomatis tercatat hari ini</li>
                                <li>Stok buku akan otomatis berkurang</li>
                                <li>Status peminjaman akan menjadi "Dipinjam"</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Peminjaman
                            </button>
                            <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 15px;
}

.form-label {
    font-weight: 600;
    color: #2d3748;
}

.form-select:focus,
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.alert {
    border: none;
    border-radius: 12px;
}
</style>
@endsection