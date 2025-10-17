@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">📚 Daftar Buku</h2>

    {{-- 🔹 Pesan sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🔹 Tabel Buku --}}
    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-striped table-bordered mb-0 align-middle text-center">
                <thead class="table-dark">
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
                        <tr>
                            <td>{{ $loop->iteration + ($buku->currentPage() - 1) * $buku->perPage() }}</td>

                            {{-- 🔹 Foto Buku --}}
                            <td>
                                @if ($item->foto && file_exists(public_path('storage/' . $item->foto)))
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#fotoModal{{ $item->id }}">
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Buku"
                                             width="60" height="60" class="rounded shadow-sm object-fit-cover"
                                             style="cursor: zoom-in;">
                                    </a>

                                    {{-- Modal Preview Foto --}}
                                    <div class="modal fade" id="fotoModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="fotoModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="fotoModalLabel{{ $item->id }}">
                                                        📖 {{ $item->judul }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

                            {{-- 🔹 Data Buku --}}
                            <td>{{ $item->judul }}</td>
                            <td>{{ $item->penulis }}</td>
                            <td>{{ $item->penerbit }}</td>
                            <td>{{ $item->tahun_terbit }}</td>
                            <td>{{ $item->stok }}</td>

                            {{-- 🔹 Tombol Aksi --}}
                            <td>
                                <a href="{{ route('admin.buku.show', $item->id) }}" class="btn btn-info btn-sm text-white">
                                    👁 Detail
                                </a>

                                <form action="{{ route('admin.buku.destroy', $item->id) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑 Hapus</button>
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
