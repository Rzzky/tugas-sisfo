@extends('layout.app')
@section('title', 'Manajemen Barang')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h2 class="text-2xl font-semibold text-slate-200 mb-4 sm:mb-0">Manajemen Barang</h2>
        <a href="{{ route('barang.create') }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
            <i class="fas fa-plus mr-2"></i> Tambah Barang
        </a>
    </div>

    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="relative"><i class="fas fa-search text-slate-500 absolute top-3.5 left-3"></i><input type="text" id="searchInput" placeholder="Cari barang..." class="bg-slate-900 border border-slate-600 text-slate-200 pl-10 pr-4 py-2 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></div>
        <select id="filterKategori" class="bg-slate-900 border border-slate-600 text-slate-200 w-full rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $k) <option value="{{ $k->nama_kategori }}">{{ $k->nama_kategori }}</option> @endforeach
        </select>
        <select id="filterStatus" class="bg-slate-900 border border-slate-600 text-slate-200 w-full rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Status</option>
            <option value="tersedia">Tersedia</option>
            <option value="tidak tersedia">Tidak Tersedia</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm" id="barangTable">
            <thead class="bg-slate-700 text-slate-300 uppercase">
                <tr>
                    <th class="py-3 px-4 text-left">Kode</th>
                    <th class="py-3 px-4 text-left">Nama Barang</th>
                    <th class="py-3 px-4 text-left">Kategori</th>
                    <th class="py-3 px-4 text-center">Jumlah</th>
                    <th class="py-3 px-4 text-center">Tersedia</th>
                    <th class="py-3 px-4 text-center">Kondisi</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-slate-400">
                @forelse($barang as $item)
                    <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                        <td class="px-4 py-3 font-medium text-slate-300">{{ $item->kode_barang }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-200">{{ $item->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $item->kategori->nama_kategori }}</td>
                        <td class="px-4 py-3 text-center">{{ $item->jumlah }}</td>
                        <td class="px-4 py-3 text-center font-bold text-green-400">{{ $item->tersedia }}</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-medium rounded-full {{ ['Baik' => 'bg-green-900 text-green-300', 'Rusak Ringan' => 'bg-yellow-900 text-yellow-300', 'Rusak Berat' => 'bg-red-900 text-red-300'][$item->kondisi] ?? '' }}">{{ $item->kondisi }}</span></td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-medium rounded-full {{ ['tersedia' => 'bg-green-900 text-green-300', 'tidak tersedia' => 'bg-slate-600 text-slate-300'][$item->status] ?? '' }}">{{ Str::title($item->status) }}</span></td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('barang.show', $item->id_barang) }}" class="text-blue-400 hover:text-blue-300" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('barang.edit', $item->id_barang) }}" class="text-yellow-400 hover:text-yellow-300" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus barang ini?')" class="inline">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-400" title="Hapus"><i class="fas fa-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-10 text-slate-500">Tidak ada data barang ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
