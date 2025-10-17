@extends('layouts.petugas')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Detail Peminjaman</h2>
                    <p class="text-muted mb-0">Informasi lengkap peminjaman buku</p>
                </div>
                <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Status Card -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Status Peminjaman</h5>
                            <p class="text-muted mb-0">ID: #{{ $peminjaman->id }}</p>
                        </div>
                        <div>
                            @if($peminjaman->status == 'dipinjam')
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="bi bi-clock"></i> Sedang Dipinjam
                                </span>
                            @else
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle"></i> Sudah Dikembalikan
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mahasiswa Info -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Informasi Mahasiswa</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <div class="avatar-circle-lg">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="info-group">
                                <div class="info-item">
                                    <label>Nama Mahasiswa</label>
                                    <p>{{ $peminjaman->mahasiswa->nama }}</p>
                                </div>
                                <div class="info-item">
                                    <label>NIM</label>
                                    <p><span class="badge bg-info">{{ $peminjaman->mahasiswa->nim }}</span></p>
                                </div>
                                <div class="info-item">
                                    <label>Email</label>
                                    <p>{{ $peminjaman->mahasiswa->email }}</p>
                                </div>
                                <div class="info-item mb-0">
                                    <label>Jurusan</label>
                                    <p>{{ $peminjaman->mahasiswa->jurusan ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Info -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-book me-2"></i>Informasi Buku</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <div class="book-cover-detail">
                                <i class="bi bi-book-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="info-group">
                                <div class="info-item">
                                    <label>Judul Buku</label>
                                    <p>{{ $peminjaman->buku->judul }}</p>
                                </div>
                                <div class="info-item">
                                    <label>Penulis</label>
                                    <p>{{ $peminjaman->buku->penulis }}</p>
                                </div>
                                <div class="info-item">
                                    <label>Penerbit</label>
                                    <p>{{ $peminjaman->buku->penerbit }}</p>
                                </div>
                                <div class="info-item mb-0">
                                    <label>Tahun Terbit</label>
                                    <p>{{ $peminjaman->buku->tahun_terbit }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrowing Details -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-calendar-range me-2"></i>Detail Peminjaman</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-box">
                                <i class="bi bi-calendar-plus detail-icon text-primary"></i>
                                <div>
                                    <label>Tanggal Pinjam</label>
                                    <p>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                                    <small class="text-muted">{{ $peminjaman->tanggal_pinjam->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-box">
                                @if($peminjaman->tanggal_kembali)
                                    <i class="bi bi-calendar-check detail-icon text-success"></i>
                                    <div>
                                        <label>Tanggal Kembali</label>
                                        <p>{{ $peminjaman->tanggal_kembali->format('d F Y') }}</p>
                                        <small class="text-muted">{{ $peminjaman->tanggal_kembali->diffForHumans() }}</small>
                                    </div>
                                @else
                                    <i class="bi bi-calendar-x detail-icon text-warning"></i>
                                    <div>
                                        <label>Tanggal Kembali</label>
                                        <p>Belum Dikembalikan</p>
                                        <small class="text-muted">Sudah {{ $peminjaman->tanggal_pinjam->diffInDays(now()) }} hari</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($peminjaman->tanggal_kembali)
                        <hr class="my-3">
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Durasi Peminjaman:</strong> 
                            {{ $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali) }} hari
                        </div>
                    @endif
                </div>
            </div>

            <!-- Petugas Info -->
            @if($peminjaman->petugas)
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Petugas yang Melayani</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-3">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $peminjaman->petugas->name }}</h6>
                                <p class="text-muted mb-0">{{ $peminjaman->petugas->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Aksi Cepat</span>
                        <div class="d-flex gap-2">
                            @if($peminjaman->status == 'dipinjam')
                                <form action="{{ route('petugas.peminjaman.show', $peminjaman->id) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Konfirmasi pengembalian buku?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-arrow-return-left"></i> Kembalikan Buku
                                    </button>
                                </form>
                            @endif
                            
                            <button type="button" 
                                    class="btn btn-danger" 
                                    onclick="confirmDelete()">
                                <i class="bi bi-trash"></i> Hapus Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Delete (Hidden) -->
<form id="delete-form" action="{{ route('petugas.peminjaman.destroy', $peminjaman->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1.25rem;
}

.card-header h5 {
    font-weight: 600;
}

.avatar-circle-lg {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
    margin: 0 auto;
}

.book-cover-detail {
    width: 100px;
    height: 130px;
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
    margin: 0 auto;
}

.info-group {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
}

.info-item {
    margin-bottom: 1.25rem;
}

.info-item label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    display: block;
}

.info-item p {
    color: #2d3748;
    font-size: 1rem;
    margin-bottom: 0;
}

.detail-box {
    display: flex;
    align-items: start;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    margin-bottom: 1rem;
}

.detail-icon {
    font-size: 2rem;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 10px;
    flex-shrink: 0;
}

.detail-box label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    display: block;
}

.detail-box p {
    color: #2d3748;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0;
}

.avatar-circle {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.badge {
    padding: 0.5rem 1rem;
    font-weight: 600;
}

.alert {
    border: none;
    border-radius: 12px;
}

.btn-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border: none;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(72, 187, 120, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
    border: none;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 101, 101, 0.4);
}
</style>

<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus data peminjaman ini?\n\nJika buku belum dikembalikan, stok akan otomatis dikembalikan.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection