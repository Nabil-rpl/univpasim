@extends('layouts.petugas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Laporan</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('petugas.laporan.update', $laporan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" 
                                   name="judul" 
                                   value="{{ old('judul', $laporan->judul) }}"
                                   placeholder="Masukkan judul laporan"
                                   required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="isi" class="form-label">Isi Laporan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('isi') is-invalid @enderror" 
                                      id="isi" 
                                      name="isi" 
                                      rows="15"
                                      placeholder="Tulis isi laporan disini..."
                                      required>{{ old('isi', $laporan->isi) }}</textarea>
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tuliskan laporan dengan detail dan jelas</small>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle"></i> 
                                Dibuat oleh: <strong>{{ $laporan->pembuat->name ?? '-' }}</strong> 
                                pada {{ $laporan->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('petugas.laporan.show', $laporan->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection