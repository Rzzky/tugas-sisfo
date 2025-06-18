<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kondisi Barang</title>
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
        <p class="subtitle">LAPORAN KONDISI BARANG</p>
        <p class="subtitle">Tanggal: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode Barang</th>
                <th width="25%">Nama Barang</th>
                <th width="15%">Kategori</th>
                <th width="10%">Jumlah</th>
                <th width="8%">Tersedia</th>
                <th width="8%">Dipinjam</th>
                <th width="8%">Kondisi</th>
                <th width="9%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kategori->nama_kategori }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-center">{{ $item->tersedia }}</td>
                    <td class="text-center">{{ $item->dipinjam }}</td>
                    <td>
                        @if($item->kondisi == 'baik')
                            <span class="badge badge-success">Baik</span>
                        @elseif($item->kondisi == 'rusak_ringan')
                            <span class="badge badge-warning">Rusak Ringan</span>
                        @elseif($item->kondisi == 'rusak_berat')
                            <span class="badge badge-danger">Rusak Berat</span>
                        @else
                            {{ $item->kondisi }}
                        @endif
                    </td>
                    <td>
                        @if($item->status == 'aktif')
                            <span class="badge badge-success">Aktif</span>
                        @elseif($item->status == 'non_aktif')
                            <span class="badge badge-danger">Non-Aktif</span>
                        @else
                            {{ $item->status }}
                        @endif
                    </td>
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
