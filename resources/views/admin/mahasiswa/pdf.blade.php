<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        /* ===== HEADER ===== */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 24px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 11px;
            opacity: 0.85;
        }

        .header-meta {
            text-align: right;
            font-size: 10px;
            opacity: 0.85;
        }

        .header-meta strong {
            display: block;
            font-size: 12px;
            margin-bottom: 2px;
        }

        /* ===== INFO BAR ===== */
        .info-bar {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .info-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 16px;
            flex: 1;
        }

        .info-item .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .info-item .value {
            font-size: 14px;
            font-weight: 700;
            color: #667eea;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        thead tr {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .no-cell {
            text-align: center;
            font-weight: 700;
            color: #667eea;
        }

        .nim-badge {
            background: #0ea5e9;
            color: white;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
        }

        .nama-cell {
            font-weight: 600;
            color: #1e293b;
        }

        .email-cell {
            color: #64748b;
        }

        .jurusan-cell {
            color: #475569;
        }

        .date-cell {
            color: #94a3b8;
            font-size: 10px;
        }

        /* ===== EMPTY STATE ===== */
        .empty-row td {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
            font-style: italic;
        }

        /* ===== FOOTER ===== */
        .footer {
            border-top: 2px solid #e2e8f0;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #94a3b8;
            font-size: 9px;
        }

        .footer-brand {
            font-weight: 700;
            color: #667eea;
            font-size: 10px;
        }

        /* ===== PAGE NUMBER ===== */
        @page {
            margin: 20px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-top">
            <div>
                <h1>&#127891; Data Mahasiswa</h1>
                <p>Laporan data mahasiswa terdaftar</p>
            </div>
            <div class="header-meta">
                <strong>Dicetak pada</strong>
                {{ $tanggal }}
            </div>
        </div>
    </div>

    <!-- Info Bar -->
    <div class="info-bar">
        <div class="info-item">
            <div class="label">Total Mahasiswa</div>
            <div class="value">{{ $mahasiswas->count() }}</div>
        </div>
        <div class="info-item">
            <div class="label">Filter Jurusan</div>
            <div class="value" style="font-size: 12px;">{{ $jurusan }}</div>
        </div>
        <div class="info-item">
            <div class="label">Tanggal Cetak</div>
            <div class="value" style="font-size: 11px;">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="22%">Nama</th>
                <th width="25%">Email</th>
                <th width="14%">NIM</th>
                <th width="20%">Jurusan</th>
                <th width="15%">Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $index => $mhs)
            <tr>
                <td class="no-cell">{{ $index + 1 }}</td>
                <td class="nama-cell">{{ $mhs->nama }}</td>
                <td class="email-cell">{{ $mhs->email }}</td>
                <td>
                    <span class="nim-badge">{{ $mhs->nim }}</span>
                </td>
                <td class="jurusan-cell">{{ $mhs->jurusan ?? '-' }}</td>
                <td class="date-cell">{{ $mhs->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="6">Tidak ada data mahasiswa</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div>
            <span class="footer-brand">Sistem Informasi Akademik</span>
            &mdash; Dokumen ini digenerate otomatis
        </div>
        <div>
            Total {{ $mahasiswas->count() }} mahasiswa &bull; {{ $tanggal }}
        </div>
    </div>

</body>
</html>