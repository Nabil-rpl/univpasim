@extends('layouts.mahasiswa')

@section('page-title', 'Perpanjangan Peminjaman')

@section('content')
<style>
    .perpanjang-container {
        background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }

    .breadcrumb-custom {
        background: transparent;
        padding: 0;
        margin-bottom: 2rem;
    }

    .breadcrumb-custom .breadcrumb-item {
        color: #64748B;
        font-weight: 600;
    }

    .breadcrumb-custom .breadcrumb-item.active {
        color: #3B82F6;
    }

    .breadcrumb-custom a {
        color: #60A5FA;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-custom a:hover {
        color: #3B82F6;
    }

    .card-custom {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
        border: 1px solid #E0E7FF;
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease;
    }

    .card-header-custom {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #E0E7FF;
    }

    .card-icon-custom {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #FBBF24, #F59E0B);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .card-header-custom h4 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 800;
        color: #1E293B;
    }

    .info-section {
        background: linear-gradient(135deg, #F8FAFC, #F1F5F9);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #F59E0B;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #E2E8F0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #64748B;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .info-value {
        color: #1E293B;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .book-info-card {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .book-cover {
        width: 100px;
        height: 140px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        flex-shrink: 0;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-details h5 {
        margin: 0 0 0.5rem 0;
        font-size: 1.25rem;
        font-weight: 800;
        color: #1E293B;
    }

    .book-details p {
        margin: 0.25rem 0;
        color: #64748B;
        font-weight: 500;
    }

    .form-label-custom {
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        display: block;
    }

    .form-control-custom,
    .form-select-custom {
        border: 2px solid #E0E7FF;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #F8FAFC;
        color: #1E293B;
        font-weight: 500;
        width: 100%;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
        border-color: #F59E0B;
        box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.1);
        outline: none;
        background: #FFFFFF;
    }

    textarea.form-control-custom {
        min-height: 120px;
        resize: vertical;
    }

    .alert-info-custom {
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        border-left: 4px solid #3B82F6;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: start;
        gap: 1rem;
    }

    .alert-icon {
        font-size: 1.5rem;
        color: #3B82F6;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content h6 {
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        color: #1E3A8A;
    }

    .alert-content ul {
        margin: 0;
        padding-left: 1.25rem;
        color: #1E40AF;
    }

    .alert-content ul li {
        margin: 0.25rem 0;
    }

    .btn-custom {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        font-size: 1rem;
        border: none;
        cursor: pointer;
    }

    .btn-warning-custom {
        background: linear-gradient(135deg, #FBBF24, #F59E0B);
        color: white;
        box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
    }

    .btn-warning-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(251, 191, 36, 0.4);
        color: white;
    }

    .btn-secondary-custom {
        background: #F1F5F9;
        color: #475569;
        border: 2px solid #E2E8F0;
    }

    .btn-secondary-custom:hover {
        background: #E2E8F0;
        transform: translateY(-2px);
        color: #475569;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #E0E7FF;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .book-info-card {
            flex-direction: column;
            text-align: center;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-custom {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="perpanjang-container">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item">
                    <a href="{{ route('mahasiswa.dashboard') }}">
                        <i class="bi bi-house-door-fill"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('mahasiswa.peminjaman.riwayat') }}">
                        Riwayat Peminjaman
                    </a>
                </li>
                <li class="breadcrumb-item active">Perpanjangan Peminjaman</li>
            </ol>
        </nav>

        <!-- Alert Error -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Card -->
        <div class="card-custom">
            <div class="card-header-custom">
                <div class="card-icon-custom">
                    <i class="bi bi-arrow-clockwise"></i>
                </div>
                <h4>Form Perpanjangan Peminjaman</h4>
            </div>

            <!-- Info Buku -->
            <div class="book-info-card">
                <div class="book-cover">
                    @if ($peminjaman->buku->foto)
                        <img src="{{ asset('storage/foto_buku/' . $peminjaman->buku->foto) }}" 
                             alt="{{ $peminjaman->buku->judul }}">
                    @else
                        <i class="bi bi-book-fill"></i>
                    @endif
                </div>
                <div class="book-details">
                    <h5>{{ $peminjaman->buku->judul }}</h5>
                    <p><i class="bi bi-person"></i> {{ $peminjaman->buku->penulis }}</p>
                    <p><i class="bi bi-building"></i> {{ $peminjaman->buku->penerbit }}</p>
                    @if($peminjaman->buku->kategori)
                        <p><i class="bi bi-tag"></i> {{ $peminjaman->buku->kategori }}</p>
                    @endif
                </div>
            </div>

            <!-- Info Peminjaman -->
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">
                        <i class="bi bi-calendar-event"></i> Tanggal Pinjam
                    </span>
                    <span class="info-value">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="bi bi-calendar-date"></i> Tanggal Deadline Saat Ini
                    </span>
                    <span class="info-value text-warning">{{ $peminjaman->tanggal_deadline->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <i class="bi bi-clock"></i> Durasi Peminjaman
                    </span>
                    <span class="info-value">{{ $peminjaman->durasi_hari }} Hari</span>
                </div>
            </div>

            <!-- Alert Info -->
            <div class="alert-info-custom">
                <i class="bi bi-info-circle-fill alert-icon"></i>
                <div class="alert-content">
                    <h6>Ketentuan Perpanjangan:</h6>
                    <ul>
                        <li>Perpanjangan hanya dapat dilakukan <strong>1 kali</strong></li>
                        <li>Durasi perpanjangan maksimal <strong>7 hari</strong></li>
                        <li>Wajib mencantumkan alasan perpanjangan yang jelas</li>
                        <li>Perpanjangan memerlukan persetujuan dari petugas perpustakaan</li>
                        <li>Jika terdapat denda keterlambatan, harus dilunasi saat pengembalian</li>
                    </ul>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('mahasiswa.perpanjangan.store', $peminjaman->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label-custom">
                        <i class="bi bi-calendar-plus"></i> Durasi Tambahan (Hari) <span class="text-danger">*</span>
                    </label>
                    <select name="durasi_tambahan" class="form-select form-select-custom" id="durasiTambahan" required>
                        <option value="">Pilih Durasi Perpanjangan</option>
                        <option value="1" {{ old('durasi_tambahan') == 1 ? 'selected' : '' }}>1 Hari</option>
                        <option value="2" {{ old('durasi_tambahan') == 2 ? 'selected' : '' }}>2 Hari</option>
                        <option value="3" {{ old('durasi_tambahan') == 3 ? 'selected' : '' }}>3 Hari</option>
                        <option value="4" {{ old('durasi_tambahan') == 4 ? 'selected' : '' }}>4 Hari</option>
                        <option value="5" {{ old('durasi_tambahan') == 5 ? 'selected' : '' }}>5 Hari</option>
                        <option value="6" {{ old('durasi_tambahan') == 6 ? 'selected' : '' }}>6 Hari</option>
                        <option value="7" {{ old('durasi_tambahan') == 7 ? 'selected' : '' }}>7 Hari</option>
                    </select>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle"></i> Pilih berapa hari tambahan yang Anda butuhkan
                    </small>
                </div>

                <div class="mb-4">
                    <label class="form-label-custom">
                        <i class="bi bi-chat-left-text"></i> Alasan Perpanjangan <span class="text-danger">*</span>
                    </label>
                    <textarea name="alasan" 
                              class="form-control form-control-custom" 
                              placeholder="Jelaskan alasan Anda memerlukan perpanjangan peminjaman buku ini..."
                              required>{{ old('alasan') }}</textarea>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle"></i> Berikan alasan yang jelas dan detail (maksimal 500 karakter)
                    </small>
                </div>

                <!-- Preview Deadline Baru -->
                <div class="info-section" id="previewDeadline" style="display: none;">
                    <div class="info-row">
                        <span class="info-label">
                            <i class="bi bi-calendar-check"></i> Tanggal Deadline Baru
                        </span>
                        <span class="info-value text-success" id="deadlineBaru">-</span>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('mahasiswa.peminjaman.riwayat') }}" class="btn-custom btn-secondary-custom">
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn-custom btn-warning-custom">
                        <i class="bi bi-send-fill"></i>
                        Ajukan Perpanjangan
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Riwayat Perpanjangan (jika ada) -->
        @if($peminjaman->perpanjangan->count() > 0)
            <div class="card-custom">
                <div class="card-header-custom">
                    <div class="card-icon-custom" style="background: linear-gradient(135deg, #60A5FA, #3B82F6);">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4>Riwayat Perpanjangan</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal Pengajuan</th>
                                <th>Deadline Lama</th>
                                <th>Deadline Baru</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjaman->perpanjangan as $perp)
                                <tr>
                                    <td>{{ $perp->tanggal_perpanjangan->format('d M Y') }}</td>
                                    <td>{{ $perp->tanggal_deadline_lama->format('d M Y') }}</td>
                                    <td class="text-success fw-bold">{{ $perp->tanggal_deadline_baru->format('d M Y') }}</td>
                                    <td>{{ $perp->durasi_tambahan }} Hari</td>
                                    <td>
                                        @if($perp->status == 'menunggu')
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock"></i> Menunggu
                                            </span>
                                        @elseif($perp->status == 'disetujui')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $perp->alasan }}</small>
                                        @if($perp->status == 'ditolak' && $perp->catatan_petugas)
                                            <br><br>
                                            <strong class="text-danger">Catatan Petugas:</strong>
                                            <small class="text-danger">{{ $perp->catatan_petugas }}</small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    // Preview tanggal deadline baru saat memilih durasi
    document.getElementById('durasiTambahan').addEventListener('change', function() {
        const durasi = parseInt(this.value);
        if (durasi > 0) {
            const deadlineSekarang = new Date("{{ $peminjaman->tanggal_deadline->format('Y-m-d') }}");
            const deadlineBaru = new Date(deadlineSekarang);
            deadlineBaru.setDate(deadlineBaru.getDate() + durasi);
            
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const deadlineBaruStr = deadlineBaru.toLocaleDateString('id-ID', options);
            
            document.getElementById('deadlineBaru').textContent = deadlineBaruStr;
            document.getElementById('previewDeadline').style.display = 'block';
        } else {
            document.getElementById('previewDeadline').style.display = 'none';
        }
    });
</script>
@endsection