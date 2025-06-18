@extends('layout.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Detail Peminjaman</h1>
    <a href="{{ route('peminjaman.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition duration-300">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Peminjaman</h2>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Status Peminjaman</p>
                    <div class="mt-1">
                        <span class="px-3 py-1 rounded text-sm font-medium
                            {{ $peminjaman->status == 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $peminjaman->status == 'kembali' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $peminjaman->status == 'menunggu' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $peminjaman->status == 'ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($peminjaman->status) }}
                        </span>

                        <span class="ml-2 px-3 py-1 rounded text-sm font-medium
                            {{ $peminjaman->label_status == 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $peminjaman->label_status == 'menunggu' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $peminjaman->label_status == 'penting' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($peminjaman->label_status) }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Tanggal Peminjaman</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }}</p>
                </div>

                @if($peminjaman->tanggal_kembali)
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Tanggal Pengembalian</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y') }}</p>
                </div>
                @endif

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Jumlah</p>
                    <p class="font-medium">{{ $peminjaman->jumlah }} unit</p>
                </div>

                @if($peminjaman->keterangan)
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Keterangan</p>
                    <p class="font-medium">{{ $peminjaman->keterangan }}</p>
                </div>
                @endif
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Barang & Peminjam</h2>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Peminjam</p>
                    <p class="font-medium">{{ $peminjaman->user->name ?? $peminjaman->user->username ?? 'N/A' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Nama Barang</p>
                    <p class="font-medium">{{ $peminjaman->barang->nama_barang ?? 'N/A' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Kode Barang</p>
                    <p class="font-medium">{{ $peminjaman->barang->kode_barang ?? 'N/A' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Kategori</p>
                    <p class="font-medium">{{ $peminjaman->barang->kategori->nama_kategori ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-5 border-t flex justify-between">
            @if($peminjaman->status == 'menunggu')
            <div class="flex space-x-4">
                <form action="{{ route('peminjaman.approve', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition duration-300">
                        <i class="fas fa-check mr-2"></i>Setujui Peminjaman
                    </button>
                </form>

                <form action="{{ route('peminjaman.reject', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak peminjaman ini?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition duration-300">
                        <i class="fas fa-times mr-2"></i>Tolak Peminjaman
                    </button>
                </form>
            </div>
            @endif

            @if($peminjaman->status == 'dipinjam')
            <form action="{{ route('peminjaman.kembalikan', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan barang ini?')">
                @csrf
                @method('PUT')
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-undo-alt mr-2"></i>Kembalikan Barang
                </button>
            </form>
            @endif

            <form action="{{ route('peminjaman.destroy', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition duration-300">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
