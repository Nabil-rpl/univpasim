@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>📱 Daftar QR Code Kamu</h3>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    @if($qrcodes->isEmpty())
        <p class="text-muted mt-3">Belum ada QR code yang dibuat.</p>
    @else
        <div class="row mt-3">
            @foreach($qrcodes as $qr)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/'.$qr->gambar_qr) }}" width="150" alt="QR">
                            <p class="mt-2"><strong>{{ $qr->kode_unik }}</strong></p>
                            <form action="{{ route('petugas.qrcode.destroy', $qr->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
