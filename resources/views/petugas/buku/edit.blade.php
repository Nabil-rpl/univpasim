@extends('layouts.petugas')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Header dengan Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('petugas.buku.index') }}">Daftar Buku</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Buku</li>
                </ol>
            </nav>

            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient text-white text-center py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Edit Buku
                    </h3>
                    <p class="mb-0 small opacity-75">Perbarui informasi buku</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('petugas.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Kolom Kiri --}}
                            <div class="col-md-6">
                                {{-- Judul Buku --}}
                                <div class="mb-3">
                                    <label for="judul" class="form-label fw-bold">
                                        <i class="bi bi-book"></i> Judul Buku
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('judul') is-invalid @enderror" 
                                           id="judul"
                                           name="judul" 
                                           value="{{ old('judul', $buku->judul) }}" 
                                           placeholder="Masukkan judul buku"
                                           required>
                                    @error('judul') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>

                                {{-- Penulis --}}
                                <div class="mb-3">
                                    <label for="penulis" class="form-label fw-bold">
                                        <i class="bi bi-person"></i> Penulis
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('penulis') is-invalid @enderror" 
                                           id="penulis"
                                           name="penulis" 
                                           value="{{ old('penulis', $buku->penulis) }}" 
                                           placeholder="Nama penulis"
                                           required>
                                    @error('penulis') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>

                                {{-- Penerbit --}}
                                <div class="mb-3">
                                    <label for="penerbit" class="form-label fw-bold">
                                        <i class="bi bi-building"></i> Penerbit
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('penerbit') is-invalid @enderror" 
                                           id="penerbit"
                                           name="penerbit" 
                                           value="{{ old('penerbit', $buku->penerbit) }}" 
                                           placeholder="Nama penerbit"
                                           required>
                                    @error('penerbit') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>
                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="col-md-6">
                                {{-- Tahun Terbit --}}
                                <div class="mb-3">
                                    <label for="tahun_terbit" class="form-label fw-bold">
                                        <i class="bi bi-calendar"></i> Tahun Terbit
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('tahun_terbit') is-invalid @enderror" 
                                           id="tahun_terbit"
                                           name="tahun_terbit" 
                                           value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" 
                                           placeholder="Contoh: 2024"
                                           min="1900"
                                           max="{{ date('Y') + 5 }}"
                                           required>
                                    @error('tahun_terbit') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>

                                {{-- Kategori --}}
                                <div class="mb-3">
                                    <label for="kategori" class="form-label fw-bold">
                                        <i class="bi bi-tags"></i> Kategori
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('kategori') is-invalid @enderror" 
                                            id="kategori"
                                            name="kategori" 
                                            required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @php
                                            $categories = [
                                                'MIPA' => ['Matematika', 'Fisika', 'Kimia', 'Biologi', 'Statistika'],
                                                'Teknik' => ['Teknik Informatika', 'Teknik Elektro', 'Teknik Sipil', 'Teknik Mesin', 'Teknik Industri'],
                                                'Ekonomi & Bisnis' => ['Akuntansi', 'Manajemen', 'Ekonomi Pembangunan', 'Bisnis Digital'],
                                                'Sosial & Humaniora' => ['Ilmu Komunikasi', 'Psikologi', 'Sosiologi', 'Ilmu Hukum'],
                                                'Pendidikan' => ['Pendidikan Matematika', 'Pendidikan Bahasa Inggris', 'PGSD'],
                                                'Kesehatan' => ['Kedokteran', 'Keperawatan', 'Farmasi', 'Kesehatan Masyarakat'],
                                                'Seni & Desain' => ['Desain Grafis', 'Desain Interior', 'Seni Rupa', 'Film'],
                                            ];
                                        @endphp
                                        @foreach($categories as $group => $items)
                                            <optgroup label="{{ $group }}">
                                                @foreach($items as $item)
                                                    <option value="{{ $item }}" {{ old('kategori', $buku->kategori) == $item ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('kategori') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>

                                {{-- Stok --}}
                                <div class="mb-3">
                                    <label for="stok" class="form-label fw-bold">
                                        <i class="bi bi-box-seam"></i> Stok Buku
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('stok') is-invalid @enderror" 
                                           id="stok"
                                           name="stok" 
                                           value="{{ old('stok', $buku->stok) }}" 
                                           placeholder="Jumlah stok"
                                           min="0"
                                           required>
                                    @error('stok') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Foto Buku --}}
                        <div class="mb-4">
                            <label for="foto" class="form-label fw-bold">
                                <i class="bi bi-image"></i> Foto Buku
                            </label>
                            
                            {{-- Preview Foto Lama --}}
                            @if($buku->foto && file_exists(public_path('storage/' . $buku->foto)))
                                <div class="mb-3">
                                    <p class="text-muted small mb-2">Foto saat ini:</p>
                                    <img src="{{ asset('storage/' . $buku->foto) }}" 
                                         alt="Foto Buku" 
                                         class="img-thumbnail shadow-sm" 
                                         style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                </div>
                            @endif

                            <input type="file" 
                                   class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto"
                                   name="foto" 
                                   accept="image/jpeg,image/png,image/jpg">
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> 
                                Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.
                            </div>
                            @error('foto') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror

                            {{-- Preview Foto Baru --}}
                            <div id="preview" class="mt-3" style="display: none;">
                                <p class="text-muted small mb-2">Preview foto baru:</p>
                                <img id="preview-image" 
                                     class="img-thumbnail shadow-sm" 
                                     style="max-width: 200px; max-height: 200px; object-fit: cover;">
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('petugas.buku.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-check-circle"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk Preview Foto --}}
<script>
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').style.display = 'block';
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('preview').style.display = 'none';
    }
});
</script>

<style>
.card-header.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.breadcrumb {
    background-color: #f8f9fa;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
}
</style>
@endsection