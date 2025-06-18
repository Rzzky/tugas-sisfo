@extends('layout.app')

@section('title', 'Daftar Barang')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Daftar Barang</h2>
            <a href="{{ route('barang.create') }}" class="bg-indigo-700 hover:bg-indigo-800 text-white font-medium py-2 px-4 rounded-lg transition duration-300 flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Barang
            </a>
        </div>

        <div class="mb-4">
            <div class="flex items-center space-x-4">
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input type="text" id="searchInput" placeholder="Cari barang..." class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="w-1/4">
                    <select id="filterKategori" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->nama_kategori }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-1/4">
                    <select id="filterStatus" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="tidak tersedia">Tidak Tersedia</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200" id="barangTable">
                <thead>
                    <tr>
                        <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tersedia</th>
                        <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kondisi</th>
                        <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($barang as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 border-b border-gray-200">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 border-b border-gray-200">{{ $item->kode_barang }}</td>
                            <td class="px-4 py-3 border-b border-gray-200">{{ $item->nama_barang }}</td>
                            <td class="px-4 py-3 border-b border-gray-200">{{ $item->kategori->nama_kategori }}</td>
                            <td class="px-4 py-3 border-b border-gray-200">{{ $item->jumlah }}</td>
                            <td class="px-4 py-3 border-b border-gray-200">{{ $item->tersedia }}</td>
                            <td class="px-3 py-4 whitespace-nowrap ">
                                @if($item->kondisi == 'Baik')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Baik</span>
                                @elseif($item->kondisi == 'Rusak Ringan')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Rusak Ringan</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rusak Berat</span>
                                @endif
                            </td>
                            <td class="ml-1 px-2 py-1 text-xs rounded-full whitespace-nowrap">
                                @if($item->status == 'tersedia')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Tersedia</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td class="ml-1 px-2 py-1 text-xs rounded-full text-sm">
                                <div class="flex space-x-1">
                                    <a href="{{ route('barang.show', $item->id_barang) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-2 rounded-md transition duration-300" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('barang.edit', $item->id_barang) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-2 rounded-md transition duration-300" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded-md transition duration-300" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-3 border-b border-gray-200 text-center text-gray-500">Tidak ada data barang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterKategori = document.getElementById('filterKategori');
        const filterStatus = document.getElementById('filterStatus');
        const table = document.getElementById('barangTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const kategoriValue = filterKategori.value.toLowerCase();
            const statusValue = filterStatus.value.toLowerCase();

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                if (row.cells.length <= 1) continue; // Skip "no data" row

                const namaBarang = row.cells[2].textContent.toLowerCase();
                const kategori = row.cells[3].textContent.toLowerCase();
                const statusText = row.cells[7].textContent.toLowerCase();

                const matchSearch = namaBarang.includes(searchValue);
                const matchKategori = kategoriValue === '' || kategori.includes(kategoriValue);
                const matchStatus = statusValue === '' || statusText.includes(statusValue);

                if (matchSearch && matchKategori && matchStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        searchInput.addEventListener('input', filterTable);
        filterKategori.addEventListener('change', filterTable);
        filterStatus.addEventListener('change', filterTable);
    });
</script>
@endpush
