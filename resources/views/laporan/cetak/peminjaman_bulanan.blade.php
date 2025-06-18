<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            font-size: 14px;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer-text {
            display: inline-block;
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 3px 7px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1 class="title">SISTEM INFORMASI SARANA PRASARANA</h1>
        <p class="subtitle">LAPORAN PEMINJAMAN BULANAN</p>
        <p class="subtitle">Periode:
        @php
            $namaBulan = [
                '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
                '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
                '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
        @endphp
        {{ $namaBulan[$bulan] }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal Pinjam</th>
                <th width="10%">Tanggal Kembali</th>
                <th width="15%">Kode Barang</th>
                <th width="20%">Nama Barang</th>
                <th width="15%">Peminjam</th>
                <th width="5%">Jumlah</th>
                <th width="10%">Status</th>
                <th width="10%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}</td>
                    <td>{{ $item->barang->kode_barang }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td>
                        @if($item->status == 'dipinjam')
                            <span class="badge badge-warning">Dipinjam</span>
                        @elseif($item->status == 'dikembalikan')
                            <span class="badge badge-success">Dikembalikan</span>
                        @elseif($item->status == 'terlambat')
                            <span class="badge badge-danger">Terlambat</span>
                        @else
                            {{ $item->status }}
                        @endif
                    </td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-text">
            <p>{{ date('d F Y') }}<br>Admin</p>
            <br><br><br>
            <p>__________________</p>
        </div>
    </div>
</body>
</html>
