@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text"></i> Detail Laporan
                    </h5>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <!-- Judul Laporan -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h3 class="text-primary">{{ $laporan->judul }}</h3>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person-circle text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Dibuat oleh</small>
                                        @if($laporan->pembuat)
                                            <strong>{{ $laporan->pembuat->name }}</strong>
                                            <span class="badge bg-info ms-2">{{ ucfirst($laporan->pembuat->role) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-calendar-check text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Tanggal Dibuat</small>
                                        <strong>{{ $laporan->created_at->format('d F Y, H:i') }} WIB</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($laporan->created_at != $laporan->updated_at)
                            <div class="alert alert-light mt-3 mb-0">
                                <i class="bi bi-info-circle"></i>
                                <small>
                                    Terakhir diperbarui: <strong>{{ $laporan->updated_at->format('d F Y, H:i') }} WIB</strong>
                                </small>
                            </div>
                        @endif
                    </div>

                    <!-- Isi Laporan -->
                    <div class="mt-4">
                        <h5 class="mb-3">
                            <i class="bi bi-file-earmark-text"></i> Isi Laporan:
                        </h5>
                        <div class="border rounded p-4 bg-light" style="white-space: pre-wrap; line-height: 1.8;">{{ $laporan->isi }}</div>
                    </div>

                    <!-- Footer Info -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="bi bi-clock-history"></i> 
                                    Dibuat {{ $laporan->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i>
                <strong>Catatan:</strong> Admin hanya dapat melihat laporan. Untuk mengedit atau menghapus laporan, silakan hubungi petugas yang membuat laporan ini.
            </div>
        </div>
    </div>
</div>
@endsection