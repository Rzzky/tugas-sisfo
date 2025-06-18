<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>body{font-family:sans-serif;font-size:10pt}.header{text-align:center;margin-bottom:20px;border-bottom:1px solid #000;padding-bottom:10px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:6px}th{background-color:#f2f2f2}.text-center{text-align:center}</style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PEMINJAMAN BULANAN</h2>
        <p>Periode: {{ \Carbon\Carbon::create()->month($bulan)->isoFormat('MMMM') }} {{ $tahun }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Pinjam</th>
                <th>Nama Barang</th>
                <th>Peminjam</th>
                <th class="text-center">Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td>{{ $item->user->username }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td>{{ Str::title($item->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data untuk periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>