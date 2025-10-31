@extends('layouts.pengguna-luar')

@section('content')
<style>
    .book-list-container {
        background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }

    .page-header {
        text-align: center;
        margin-bottom: 3rem;
        animation: fadeInDown 0.8s ease;
    }

    .header-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #DBEAFE;
        color: #3B82F6;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .page-header h2 {
        font-size: 2.75rem;
        font-weight: 800;
        color: #1E293B;
        margin-bottom: 0.75rem;
        letter-spacing: -0.5px;
    }

    .page-header p {
        font-size: 1.15rem;
        color: #64748B;
        font-weight: 500;
    }

    .search-container {
        background: #FFFFFF;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.15);
        margin-bottom: 3rem;
        animation: fadeInUp 0.8s ease;
        border: 1px solid #E0E7FF;
    }

    .search-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .search-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .search-header h5 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: #1E293B;
    }

    .search-form input,
    .search-form select {
        border: 2px solid #E0E7FF;
        border-radius: 14px;
        padding: 0.875rem 1.25rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #F8FAFC;
        color: #1E293B;
    }

    .search-form input:focus,
    .search-form select:focus {
        border-color: #60A5FA;
        box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.1);
        outline: none;
        background: #FFFFFF;
    }

    .search-form input::placeholder {
        color: #94A3B8;
    }

    .btn-search {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        border: none;
        border-radius: 14px;
        padding: 0.875rem 2rem;
        color: white;
        font-weight: 700;
        transition: all 0.3s ease;
        width: 100%;
        font-size: 0.95rem;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.3);
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(96, 165, 250, 0.4);
        background: linear-gradient(135deg, #3B82F6, #2563EB);
    }

    .btn-search:active {
        transform: translateY(0);
    }

    .results-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.25rem;
        background: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(96, 165, 250, 0.1);
    }

    .results-count {
        font-size: 0.95rem;
        color: #64748B;
        font-weight: 600;
    }

    .results-count strong {
        color: #3B82F6;
        font-size: 1.1rem;
    }

    .book-card {
        background: #FFFFFF;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.12);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease;
        animation-fill-mode: both;
        border: 1px solid #E0E7FF;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .book-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(96, 165, 250, 0.25);
        border-color: #60A5FA;
    }

    .book-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 320px;
        background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
    }

    .book-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .book-card:hover img {
        transform: scale(1.15);
    }

    .book-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #3B82F6;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .book-card-body {
        padding: 1.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .book-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #1E293B;
        margin-bottom: 1rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3.4rem;
    }

    .book-meta {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex: 1;
    }

    .meta-item {
        display: flex;
        align-items: start;
        gap: 0.75rem;
        font-size: 0.9rem;
        color: #64748B;
        line-height: 1.5;
    }

    .meta-icon {
        width: 32px;
        height: 32px;
        background: #EFF6FF;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #60A5FA;
        font-size: 0.9rem;
    }

    .meta-content {
        flex: 1;
    }

    .meta-label {
        font-weight: 700;
        color: #3B82F6;
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .btn-detail {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        border: none;
        border-radius: 12px;
        padding: 0.875rem 1.5rem;
        color: white;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        width: 100%;
        text-align: center;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.3);
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(96, 165, 250, 0.4);
        color: white;
        background: linear-gradient(135deg, #3B82F6, #2563EB);
    }

    .btn-detail i {
        transition: transform 0.3s ease;
    }

    .btn-detail:hover i {
        transform: translateX(3px);
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: #FFFFFF;
        border-radius: 24px;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
        border: 1px solid #E0E7FF;
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        margin: 0 auto 2rem;
        color: #60A5FA;
    }

    .empty-state h3 {
        color: #1E293B;
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        color: #64748B;
        font-size: 1.05rem;
        margin-bottom: 2rem;
    }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.3);
    }

    .btn-reset:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(96, 165, 250, 0.4);
        color: white;
    }

    .pagination {
        justify-content: center;
        margin-top: 3rem;
        gap: 0.5rem;
    }

    .pagination .page-link {
        border-radius: 12px;
        border: 2px solid #E0E7FF;
        color: #3B82F6;
        font-weight: 700;
        padding: 0.75rem 1.25rem;
        background: #FFFFFF;
        box-shadow: 0 2px 10px rgba(96, 165, 250, 0.1);
        transition: all 0.3s ease;
        margin: 0;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        transform: translateY(-2px);
        border-color: #60A5FA;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.3);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        border-color: #60A5FA;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        background: #F1F5F9;
        color: #CBD5E1;
        border-color: #E2E8F0;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stagger animation for cards */
    .book-card:nth-child(1) { animation-delay: 0.1s; }
    .book-card:nth-child(2) { animation-delay: 0.15s; }
    .book-card:nth-child(3) { animation-delay: 0.2s; }
    .book-card:nth-child(4) { animation-delay: 0.25s; }
    .book-card:nth-child(5) { animation-delay: 0.3s; }
    .book-card:nth-child(6) { animation-delay: 0.35s; }
    .book-card:nth-child(7) { animation-delay: 0.4s; }
    .book-card:nth-child(8) { animation-delay: 0.45s; }

    @media (max-width: 768px) {
        .page-header h2 {
            font-size: 2rem;
        }
        
        .search-container {
            padding: 1.5rem;
        }

        .book-image-wrapper {
            height: 280px;
        }

        .results-info {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
</style>

<div class="book-list-container">
    <div class="container">
        <div class="page-header">
            <div class="header-badge">
                <i class="bi bi-book-fill"></i>
                <span>Perpustakaan Digital</span>
            </div>
            <h2>Koleksi Buku Kami</h2>
            <p>Temukan dan pinjam buku favorit Anda dengan mudah</p>
        </div>

        <div class="search-container">
            <div class="search-header">
                <div class="search-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h5>Cari Buku</h5>
            </div>
            <form action="{{ route('pengguna-luar.buku.index') }}" method="GET" class="search-form">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Cari judul, penulis, atau penerbit..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="kategori" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-search">
                            <i class="bi bi-search"></i> Cari Buku
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(request('search') || request('kategori'))
        <div class="results-info">
            <div class="results-count">
                Menampilkan <strong>{{ $buku->total() }}</strong> buku
                @if(request('search'))
                    untuk "<strong>{{ request('search') }}</strong>"
                @endif
                @if(request('kategori'))
                    dalam kategori "<strong>{{ request('kategori') }}</strong>"
                @endif
            </div>
            <a href="{{ route('pengguna-luar.buku.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 10px; padding: 0.5rem 1rem;">
                <i class="bi bi-x-circle"></i> Reset Filter
            </a>
        </div>
        @endif

        <div class="row">
            @forelse ($buku as $item)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="book-card">
                        <div class="book-image-wrapper">
                            @if ($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}">
                            @else
                                <img src="https://via.placeholder.com/300x400/60A5FA/ffffff?text=📚" alt="{{ $item->judul }}">
                            @endif
                            @if($item->kategori)
                                <div class="book-badge">
                                    <i class="bi bi-tag-fill"></i> {{ $item->kategori }}
                                </div>
                            @endif
                        </div>
                        <div class="book-card-body">
                            <h5 class="book-title">{{ $item->judul }}</h5>
                            <div class="book-meta">
                                <div class="meta-item">
                                    <div class="meta-icon">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div class="meta-content">
                                        <span class="meta-label">Penulis</span>
                                        {{ $item->penulis }}
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-icon">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="meta-content">
                                        <span class="meta-label">Penerbit</span>
                                        {{ $item->penerbit }}
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('pengguna-luar.buku.show', $item->id) }}" class="btn-detail">
                                <span>Lihat Detail</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h3>Tidak Ada Buku Ditemukan</h3>
                        <p>Maaf, kami tidak menemukan buku yang sesuai dengan pencarian Anda.<br>Coba gunakan kata kunci yang berbeda atau ubah filter kategori.</p>
                        <a href="{{ route('pengguna-luar.buku.index') }}" class="btn-reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>Reset Pencarian</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($buku->hasPages())
        <div class="d-flex justify-content-center">
            {{ $buku->links() }}
        </div>
        @endif
    </div>
</div>
@endsection