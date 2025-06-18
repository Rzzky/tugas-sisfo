@extends('layout.app')

@section('title', 'Daftar Peminjaman')

@section('content')

<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Daftar Peminjaman</h2>
        </div>

    <div class="p-4">
        <form action="{{ route('peminjaman.index') }}" method="GET" class="mb-4">
            <div class="flex items-center space-x-4">
                <div class="w-1/3">
                    <label for="periode" class="block text-sm font-medium text-gray-700 mb-1">Filter Periode</label>
                    <select name="periode" id="periode" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="all" {{ request('periode') == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="minggu" {{ request('periode') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan" {{ request('periode') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="tahun" {{ request('periode') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>
                <div class="pt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-300">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Peminjam</th>
                        <th class="py-3 px-4 text-left">Barang</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                        <th class="py-3 px-4 text-left">Tanggal Pinjam</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Label</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @forelse($peminjaman as $index => $item)
                    <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $item->user->name ?? $item->user->username ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $item->barang->nama_barang ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $item->jumlah }}</td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ $item->status == 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $item->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $item->status == 'menunggu' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $item->status == 'ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ $item->label_status == 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $item->label_status == 'menunggu' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $item->label_status == 'penting' ? 'bg-red-100 text-red-800' : '' }}
                                 {{ $item->label_status == 'ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($item->label_status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('peminjaman.show', $item->id_peminjaman) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($item->status == 'dipinjam')
                                <form action="{{ route('peminjaman.kembalikan', $item->id_peminjaman) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan barang ini?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-green-500 hover:text-green-700">
                                        <i class="fas fa-undo-alt"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('peminjaman.destroy', $item->id_peminjaman) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-3 px-4 text-center text-gray-500">Tidak ada data peminjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Automatically submit the form when the periode select changes
        document.getElementById('periode').addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush
