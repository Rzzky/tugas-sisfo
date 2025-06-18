@extends('layout.app')

@section('title', 'Detail Barang')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 md:p-8 border border-slate-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <div class="flex items-center mb-2">
                 <a href="{{ route('barang.index') }}" class="text-slate-400 hover:text-indigo-400 mr-4" title="Kembali">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-100">{{ $barang->nama_barang }}</h2>
                    <p class="text-sm text-slate-400">{{ $barang->kode_barang }}</p>
                </div>
            </div>
        </div>
        <div class="flex space-x-2 mt-4 md:mt-0">
            <a href="{{ route('barang.edit', $barang->id_barang) }}" class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus barang ini? Ini akan menghapus semua riwayat peminjamannya juga.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Detail Information -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-slate-700 pt-6">
        {{-- Kolom 1: Info Utama --}}
        <div class="md:col-span-2 space-y-4">
            <h3 class="text-lg font-semibold text-slate-200 border-b border-slate-700 pb-2">Informasi Detail</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="text-slate-400">Kategori</div>
                <div class="text-slate-200 font-medium">{{ $barang->kategori->nama_kategori }}</div>

                <div class="text-slate-400">Lokasi</div>
                <div class="text-slate-200 font-medium">{{ $barang->lokasi }}</div>
                
                <div class="text-slate-400">Tanggal Ditambahkan</div>
                <div class="text-slate-200 font-medium">{{ $barang->created_at->translatedFormat('l, d F Y') }}</div>

                <div class="text-slate-400">Terakhir Diperbarui</div>
                <div class="text-slate-200 font-medium">{{ $barang->updated_at->diffForHumans() }}</div>

                <div class="text-slate-400 col-span-2">Keterangan</div>
                <div class="text-slate-200 col-span-2 font-light italic bg-slate-900 p-3 rounded-md">
                    {{ $barang->keterangan ?: 'Tidak ada keterangan.' }}
                </div>
            </div>
        </div>

        {{-- Kolom 2: Info Stok --}}
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-slate-200 border-b border-slate-700 pb-2">Status & Stok</h3>
             <div class="space-y-4 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Status</span>
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $barang->status == 'tersedia' ? 'bg-green-900 text-green-300' : 'bg-slate-600 text-slate-300' }}">
                        {{ Str::title($barang->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Kondisi</span>
                     <span class="px-3 py-1 text-xs font-bold rounded-full {{ ['Baik' => 'bg-green-900 text-green-300', 'Rusak Ringan' => 'bg-yellow-900 text-yellow-300', 'Rusak Berat' => 'bg-red-900 text-red-300'][$barang->kondisi] ?? '' }}">
                        {{ $barang->kondisi }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Jumlah Total</span>
                    <span class="font-semibold text-xl text-slate-200">{{ $barang->jumlah }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Tersedia</span>
                    <span class="font-semibold text-xl text-green-400">{{ $barang->tersedia }}</span>
                </div>
                 <div class="flex justify-between items-center">
                    <span class="text-slate-400">Dipinjam</span>
                    <span class="font-semibold text-xl text-yellow-400">{{ $barang->dipinjam }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Peminjaman -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-slate-200 mb-4">Riwayat Peminjaman</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-700 text-slate-300 uppercase">
                    <tr>
                        <th class="py-3 px-4 text-left">Peminjam</th>
                        <th class="py-3 px-4 text-left">Tgl Pinjam</th>
                        <th class="py-3 px-4 text-left">Tgl Kembali</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-slate-400">
                    @forelse($barang->peminjaman as $peminjaman)
                        <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                            <td class="px-4 py-3 text-slate-200 font-medium">{{ $peminjaman->user->username }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-center">
                                 <span class="px-2 py-1 text-xs font-medium rounded-full {{ ['dipinjam' => 'bg-indigo-900 text-indigo-300', 'dikembalikan' => 'bg-green-900 text-green-300', 'menunggu' => 'bg-yellow-900 text-yellow-300', 'ditolak' => 'bg-red-900 text-red-300', 'menunggu_pengembalian' => 'bg-cyan-900 text-cyan-300'][$peminjaman->status] ?? 'bg-slate-700 text-slate-300' }}">
                                    {{ str_replace('_', ' ', Str::ucfirst($peminjaman->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-slate-500">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <p>Barang ini belum pernah dipinjam.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
