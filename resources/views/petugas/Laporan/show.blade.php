@extends('layouts.petugas')

@section('page-title', 'Detail Laporan')

@section('content')
<style>
    .detail-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: 100vh;
        padding: 24px 0;
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.07);
        border: 1px solid #e2e8f0;
    }

    .detail-header {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
        padding: 28px 32px;
    }

    .periode-title {
        font-size: 1.75rem;
        font-weight: 800;
        margin: 0;
    }

    .meta-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 24px;
    }

    .meta-item {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        padding: 14px 18px;
        border-radius: 12px;
    }

    .meta-label {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 4px;
    }

    .meta-value {
        font-weight: 600;
        font-size: 1.05rem;
    }

    .content-card {
        padding: 32px;
    }

    .isi-laporan {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        line-height: 1.7;
        white-space: pre-wrap;
        font-size: 1.02rem;
        color: #374151;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 32px;
    }

    @media (max-width: 768px) {
        .detail-header {
            padding: 24px 20px;
        }
        
        .periode-title {
            font-size: 1.5rem;
        }

        .meta-info {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .content-card {
            padding: 24px 20px;
        }

        .isi-laporan {
            padding: 20px;
            font-size: 1rem;
        }

        .action-buttons .btn {
            flex: 1;
            min-width: 120px;
        }
    }
</style>

<div class="detail-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">

                <!-- Main Card -->
                <div class="detail-card">

                    <!-- Header -->
                    <div class="detail-header">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <h1 class="periode-title">
                                    <i class="bi bi-calendar-month me-3"></i>
                                    Laporan Bulan {{ $namaBulan[$laporan->bulan] ?? '-' }} {{ $laporan->tahun }}
                                </h1>
                            </div>

                            @if($laporan->dibuat_oleh === Auth::id() || Auth::user()->role === 'admin')
                                <div class="d-flex gap-2">
                                    <a href="{{ route('petugas.laporan.edit', $laporan->id) }}"
                                       class="btn btn-light btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('petugas.laporan.destroy', $laporan->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="content-card">

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Meta Information -->
                        <div class="meta-info">
                            <div class="meta-item">
                                <div class="meta-label">
                                    <i class="bi bi-person-fill"></i> Dibuat oleh
                                </div>
                                <div class="meta-value">
                                    @if($laporan->pembuat)
                                        {{ $laporan->pembuat->name }}
                                        <span class="badge bg-secondary ms-2">
                                            {{ ucfirst($laporan->pembuat->role ?? '-') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>

                            <div class="meta-item">
                                <div class="meta-label">
                                    <i class="bi bi-clock"></i> Tanggal Dibuat
                                </div>
                                <div class="meta-value">
                                    {{ $laporan->created_at->format('d F Y') }} • 
                                    {{ $laporan->created_at->format('H:i') }} WIB
                                </div>
                            </div>
                        </div>

                        @if($laporan->created_at != $laporan->updated_at)
                            <div class="alert alert-info mt-4">
                                <small>
                                    <i class="bi bi-info-circle me-1"></i>
                                    Terakhir diperbarui: 
                                    {{ $laporan->updated_at->format('d F Y, H:i') }} WIB
                                </small>
                            </div>
                        @endif

                        <hr class="my-4">

                        <!-- Isi Laporan -->
                        <h5 class="mb-3">
                            <i class="bi bi-journal-text me-2"></i> Isi Laporan
                        </h5>
                        <div class="isi-laporan">
                            {{ $laporan->isi }}
                        </div>

                        <!-- Back Button -->
                        <div class="action-buttons">
                            <a href="{{ route('petugas.laporan.index') }}" 
                               class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Laporan
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Auto hide alert
    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            alert.style.transition = 'opacity 0.4s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 450);
        }
    }, 4500);
</script>
@endsection