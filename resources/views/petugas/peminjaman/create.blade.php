@extends('layouts.petugas')

@section('page-title', 'Tambah Peminjaman Baru')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid #667eea;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-info-card {
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        border: 2px solid #667eea30;
        border-radius: 15px;
        padding: 20px;
        margin-top: 15px;
    }

    /* Book Catalog Styles */
    .book-catalog {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .book-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: var(--transition);
        cursor: pointer;
        border: 3px solid transparent;
        position: relative;
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .book-card.selected {
        border-color: #667eea;
        box-shadow: 0 10px 35px rgba(102, 126, 234, 0.4);
    }

    .book-card-image {
        width: 100%;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
        position: relative;
    }

    .book-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .book-card:hover .book-card-image img {
        transform: scale(1.1);
    }

    .book-card-image .no-image {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #a0aec0;
    }

    .book-card-image .no-image i {
        font-size: 3rem;
        margin-bottom: 0.5rem;
    }

    .book-card-body {
        padding: 1.25rem;
    }

    .book-title {
        font-size: 1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
        min-height: 2.8rem;
    }

    .book-author {
        font-size: 0.85rem;
        color: #718096;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .book-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .book-stock {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 10px;
    }

    .stock-available {
        background: #10b981;
        color: white;
    }

    .stock-low {
        background: #f59e0b;
        color: white;
    }

    .stock-empty {
        background: #ef4444;
        color: white;
    }

    .select-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 35px;
        height: 35px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        opacity: 0;
        transition: var(--transition);
    }

    .book-card.selected .select-indicator {
        opacity: 1;
        background: #667eea;
        color: white;
    }

    .search-filter-section {
        background: #f7fafc;
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 1.5rem;
    }

    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #667eea;
        border: 2px solid #667eea;
        cursor: pointer;
        transition: var(--transition);
    }

    .filter-badge:hover {
        background: #667eea;
        color: white;
    }

    .selected-book-preview {
        background: linear-gradient(135deg, #11998e15 0%, #38ef7d15 100%);
        border: 2px solid #11998e40;
        border-radius: 15px;
        padding: 1.5rem;
        display: none;
    }

    .selected-book-preview.show {
        display: block;
        animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #a0aec0;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .btn-primary-custom {
        background: var(--primary-gradient);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        color: white;
        transition: var(--transition);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .category-badge {
        background: #4facfe;
        color: white;
        padding: 0.3rem 0.7rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Left Column: Form Peminjam -->
        <div class="col-lg-4">
            <div class="form-card sticky-top" style="top: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle text-primary me-2"></i>
                        Form Peminjaman
                    </h4>
                    <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary btn-sm">
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

                <form action="{{ route('petugas.peminjaman.store') }}" method="POST" id="peminjamanForm">
                    @csrf

                    <!-- Hidden input untuk buku_id -->
                    <input type="hidden" name="buku_id" id="selected_buku_id" value="{{ old('buku_id') }}">

                    <!-- Pilih Peminjam -->
                    <div class="section-title">
                        <i class="bi bi-person-fill"></i>
                        <span>Data Peminjam</span>
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
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
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
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
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
                    </div>

                    <!-- Info Peminjam -->
                    <div id="user-info" class="user-info-card d-none">
                        <div class="row g-3">
                            <div class="col-12">
                                <small class="text-muted d-block mb-1">Nama Lengkap</small>
                                <strong id="user-name">-</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">Tipe</small>
                                <span id="user-role-badge"></span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block mb-1" id="user-identifier-label">-</small>
                                <span id="user-identifier">-</span>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block mb-1">Email</small>
                                <span id="user-email">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Buku Terpilih -->
                    <div id="selected-book-preview" class="selected-book-preview mt-4">
                        <div class="section-title" style="border-color: #11998e;">
                            <i class="bi bi-book-fill"></i>
                            <span>Buku Terpilih</span>
                        </div>
                        <div class="d-flex gap-3">
                            <img id="preview-foto" src="" alt="Book Cover" style="width: 80px; height: 100px; object-fit: cover; border-radius: 10px;">
                            <div class="flex-grow-1">
                                <strong id="preview-judul" class="d-block mb-2"></strong>
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-person"></i> <span id="preview-penulis"></span>
                                </small>
                                <small class="text-muted d-block">
                                    <i class="bi bi-box-seam"></i> Stok: <span id="preview-stok"></span>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Durasi Peminjaman -->
                    <div class="section-title mt-4">
                        <i class="bi bi-calendar-range"></i>
                        <span>Durasi Peminjaman</span>
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
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary flex-grow-1">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary-custom flex-grow-1">
                            <i class="bi bi-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Katalog Buku -->
        <div class="col-lg-8">
            <div class="form-card">
                <div class="section-title">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    <span>Pilih Buku yang Akan Dipinjam</span>
                </div>

                <!-- Search & Filter -->
                <div class="search-filter-section">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" id="searchBook" class="form-control" placeholder="Cari judul atau penulis buku...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select id="filterStok" class="form-select">
                                <option value="">Semua Stok</option>
                                <option value="available">Tersedia</option>
                                <option value="empty">Kosong</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Book Catalog Grid -->
                <div class="book-catalog" id="bookCatalog">
                    @forelse($bukus as $buku)
                    <div class="book-card" 
                         data-buku-id="{{ $buku->id }}"
                         data-buku-judul="{{ $buku->judul }}"
                         data-buku-penulis="{{ $buku->penulis }}"
                         data-buku-stok="{{ $buku->stok }}"
                         data-buku-foto="{{ $buku->foto ? asset('storage/' . $buku->foto) : '' }}">
                        
                        <div class="select-indicator">
                            <i class="bi bi-check-lg"></i>
                        </div>

                        <div class="book-card-image">
                            @if($buku->foto && file_exists(public_path('storage/' . $buku->foto)))
                                <img src="{{ asset('storage/' . $buku->foto) }}" alt="{{ $buku->judul }}">
                            @else
                                <div class="no-image">
                                    <i class="bi bi-book"></i>
                                    <small>No Cover</small>
                                </div>
                            @endif
                        </div>

                        <div class="book-card-body">
                            <h5 class="book-title">{{ $buku->judul }}</h5>
                            <div class="book-author">
                                <i class="bi bi-person-fill"></i>
                                <span>{{ $buku->penulis }}</span>
                            </div>
                            
                            @if($buku->kategori)
                            <div class="mb-2">
                                <span class="category-badge">{{ Str::limit($buku->kategori, 20) }}</span>
                            </div>
                            @endif

                            <div class="book-info">
                                <span class="book-stock {{ $buku->stok > 5 ? 'stock-available' : ($buku->stok > 0 ? 'stock-low' : 'stock-empty') }}">
                                    <i class="bi bi-box-seam"></i>
                                    <span>{{ $buku->stok }} tersedia</span>
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $buku->tahun_terbit }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Belum ada buku tersedia</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mahasiswaSelect = document.getElementById('mahasiswa_id');
    const userInfo = document.getElementById('user-info');
    const bookCards = document.querySelectorAll('.book-card');
    const selectedBukuInput = document.getElementById('selected_buku_id');
    const selectedBookPreview = document.getElementById('selected-book-preview');
    const searchBook = document.getElementById('searchBook');
    const filterStok = document.getElementById('filterStok');

    // Handle pemilihan peminjam
    mahasiswaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const role = selectedOption.dataset.role;
            const name = selectedOption.dataset.name;
            const email = selectedOption.dataset.email;
            
            document.getElementById('user-name').textContent = name;
            document.getElementById('user-email').textContent = email;
            
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

    // Handle pemilihan buku
    bookCards.forEach(card => {
        card.addEventListener('click', function() {
            const bukuId = this.dataset.bukuId;
            const stok = parseInt(this.dataset.bukuStok);
            
            // Cek stok
            if (stok <= 0) {
                alert('Maaf, stok buku ini sedang kosong.');
                return;
            }
            
            // Remove selected class from all cards
            bookCards.forEach(c => c.classList.remove('selected'));
            
            // Add selected class to clicked card
            this.classList.add('selected');
            
            // Set hidden input value
            selectedBukuInput.value = bukuId;
            
            // Update preview
            document.getElementById('preview-judul').textContent = this.dataset.bukuJudul;
            document.getElementById('preview-penulis').textContent = this.dataset.bukuPenulis;
            document.getElementById('preview-stok').textContent = stok;
            
            const fotoUrl = this.dataset.bukuFoto;
            if (fotoUrl) {
                document.getElementById('preview-foto').src = fotoUrl;
            } else {
                document.getElementById('preview-foto').src = 'https://via.placeholder.com/80x100?text=No+Cover';
            }
            
            selectedBookPreview.classList.add('show');
            
            // Scroll to preview (smooth)
            selectedBookPreview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });

    // Search functionality
    searchBook.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterBooks();
    });

    // Filter by stock
    filterStok.addEventListener('change', function() {
        filterBooks();
    });

    function filterBooks() {
        const searchTerm = searchBook.value.toLowerCase();
        const stokFilter = filterStok.value;

        bookCards.forEach(card => {
            const judul = card.dataset.bukuJudul.toLowerCase();
            const penulis = card.dataset.bukuPenulis.toLowerCase();
            const stok = parseInt(card.dataset.bukuStok);

            let matchSearch = judul.includes(searchTerm) || penulis.includes(searchTerm);
            let matchStok = true;

            if (stokFilter === 'available') {
                matchStok = stok > 0;
            } else if (stokFilter === 'empty') {
                matchStok = stok === 0;
            }

            if (matchSearch && matchStok) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Form validation
    document.getElementById('peminjamanForm').addEventListener('submit', function(e) {
        if (!selectedBukuInput.value) {
            e.preventDefault();
            alert('Silakan pilih buku terlebih dahulu!');
            return false;
        }
        
        if (!mahasiswaSelect.value) {
            e.preventDefault();
            alert('Silakan pilih peminjam terlebih dahulu!');
            return false;
        }
    });

    // Trigger change jika ada old value
    if (mahasiswaSelect.value) {
        mahasiswaSelect.dispatchEvent(new Event('change'));
    }

    // Select book if old value exists
    if (selectedBukuInput.value) {
        const selectedCard = document.querySelector(`[data-buku-id="${selectedBukuInput.value}"]`);
        if (selectedCard) {
            selectedCard.click();
        }
    }
});
</script>
@endpush
@endsection