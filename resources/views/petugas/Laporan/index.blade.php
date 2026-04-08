@extends('layouts.petugas')

@section('page-title', 'Laporan Peminjaman')

@section('content')
<style>
    .laporan-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .page-header {
        background: white;
        border-radius: 20px;
        padding: 24px 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }

    .header-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Card Table */
    .table-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
    }

    .table-custom thead {
        background: linear-gradient(135deg, #f1f5f9, #e0e7ff);
    }

    .table-custom thead th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
        color: #3b82f6;
        padding: 18px 14px;
        white-space: nowrap;
    }

    .table-custom tbody td {
        padding: 18px 14px;
        vertical-align: middle;
        color: #475569;
    }

    .table-custom tbody tr:hover {
        background: #f8fafc;
    }

    .btn-action {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .periode-badge {
        background: #eff6ff;
        color: #3b82f6;
        padding: 6px 14px;
        border-radius: 50px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Responsive Fixes */
    @media (max-width: 768px) {
        .header-title {
            font-size: 1.55rem;
        }

        .action-buttons {
            width: 100%;
            justify-content: center;
        }

        .action-buttons .btn {
            flex: 1;
            text-align: center;
            min-width: 140px;
        }

        .table-custom thead th,
        .table-custom tbody td {
            font-size: 0.85rem;
            padding: 14px 8px;
        }

        .table-responsive {
            border-radius: 16px;
        }

        .periode-badge {
            font-size: 0.9rem;
            padding: 5px 12px;
        }
    }

    @media (max-width: 576px) {
        .table-custom thead th:nth-child(3),
        .table-custom tbody td:nth-child(3) {
            display: none; /* Sembunyikan kolom "Dibuat Oleh" di HP kecil */
        }
    }
</style>

<div class="laporan-container">
    <div class="container">

        <!-- Header -->
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h1 class="header-title">
                        <i class="bi bi-file-earmark-bar-graph me-3 text-primary"></i>
                        Daftar Laporan Peminjaman
                    </h1>
                </div>
                
                <div class="action-buttons">
                    <a href="{{ route('petugas.laporan.export-pdf') }}" class="btn btn-danger btn-action">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('petugas.laporan.create') }}" class="btn btn-primary btn-action">
                        <i class="bi bi-plus-circle"></i> Buat Laporan Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="table-card">
            <div class="card-body p-0">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-4 mt-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @php
                    $namaBulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                @endphp

                @if($laporan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="28%">Periode</th>
                                    <th width="22%">Dibuat Oleh</th>
                                    <th width="18%">Tanggal Dibuat</th>
                                    <th width="27%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan as $index => $item)
                                    <tr>
                                        <td><strong>{{ $laporan->firstItem() + $index }}</strong></td>
                                        <td>
                                            <span class="periode-badge">
                                                <i class="bi bi-calendar-month"></i>
                                                {{ $namaBulan[$item->bulan] ?? '-' }} {{ $item->tahun }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->pembuat)
                                                <div>
                                                    <strong>{{ $item->pembuat->name }}</strong>
                                                </div>
                                                <small class="text-muted">
                                                    ({{ ucfirst($item->pembuat->role ?? '-') }})
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $item->created_at->format('d/m/Y') }}</strong><br>
                                            <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                <a href="{{ route('petugas.laporan.show', $item->id) }}"
                                                   class="btn btn-sm btn-info btn-action">
                                                    <i class="bi bi-eye"></i> Lihat
                                                </a>

                                                @if($item->dibuat_oleh === Auth::id() || Auth::user()->role === 'admin')
                                                    <a href="{{ route('petugas.laporan.edit', $item->id) }}"
                                                       class="btn btn-sm btn-warning btn-action">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>

                                                    <form action="{{ route('petugas.laporan.destroy', $item->id) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger btn-action">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-top">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <small class="text-muted">
                                Menampilkan {{ $laporan->firstItem() }} - {{ $laporan->lastItem() }} 
                                dari {{ $laporan->total() }} laporan
                            </small>
                            <div>
                                {{ $laporan->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5 my-5">
                        <i class="bi bi-file-earmark-text display-1 text-muted mb-4"></i>
                        <h4 class="text-dark">Belum Ada Laporan</h4>
                        <p class="text-muted mb-4">Mulai buat laporan peminjaman pertama Anda</p>
                        <a href="{{ route('petugas.laporan.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle"></i> Buat Laporan Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Auto hide success alert
    setTimeout(() => {
        document.querySelectorAll('.alert-success').forEach(alert => {
            alert.style.transition = 'opacity 0.4s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 450);
        });
    }, 4500);
</script>
@endsection