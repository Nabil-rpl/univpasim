@extends('layouts.petugas')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-center">📚 Daftar Buku</h2>

        {{-- 🔹 Tombol Tambah Buku & Pencarian --}}
        <div class="d-flex justify-content-between mb-3 align-items-center flex-wrap gap-2">
            <a href="{{ route('petugas.buku.create') }}" class="btn btn-primary">
                ➕ Tambah Buku
            </a>

            <form action="{{ route('petugas.buku.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari judul / penulis..."
                    value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </form>
        </div>

        {{-- 🔹 Tabel Buku --}}
        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-striped table-bordered mb-0 align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun Terbit</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buku as $item)
                            <tr class="text-center">
                                <td>{{ $loop->iteration + ($buku->currentPage() - 1) * $buku->perPage() }}</td>

                                {{-- 🔹 Foto Buku --}}
                                <td>
                                    @if ($item->foto && file_exists(public_path('storage/' . $item->foto)))
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#fotoModal{{ $item->id }}">
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Buku" width="60"
                                                height="60" class="rounded shadow-sm object-fit-cover"
                                                style="cursor: zoom-in;">
                                        </a>

                                        <!-- Modal Preview Foto -->
                                        <div class="modal fade" id="fotoModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="fotoModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="fotoModalLabel{{ $item->id }}">📖
                                                            {{ $item->judul }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Buku"
                                                            class="img-fluid rounded shadow">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada</span>
                                    @endif

                                </td>

                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->penulis }}</td>
                                <td>{{ $item->penerbit }}</td>
                                <td>{{ $item->tahun_terbit }}</td>
                                <td>{{ $item->stok }}</td>

                                {{-- 🔹 Tombol Aksi --}}
                                <td>
                                    <a href="{{ route('petugas.buku.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        ✏ Edit
                                    </a>

                                    <a href="{{ route('petugas.buku.show', $item->id) }}"
                                        class="btn btn-sm btn-info text-white">
                                        👁 Detail
                                    </a>


                                    <form action="{{ route('petugas.buku.destroy', $item->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            🗑 Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-3 text-muted fst-italic">
                                    Belum ada data buku.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 🔹 Pagination --}}
        <div class="mt-3">
            {{ $buku->links() }}
        </div>
    </div>
@endsection
