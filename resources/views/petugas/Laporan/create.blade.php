@extends('layouts.petugas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Buat Laporan Baru</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('petugas.laporan.store') }}" method="POST">
                        @csrf

                        {{-- Periode Laporan --}}
                        <div class="mb-3">
                            <label class="form-label">Periode Laporan <span class="text-danger">*</span></label>
                            <div class="row g-2">

                                <div class="col-md-7">
                                    <select class="form-select @error('bulan') is-invalid @enderror"
                                            name="bulan"
                                            id="bulan"
                                            required>
                                        <option value="" disabled {{ old('bulan') ? '' : 'selected' }}>-- Pilih Bulan --</option>
                                        @php
                                            $namaBulan = [
                                                1  => 'Januari',  2  => 'Februari', 3  => 'Maret',
                                                4  => 'April',    5  => 'Mei',      6  => 'Juni',
                                                7  => 'Juli',     8  => 'Agustus',  9  => 'September',
                                                10 => 'Oktober',  11 => 'November', 12 => 'Desember',
                                            ];
                                        @endphp
                                        @foreach ($namaBulan as $num => $nama)
                                            <option value="{{ $num }}" {{ old('bulan') == $num ? 'selected' : '' }}>
                                                {{ $nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bulan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-5">
                                    <select class="form-select @error('tahun') is-invalid @enderror"
                                            name="tahun"
                                            id="tahun"
                                            required>
                                        <option value="" disabled {{ old('tahun') ? '' : 'selected' }}>-- Pilih Tahun --</option>
                                        @php $tahunSekarang = (int) date('Y'); @endphp
                                        @for ($t = $tahunSekarang; $t >= $tahunSekarang - 4; $t--)
                                            <option value="{{ $t }}" {{ old('tahun', $tahunSekarang) == $t ? 'selected' : '' }}>
                                                {{ $t }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <small class="text-muted">Pilih bulan dan tahun periode laporan</small>
                        </div>

                        {{-- Isi Laporan --}}
                        <div class="mb-3">
                            <label for="isi" class="form-label">Isi Laporan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('isi') is-invalid @enderror"
                                      id="isi"
                                      name="isi"
                                      rows="15"
                                      placeholder="Tulis isi laporan disini..."
                                      required>{{ old('isi') }}</textarea>
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tuliskan laporan dengan detail dan jelas</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('petugas.laporan.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection