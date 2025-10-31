@extends('layouts.petugas')

@section('page-title', 'Tambah Peminjaman Baru')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
    }

    .user-option {
        padding: 12px;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .user-option:hover {
        background: #f1f5f9;
    }

    .badge-user-role {
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 10px;
        margin-left: 8px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle text-primary me-2"></i>
                    Tambah Peminjaman Baru
                </h4>
                <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('petugas.peminjaman.store') }}" method="POST">
                @csrf

                <!-- Pilih Peminjam -->
                <div class="section-title">
                    <i class="bi bi-person-fill me-2"></i>Data Peminjam
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">
                        Pilih Peminjam <span class="text-danger">*</span>
                    </label>
                    <select name="mahasiswa_id" id="mahasiswa_id" class="form-select @error('mahasiswa_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Peminjam --</option>
                        
                        @php
                            $mahasiswaList = $peminjams->where('role', 'mahasiswa');
                            $penggunaLuarList = $peminjams->where('role', 'pengguna_luar');
                        @endphp

                        @if($mahasiswaList->count() > 0)
                            <optgroup label="Mahasiswa">
                                @foreach($mahasiswaList as $user)
                                    <option value="{{ $user->id }}" 
                                            data-role="mahasiswa"
                                            data-nim="{{ $user->nim }}"
                                            {{ old('mahasiswa_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - NIM: {{ $user->nim ?? '-' }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif

                        @if($penggunaLuarList->count() > 0)
                            <optgroup label="Pengguna Luar">
                                @foreach($penggunaLuarList as $user)
                                    <option value="{{ $user->id }}" 
                                            data-role="pengguna_luar"
                                            data-nohp="{{ $user->no_hp }}"
                                            {{ old('mahasiswa_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - HP: {{ $user->no_hp ?? '-' }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                    @error('mahasiswa_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <!-- Info Peminjam -->
                    <div id="user-info" class="mt-3 p-3 bg-light rounded d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Nama</small>
                                <strong id="user-name">-</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Tipe Pengguna</small>
                                <span id="user-role-badge"></span>
                            </div>
                            <div class="col-md-6 mt-2">
                                <small class="text-muted d-block">Email</small>
                                <span id="user-email">-</span>
                            </div>
                            <div class="col-md-6 mt-2">
                                <small class="text-muted d-block" id="user-identifier-label">-</small>
                                <span id="user-identifier">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pilih Buku -->
                <div class="section-title">
                    <i class="bi bi-book-fill me-2"></i>Data Buku
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">
                        Pilih Buku <span class="text-danger">*</span>
                    </label>
                    <select name="buku_id" id="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($bukus as $buku)
                            <option value="{{ $buku->id }}" 
                                    data-stok="{{ $buku->stok }}"
                                    data-penulis="{{ $buku->penulis }}"
                                    {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                {{ $buku->judul }} (Stok: {{ $buku->stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('buku_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <!-- Info Buku -->
                    <div id="buku-info" class="mt-3 p-3 bg-light rounded d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Judul</small>
                                <strong id="buku-judul">-</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Penulis</small>
                                <span id="buku-penulis">-</span>
                            </div>
                            <div class="col-md-6 mt-2">
                                <small class="text-muted d-block">Stok Tersedia</small>
                                <span class="badge bg-success" id="buku-stok">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Durasi Peminjaman -->
                <div class="section-title">
                    <i class="bi bi-calendar-range me-2"></i>Durasi Peminjaman
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">
                        Durasi (Hari) <span class="text-danger">*</span>
                    </label>
                    <select name="durasi_hari" class="form-select @error('durasi_hari') is-invalid @enderror" required>
                        <option value="3" {{ old('durasi_hari', 3) == 3 ? 'selected' : '' }}>3 Hari</option>
                        <option value="7" {{ old('durasi_hari') == 7 ? 'selected' : '' }}>7 Hari</option>
                        <option value="14" {{ old('durasi_hari') == 14 ? 'selected' : '' }}>14 Hari</option>
                        <option value="30" {{ old('durasi_hari') == 30 ? 'selected' : '' }}>30 Hari</option>
                    </select>
                    @error('durasi_hari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Pilih durasi peminjaman sesuai kebijakan perpustakaan
                    </small>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mahasiswaSelect = document.getElementById('mahasiswa_id');
    const bukuSelect = document.getElementById('buku_id');
    const userInfo = document.getElementById('user-info');
    const bukuInfo = document.getElementById('buku-info');

    // Event handler untuk pemilihan peminjam
    mahasiswaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const role = selectedOption.dataset.role;
            const name = selectedOption.text.split(' - ')[0];
            
            document.getElementById('user-name').textContent = name;
            
            // Set badge berdasarkan role
            const roleBadge = document.getElementById('user-role-badge');
            if (role === 'mahasiswa') {
                roleBadge.innerHTML = '<span class="badge bg-primary"><i class="bi bi-mortarboard-fill me-1"></i>Mahasiswa</span>';
                document.getElementById('user-identifier-label').textContent = 'NIM';
                document.getElementById('user-identifier').textContent = selectedOption.dataset.nim || '-';
            } else {
                roleBadge.innerHTML = '<span class="badge bg-info"><i class="bi bi-person-fill me-1"></i>Pengguna Luar</span>';
                document.getElementById('user-identifier-label').textContent = 'No. HP';
                document.getElementById('user-identifier').textContent = selectedOption.dataset.nohp || '-';
            }
            
            userInfo.classList.remove('d-none');
        } else {
            userInfo.classList.add('d-none');
        }
    });

    // Event handler untuk pemilihan buku
    bukuSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const judul = selectedOption.text.split(' (Stok:')[0];
            const penulis = selectedOption.dataset.penulis;
            const stok = selectedOption.dataset.stok;
            
            document.getElementById('buku-judul').textContent = judul;
            document.getElementById('buku-penulis').textContent = penulis;
            document.getElementById('buku-stok').textContent = stok;
            
            bukuInfo.classList.remove('d-none');
        } else {
            bukuInfo.classList.add('d-none');
        }
    });

    // Trigger change jika ada old value
    if (mahasiswaSelect.value) {
        mahasiswaSelect.dispatchEvent(new Event('change'));
    }
    if (bukuSelect.value) {
        bukuSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection