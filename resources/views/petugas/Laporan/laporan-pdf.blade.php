<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perpustakaan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .header p { font-size: 11px; color: #555; margin-top: 3px; }

        .info-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 11px; }
        .info-row span { color: #555; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead tr { background-color: #2c3e50; color: #fff; }
        thead th { padding: 8px 10px; text-align: left; font-size: 11px; }
        tbody tr:nth-child(even) { background-color: #f5f5f5; }
        tbody tr:nth-child(odd)  { background-color: #ffffff; }
        tbody td { padding: 7px 10px; font-size: 11px; border-bottom: 1px solid #e0e0e0; vertical-align: top; }

        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 10px; font-weight: bold; }
        .badge-primary { background-color: #3498db; color: #fff; }

        .footer { margin-top: 25px; font-size: 10px; color: #777; text-align: right; }
        .total-row { font-weight: bold; background-color: #ecf0f1 !important; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Rekap Laporan Bulanan Perpustakaan</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
    </div>

    @php
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April',   5 => 'Mei',      6 => 'Juni',
            7 => 'Juli',    8 => 'Agustus',  9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    @endphp

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Periode</th>
                <th width="20%">Dibuat Oleh</th>
                <th width="15%">Tanggal Dibuat</th>
                <th width="40%">Isi Laporan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span class="badge badge-primary">
                            {{ $namaBulan[$item->bulan] ?? '-' }} {{ $item->tahun }}
                        </span>
                    </td>
                    <td>
                        {{ $item->pembuat->name ?? '-' }}<br>
                        <small style="color:#777;">{{ ucfirst($item->pembuat->role ?? '') }}</small>
                    </td>
                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($item->isi, 150) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#999;">Tidak ada data laporan.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="4">Total Laporan</td>
                <td>{{ $laporan->count() }} laporan</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Sistem Perpustakaan &mdash; Dokumen ini digenerate otomatis.
    </div>

</body>
</html>