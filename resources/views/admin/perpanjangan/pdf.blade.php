<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Perpanjangan Buku</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1e293b;
            background: #fff;
        }

        /* ===== HEADER ===== */
        .header {
            background: #667eea;
            color: white;
            padding: 16px 22px;
            margin-bottom: 14px;
            border-radius: 8px;
        }

        .header-left {
            display: inline-block;
            width: 70%;
            vertical-align: top;
        }

        .header-right {
            display: inline-block;
            width: 28%;
            text-align: right;
            vertical-align: top;
            font-size: 9px;
            opacity: 0.9;
        }

        .header h1 {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 10px;
            opacity: 0.85;
        }

        .header-right strong {
            display: block;
            font-size: 10px;
            margin-bottom: 2px;
        }

        /* ===== INFO BAR ===== */
        .info-bar {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: separate;
            border-spacing: 8px 0;
        }

        .info-bar td {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 14px;
            width: 33%;
        }

        .info-label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 13px;
            font-weight: 700;
            color: #667eea;
        }

        /* ===== TABLE ===== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .data-table thead tr {
            background: #667eea;
            color: white;
        }

        .data-table thead th {
            padding: 9px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .data-table thead th.text-center {
            text-align: center;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .data-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .data-table tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .no-cell {
            text-align: center;
            font-weight: 700;
            color: #667eea;
        }

        .name-cell {
            font-weight: 600;
            color: #1e293b;
        }

        .nim-cell {
            color: #64748b;
            font-size: 9px;
        }

        .book-cell {
            font-weight: 600;
            color: #1e293b;
        }

        .date-lama {
            color: #ef4444;
            font-size: 9px;
        }

        .date-baru {
            color: #10b981;
            font-size: 9px;
        }

        .durasi-badge {
            background: #667eea;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 700;
            display: inline-block;
        }

        .status-menunggu  { background: #fef3c7; color: #92400e; }
        .status-disetujui { background: #d1fae5; color: #065f46; }
        .status-ditolak   { background: #fee2e2; color: #991b1b; }
        .status-dibatalkan{ background: #f1f5f9; color: #475569; }

        .petugas-cell {
            color: #475569;
            font-size: 9px;
        }

        /* ===== EMPTY STATE ===== */
        .empty-row td {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
            font-style: italic;
        }

        /* ===== FOOTER ===== */
        .footer {
            border-top: 2px solid #e2e8f0;
            padding-top: 10px;
        }

        .footer-left {
            display: inline-block;
            width: 60%;
            color: #94a3b8;
            font-size: 9px;
        }

        .footer-right {
            display: inline-block;
            width: 38%;
            text-align: right;
            color: #94a3b8;
            font-size: 9px;
        }

        .footer-brand {
            font-weight: 700;
            color: #667eea;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <h1>Laporan Perpanjangan Buku</h1>
            <p>Data perpanjangan peminjaman buku perpustakaan</p>
        </div>
        <div class="header-right">
            <strong>Dicetak pada</strong>
            {{ $tanggal }}
        </div>
    </div>

    <!-- Info Bar -->
    <table class="info-bar">
        <tr>
            <td>
                <div class="info-label">Total Data</div>
                <div class="info-value">{{ $perpanjangan->count() }}</div>
            </td>
            <td>
                <div class="info-label">Filter Status</div>
                <div class="info-value" style="font-size: 11px;">{{ $status }}</div>
            </td>
            <td>
                <div class="info-label">Tanggal Cetak</div>
                <div class="info-value" style="font-size: 10px;">{{ now()->format('d M Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="16%">Peminjam</th>
                <th width="20%">Judul Buku</th>
                <th width="12%">Deadline Lama</th>
                <th width="12%">Deadline Baru</th>
                <th width="8%" class="text-center">Tambahan</th>
                <th width="12%" class="text-center">Status</th>
                <th width="10%">Petugas</th>
                <th width="7%">Tgl Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($perpanjangan as $index => $item)
            <tr>
                <td class="no-cell">{{ $index + 1 }}</td>
                <td>
                    <div class="name-cell">{{ $item->peminjaman->mahasiswa->name ?? '-' }}</div>
                    <div class="nim-cell">{{ $item->peminjaman->mahasiswa->nim ?? $item->peminjaman->mahasiswa->nik ?? '-' }}</div>
                </td>
                <td class="book-cell">{{ $item->peminjaman->buku->judul ?? '-' }}</td>
                <td class="date-lama">{{ $item->tanggal_deadline_lama->format('d/m/Y') }}</td>
                <td class="date-baru">{{ $item->tanggal_deadline_baru->format('d/m/Y') }}</td>
                <td class="text-center">
                    <span class="durasi-badge">+{{ $item->durasi_tambahan }} Hari</span>
                </td>
                <td class="text-center">
                    <span class="status-badge status-{{ $item->status }}">
                        {{ $item->getStatusLabel() }}
                    </span>
                </td>
                <td class="petugas-cell">{{ $item->petugas->name ?? '-' }}</td>
                <td class="petugas-cell">{{ $item->tanggal_perpanjangan->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="9">Tidak ada data perpanjangan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-left">
            <span class="footer-brand">Sistem Informasi Perpustakaan</span>
            &mdash; Dokumen ini digenerate otomatis
        </div>
        <div class="footer-right">
            Total {{ $perpanjangan->count() }} data &bull; {{ $tanggal }}
        </div>
    </div>

</body>
</html>