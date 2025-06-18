@extends('layout.app')

@section('title', 'Pengembalian')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                <i class="fas fa-undo-alt mr-2"></i>Data Pengembalian
            </h2>
            <div class="flex items-center">
                <form action="{{ route('pengembalian.index') }}" method="GET" class="flex space-x-2">
                    <select name="periode" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5">
                        <option value="">Semua Periode</option>
                        <option value="minggu" {{ request('periode') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan" {{ request('periode') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="tahun" {{ request('periode') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-indigo-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium">No</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Peminjam</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Barang</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Jumlah</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Tgl Pinjam</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Tgl Kembali</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pengembalian as $index => $item)
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-indigo-50 transition duration-150">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $item->peminjaman->user->nama ?? $item->peminjaman->user->username ?? 'User tidak tersedia' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $item->peminjaman->barang->nama_barang ?? 'Barang tidak tersedia' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $item->peminjaman->jumlah ?? 0 }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $item->peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($item->peminjaman->tanggal_pinjam)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($item->label_status == 'selesai')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                                @elseif($item->label_status == 'menunggu')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Menunggu</span>
                                @elseif($item->label_status == 'penting')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Penting</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex space-x-2">
                                    <a href="{{ route('pengembalian.show', $item->id_pengembalian) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm transition duration-300">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    <form action="{{ route('pengembalian.destroy', $item->id_pengembalian) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data pengembalian ini? Status peminjaman akan dikembalikan ke dipinjam dan stok barang akan disesuaikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm transition duration-300">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-3 text-sm text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <i class="fas fa-folder-open text-gray-400 text-5xl mb-3"></i>
                                    <p>Tidak ada data pengembalian yang tersedia</p>
                                </div>
                            </td>
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
        // Any JavaScript functionality can be added here
    });
</script>
@endpush
