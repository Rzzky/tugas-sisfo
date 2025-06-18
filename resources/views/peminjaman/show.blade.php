@extends('layout.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 md:p-8 border border-slate-700">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-700">
        <div class="flex items-center">
            <a href="{{ url()->previous() }}" class="text-slate-400 hover:text-indigo-400 mr-4" title="Kembali">
                <i class="fas fa-arrow-left fa-lg"></i>
            </a>
            <h2 class="text-2xl font-semibold text-slate-200">Detail Peminjaman</h2>
        </div>
        <div>
            @php
                $statusColors = [
                    'dipinjam' => 'bg-yellow-900 text-yellow-300',
                    'dikembalikan' => 'bg-green-900 text-green-300',
                    'menunggu' => 'bg-blue-900 text-blue-300',
                    'ditolak' => 'bg-red-900 text-red-300',
                    'menunggu_pengembalian' => 'bg-cyan-900 text-cyan-300',
                ];
            @endphp
            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusColors[$peminjaman->status] ?? 'bg-slate-700 text-slate-300' }}">
                Status: {{ str_replace('_', ' ', Str::title($peminjaman->status)) }}
            </span>
        </div>
    </div>

    {{-- Detail Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Kolom Kiri: Info Peminjam & Barang --}}
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-slate-200">Informasi Peminjam & Barang</h3>
            <div class="text-sm space-y-3 text-slate-400">
                <div class="flex justify-between"><span class="font-medium">Peminjam:</span> <span class="text-slate-200">{{ $peminjaman->user->name ?? $peminjaman->user->username ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Barang:</span> <span class="text-slate-200">{{ $peminjaman->barang->nama_barang ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Kode Barang:</span> <span class="text-slate-200">{{ $peminjaman->barang->kode_barang ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Kategori:</span> <span class="text-slate-200">{{ $peminjaman->barang->kategori->nama_kategori ?? 'N/A' }}</span></div>
            </div>
        </div>
        
        {{-- Kolom Kanan: Info Peminjaman --}}
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-slate-200">Informasi Peminjaman</h3>
            <div class="text-sm space-y-3 text-slate-400">
                <div class="flex justify-between"><span class="font-medium">Jumlah Pinjam:</span> <span class="text-slate-200">{{ $peminjaman->jumlah }} unit</span></div>
                <div class="flex justify-between"><span class="font-medium">Tanggal Pinjam:</span> <span class="text-slate-200">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->isoFormat('dddd, DD MMMM YYYY') }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Estimasi Kembali:</span> <span class="text-slate-200">{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->isoFormat('dddd, DD MMMM YYYY') : '-' }}</span></div>
            </div>
             @if($peminjaman->keterangan)
                <div class="pt-2">
                    <p class="text-sm font-medium text-slate-400">Keterangan:</p>
                    <p class="text-sm text-slate-300 italic bg-slate-900 p-3 rounded-md mt-1">{{ $peminjaman->keterangan }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Aksi --}}
    <div class="mt-8 pt-6 border-t border-slate-700">
        <h3 class="text-lg font-semibold text-slate-200 mb-4">Tindakan</h3>
        <div class="flex flex-wrap gap-4">
            @if($peminjaman->status == 'menunggu')
                {{-- PERBAIKAN: Mengganti method('PUT') menjadi method('POST') dan route yang benar --}}
                <form action="{{ route('request.peminjaman.approve', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyetujui peminjaman ini?')">
                    @csrf
                    @method('POST')
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-check mr-2"></i>Setujui
                    </button>
                </form>
                <form action="{{ route('request.peminjaman.reject', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menolak peminjaman ini?')">
                    @csrf
                    @method('POST')
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-times mr-2"></i>Tolak
                    </button>
                </form>
            @endif

            @if($peminjaman->status == 'dipinjam' || $peminjaman->status == 'menunggu_pengembalian')
                <form action="{{ route('pengembalian.store') }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian barang ini?')">
                    @csrf
                    <input type="hidden" name="id_peminjaman" value="{{ $peminjaman->id_peminjaman }}">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-undo-alt mr-2"></i>Proses Pengembalian
                    </button>
                </form>
            @endif
            
            <form action="{{ route('peminjaman.destroy', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
