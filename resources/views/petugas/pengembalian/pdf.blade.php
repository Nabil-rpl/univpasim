<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman Aktif</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 9px;
            color: #333;
            line-height: 1.3;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #2563eb;
        }

        .header h1 {
            font-size: 16px;
            color: #2563eb;
            margin-bottom: 3px;
            font-weight: bold;
        }

        .header h2 {
            font-size: 12px;
            color: #666;
            font-weight: normal;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 8px;
            color: #888;
            margin-top: 3px;
        }

        .filter-info {
            background: #eff6ff;
            padding: 6px 10px;
            margin-bottom: 12px;
            border-left: 3px solid #2563eb;
            font-size: 7px;
        }

        .filter-info strong {
            color: #1e40af;
            font-size: 8px;
        }

        .stats-section {
            margin-bottom: 15px;
            background: #f9fafb;
            padding: 10px;
            border: 1px solid #e5e7eb;
        }

        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .stats-item {
            display: table-cell;
            width: 25%;
            padding: 6px;
            text-align: center;
            border-right: 1px solid #ddd;
        }

        .stats-item:last-child {
            border-right: none;
        }

        .stats-label {
            font-size: 7px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 3px;
            letter-spacing: 0.3px;
        }

        .stats-value {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin-top: 2px;
        }

        .stats-value.primary {
            color: #2563eb;
        }

        .stats-value.warning {
            color: #f59e0b;
        }

        .stats-value.danger {
            color: #ef4444;
        }

        .stats-value.success {
            color: #10b981;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border: 1px solid #e5e7eb;
        }

        table thead {
            background: #2563eb;
            color: white;
        }

        table thead th {
            padding: 6px 4px;
            text-align: left;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #1e40af;
        }

        table tbody td {
            padding: 5px 4px;
            border: 1px solid #e5e7eb;
            font-size: 8px;
            vertical-align: top;
        }

        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        table tbody tr.priority-high {
            background: #fee2e2;
        }

        table tbody tr.priority-medium {
            background: #fef3c7;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 2px 5px;
            border-radius: 2px;
            font-size: 6px;
            font-weight: bold;
            display: inline-block;
            white-space: nowrap;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-primary {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-info {
            background: #e0f2fe;
            color: #075985;
        }

        .denda-amount {
            font-weight: bold;
            color: #ef4444;
            font-size: 8px;
        }

        .footer {
            margin-top: 15px;
            padding: 8px 10px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }

        .footer-grid {
            display: table;
            width: 100%;
        }

        .footer-item {
            display: table-cell;
            width: 50%;
            padding: 3px 5px;
        }

        .footer p {
            font-size: 8px;
            color: #666;
            margin-bottom: 2px;
        }

        .footer strong {
            color: #333;
            font-size: 9px;
        }

        .signature-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-grid {
            display: table;
            width: 100%;
        }

        .signature-left {
            display: table-cell;
            width: 50%;
            padding: 5px;
        }

        .signature-right {
            display: table-cell;
            width: 50%;
            padding: 5px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 150px;
        }

        .signature-box p {
            font-size: 8px;
            margin-bottom: 40px;
        }

        .signature-box .name {
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 3px;
            font-size: 8px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #888;
            font-style: italic;
            font-size: 9px;
        }

        small {
            font-size: 7px;
            color: #666;
        }

        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PEMINJAMAN AKTIF</h1>
        <h2>Sistem Perpustakaan</h2>
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
    </div>

    <!-- Filter Info -->
    @if(!empty(array_filter($filters)))
    <div class="filter-info">
        <strong>Filter yang Diterapkan:</strong>
        @if($filters['search'])
            Pencarian: "{{ $filters['search'] }}" |
        @endif
        @if($filters['role'])
            Tipe: {{ $filters['role'] == 'mahasiswa' ? 'Mahasiswa' : 'Pengguna Luar' }} |
        @endif
        @if($filters['filter'])
            Status: 
            @if($filters['filter'] == 'terlambat') Terlambat
            @elseif($filters['filter'] == 'tepat_waktu') Tepat Waktu
            @elseif($filters['filter'] == 'hari_ini') Deadline Hari Ini
            @endif |
        @endif
        @if($filters['sort'])
            Urutan: 
            @if($filters['sort'] == 'deadline_desc') Deadline Terbaru
            @elseif($filters['sort'] == 'denda_desc') Denda Tertinggi
            @else Deadline Terlama
            @endif
        @endif
    </div>
    @endif

    <!-- Statistik -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stats-item">
                <div class="stats-label">Total Aktif</div>
                <div class="stats-value primary">{{ $totalAktif }} Peminjaman</div>
            </div>
            <div class="stats-item">
                <div class="stats-label">Tepat Waktu</div>
                <div class="stats-value success">{{ $totalTepatWaktu }}</div>
            </div>
            <div class="stats-item">
                <div class="stats-label">Terlambat</div>
                <div class="stats-value danger">{{ $totalTerlambat }}</div>
            </div>
            <div class="stats-item">
                <div class="stats-label">Total Denda</div>
                <div class="stats-value warning">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">NO</th>
                <th width="18%">PEMINJAM</th>
                <th width="20%">BUKU</th>
                <th width="10%" class="text-center">TGL PINJAM</th>
                <th width="10%" class="text-center">DEADLINE</th>
                <th width="8%" class="text-center">DURASI</th>
                <th width="10%" class="text-center">STATUS</th>
                <th width="12%" class="text-right">DENDA</th>
                <th width="9%" class="text-center">SISA/LEWAT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamanList as $index => $item)
                @php
                    $isTerlambat = $item->isTerlambat();
                    $hariTerlambat = $isTerlambat ? $item->getHariTerlambat() : 0;
                    $denda = $isTerlambat ? $item->hitungDenda() : 0;
                    $sisaHari = $isTerlambat ? 0 : $item->tanggal_deadline->diffInDays(now());
                    
                    $rowClass = '';
                    if ($isTerlambat && $hariTerlambat > 3) {
                        $rowClass = 'priority-high';
                    } elseif ($isTerlambat) {
                        $rowClass = 'priority-medium';
                    }
                @endphp
                <tr class="{{ $rowClass }}">
                    <td class="text-center"><strong>{{ $index + 1 }}</strong></td>
                    <td>
                        <strong>{{ $item->mahasiswa->name }}</strong><br>
                        @if($item->mahasiswa->role == 'mahasiswa')
                            <span class="badge badge-primary">Mahasiswa</span><br>
                            <small>NIM: {{ $item->mahasiswa->nim ?? '-' }}</small>
                        @else
                            <span class="badge badge-info">Pengguna Luar</span><br>
                            <small>HP: {{ $item->mahasiswa->no_hp ?? '-' }}</small>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $item->buku->judul }}</strong><br>
                        <small>{{ $item->buku->penulis }}</small>
                    </td>
                    <td class="text-center">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $item->tanggal_deadline->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="badge badge-info">{{ $item->durasi_hari }} hari</span>
                    </td>
                    <td class="text-center">
                        @if($isTerlambat)
                            @php
                                $badgeClass = $hariTerlambat > 3 ? 'badge-danger' : 'badge-warning';
                            @endphp
                            <span class="badge {{ $badgeClass }}">Terlambat</span>
                        @else
                            <span class="badge badge-success">Normal</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($denda > 0)
                            <span class="denda-amount">Rp {{ number_format($denda, 0, ',', '.') }}</span><br>
                            <small>({{ $hariTerlambat }} hari)</small>
                        @else
                            <span class="badge badge-success">Rp 0</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($isTerlambat)
                            <span class="badge badge-danger">+{{ $hariTerlambat }} hari</span>
                        @else
                            <span class="badge badge-success">{{ $sisaHari }} hari</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="no-data">
                        Tidak ada peminjaman aktif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer Summary -->
    <div class="footer">
        <div class="footer-grid">
            <div class="footer-item">
                <p><strong>Ringkasan:</strong></p>
                <p>Total Peminjaman Aktif: <strong>{{ $totalAktif }}</strong></p>
                <p>Tepat Waktu: <strong style="color: #10b981;">{{ $totalTepatWaktu }}</strong></p>
                <p>Terlambat: <strong style="color: #ef4444;">{{ $totalTerlambat }}</strong></p>
            </div>
            <div class="footer-item" style="text-align: right;">
                <p><strong>Informasi Denda:</strong></p>
                <p>Total Denda Tertunggak: <strong style="color: #f59e0b;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong></p>
                <p>Tarif Denda: <strong>Rp 5.000/hari</strong></p>
                <p>Tanggal Cetak: <strong>{{ \Carbon\Carbon::parse($tanggal_cetak)->format('d/m/Y H:i') }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-grid">
            <div class="signature-left">
                <div class="signature-box">
                    <p>Mengetahui,<br>Kepala Perpustakaan</p>
                    <p class="name">(...........................)</p>
                </div>
            </div>
            <div class="signature-right">
                <div class="signature-box">
                    <p>Petugas Perpustakaan</p>
                    <p class="name">(...........................)</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>