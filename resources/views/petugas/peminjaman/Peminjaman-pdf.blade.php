<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Peminjaman Buku</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2c3e50; padding-bottom: 12px; }
        .header h2 { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #2c3e50; }
        .header p { font-size: 10px; color: #666; margin-top: 4px; }

        .filter-info { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 8px 12px; margin-bottom: 14px; font-size: 10px; color: #555; }

        .stats-row { display: table; width: 100%; margin-bottom: 16px; border-collapse: separate; border-spacing: 6px; }
        .stat-box { display: table-cell; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 8px 12px; text-align: center; width: 25%; }
        .stat-box .label { font-size: 9px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-box .value { font-size: 18px; font-weight: bold; color: #2c3e50; margin-top: 2px; }
        .stat-box.dipinjam .value { color: #e67e22; }
        .stat-box.dikembalikan .value { color: #27ae60; }
        .stat-box.terlambat .value { color: #e74c3c; }

        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        thead tr { background-color: #2c3e50; color: #fff; }
        thead th { padding: 8px 7px; text-align: left; font-size: 10px; }
        tbody tr:nth-child(even) { background-color: #f8f9fa; }
        tbody tr:nth-child(odd)  { background-color: #ffffff; }
        tbody td { padding: 6px 7px; font-size: 10px; border-bottom: 1px solid #e9ecef; vertical-align: top; }

        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .badge-dipinjam  { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .badge-kembali   { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-terlambat { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        .text-muted { color: #888; font-style: italic; }
        .footer { margin-top: 20px; font-size: 9px; color: #999; text-align: right; border-top: 1px solid #dee2e6; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Rekap Data Peminjaman Buku Perpustakaan</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
    </div>

    @if($filterInfo)
        <div class="filter-info">
            <strong>Filter aktif:</strong> {{ $filterInfo }}
        </div>
    @endif

    <!-- Statistik -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="label">Total Data</div>
            <div class="value">{{ $peminjamans->count() }}</div>
        </div>
        <div class="stat-box dipinjam">
            <div class="label">Dipinjam</div>
            <div class="value">{{ $peminjamans->where('status', 'dipinjam')->count() }}</div>
        </div>
        <div class="stat-box dikembalikan">
            <div class="label">Dikembalikan</div>
            <div class="value">{{ $peminjamans->where('status', 'dikembalikan')->count() }}</div>
        </div>
        <div class="stat-box terlambat">
            <div class="label">Terlambat</div>
            <div class="value">
                {{ $peminjamans->filter(fn($p) => $p->status === 'dipinjam' && $p->tanggal_deadline < now())->count() }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="16%">Peminjam</th>
                <th width="18%">Buku</th>
                <th width="12%">Tgl Pinjam</th>
                <th width="8%">Durasi</th>
                <th width="12%">Deadline</th>
                <th width="12%">Tgl Kembali</th>
                <th width="10%">Status</th>
                <th width="8%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $item)
                @php
                    $isTerlambat = $item->status === 'dipinjam' && $item->tanggal_deadline < now();
                    $hariTerlambat = $isTerlambat ? now()->diffInDays($item->tanggal_deadline) : 0;
                    $denda = $hariTerlambat * 5000;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->mahasiswa->name ?? '-' }}</strong><br>
                        <span style="color:#888;">
                            @if($item->mahasiswa?->role === 'mahasiswa')
                                NIM: {{ $item->mahasiswa->nim ?? '-' }}
                            @else
                                {{ $item->mahasiswa?->no_hp ?? '-' }}
                            @endif
                        </span>
                    </td>
                    <td>
                        <strong>{{ $item->buku->judul ?? '-' }}</strong><br>
                        <span style="color:#888;">{{ $item->buku->penulis ?? '' }}</span>
                    </td>
                    <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $item->durasi_hari }} hari</td>
                    <td>
                        {{ $item->tanggal_deadline ? $item->tanggal_deadline->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        @if($isTerlambat)
                            <span class="badge badge-terlambat">Terlambat</span><br>
                            <span style="color:#e74c3c; font-size:9px;">{{ $hariTerlambat }}hr | Rp{{ number_format($denda, 0, ',', '.') }}</span>
                        @elseif($item->status === 'dipinjam')
                            <span class="badge badge-dipinjam">Dipinjam</span>
                        @else
                            <span class="badge badge-kembali">Dikembalikan</span>
                        @endif
                    </td>
                    <td>{{ $item->petugas->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; color:#999; padding:20px;">Tidak ada data peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Sistem Perpustakaan &mdash; Dokumen ini digenerate otomatis.
    </div>

</body>
</html>