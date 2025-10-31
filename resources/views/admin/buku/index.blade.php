@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    {{-- Header --}}
    <div class="header-section mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="page-title mb-2">
                    <div class="icon-wrapper">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <span>Daftar Books</span>
                </h2>
                <p class="page-subtitle mb-0">Kelola koleksi buku perpustakaan Anda</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="stats-badge">
                    <div class="stats-icon">
                        <i class="bi bi-collection"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-label">Total Buku</div>
                        <div class="stats-value">{{ $buku->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="alert alert-success-custom alert-dismissible fade show" role="alert">
            <div class="alert-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="alert-content">
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card main-card">
        <div class="card-header-custom">
            <h5 class="card-title-custom">
                <i class="bi bi-table"></i> Data Buku Perpustakaan
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="100">Foto</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th width="100">Tahun</th>
                            <th width="100">Kategori</th>
                            <th width="80">Stok</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buku as $item)
                            <tr>
                                <td class="text-center">
                                    <div class="number-badge">
                                        {{ $loop->iteration + ($buku->currentPage() - 1) * $buku->perPage() }}
                                    </div>
                                </td>

                                {{-- Foto Buku --}}
                                <td class="text-center">
                                    @if ($item->foto && file_exists(public_path('storage/' . $item->foto)))
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#fotoModal{{ $item->id }}">
                                            <div class="book-cover">
                                                <img src="{{ asset('storage/' . $item->foto) }}" 
                                                     alt="Foto Buku">
                                                <div class="book-cover-overlay">
                                                    <i class="bi bi-zoom-in"></i>
                                                </div>
                                            </div>
                                        </a>

                                        {{-- Modal Preview Foto --}}
                                        <div class="modal fade" id="fotoModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="fotoModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content modal-custom">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="fotoModalLabel{{ $item->id }}">
                                                            <i class="bi bi-book"></i> {{ $item->judul }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center p-4">
                                                        <img src="{{ asset('storage/' . $item->foto) }}" 
                                                             alt="Foto Buku"
                                                             class="img-fluid rounded-3 shadow-lg"
                                                             style="max-height: 500px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="no-image">
                                            <i class="bi bi-image"></i>
                                            <small>No Image</small>
                                        </div>
                                    @endif
                                </td>

                                {{-- Data Buku --}}
                                <td class="text-start">
                                    <strong class="book-title">{{ $item->judul }}</strong>
                                </td>
                                <td>
                                    <span class="text-secondary">{{ $item->penulis }}</span>
                                </td>
                                <td>
                                    <span class="text-secondary">{{ $item->penerbit }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="year-badge">{{ $item->tahun_terbit }}</span>
                                </td>
                                <td class="text-center">
                                    @if($item->kategori)
                                        <span class="category-badge">
                                            {{ Str::limit($item->kategori, 15) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="stock-badge {{ $item->stok > 0 ? 'stock-available' : 'stock-empty' }}">
                                        <i class="bi bi-box-seam"></i> {{ $item->stok }}
                                    </span>
                                </td>

                                {{-- Tombol Aksi --}}
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.buku.show', $item->id) }}" 
                                           class="btn-action btn-info-custom"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <button type="button" 
                                                class="btn-action btn-danger-custom"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $item->id }}"
                                                title="Hapus Buku">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    {{-- Modal Konfirmasi Hapus --}}
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content modal-custom">
                                                <div class="modal-header modal-header-danger">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-1">Yakin ingin menghapus buku ini?</p>
                                                    <strong class="text-danger">"{{ $item->judul }}"</strong>
                                                    <p class="text-muted mt-2 mb-0">
                                                        <small><i class="bi bi-info-circle"></i> Data yang dihapus tidak dapat dikembalikan.</small>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x"></i> Batal
                                                    </button>
                                                    <form action="{{ route('admin.buku.destroy', $item->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>Belum ada data buku tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Card Footer with Pagination --}}
        @if($buku->hasPages())
        <div class="card-footer-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div class="pagination-info">
                    <i class="bi bi-info-circle"></i>
                    Menampilkan {{ $buku->firstItem() }} - {{ $buku->lastItem() }} dari {{ $buku->total() }} data
                </div>
                <div>
                    {{ $buku->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* =========================
   General & Root Variables
   ========================= */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    --card-hover-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* =========================
   Header Section
   ========================= */
.header-section {
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
    border-radius: 20px;
    margin-bottom: 2rem;
    border: 2px solid #667eea20;
}

.page-title {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.icon-wrapper {
    width: 55px;
    height: 55px;
    background: var(--primary-gradient);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.page-subtitle {
    color: #718096;
    font-size: 0.95rem;
    margin-left: 70px;
}

/* Stats Badge */
.stats-badge {
    display: inline-flex;
    align-items: center;
    gap: 1rem;
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border: 2px solid #667eea20;
}

.stats-icon {
    width: 50px;
    height: 50px;
    background: var(--primary-gradient);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.stats-content {
    text-align: left;
}

.stats-label {
    font-size: 0.75rem;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.stats-value {
    font-size: 1.5rem;
    font-weight: 700;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* =========================
   Alert Styling
   ========================= */
.alert-success-custom {
    background: linear-gradient(135deg, #11998e15 0%, #38ef7d15 100%);
    border: 2px solid #11998e40;
    border-radius: 15px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 5px 20px rgba(17, 153, 142, 0.15);
}

.alert-icon {
    width: 45px;
    height: 45px;
    background: var(--success-gradient);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.alert-content {
    flex: 1;
    color: #2d3748;
}

/* =========================
   Main Card
   ========================= */
.main-card {
    border: none;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    overflow: hidden;
    transition: var(--transition);
}

.main-card:hover {
    box-shadow: var(--card-hover-shadow);
}

.card-header-custom {
    background: var(--primary-gradient);
    padding: 1.5rem;
    border: none;
}

.card-title-custom {
    color: white;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* =========================
   Table Styling
   ========================= */
.table-custom {
    margin: 0;
}

.table-custom thead {
    background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
    color: white;
}

.table-custom thead th {
    padding: 1.25rem 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    border: none;
    text-align: center;
    white-space: nowrap;
}

.table-custom tbody tr {
    transition: var(--transition);
    border-bottom: 1px solid #e2e8f0;
}

.table-custom tbody tr:hover {
    background: linear-gradient(90deg, #667eea05 0%, #764ba205 100%);
    transform: translateX(5px);
}

.table-custom tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

/* =========================
   Number Badge
   ========================= */
.number-badge {
    width: 35px;
    height: 35px;
    background: var(--primary-gradient);
    color: white;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

/* =========================
   Book Cover
   ========================= */
.book-cover {
    width: 70px;
    height: 70px;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    transition: var(--transition);
}

.book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.book-cover-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(102, 126, 234, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    opacity: 0;
    transition: var(--transition);
}

.book-cover:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
}

.book-cover:hover .book-cover-overlay {
    opacity: 1;
}

.book-cover:hover img {
    transform: scale(1.1);
}

/* No Image Placeholder */
.no-image {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
    border-radius: 12px;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #a0aec0;
}

.no-image i {
    font-size: 1.5rem;
    margin-bottom: 0.25rem;
}

.no-image small {
    font-size: 0.65rem;
}

/* =========================
   Book Title & Info
   ========================= */
.book-title {
    color: #2d3748;
    font-size: 0.95rem;
    line-height: 1.5;
}

/* =========================
   Badges
   ========================= */
.year-badge {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    padding: 0.4rem 0.9rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-block;
    box-shadow: 0 4px 10px rgba(79, 172, 254, 0.3);
}

.category-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.4rem 0.9rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-block;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

.stock-badge {
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.stock-available {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    box-shadow: 0 4px 10px rgba(17, 153, 142, 0.3);
}

.stock-empty {
    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    color: white;
    box-shadow: 0 4px 10px rgba(235, 51, 73, 0.3);
}

/* =========================
   Action Buttons
   ========================= */
.action-buttons {
    display: inline-flex;
    gap: 0.5rem;
}

.btn-action {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: var(--transition);
    font-size: 0.9rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.btn-info-custom {
    background: var(--info-gradient);
}

.btn-danger-custom {
    background: var(--danger-gradient);
}

.btn-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}

/* =========================
   Modal Styling
   ========================= */
.modal-custom .modal-header {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 1.25rem 1.5rem;
}

.modal-custom .modal-title {
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-custom .modal-body {
    padding: 2rem;
    background: #f7fafc;
}

.modal-header-danger {
    background: var(--danger-gradient);
    color: white;
    border: none;
    padding: 1.25rem 1.5rem;
}

/* =========================
   Empty State
   ========================= */
.empty-state {
    padding: 3rem 0;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #a0aec0;
    font-size: 1.1rem;
    margin: 0;
}

/* =========================
   Card Footer
   ========================= */
.card-footer-custom {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-top: 2px solid #e2e8f0;
    padding: 1.25rem 1.5rem;
}

.pagination-info {
    color: #718096;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* =========================
   Responsive Design
   ========================= */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .stats-badge {
        margin-top: 1rem;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .book-cover {
        width: 50px;
        height: 50px;
    }
}

/* =========================
   Animation
   ========================= */
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

.main-card {
    animation: fadeInUp 0.5s ease-out;
}
</style>

<script>
// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection