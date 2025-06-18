<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Terpopuler</title>
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
        .no-border {
            border: none;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1 class="title">SISTEM INFORMASI SARANA PRASARANA</h1>
        <p class="subtitle">LAPORAN BARANG TERPOPULER</p>
        <p class="subtitle">Periode: Tahun {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Barang</th>
                <th width="40%">Nama Barang</th>
                <th width="20%">Kategori</th>
                <th width="20%">Jumlah Peminjaman</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->barang->kode_barang }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td>{{ $item->barang->kategori->nama_kategori }}</td>
                    <td class="text-center">{{ $item->jumlah_peminjaman }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
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
