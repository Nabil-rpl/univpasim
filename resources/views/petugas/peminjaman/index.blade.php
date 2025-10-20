@extends('layouts.petugas')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-center">📚 Data Peminjaman Buku</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- 🔹 Tombol Tambah & Filter/Search --}}
        <div class="d-flex justify-content-between mb-3 align-items-end flex-wrap gap-2">


            <form action="{{ route('petugas.peminjaman.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Cari nama mahasiswa, NIM, atau judul buku..."
                       value="{{ request('search') }}">
                <select name="status" class="form-select" style="width: auto; min-width: 140px;">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
                <button type="submit" class="btn btn-outline-secondary">Cari</button>
                <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>

        {{-- 🔹 Tabel Peminjaman --}}
        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0 align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjamans as $index => $p)
                                <tr>
                                    <td class="text-center">{{ $peminjamans->firstItem() + $index }}</td>
                                    <td class="text-start">
                                        <strong>{{ $p->mahasiswa->nama }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $p->mahasiswa->nim }}</span>
                                    </td>
                                    <td class="text-start">
                                        <strong>{{ $p->buku->judul }}</strong><br>
                                        <small class="text-muted">{{ $p->buku->penulis }}</small>
                                    </td>
                                    <td class="text-center">
                                        <i class="bi bi-calendar-event text-primary"></i>
                                        {{ $p->tanggal_pinjam->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center">
                                        @if($p->tanggal_kembali)
                                            <i class="bi bi-calendar-check text-success"></i>
                                            {{ $p->tanggal_kembali->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
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
                                    <td class="text-center">
                                        <a href="{{ route('petugas.peminjaman.show', $p->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Detail">
                                            👁 Detail
                                        </a>
                                        
                                        @if($p->status == 'dipinjam')
                                            <form action="{{ route('petugas.peminjaman.index', $p->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Konfirmasi pengembalian buku?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Kembalikan">
                                                    🔄 Kembalikan
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('petugas.peminjaman.index', $p->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peminjaman ini?\n\nJika buku belum dikembalikan, stok akan otomatis dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                🗑 Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3 text-muted fst-italic">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada data peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 🔹 Pagination --}}
        <div class="mt-3">
            {{ $peminjamans->links() }}
        </div>
    </div>
@endsection