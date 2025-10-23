@extends('layouts.petugas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Laporan</h5>
                    <div>
                        @if($laporan->dibuat_oleh === Auth::id() || Auth::user()->role === 'admin')
                            <a href="{{ route('petugas.laporan.edit', $laporan->id) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            
                            <form action="{{ route('petugas.laporan.destroy', $laporan->id) }}" 
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
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h3>{{ $laporan->judul }}</h3>
                        <hr>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1">
                                <strong><i class="bi bi-person"></i> Dibuat oleh:</strong><br>
                                @if($laporan->pembuat)
                                    {{ $laporan->pembuat->name }} 
                                    <span class="badge bg-secondary">{{ ucfirst($laporan->pembuat->role) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1">
                                <strong><i class="bi bi-calendar"></i> Tanggal:</strong><br>
                                {{ $laporan->created_at->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    @if($laporan->created_at != $laporan->updated_at)
                        <div class="alert alert-light">
                            <small>
                                <i class="bi bi-info-circle"></i> 
                                Terakhir diperbarui: {{ $laporan->updated_at->format('d F Y, H:i') }} WIB
                            </small>
                        </div>
                    @endif

                    <hr>

                    <div class="mt-4">
                        <h5>Isi Laporan:</h5>
                        <div class="border rounded p-3 bg-light" style="white-space: pre-wrap;">{{ $laporan->isi }}</div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('petugas.laporan.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection