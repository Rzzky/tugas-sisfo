<!DOCTYPE html>
<html>
<head>
    <title>Laporan Barang Terpopuler</title>
    <style>body{font-family:sans-serif;font-size:10pt}.header{text-align:center;margin-bottom:20px;border-bottom:1px solid #000;padding-bottom:10px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:6px}th{background-color:#f2f2f2}.text-center{text-align:center}</style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN BARANG TERPOPULER</h2>
        <p>Periode: Tahun {{ $tahun }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th class="text-center">Total Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->barang->kode_barang }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td>{{ $item->barang->kategori->nama_kategori }}</td>
                    <td class="text-center">{{ $item->jumlah_peminjaman }} kali</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Tidak ada data untuk periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>