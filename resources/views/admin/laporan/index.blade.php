@extends('layouts.app')

@section('title', 'Laporan Petugas')
@section('page-title', 'Laporan Petugas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text"></i> Daftar Laporan dari Petugas
                    </h5>
                    <span class="badge bg-light text-dark">Total: {{ $laporan->total() }}</span>
                </div>

                <div class="card-body">
                    @if($laporan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Judul Laporan</th>
                                        <th width="20%">Dibuat Oleh</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="15%">Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($laporan as $index => $item)
                                        <tr>
                                            <td>{{ $laporan->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $item->judul }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ Str::limit(strip_tags($item->isi), 60) }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($item->pembuat)
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 35px; height: 35px; font-size: 0.9rem;">
                                                            {{ strtoupper(substr($item->pembuat->name, 0, 1)) }}
                                                        </div>
                                                        <div class="ms-2">
                                                            <div>{{ $item->pembuat->name }}</div>
                                                            <small class="badge bg-info">
                                                                {{ ucfirst($item->pembuat->role) }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $item->created_at->format('d M Y') }}</div>
                                                <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($item->created_at->diffInDays(now()) < 7)
                                                    <span class="badge bg-success">Baru</span>
                                                @else
                                                    <span class="badge bg-secondary">Lama</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.laporan.show', $item->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> Lihat Detail
                                                </a>
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
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle fs-1"></i>
                            <h5 class="mt-3">Belum Ada Laporan</h5>
                            <p class="mb-0">Belum ada laporan yang dibuat oleh petugas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection