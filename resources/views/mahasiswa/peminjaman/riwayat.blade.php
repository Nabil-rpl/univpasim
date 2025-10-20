@extends('layouts.app')

@section('page-title', 'Riwayat Peminjaman')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">📖 Riwayat Peminjaman</h2>
                    <p class="text-muted mb-0">Semua riwayat peminjaman buku Anda</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('mahasiswa.peminjaman.index') }}" class="btn btn-primary">
                        <i class="bi bi-bookmark-plus"></i> Pinjam Buku
                    </a>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($peminjamans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Buku</th>
                                        <th width="15%">Tanggal Pinjam</th>
                                        <th width="15%">Tanggal Kembali</th>
                                        <th width="15%">Status</th>
                                        <th width="10%">Durasi</th>
                                        <th width="10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjamans as $index => $p)
                                        <tr>
                                            <td>{{ $peminjamans->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="book-icon me-3">
                                                        <i class="bi bi-book-fill"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="d-block">{{ $p->buku->judul }}</strong>
                                                        <small class="text-muted">{{ $p->buku->penulis }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="bi bi-calendar-event text-primary me-1"></i>
                                                {{ $p->tanggal_pinjam->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                @if($p->tanggal_kembali)
                                                    <i class="bi bi-calendar-check text-success me-1"></i>
                                                    {{ $p->tanggal_kembali->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($p->status == 'dipinjam')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock"></i> Dipinjam
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Dikembalikan
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($p->tanggal_kembali)
                                                    {{ $p->tanggal_pinjam->diffInDays($p->tanggal_kembali) }} hari
                                                @else
                                                    {{ $p->tanggal_pinjam->diffInDays(now()) }} hari
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('mahasiswa.peminjaman.show', $p->id) }}" 
                                                   class="btn btn-sm btn-info"
                                                   title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $peminjamans->firstItem() }} - {{ $peminjamans->lastItem() }} 
                                dari {{ $peminjamans->total() }} data
                            </div>
                            {{ $peminjamans->links() }}
                        </div>
                    @else
                        <div class="empty-state py-5">
                            <i class="bi bi-inbox"></i>
                            <h4>Belum Ada Riwayat Peminjaman</h4>
                            <p>Anda belum pernah meminjam buku</p>
                            <a href="{{ route('mahasiswa.peminjaman.index') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-bookmark-plus"></i> Pinjam Buku Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.table {
    margin-bottom: 0;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
}

.book-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.badge {
    padding: 0.5rem 0.75rem;
    font-weight: 600;
}

.empty-state {
    text-align: center;
}

.empty-state i {
    font-size: 5rem;
    color: #cbd5e0;
    display: block;
    margin-bottom: 1rem;
}

.empty-state h4 {
    color: #4a5568;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #a0aec0;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}
</style>
@endsection