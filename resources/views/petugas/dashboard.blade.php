@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        border-radius: 16px;
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

    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .welcome-card .card-body {
        position: relative;
        z-index: 1;
        padding: 35px;
    }

    .welcome-card .card-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: white;
    }

    .welcome-card .text-muted {
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 1.05rem;
    }

    .stat-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--card-gradient);
    }

    .stat-card.card-success {
        --card-gradient: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    }

    .stat-card.card-primary {
        --card-gradient: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
    }

    .stat-card.card-warning {
        --card-gradient: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
    }

    .stat-card.card-purple {
        --card-gradient: linear-gradient(90deg, #8b5cf6 0%, #a78bfa 100%);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-card .card-body {
        padding: 30px 25px;
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
    }

    .card-success .icon-wrapper {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    }

    .card-primary .icon-wrapper {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    }

    .card-warning .icon-wrapper {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    }

    .card-purple .icon-wrapper {
        background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
    }

    .icon-wrapper i {
        font-size: 2rem;
    }

    .card-success .icon-wrapper i {
        color: #10b981;
    }

    .card-primary .icon-wrapper i {
        color: #2563eb;
    }

    .card-warning .icon-wrapper i {
        color: #f59e0b;
    }

    .card-purple .icon-wrapper i {
        color: #8b5cf6;
    }

    .stat-card h5 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1e293b;
        margin-top: 15px;
        margin-bottom: 10px;
    }

    .stat-card p {
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .welcome-card .card-title {
            font-size: 1.4rem;
        }
        
        .stat-card h5 {
            font-size: 1.8rem;
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
        }

        .icon-wrapper i {
            font-size: 1.6rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card welcome-card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Selamat Datang, {{ Auth::user()->name }} 👋</h5>
                    <p class="text-muted">Berikut ringkasan data yang kamu miliki.</p>
                </div>
            </div>
        </div>

        <!-- Contoh statistik -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stat-card card-success border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h5 class="mt-3">{{ $laporans->count() }}</h5>
                    <p class="text-muted mb-0">Total Laporan</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stat-card card-primary border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-qr-code"></i>
                    </div>
                    <h5 class="mt-3">{{ $qrcodes->count() }}</h5>
                    <p class="text-muted mb-0">QR Code Dibuat</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stat-card card-primary border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5 class="mt-3">{{ $buku->count() }}</h5>
                    <p class="text-muted mb-0">Total Buku</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection