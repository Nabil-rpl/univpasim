@extends('layouts.petugas')

@section('page-title', 'Tambah Peminjaman Buku')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .form-header {
        padding-bottom: 20px;
        margin-bottom: 25px;
        border-bottom: 2px solid #e2e8f0;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-custom {
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .info-box {
        background: #f0f9ff;
        border-left: 4px solid #2563eb;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .select2-container--default .select2-selection--single {
        height: 45px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 41px;
        padding-left: 15px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 41px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card">
            <div class="form-header">
                <h4 class="mb-1"><i class="bi bi-plus-circle me-2"></i>Tambah Peminjaman Buku</h4>
                <p class="text-muted mb-0">Isi form di bawah ini untuk menambah data peminjaman buku</p>
            </div>

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-triangle me-2"></i>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="info-box">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Informasi:</strong> Sistem akan otomatis mengurangi stok buku saat peminjaman dibuat.
            </div>

            <form action="{{ route('petugas.peminjaman.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-person me-2"></i>Pilih Mahasiswa <span class="text-danger">*</span>
                    </label>
                    <select name="mahasiswa_id" id="mahasiswa_id" class="form-select select2" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswas as $mhs)
                            <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                {{ $mhs->nim }} - {{ $mhs->nama }} ({{ $mhs->jurusan }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Pilih mahasiswa yang akan meminjam buku</small>
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-book me-2"></i>Pilih Buku <span class="text-danger">*</span>
                    </label>
                    <select name="buku_id" id="buku_id" class="form-select select2" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($bukus as $buku)
                            <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}
                                    data-stok="{{ $buku->stok }}">
                                {{ $buku->judul }} - {{ $buku->penulis }} (Stok: {{ $buku->stok }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hanya menampilkan buku yang stoknya masih tersedia</small>
                </div>

                <div class="alert alert-info d-none" id="stokInfo">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Stok tersedia:</strong> <span id="stokValue">0</span> buku
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-calendar me-2"></i>Tanggal Pinjam
                    </label>
                    <input type="text" class="form-control" value="{{ date('d F Y') }}" disabled>
                    <small class="text-muted">Tanggal pinjam otomatis hari ini</small>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-custom">
                        <i class="bi bi-save me-2"></i>Simpan Peminjaman
                    </button>
                    <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-secondary btn-custom">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: function() {
                return $(this).find('option:first').text();
            }
        });

        // Show stok info when buku is selected
        $('#buku_id').on('change', function() {
            const selectedOption = $(this).find(':selected');
            const stok = selectedOption.data('stok');
            
            if (stok !== undefined) {
                $('#stokValue').text(stok);
                $('#stokInfo').removeClass('d-none');
            } else {
                $('#stokInfo').addClass('d-none');
            }
        });
    });
</script>
@endpush
@endsection