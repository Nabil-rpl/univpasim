@extends('layouts.mahasiswa')

@section('page-title', 'Riwayat Peminjaman')

@section('content')
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .stats-card {
            border-radius: 15px;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid;
            margin-bottom: 20px;
        }

        .stats-card.primary {
            border-left-color: #2563eb;
        }

        .stats-card.success {
            border-left-color: #10b981;
        }

        .stats-card.warning {
            border-left-color: #f59e0b;
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .bg-primary-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }

        .bg-success-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .bg-warning-gradient {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            background: #f8fafc;
        }

        .book-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .badge-status {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            border-radius: 20px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 5rem;
            color: #cbd5e0;
            display: block;
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #a0aec0;
        }

        .btn-custom {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .alert {
            border: none;
            border-radius: 12px;
        }
    </style>

    <div class="container mt-4">
        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1"><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman</h3>
                        <p class="text-muted mb-0">Semua riwayat peminjaman buku Anda</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('mahasiswa.buku.index') }}" class="btn btn-primary btn-custom">
                            <i class="bi bi-book me-2"></i>Lihat Buku
                        </a>
                        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary btn-custom">
                            <i class="bi bi-arrow-left me-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        @php
            $totalPeminjaman = $peminjaman->count();
            $sedangDipinjam = $peminjaman->where('status', 'dipinjam')->count();
            $sudahDikembalikan = $peminjaman->where('status', 'dikembalikan')->count();
        @endphp

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Peminjaman</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalPeminjaman }}</h2>
                        </div>
                        <div class="stats-icon bg-primary-gradient">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Sedang Dipinjam</h6>
                            <h2 class="mb-0 fw-bold">{{ $sedangDipinjam }}</h2>
                        </div>
                        <div class="stats-icon bg-warning-gradient">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Sudah Dikembalikan</h6>
                            <h2 class="mb-0 fw-bold">{{ $sudahDikembalikan }}</h2>
                        </div>
                        <div class="stats-icon bg-success-gradient">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="filter-card">
            <form method="GET" action="{{ route('mahasiswa.peminjaman.riwayat') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam
                            </option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah
                                Dikembalikan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" class="form-control"
                            value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" class="form-control"
                            value="{{ request('tanggal_sampai') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($peminjaman->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="30%">Buku</th>
                                            <th width="13%">Tanggal Pinjam</th>
                                            <th width="13%">Tanggal Deadline</th>
                                            <th width="13%">Tanggal Kembali</th>
                                            <th width="12%">Status</th>
                                            <th width="12%">Denda</th>
                                            <th width="12%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($peminjaman as $index => $p)
                                            <tr>
                                                <td>{{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="book-icon me-3">
                                                            @if ($p->buku->foto)
                                                                <img src="{{ asset('storage/foto_buku/' . $p->buku->foto) }}"
                                                                    alt="{{ $p->buku->judul }}" class="rounded"
                                                                    width="45" height="45"
                                                                    style="object-fit: cover;">
                                                            @else
                                                                <i class="bi bi-book-fill"></i>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <strong class="d-block">{{ $p->buku->judul }}</strong>
                                                            <small class="text-muted">{{ $p->buku->penulis }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <i class="bi bi-calendar-event text-primary me-1"></i>
                                                    {{ $p->tanggal_pinjam->format('d M Y') }}
                                                </td>
                                                <td>
                                                    <i class="bi bi-calendar-date text-warning me-1"></i>
                                                    {{ $p->tanggal_deadline->format('d M Y') }}
                                                </td>
                                                <td>
                                                    @if ($p->tanggal_kembali)
                                                        <i class="bi bi-calendar-check text-success me-1"></i>
                                                        {{ $p->tanggal_kembali->format('d M Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($p->status == 'dipinjam')
                                                        @php
                                                            $isOverdue = $p->tanggal_deadline->isPast();
                                                            $badgeClass = $isOverdue
                                                                ? 'bg-danger'
                                                                : 'bg-warning text-dark';
                                                        @endphp
                                                        <span class="badge badge-status {{ $badgeClass }}">
                                                            <i class="bi bi-hourglass-split me-1"></i>Dipinjam
                                                        </span>
                                                    @else
                                                        <span class="badge badge-status bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($p->status == 'dipinjam' && $p->tanggal_deadline->isPast())
                                                        @php
                                                            $hariTerlambat = $p->tanggal_deadline->diffInDays(now());
                                                            $denda = $hariTerlambat * 5000; // From PengembalianController
                                                        @endphp
                                                        <span class="text-danger">Rp
                                                            {{ number_format($denda, 0, ',', '.') }}</span>
                                                    @elseif($p->pengembalian && $p->pengembalian->denda > 0)
                                                        <span class="text-danger">Rp
                                                            {{ number_format($p->pengembalian->denda, 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('mahasiswa.peminjaman.show', $p->id) }}"
                                                        class="btn btn-sm btn-info" title="Detail">
                                                        <i class="bi bi-eye me-1"></i>Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if ($peminjaman instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="mt-3">
                                    {{ $peminjaman->appends(request()->except('page'))->links() }}
                                </div>
                            @endif
                        @else
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h4>Belum Ada Riwayat Peminjaman</h4>
                                <p>Anda belum pernah meminjam buku</p>
                                <a href="{{ route('mahasiswa.buku.index') }}" class="btn btn-primary btn-custom mt-3">
                                    <i class="bi bi-book me-2"></i>Lihat Koleksi Buku
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto hide alerts
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endsection
