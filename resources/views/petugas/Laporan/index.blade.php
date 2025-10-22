@extends('layouts.petugas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Laporan</h5>
                    <a href="{{ route('petugas.laporan.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Buat Laporan Baru
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($laporan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Judul</th>
                                        <th width="20%">Dibuat Oleh</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="30%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($laporan as $index => $item)
                                        <tr>
                                            <td>{{ $laporan->firstItem() + $index }}</td>
                                            <td>{{ $item->judul }}</td>
                                            <td>
                                                @if($item->pembuat)
                                                    {{ $item->pembuat->name }}
                                                    <small class="text-muted">({{ ucfirst($item->pembuat->role) }})</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('petugas.laporan.show', $item->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Lihat
                                                </a>
                                                
                                                @if($item->dibuat_oleh === Auth::id() || Auth::user()->role === 'admin')
                                                    <a href="{{ route('petugas.laporan.edit', $item->id) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    
                                                    <form action="{{ route('petugas.laporan.destroy', $item->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $laporan->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Belum ada laporan yang dibuat.
                            <a href="{{ route('petugas.laporan.create') }}">Buat laporan pertama</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection