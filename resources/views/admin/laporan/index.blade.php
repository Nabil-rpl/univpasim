@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Laporan</h5>
                    <a href="{{ route('admin.laporan.export-pdf') }}" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @php
                        $namaBulan = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                            4 => 'April',   5 => 'Mei',      6 => 'Juni',
                            7 => 'Juli',    8 => 'Agustus',  9 => 'September',
                            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        ];
                    @endphp

                    @if($laporan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Periode</th>
                                        <th width="20%">Dibuat Oleh</th>
                                        <th width="15%">Tanggal Dibuat</th>
                                        <th width="35%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($laporan as $index => $item)
                                        <tr>
                                            <td>{{ $laporan->firstItem() + $index }}</td>
                                            <td>
                                                <i class="bi bi-calendar-month"></i>
                                                {{ $namaBulan[$item->bulan] ?? '-' }} {{ $item->tahun }}
                                            </td>
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
                                                <a href="{{ route('admin.laporan.show', $item->id) }}"
                                                   class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Lihat
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
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Belum ada laporan yang dibuat.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection