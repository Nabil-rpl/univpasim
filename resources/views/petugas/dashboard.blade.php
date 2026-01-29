@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        border-radius: 20px;
        border: none;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(37, 99, 235, 0.25);
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .welcome-card .card-body {
        position: relative;
        z-index: 1;
        padding: 40px;
    }

    .welcome-card .card-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: white;
    }

    .welcome-card .text-muted {
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 1.1rem;
    }

    /* Stats Grid dalam Welcome Card */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .stat-item {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 25px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-5px);
    }

    .stat-item .icon {
        font-size: 2rem;
        margin-bottom: 15px;
        opacity: 0.9;
    }

    .stat-item .value {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 5px;
        color: white;
    }

    .stat-item .label {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.95);
        font-weight: 500;
    }

    /* Chart Cards */
    .chart-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        background: white;
        margin-bottom: 24px;
    }

    .chart-card .card-header {
        background: white;
        border-bottom: 2px solid #f1f5f9;
        padding: 25px 30px;
        border-radius: 20px 20px 0 0;
    }

    .chart-card .card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
        font-size: 1.15rem;
    }

    .chart-card .card-body {
        padding: 30px;
    }

    /* List Card */
    .list-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .list-card .card-header {
        background: white;
        border-bottom: 2px solid #f1f5f9;
        padding: 25px 30px;
        border-radius: 20px 20px 0 0;
    }

    .list-card .card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
        font-size: 1.15rem;
    }

    .list-item {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s;
    }

    .list-item:last-child {
        border-bottom: none;
    }

    .list-item:hover {
        background: #f8fafc;
    }

    @media (max-width: 768px) {
        .welcome-card .card-title {
            font-size: 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-item .value {
            font-size: 2rem;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Welcome Card dengan Stats Terintegrasi -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card welcome-card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Selamat Datang, {{ Auth::user()->name }} 👋</h5>
                    <p class="text-muted">Berikut ringkasan data perpustakaan hari ini</p>

                    <!-- Stats Grid Inside Welcome Card -->
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="icon">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div class="value">{{ $totalPeminjaman }}</div>
                            <div class="label">Total Peminjaman</div>
                        </div>

                        <div class="stat-item">
                            <div class="icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="value">{{ $peminjamanAktif }}</div>
                            <div class="label">Peminjaman Aktif</div>
                        </div>

                        <div class="stat-item">
                            <div class="icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="value">{{ $peminjamanTerlambat }}</div>
                            <div class="label">Terlambat</div>
                        </div>

                        <div class="stat-item">
                            <div class="icon">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div class="value">{{ $perpanjanganMenunggu }}</div>
                            <div class="label">Perpanjangan Menunggu</div>
                        </div>

                        <div class="stat-item">
                            <div class="icon">
                                <i class="bi bi-book"></i>
                            </div>
                            <div class="value">{{ $buku->count() }}</div>
                            <div class="label">Total Buku</div>
                        </div>

                        <div class="stat-item">
                            <div class="icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div class="value">Rp {{ number_format($dendaBelumLunas / 1000, 0) }}K</div>
                            <div class="label">Denda Belum Lunas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <!-- Grafik Utama: Peminjaman & Pengembalian -->
        <div class="col-12 mb-4">
            <div class="card chart-card">
                <div class="card-header">
                    <h5><i class="bi bi-graph-up me-2"></i>Tren Peminjaman & Pengembalian</h5>
                </div>
                <div class="card-body">
                    <canvas id="peminjamanChart" height="60"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Charts -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card chart-card">
                <div class="card-header">
                    <h5><i class="bi bi-star me-2"></i>Buku Terpopuler</h5>
                </div>
                <div class="card-body">
                    <canvas id="topBukuChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card chart-card">
                <div class="card-header">
                    <h5><i class="bi bi-cash me-2"></i>Tren Denda</h5>
                </div>
                <div class="card-body">
                    <canvas id="dendaChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Lists -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card list-card">
                <div class="card-header">
                    <h5><i class="bi bi-clock-history me-2"></i>Peminjaman Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($peminjamanTerbaru as $p)
                    <div class="list-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong class="text-dark">{{ $p->mahasiswa->name }}</strong>
                                <p class="mb-0 small text-muted">{{ $p->buku->judul }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> {{ $p->tanggal_pinjam->format('d M Y') }}
                                </small>
                            </div>
                            <span class="badge bg-{{ $p->status == 'dipinjam' ? 'success' : 'secondary' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="mt-3 mb-0">Belum ada peminjaman</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card list-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-hourglass-split me-2"></i>Perpanjangan Menunggu</h5>
                    @if($perpanjanganMenungguList->count() > 0)
                    <span class="badge bg-warning">{{ $perpanjanganMenungguList->count() }}</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    @forelse($perpanjanganMenungguList as $perp)
                    <div class="list-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong class="text-dark">{{ $perp->peminjaman->mahasiswa->name }}</strong>
                                <p class="mb-0 small text-muted">{{ $perp->peminjaman->buku->judul }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-plus"></i> +{{ $perp->durasi_tambahan }} hari
                                </small>
                            </div>
                            <span class="badge bg-warning">
                                <i class="bi bi-hourglass-split"></i> Menunggu
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="mt-3 mb-0">Tidak ada perpanjangan menunggu</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Konfigurasi global untuk semua chart
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 15;

    // Gradien untuk Peminjaman
    const peminjamanCtx = document.getElementById('peminjamanChart').getContext('2d');
    const gradientPinjam = peminjamanCtx.createLinearGradient(0, 0, 0, 400);
    gradientPinjam.addColorStop(0, 'rgba(37, 99, 235, 0.3)');
    gradientPinjam.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

    const gradientKembali = peminjamanCtx.createLinearGradient(0, 0, 0, 400);
    gradientKembali.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
    gradientKembali.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    // Grafik Peminjaman & Pengembalian - Area Chart Modern
    new Chart(peminjamanCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($bulanLabels) !!},
            datasets: [{
                label: 'Peminjaman',
                data: {!! json_encode($peminjamanPerBulan) !!},
                borderColor: '#2563eb',
                backgroundColor: gradientPinjam,
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#2563eb',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: '#2563eb',
                pointHoverBorderColor: '#fff'
            }, {
                label: 'Pengembalian',
                data: {!! json_encode($pengembalianPerBulan) !!},
                borderColor: '#10b981',
                backgroundColor: gradientKembali,
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: '#10b981',
                pointHoverBorderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 12,
                        boxHeight: 12,
                        borderRadius: 6,
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    displayColors: true,
                    boxPadding: 6
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        },
                        color: '#64748b'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#64748b'
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });

    // Grafik Top Buku - Bar Chart dengan Gradien
    const topBukuCtx = document.getElementById('topBukuChart').getContext('2d');
    const colors = [
        { bg: 'rgba(37, 99, 235, 0.8)', border: '#2563eb' },
        { bg: 'rgba(16, 185, 129, 0.8)', border: '#10b981' },
        { bg: 'rgba(245, 158, 11, 0.8)', border: '#f59e0b' },
        { bg: 'rgba(139, 92, 246, 0.8)', border: '#8b5cf6' },
        { bg: 'rgba(6, 182, 212, 0.8)', border: '#06b6d4' }
    ];

    new Chart(topBukuCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topBukuLabels) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($topBukuData) !!},
                backgroundColor: colors.map(c => c.bg),
                borderColor: colors.map(c => c.border),
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.parsed.x + ' peminjaman';
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        },
                        color: '#64748b'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                y: {
                    ticks: {
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        color: '#1e293b'
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });

    // Grafik Denda - Bar Chart dengan Gradien
    const dendaCtx = document.getElementById('dendaChart').getContext('2d');
    const gradientDenda = dendaCtx.createLinearGradient(0, 0, 0, 400);
    gradientDenda.addColorStop(0, 'rgba(239, 68, 68, 0.8)');
    gradientDenda.addColorStop(1, 'rgba(239, 68, 68, 0.4)');

    new Chart(dendaCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bulanLabels) !!},
            datasets: [{
                label: 'Denda',
                data: {!! json_encode($dendaPerBulan) !!},
                backgroundColor: gradientDenda,
                borderColor: '#ef4444',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return ' Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#64748b',
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000) + 'jt';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000) + 'rb';
                            }
                            return 'Rp ' + value;
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#64748b'
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endpush