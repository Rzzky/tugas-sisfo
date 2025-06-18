<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kondisi Barang</title>
    <style>body{font-family:sans-serif;font-size:10pt}.header{text-align:center;margin-bottom:20px;border-bottom:1px solid #000;padding-bottom:10px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:6px}th{background-color:#f2f2f2}.text-center{text-align:center}</style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KONDISI SEMUA BARANG</h2>
        <p>Per Tanggal: {{ date('d F Y') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Tersedia</th>
                <th class="text-center">Dipinjam</th>
                <th>Kondisi</th>
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
                    <td>{{ $item->kondisi }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Tidak ada data barang.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>