@extends('layout.app')

@section('title', 'Detail Pengembalian')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                <i class="fas fa-undo-alt mr-2"></i>Detail Pengembalian
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('pengembalian.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <form action="{{ route('pengembalian.destroy', $pengembalian->id_pengembalian) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data pengembalian ini? Status peminjaman akan dikembalikan ke dipinjam dan stok barang akan disesuaikan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Pengembalian -->
            <div class="bg-indigo-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Pengembalian
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Pengembalian:</span>
                        <span class="font-medium text-gray-800">{{ $pengembalian->id_pengembalian }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Kembali:</span>
                        <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span>
                            @if($pengembalian->label_status == 'selesai')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                            @elseif($pengembalian->label_status == 'menunggu')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Menunggu</span>
                            @elseif($pengembalian->label_status == 'penting')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Penting</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">-</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Peminjaman -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                    <i class="fas fa-hand-holding mr-2"></i>Informasi Peminjaman
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Peminjaman:</span>
                        <span class="font-medium text-gray-800">{{ $pengembalian->peminjaman->id_peminjaman }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Pinjam:</span>
                        <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_pinjam)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Deadline:</span>
                        <span class="font-medium text-gray-800">
                            {{ $pengembalian->peminjaman->tanggal_deadline ? \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_deadline)->format('d M Y') : 'Tidak ada' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Data Peminjam -->
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2"></i>Data Peminjam
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-medium text-gray-800">
                            {{ $pengembalian->peminjaman->user->nama ?? $pengembalian->peminjaman->user->username ?? 'User tidak tersedia' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium text-gray-800">
                            {{ $pengembalian->peminjaman->user->email ?? 'Email tidak tersedia' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Telepon:</span>
                        <span class="font-medium text-gray-800">
                            {{ $pengembalian->peminjaman->user->telepon ?? 'Telepon tidak tersedia' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Barang -->
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
                    <i class="fas fa-box mr-2"></i>Informasi Barang
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Barang:</span>
                        <span class="font-medium text-gray-800">
                            {{ $pengembalian->peminjaman->barang->nama_barang ?? 'Barang tidak tersedia' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode:</span>
                        <span class="font-medium text-gray-800">
                            {{ $pengembalian->peminjaman->barang->kode ?? '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah:</span>
                        <span class="font-medium text-gray-800">
                            {{ $pengembalian->peminjaman->jumlah ?? '0' }} unit
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>Keterangan
            </h3>
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                {{ $pengembalian->keterangan ?? 'Tidak ada keterangan' }}
            </div>
        </div>

        <!-- Riwayat -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-history mr-2"></i>Timeline
            </h3>
            <div class="relative pl-8 border-l-2 border-indigo-600">
                <!-- Peminjaman dibuat -->
                <div class="mb-6 relative">
                    <div class="absolute -left-[27px] bg-indigo-600 rounded-full w-6 h-6 flex items-center justify-center text-white">
                        <i class="fas fa-hand-holding text-xs"></i>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-medium text-indigo-800">Peminjaman Dibuat</span>
                            <span class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($pengembalian->peminjaman->created_at)->format('d M Y H:i') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">
                            Peminjaman baru dibuat dengan status
                            <span class="inline-block px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">dipinjam</span>
                        </p>
                    </div>
                </div>

                <!-- Barang dikembalikan -->
                <div class="relative">
                    <div class="absolute -left-[27px] bg-green-600 rounded-full w-6 h-6 flex items-center justify-center text-white">
                        <i class="fas fa-undo-alt text-xs"></i>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-medium text-green-800">Barang Dikembalikan</span>
                            <span class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($pengembalian->created_at)->format('d M Y H:i') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">
                            Barang telah dikembalikan dengan status
                            <span class="inline-block px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">kembali</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
