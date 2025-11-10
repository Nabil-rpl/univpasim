@extends('layouts.petugas')

@section('content')
    <div class="container mt-4 mb-5">
        <!-- Hero Header dengan Ilustrasi -->
        <div class="hero-header text-center mb-5 position-relative">
            <div class="hero-bg"></div>
            <div class="position-relative z-1">
                <div class="mb-3">
                    <span class="hero-icon">📚</span>
                </div>
                <h1 class="fw-bold mb-3 hero-title">Perpustakaan Digital</h1>
                <p class="text-muted fs-5">Kelola koleksi buku dengan mudah dan efisien</p>
                
                <!-- Stats Cards -->
                <div class="row g-3 mt-4 mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon">📖</div>
                            <div class="stat-number">{{ $buku->total() }}</div>
                            <div class="stat-label">Total Buku</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon">📦</div>
                            <div class="stat-number">{{ $buku->sum('stok') }}</div>
                            <div class="stat-label">Total Stok</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon">🏷️</div>
                            <div class="stat-number">{{ $buku->unique('kategori')->count() }}</div>
                            <div class="stat-label">Kategori</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-lg-4">
                    <a href="{{ route('petugas.buku.create') }}" class="btn btn-add w-100">
                        <i class="bi bi-plus-circle me-2"></i>
                        <span>Tambah Buku Baru</span>
                        <div class="btn-shine"></div>
                    </a>
                </div>
                <div class="col-lg-8">
                    <form action="{{ route('petugas.buku.index') }}" method="GET">
                        <div class="search-box">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" name="search" class="form-control search-input" 
                                   placeholder="Cari judul, penulis, atau penerbit..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-search" type="submit">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Books Grid/Table View -->
        <div class="books-container">
            @forelse ($buku as $item)
                <div class="book-card">
                    <!-- Book Image -->
                    <div class="book-image-wrapper">
                        @if ($item->foto && file_exists(public_path('storage/' . $item->foto)))
                            <a href="#" data-bs-toggle="modal" data-bs-target="#fotoModal{{ $item->id }}">
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" 
                                     class="book-image">
                                <div class="image-overlay">
                                    <i class="bi bi-zoom-in"></i>
                                </div>
                            </a>

                            <!-- Modal Preview -->
                            <div class="modal fade" id="fotoModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content modal-modern">
                                        <div class="modal-header">
                                            <h5 class="modal-title">📖 {{ $item->judul }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center p-4">
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}"
                                                class="img-fluid rounded-3 shadow-lg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="book-image-placeholder">
                                <i class="bi bi-book" style="font-size: 48px; color: #9e9e9e;"></i>
                            </div>
                        @endif
                        
                        <!-- Stock Badge -->
                        <div class="stock-badge">
                            <i class="bi bi-box-seam me-1"></i>{{ $item->stok }}
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div class="book-info">
                        <div class="book-category mb-2">
                            @if($item->kategori)
                                <span class="category-badge">{{ $item->kategori }}</span>
                            @else
                                <span class="category-badge">Umum</span>
                            @endif
                            <span class="year-badge">{{ $item->tahun_terbit }}</span>
                        </div>

                        <h5 class="book-title" title="{{ $item->judul }}">{{ $item->judul }}</h5>
                        
                        <div class="book-meta">
                            <div class="meta-item">
                                <i class="bi bi-person"></i>
                                <span>{{ $item->penulis }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-building"></i>
                                <span>{{ $item->penerbit }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="book-actions mt-3">
                            <a href="{{ route('petugas.buku.show', $item->id) }}" 
                               class="btn-action btn-detail" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('petugas.buku.edit', $item->id) }}" 
                               class="btn-action btn-edit" title="Edit Buku">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('petugas.buku.destroy', $item->id) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Hapus Buku">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📚</div>
                    <h4>Belum Ada Buku</h4>
                    <p class="text-muted">Mulai tambahkan koleksi buku perpustakaan Anda</p>
                    <a href="{{ route('petugas.buku.create') }}" class="btn btn-add mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Buku Pertama
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-5 d-flex justify-content-center">
            {{ $buku->links() }}
        </div>
    </div>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
        }

        /* Hero Header */
        .hero-header {
            padding: 60px 20px;
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 40px;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border: 2px solid #667eea30;
            border-radius: 24px;
        }

        .hero-icon {
            font-size: 80px;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .hero-title {
            font-size: 3rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            font-size: 36px;
            margin-bottom: 12px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 4px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }

        /* Action Bar */
        .action-bar {
            background: white;
            padding: 24px;
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
        }

        .btn-add {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-add:hover .btn-shine {
            left: 100%;
        }

        /* Search Box */
        .search-box {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-icon {
            position: absolute;
            left: 20px;
            color: #9e9e9e;
            font-size: 18px;
            z-index: 1;
        }

        .search-input {
            padding: 16px 20px 16px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            flex: 1;
        }

        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .btn-search {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Books Grid */
        .books-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 32px;
        }

        .book-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            animation: fadeInUp 0.5s ease;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Book Image */
        .book-image-wrapper {
            position: relative;
            width: 100%;
            height: 280px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            overflow: hidden;
        }

        .book-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .book-card:hover .book-image {
            transform: scale(1.05);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-overlay i {
            color: white;
            font-size: 48px;
        }

        .book-image-wrapper:hover .image-overlay {
            opacity: 1;
        }

        .book-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .stock-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            color: #667eea;
            box-shadow: var(--shadow-sm);
        }

        /* Book Info */
        .book-info {
            padding: 24px;
        }

        .book-category {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .category-badge {
            background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
            color: #667eea;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #667eea30;
        }

        .year-badge {
            background: #f8f9fa;
            color: #6c757d;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #e9ecef;
        }

        .book-title {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
            margin: 16px 0 12px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 50px;
        }

        .book-meta {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 14px;
        }

        .meta-item i {
            color: #9e9e9e;
            font-size: 16px;
        }

        .meta-item span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Book Actions */
        .book-actions {
            display: flex;
            gap: 8px;
            padding-top: 16px;
            border-top: 1px solid #f0f0f0;
        }

        .btn-action {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-detail {
            background: #468aff; 0%, #f5576c 100%);
            color: white;
        }

        .btn-edit {
            background: #0046FF; 0%, #f5576c 100%);
            color: white;
        }

        .btn-delete {
            background: #ec2416; 0%, #f5576c 100%);
            color: white;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
        }

        .empty-icon {
            font-size: 120px;
            margin-bottom: 24px;
            animation: float 3s ease-in-out infinite;
        }

        .empty-state h4 {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
        }

        /* Modal Modern */
        .modal-modern {
            border-radius: 20px;
            border: none;
            overflow: hidden;
        }

        .modal-modern .modal-header {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 24px;
        }

        .modal-modern .modal-body {
            background: #f8f9fa;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-icon {
                font-size: 60px;
            }

            .books-container {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 16px;
            }

            .stat-number {
                font-size: 24px;
            }

            .action-bar {
                padding: 16px;
            }
        }
    </style>
@endsection