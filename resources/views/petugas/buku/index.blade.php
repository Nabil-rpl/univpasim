@extends('layouts.petugas')

@section('content')
    <div class="container mt-4">
        <!-- Header dengan Gradien -->
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                📚 Daftar Buku Perpustakaan
            </h2>
            <p class="text-muted">Kelola koleksi buku perpustakaan Anda dengan mudah</p>
        </div>

        {{-- 🔹 Tombol Tambah Buku & Pencarian --}}
        <div class="d-flex justify-content-between mb-4 align-items-center flex-wrap gap-3">
            <a href="{{ route('petugas.buku.create') }}" class="btn btn-lg shadow-sm" 
               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 12px; transition: all 0.3s;">
                <i class="bi bi-plus-circle"></i> ➕ Tambah Buku Baru
            </a>

            <form action="{{ route('petugas.buku.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control shadow-sm" 
                       placeholder="🔍 Cari judul atau penulis..."
                       value="{{ request('search') }}"
                       style="border-radius: 10px; border: 2px solid #e0e0e0; padding: 10px 15px;">
                <button class="btn btn-outline-primary shadow-sm" type="submit" 
                        style="border-radius: 10px; border: 2px solid #667eea; padding: 10px 20px;">
                    Cari
                </button>
            </form>
        </div>

        {{-- 🔹 Tabel Buku dengan Card Modern --}}
        <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header text-white text-center py-3" 
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 fw-bold">📖 Katalog Buku</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead style="background-color: #f8f9fa;">
                            <tr class="text-center">
                                <th style="border: none; padding: 15px; color: #495057;">No</th>
                                <th style="border: none; padding: 15px; color: #495057;">Foto</th>
                                <th style="border: none; padding: 15px; color: #495057;">Judul</th>
                                <th style="border: none; padding: 15px; color: #495057;">Penulis</th>
                                <th style="border: none; padding: 15px; color: #495057;">Penerbit</th>
                                <th style="border: none; padding: 15px; color: #495057;">Tahun Terbit</th>
                                <th style="border: none; padding: 15px; color: #495057;">Kategori</th>
                                <th style="border: none; padding: 15px; color: #495057;">Stok</th>
                                <th style="border: none; padding: 15px; color: #495057;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($buku as $item)
                                <tr class="text-center" style="transition: all 0.3s;">
                                    <td style="border-bottom: 1px solid #f0f0f0; padding: 15px;">
                                        <span class="badge bg-primary" style="font-size: 14px; padding: 8px 12px; border-radius: 8px;">
                                            {{ $loop->iteration + ($buku->currentPage() - 1) * $buku->perPage() }}
                                        </span>
                                    </td>

                                    {{-- 🔹 Foto Buku --}}
                                    <td style="border-bottom: 1px solid #f0f0f0; padding: 15px;">
                                        @if ($item->foto && file_exists(public_path('storage/' . $item->foto)))
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#fotoModal{{ $item->id }}">
                                                <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Buku" width="70" height="70" 
                                                     class="rounded-3 shadow object-fit-cover" 
                                                     style="cursor: zoom-in; border: 2px solid #dee2e6; transition: transform 0.3s;"
                                                     onmouseover="this.style.transform='scale(1.1)'" 
                                                     onmouseout="this.style.transform='scale(1)'">
                                            </a>

                                            <!-- Modal Preview Foto -->
                                            <div class="modal fade" id="fotoModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="fotoModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content" style="border-radius: 15px; border: none;">
                                                        <div class="modal-header text-white" 
                                                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                                                            <h5 class="modal-title" id="fotoModalLabel{{ $item->id }}">
                                                                📖 {{ $item->judul }}
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                                aria-label="Tutup"></button>
                                                        </div>
                                                        <div class="modal-body text-center p-4" style="background: #f8f9fa;">
                                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Buku"
                                                                class="img-fluid rounded-3 shadow-lg">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center" 
                                                 style="width: 70px; height: 70px; background-color: #e9ecef; border-radius: 10px;">
                                                <span style="font-size: 24px;">📕</span>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-start fw-semibold" style="border-bottom: 1px solid #f0f0f0; padding: 15px; color: #2c3e50;">
                                        {{ $item->judul }}
                                    </td>
                                    <td style="border-bottom: 1px solid #f0f0f0; padding: 15px; color: #5a6c7d;">
                                        {{ $item->penulis }}
                                    </td>
                                    <td style="border-bottom: 1px solid #f0f0f0; padding: 15px; color: #5a6c7d;">
                                        {{ $item->penerbit }}
                                    </td>
                                    <td style="border-bottom: 1px solid #f0f0f0; padding: 15px;">
                                        <span class="badge bg-secondary" style="font-size: 13px; padding: 6px 12px; border-radius: 8px;">
                                            {{ $item->tahun_terbit }}
                                        </span>
                                    </td>
                                    
                                    {{-- 🔹 Kolom Kategori --}}
                                    <td style="border-bottom: 1px solid #f0f0f0; padding: 15px;">
                                        @if($item->kategori)
                                            <span class="badge bg-info text-dark" style="font-size: 13px; padding: 8px 15px; border-radius: 20px;">
                                                {{ $item->kategori }}
                                            </span>
                                        @else
                                            <span class="text-muted fst-italic">-</span>
                                        @endif
                                    </td>
                                    
                                    <td style="border-bottom: 1px solid #f0f0f0; padding: 15px;">
                                        <span class="badge bg-success" style="font-size: 14px; padding: 8px 15px; border-radius: 20px; font-weight: 600;">
                                            {{ $item->stok }}
                                        </span>
                                    </td>

                                    {{-- 🔹 Tombol Aksi --}}
                                    <td style="border-bottom: 1px solid #f0f0f0; padding: 15px;">
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            <a href="{{ route('petugas.buku.show', $item->id) }}"
                                                class="btn btn-sm shadow-sm" 
                                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; padding: 6px 12px; transition: all 0.3s;">
                                                👁 Detail
                                            </a>
                                            
                                            <a href="{{ route('petugas.buku.edit', $item->id) }}" 
                                                class="btn btn-sm shadow-sm" 
                                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; padding: 6px 12px; transition: all 0.3s;">
                                                ✏ Edit
                                            </a>

                                            <form action="{{ route('petugas.buku.destroy', $item->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm shadow-sm" 
                                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; padding: 6px 12px; transition: all 0.3s;">
                                                    🗑 Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5" style="border: none;">
                                        <div style="color: #9e9e9e;">
                                            <div style="font-size: 48px; margin-bottom: 10px;">📚</div>
                                            <p class="fst-italic mb-0">Belum ada data buku.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 🔹 Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $buku->links() }}
        </div>
    </div>

    <style>
        /* Hover effect untuk tombol */
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important;
        }

        /* Hover effect untuk baris tabel */
        tbody tr:hover {
            background-color: #f8f9fa !important;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        /* Animasi untuk card */
        .card {
            animation: fadeInUp 0.5s ease-in-out;
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

        /* Style untuk input search */
        input[type="text"]:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
            outline: none;
        }
    </style>
@endsection