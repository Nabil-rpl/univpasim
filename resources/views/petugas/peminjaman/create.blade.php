@extends('layouts.petugas')

@section('page-title', 'Peminjaman Baru')

@section('content')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        @media (min-width: 992px) {
            .form-card {
                padding: 30px;
            }
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 3px solid #667eea;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .book-catalog {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.2rem;
        }

        @media (max-width: 576px) {
            .book-catalog {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1rem;
            }
        }

        .book-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .book-card-image {
            height: 180px;
            background: #f1f5f9;
        }

        @media (max-width: 576px) {
            .book-card-image {
                height: 160px;
            }
        }

        .book-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info-card {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border: 2px solid #667eea30;
            border-radius: 15px;
            padding: 16px;
            margin-top: 15px;
        }

        .selected-book-preview {
            animation: fadeInUp 0.4s ease;
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

        /* Responsive Form */
        @media (max-width: 991px) {
            .form-card {
                position: relative !important;
                top: 0 !important;
            }
        }
    </style>

    <div class="container-fluid py-3">
        <div class="row g-3">

            <!-- === FORM PINJAMAN (Kiri) === -->
            <div class="col-lg-5 col-xl-4">
                <div class="form-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">
                            <i class="bi bi-plus-circle text-primary me-2"></i>
                            Form Peminjaman
                        </h4>
                        <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('petugas.peminjaman.store') }}" method="POST" id="peminjamanForm">
                        @csrf
                        <input type="hidden" name="buku_id" id="selected_buku_id" value="{{ old('buku_id') }}">

                        <!-- Data Peminjam -->
                        <div class="section-title">
                            <i class="bi bi-person-fill"></i>
                            <span>Data Peminjam</span>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Peminjam <span class="text-danger">*</span></label>
                            <select name="mahasiswa_id" id="mahasiswa_id" class="form-select" required>
                                <option value="">-- Pilih Peminjam --</option>
                                <!-- ... (optgroup tetap sama) ... -->
                                @php
                                    $mahasiswaList = $peminjams->where('role', 'mahasiswa');
                                    $penggunaLuarList = $peminjams->where('role', 'pengguna_luar');
                                @endphp

                                @if ($mahasiswaList->count() > 0)
                                    <optgroup label="Mahasiswa">
                                        @foreach ($mahasiswaList as $user)
                                            <option value="{{ $user->id }}" data-role="mahasiswa"
                                                data-nim="{{ $user->nim }}" data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}">
                                                {{ $user->name }} - NIM: {{ $user->nim ?? '-' }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif

                                @if ($penggunaLuarList->count() > 0)
                                    <optgroup label="Pengguna Luar">
                                        @foreach ($penggunaLuarList as $user)
                                            <option value="{{ $user->id }}" data-role="pengguna_luar"
                                                data-nohp="{{ $user->no_hp }}" data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}">
                                                {{ $user->name }} - HP: {{ $user->no_hp ?? '-' }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>

                        <!-- Info User -->
                        <div id="user-info" class="user-info-card d-none">
                            <!-- isinya sama seperti sebelumnya -->
                            <div class="row g-3">
                                <div class="col-12"><small class="text-muted">Nama Lengkap</small><br><strong
                                        id="user-name">-</strong></div>
                                <div class="col-6"><small class="text-muted">Tipe</small><br><span
                                        id="user-role-badge"></span></div>
                                <div class="col-6"><small class="text-muted" id="user-identifier-label">-</small><br><span
                                        id="user-identifier">-</span></div>
                                <div class="col-12"><small class="text-muted">Email</small><br><span
                                        id="user-email">-</span></div>
                            </div>
                        </div>

                        <!-- Preview Buku -->
                        <div id="selected-book-preview" class="selected-book-preview mt-4 d-none">
                            <div class="section-title" style="border-color: #11998e;">
                                <i class="bi bi-book-fill"></i>
                                <span>Buku Terpilih</span>
                            </div>
                            <div class="d-flex gap-3">
                                <img id="preview-foto" src="" alt=""
                                    style="width: 70px; height: 95px; object-fit: cover; border-radius: 8px;">
                                <div>
                                    <strong id="preview-judul" class="d-block"></strong>
                                    <small class="text-muted" id="preview-penulis"></small>
                                    <small class="text-muted">Stok: <span id="preview-stok"></span></small>
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
                            <select name="durasi_hari" class="form-select @error('durasi_hari') is-invalid @enderror"
                                required>
                                <option value="3" {{ old('durasi_hari', 3) == 3 ? 'selected' : '' }}>3 Hari</option>
                                <option value="7" {{ old('durasi_hari') == 7 ? 'selected' : '' }}>7 Hari</option>
                                <option value="14" {{ old('durasi_hari') == 14 ? 'selected' : '' }}>14 Hari</option>
                            </select>
                            @error('durasi_hari')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 pt-3 border-top">
                            <a href="{{ route('petugas.peminjaman.index') }}"
                                class="btn btn-secondary flex-grow-1">Batal</a>
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-save me-2"></i>Simpan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- === KATALOG BUKU (Kanan) === -->
            <div class="col-lg-7 col-xl-8">
                <div class="form-card">
                    <div class="section-title">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        <span>Pilih Buku</span>
                    </div>

                    <!-- Search + Filter -->
                    <div class="row g-2 mb-4">
                        <div class="col-8 col-md-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" id="searchBook" class="form-control"
                                    placeholder="Cari judul atau penulis...">
                            </div>
                        </div>
                        <div class="col-4 col-md-3">
                            <select id="filterStok" class="form-select">
                                <option value="">Semua</option>
                                <option value="available">Tersedia</option>
                                <option value="empty">Habis</option>
                            </select>
                        </div>
                    </div>

                    <div class="book-catalog" id="bookCatalog">
                        @forelse($bukus as $buku)
                            <div class="book-card" data-buku-id="{{ $buku->id }}"
                                data-buku-judul="{{ $buku->judul }}" data-buku-penulis="{{ $buku->penulis }}"
                                data-buku-stok="{{ $buku->stok }}"
                                data-buku-foto="{{ $buku->foto ? asset('storage/' . $buku->foto) : '' }}">

                                <div class="book-card-image">
                                    @if ($buku->foto)
                                        <img src="{{ asset('storage/' . $buku->foto) }}" alt="{{ $buku->judul }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                            <i class="bi bi-book fs-1"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-3">
                                    <h6 class="book-title mb-1">{{ $buku->judul }}</h6>
                                    <small class="text-muted">{{ $buku->penulis }}</small>
                                    <div class="mt-2">
                                        <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                            Stok: {{ $buku->stok }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted py-5">Tidak ada buku tersedia</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const bookCards = document.querySelectorAll('.book-card');
                const selectedBukuInput = document.getElementById('selected_buku_id');
                const selectedBookPreview = document.getElementById('selected-book-preview');
                const searchBook = document.getElementById('searchBook');
                const filterStok = document.getElementById('filterStok');
                const mahasiswaSelect = document.getElementById('mahasiswa_id');

                let currentlySelectedCard = null; // ← Tambahan untuk kontrol yang lebih baik

                // ==================== PILIH PEMINJAM ====================
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
                            roleBadge.innerHTML =
                                `<span class="badge bg-primary"><i class="bi bi-mortarboard-fill me-1"></i>Mahasiswa</span>`;
                            document.getElementById('user-identifier-label').textContent = 'NIM';
                            document.getElementById('user-identifier').textContent = selectedOption.dataset
                                .nim || '-';
                        } else {
                            roleBadge.innerHTML =
                                `<span class="badge bg-info"><i class="bi bi-person-fill me-1"></i>Pengguna Luar</span>`;
                            document.getElementById('user-identifier-label').textContent = 'No. HP';
                            document.getElementById('user-identifier').textContent = selectedOption.dataset
                                .nohp || '-';
                        }

                        document.getElementById('user-info').classList.remove('d-none');
                    } else {
                        document.getElementById('user-info').classList.add('d-none');
                    }
                });

                // ==================== PILIH BUKU ====================
                function selectBook(card) {
                    const stok = parseInt(card.dataset.bukuStok || 0);

                    if (stok <= 0) {
                        alert('Maaf, stok buku ini sedang kosong!');
                        return;
                    }

                    // Reset semua card
                    bookCards.forEach(c => c.classList.remove('selected'));

                    // Pilih card baru
                    card.classList.add('selected');
                    currentlySelectedCard = card;

                    // Isi data
                    selectedBukuInput.value = card.dataset.bukuId;

                    document.getElementById('preview-judul').textContent = card.dataset.bukuJudul;
                    document.getElementById('preview-penulis').textContent = card.dataset.bukuPenulis;
                    document.getElementById('preview-stok').textContent = stok;

                    const fotoUrl = card.dataset.bukuFoto;
                    document.getElementById('preview-foto').src = fotoUrl ||
                        'https://via.placeholder.com/70x95?text=No+Cover';

                    // Tampilkan preview
                    selectedBookPreview.classList.remove('d-none');
                    selectedBookPreview.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }

                bookCards.forEach(card => {
                    card.addEventListener('click', function() {
                        selectBook(this);
                    });
                });

                // ==================== SEARCH & FILTER ====================
                function filterBooks() {
                    const searchTerm = searchBook.value.toLowerCase().trim();
                    const stokFilter = filterStok.value;

                    let hasVisible = false;

                    bookCards.forEach(card => {
                        const judul = (card.dataset.bukuJudul || '').toLowerCase();
                        const penulis = (card.dataset.bukuPenulis || '').toLowerCase();
                        const stok = parseInt(card.dataset.bukuStok || 0);

                        const matchSearch = judul.includes(searchTerm) || penulis.includes(searchTerm);
                        let matchStok = true;

                        if (stokFilter === 'available') matchStok = stok > 0;
                        if (stokFilter === 'empty') matchStok = stok === 0;

                        const isVisible = matchSearch && matchStok;
                        card.style.display = isVisible ? 'block' : 'none';

                        if (isVisible) hasVisible = true;
                    });

                    // Jika buku yang dipilih saat ini disembunyikan oleh filter → reset
                    if (currentlySelectedCard && currentlySelectedCard.style.display === 'none') {
                        selectedBukuInput.value = '';
                        selectedBookPreview.classList.add('d-none');
                        currentlySelectedCard = null;
                    }
                }

                searchBook.addEventListener('input', filterBooks);
                filterStok.addEventListener('change', filterBooks);

                // ==================== VALIDASI SUBMIT ====================
                document.getElementById('peminjamanForm').addEventListener('submit', function(e) {
                    if (!selectedBukuInput.value) {
                        e.preventDefault();
                        alert('Silakan pilih buku terlebih dahulu!');
                        return;
                    }
                    if (!mahasiswaSelect.value) {
                        e.preventDefault();
                        alert('Silakan pilih peminjam terlebih dahulu!');
                        return;
                    }
                });

                // ==================== LOAD OLD VALUE ====================
                if (mahasiswaSelect.value) {
                    mahasiswaSelect.dispatchEvent(new Event('change'));
                }

                if (selectedBukuInput.value) {
                    const selectedCard = document.querySelector(`[data-buku-id="${selectedBukuInput.value}"]`);
                    if (selectedCard) selectBook(selectedCard);
                }

            });
        </script>
    @endpush
@endsection
