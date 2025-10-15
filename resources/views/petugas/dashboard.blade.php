@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>📋 Dashboard Petugas</h2>
    <p>Selamat datang, {{ Auth::user()->name }}</p>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    Daftar Laporan  
                </div>
                <div class="card-body">
                    @if($laporans->isEmpty())
                        <p class="text-muted">Belum ada laporan.</p>
                    @else
                        <ul class="list-group">
                            @foreach($laporans as $laporan)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $laporan->judul }}
                                    <span class="badge bg-success">{{ $laporan->status ?? 'Belum diproses' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    QR Code Kamu
                </div>
                <div class="card-body">
                    @if($qrcodes->isEmpty())
                        <p class="text-muted">Belum ada QR code yang dibuat.</p>
                    @else
                        <ul class="list-group">
                            @foreach($qrcodes as $qr)
                                <li class="list-group-item">
                                    <strong>{{ $qr->kode_unik }}</strong><br>
                                    <img src="{{ asset('storage/'.$qr->gambar_qr) }}" alt="QR" width="100">
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
