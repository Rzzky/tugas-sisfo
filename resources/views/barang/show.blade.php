@extends('layout.app')

@section('title', 'Detail Barang')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('barang.index') }}" class="text-indigo-600 hover:text-indigo-800 mr-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-semibold text-gray-800">Detail Barang</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-md font-semibold text-gray-700 mb-3">Informasi Umum</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode Barang:</span>
                        <span class="font-medium">{{ $barang->kode_barang }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Barang:</span>
                        <span class="font-medium">{{ $barang->nama_barang }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kategori:</span>
                        <span class="font-medium">{{ $barang->kategori->nama_kategori }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Lokasi:</span>
                        <span class="font-medium">{{ $barang->lokasi }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-md font-semibold text-gray-700 mb-3">Status Barang</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Total:</span>
                        <span class="font-medium">{{ $barang->jumlah }} unit</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tersedia:</span>
                        <span class="font-medium text-green-600">{{ $barang->tersedia }} unit</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dipinjam:</span>
                        <span class="font-medium text-blue-600">{{ $barang->dipinjam }} unit</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        @if($barang->status == 'tersedia')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Tersedia</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Tidak Tersedia</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-md font-semibold text-gray-700 mb-3">Kondisi Barang</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Kondisi:</span>
                    @if($barang->kondisi == 'Baik')
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Baik</span>
                    @elseif($barang->kondisi == 'Rusak Ringan')
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Rusak Ringan</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rusak Berat</span>
                    @endif
                </div>
                <div>
                    <span class="text-gray-600">Keterangan:</span>
                    <p class="mt-1 text-gray-700">{{ $barang->keterangan ?: 'Tidak ada keterangan' }}</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
                <p>Diperbarui: {{ $barang->updated_at->format('d M Y H:i') }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('barang.edit', $barang->id_barang) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
