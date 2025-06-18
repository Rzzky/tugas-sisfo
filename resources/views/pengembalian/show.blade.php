@extends('layout.app')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 md:p-8 border border-slate-700">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-slate-700">
        <div class="flex items-center mb-4 sm:mb-0">
            <a href="{{ route('pengembalian.index') }}" class="text-slate-400 hover:text-indigo-400 mr-4" title="Kembali">
                <i class="fas fa-arrow-left fa-lg"></i>
            </a>
            <h2 class="text-2xl font-semibold text-slate-200">Detail Pengembalian</h2>
        </div>
        <div class="flex items-center gap-x-3">
            <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-green-900 text-green-300">
                Status: Selesai
            </span>
            <form action="{{ route('pengembalian.destroy', $pengembalian->id_pengembalian) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data pengembalian ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded-lg transition duration-300">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Detail Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Info Peminjam --}}
        <div class="bg-slate-900/50 p-4 rounded-lg border border-slate-700">
            <h3 class="font-semibold text-slate-200 mb-3 border-b border-slate-700 pb-2 flex items-center gap-x-2"><i class="fas fa-user"></i>Informasi Peminjam</h3>
            <div class="text-sm space-y-2 text-slate-400">
                <p><strong>Nama:</strong> <span class="text-slate-200">{{ $pengembalian->peminjaman->user->username ?? 'N/A' }}</span></p>
                <p><strong>Email:</strong> <span class="text-slate-200">{{ $pengembalian->peminjaman->user->email ?? 'N/A' }}</span></p>
            </div>
        </div>

        {{-- Info Barang --}}
        <div class="bg-slate-900/50 p-4 rounded-lg border border-slate-700">
            <h3 class="font-semibold text-slate-200 mb-3 border-b border-slate-700 pb-2 flex items-center gap-x-2"><i class="fas fa-box"></i>Informasi Barang</h3>
            <div class="text-sm space-y-2 text-slate-400">
                <p><strong>Nama:</strong> <span class="text-slate-200">{{ $pengembalian->peminjaman->barang->nama_barang ?? 'N/A' }}</span></p>
                <p><strong>Kode:</strong> <span class="text-slate-200">{{ $pengembalian->peminjaman->barang->kode_barang ?? 'N/A' }}</span></p>
                <p><strong>Jumlah:</strong> <span class="text-slate-200">{{ $pengembalian->peminjaman->jumlah ?? 0 }} unit</span></p>
            </div>
        </div>

        {{-- Info Transaksi --}}
        <div class="bg-slate-900/50 p-4 rounded-lg border border-slate-700">
            <h3 class="font-semibold text-slate-200 mb-3 border-b border-slate-700 pb-2 flex items-center gap-x-2"><i class="fas fa-calendar-alt"></i>Informasi Transaksi</h3>
            <div class="text-sm space-y-2 text-slate-400">
                <p><strong>Dipinjam:</strong> <span class="text-slate-200">{{ \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_pinjam)->isoFormat('DD MMM YYYY') }}</span></p>
                <p><strong>Dikembalikan:</strong> <span class="text-slate-200">{{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->isoFormat('DD MMM YYYY') }}</span></p>
            </div>
        </div>
    </div>
</div>
@endsection
