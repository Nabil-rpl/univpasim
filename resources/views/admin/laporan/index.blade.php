@extends('layouts.app')

@section('title', 'Laporan Petugas')
@section('page-title', 'Laporan Petugas')

@section('content')
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 28px;
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 5px solid;
            margin-bottom: 24px;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stats-card.primary {
            border-left-color: #667eea;
        }

        .stats-card.success {
            border-left-color: #10b981;
        }

        .stats-card.warning {
            border-left-color: #f59e0b;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .bg-primary-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-success-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .bg-warning-gradient {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .laporan-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
            border: 1px solid rgba(102, 126, 234, 0.08);
            transition: all 0.3s ease;
        }

        .laporan-card:hover {
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .laporan-item {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .laporan-item:hover {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            border-left-color: #8b5cf6;
            transform: translateX(5px);
        }

        .laporan-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }

        .laporan-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .laporan-excerpt {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .laporan-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #64748b;
        }

        .meta-item i {
            color: #667eea;
            font-size: 1rem;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            flex-shrink: 0;
        }

        .author-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .author-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
        }

        .author-role {
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
        }

        .btn-detail {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-detail:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
        }

        .empty-icon {
            font-size: 5rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #667eea;
            font-size: 1.3rem;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 24px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .laporan-header {
                flex-direction: column;
                gap: 12px;
            }

            .laporan-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>

    <div class="container-fluid mt-4">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-2 fw-bold">
                        <i class="bi bi-file-text-fill me-2"></i>Laporan Petugas
                    </h3>
                    <p class="mb-0 opacity-90" style="font-size: 0.95rem;">
                        Monitor dan kelola semua laporan yang dibuat oleh petugas perpustakaan
                    </p>
                </div>
                <div>
                    <span class="badge bg-light text-dark px-3 py-2" style="font-size: 0.95rem;">
                        <i class="bi bi-journal-text me-1"></i>
                        Total: <strong>{{ $laporan->total() }}</strong> Laporan
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        @php
            $totalLaporan = $laporan->total();
            $laporanBaru = $laporan
                ->filter(function ($item) {
                    return $item->created_at->diffInDays(now()) < 7;
                })
                ->count();
            $laporanBulanIni = $laporan
                ->filter(function ($item) {
                    return $item->created_at->isCurrentMonth();
                })
                ->count();
        @endphp

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Laporan</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalLaporan }}</h2>
                            <small class="text-muted">Semua laporan</small>
                        </div>
                        <div class="stats-icon bg-primary-gradient">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="stats-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Laporan Baru</h6>
                            <h2 class="mb-0 fw-bold">{{ $laporanBaru }}</h2>
                            <small class="text-muted">7 hari terakhir</small>
                        </div>
                        <div class="stats-icon bg-success-gradient">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="stats-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Bulan Ini</h6>
                            <h2 class="mb-0 fw-bold">{{ $laporanBulanIni }}</h2>
                            <small class="text-muted">{{ now()->format('F Y') }}</small>
                        </div>
                        <div class="stats-icon bg-warning-gradient">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan List -->
        <div class="laporan-card">
            <h5 class="section-title">
                <i class="bi bi-list-ul"></i>
                Daftar Laporan Petugas
            </h5>

            @if ($laporan->count() > 0)
                @foreach ($laporan as $index => $item)
                    <div class="laporan-item">
                        <div class="laporan-header">
                            <div class="flex-grow-1">
                                <div class="laporan-title">
                                    <i class="bi bi-file-earmark-text text-primary"></i>
                                    {{ $item->judul }}
                                </div>
                                <div class="laporan-excerpt">
                                    {{ Str::limit(strip_tags($item->isi), 120) }}
                                </div>
                            </div>
                            <div>
                                @if ($item->created_at->diffInDays(now()) < 7)
                                    <span class="status-badge bg-success text-white">
                                        <i class="bi bi-star-fill me-1"></i>Baru
                                    </span>
                                @else
                                    <span class="status-badge bg-secondary text-white">
                                        <i class="bi bi-archive me-1"></i>Arsip
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="laporan-meta">
                                <!-- Author Info -->
                                <div class="author-info">
                                    @if ($item->pembuat)
                                        <div class="author-avatar">
                                            {{ strtoupper(substr($item->pembuat->name, 0, 1)) }}
                                        </div>
                                        <div class="author-details">
                                            <div class="author-name">{{ $item->pembuat->name }}</div>
                                            <span class="author-role bg-info bg-opacity-10 text-info">
                                                <i class="bi bi-person-badge me-1"></i>
                                                {{ ucfirst($item->pembuat->role) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted">Tidak diketahui</span>
                                    @endif
                                </div>

                                <!-- Date Info -->
                                <div class="meta-item">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>
                                        <strong>{{ $item->created_at->format('d M Y') }}</strong>
                                        <span class="text-muted">{{ $item->created_at->format('H:i') }}</span>
                                    </span>
                                </div>

                                <!-- Time Ago -->
                                <div class="meta-item">
                                    <i class="bi bi-clock"></i>
                                    <span>{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div>
                                <a href="{{ route('admin.laporan.show', $item->id) }}" class="btn btn-detail">
                                    <i class="bi bi-eye me-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $laporan->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Belum Ada Laporan</h4>
                    <p class="text-muted mb-4">
                        Belum ada laporan yang dibuat oleh petugas perpustakaan.
                        <br>Laporan akan muncul di sini setelah petugas membuat laporan baru.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-primary px-3 py-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Sistem siap menerima laporan
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
