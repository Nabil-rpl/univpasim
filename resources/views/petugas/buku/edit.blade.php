@extends('layouts.petugas')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <!-- Header Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary bg-gradient text-white rounded-3 p-3 me-3">
                            <i class="fas fa-edit fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-1 fw-bold text-dark">✏️ Edit Buku</h3>
                            <p class="text-muted mb-0">Perbarui informasi buku perpustakaan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('petugas.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Judul Buku -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-heading text-primary me-2"></i>Judul Buku
                                </label>
                                <input type="text" 
                                       name="judul" 
                                       class="form-control form-control-lg @error('judul') is-invalid @enderror" 
                                       value="{{ old('judul', $buku->judul) }}"
                                       placeholder="Masukkan judul buku"
                                       required>
                                @error('judul')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Penulis -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-user-edit text-primary me-2"></i>Penulis
                                </label>
                                <input type="text" 
                                       name="penulis" 
                                       class="form-control form-control-lg @error('penulis') is-invalid @enderror" 
                                       value="{{ old('penulis', $buku->penulis) }}"
                                       placeholder="Masukkan nama penulis"
                                       required>
                                @error('penulis')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Penerbit -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-building text-primary me-2"></i>Penerbit
                                </label>
                                <input type="text" 
                                       name="penerbit" 
                                       class="form-control form-control-lg @error('penerbit') is-invalid @enderror" 
                                       value="{{ old('penerbit', $buku->penerbit) }}"
                                       placeholder="Masukkan nama penerbit"
                                       required>
                                @error('penerbit')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Tahun Terbit -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>Tahun Terbit
                                </label>
                                <input type="number" 
                                       name="tahun_terbit" 
                                       class="form-control form-control-lg @error('tahun_terbit') is-invalid @enderror" 
                                       value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                                       placeholder="Contoh: 2024"
                                       min="1900"
                                       max="{{ date('Y') + 5 }}"
                                       required>
                                @error('tahun_terbit')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-tag text-primary me-2"></i>Kategori (Jurusan Kuliah)
                                </label>
                                <select name="kategori" 
                                        class="form-select form-select-lg @error('kategori') is-invalid @enderror"
                                        required>
                                    <option value="">Pilih Kategori</option>
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
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Stok Buku -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-boxes text-primary me-2"></i>Stok Buku
                                </label>
                                <input type="number" 
                                       name="stok" 
                                       class="form-control form-control-lg @error('stok') is-invalid @enderror" 
                                       value="{{ old('stok', $buku->stok) }}"
                                       placeholder="Jumlah stok tersedia"
                                       min="0"
                                       required>
                                @error('stok')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- 🖼 Input foto --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-image text-primary me-2"></i>Foto Buku
                                </label>
                                
                                {{-- Preview Foto Lama --}}
                                @if($buku->foto && file_exists(public_path('storage/' . $buku->foto)))
                                    <div class="mb-3">
                                        <p class="text-muted small mb-2">📷 Foto saat ini:</p>
                                        <img src="{{ asset('storage/' . $buku->foto) }}" 
                                             alt="Foto Buku" 
                                             class="img-thumbnail shadow-sm" 
                                             style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: 10px;">
                                    </div>
                                @endif

                                <div class="upload-area border border-2 border-dashed rounded-3 p-4 text-center bg-light">
                                    <input type="file" 
                                           name="foto" 
                                           class="form-control @error('foto') is-invalid @enderror"
                                           accept="image/*"
                                           id="fotoInput"
                                           onchange="previewImage(event)">
                                    <div class="mt-3">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Klik untuk memilih foto baru atau drag & drop</p>
                                        <small class="text-muted">Format: JPG, PNG, JPEG (Max: 2MB) • Kosongkan jika tidak ingin mengubah foto</small>
                                    </div>
                                    <div id="imagePreview" class="mt-3" style="display: none;">
                                        <p class="text-success small mb-2">✅ Preview foto baru:</p>
                                        <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px; border-radius: 10px;">
                                    </div>
                                </div>
                                @error('foto')
                                    <div class="text-danger mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-5 pt-4 border-top">
                            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-end">
                                <a href="{{ route('petugas.buku.index') }}" 
                                   class="btn btn-lg btn-outline-secondary px-4">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-lg btn-primary px-5 shadow-sm">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-box {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-area {
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        background-color: #f8f9fa !important;
        border-color: #0d6efd !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .form-control-lg,
    .form-select-lg {
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
</style>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
}
</script>
@endsection