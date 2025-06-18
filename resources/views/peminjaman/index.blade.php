@extends('layout.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
    {{-- Header dan Filter --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-semibold text-slate-200">Riwayat Peminjaman</h2>
        <form action="{{ route('peminjaman.index') }}" method="GET" class="flex items-center gap-x-2">
            <label for="periode" class="text-sm font-medium text-slate-400">Periode:</label>
            <select name="periode" id="periode" onchange="this.form.submit()" class="bg-slate-900 border border-slate-600 text-slate-200 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 py-2 px-3">
                <option value="all" {{ request('periode') == 'all' ? 'selected' : '' }}>Semua</option>
                <option value="minggu" {{ request('periode') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="bulan" {{ request('periode') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="tahun" {{ request('periode') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
            </select>
        </form>
    </div>

    {{-- Tabel Daftar Peminjaman --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-700 text-slate-300 uppercase">
                <tr>
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Peminjam</th>
                    <th class="py-3 px-4 text-left">Barang</th>
                    <th class="py-3 px-4 text-center">Jumlah</th>
                    <th class="py-3 px-4 text-left">Tgl Pinjam</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-slate-400">
                @php
                    $statusColors = [
                        'dipinjam' => 'bg-yellow-900 text-yellow-300',
                        'dikembalikan' => 'bg-green-900 text-green-300',
                        'menunggu' => 'bg-blue-900 text-blue-300',
                        'ditolak' => 'bg-red-900 text-red-300',
                        'menunggu_pengembalian' => 'bg-cyan-900 text-cyan-300',
                    ];
                @endphp
                @forelse($peminjaman as $index => $item)
                    <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-200">{{ $item->user->name ?? $item->user->username ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $item->barang->nama_barang ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-center">{{ $item->jumlah }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->isoFormat('DD MMM YY') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$item->status] ?? 'bg-slate-700 text-slate-300' }}">
                                {{ str_replace('_', ' ', Str::title($item->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('peminjaman.show', $item->id_peminjaman) }}" class="text-blue-400 hover:text-blue-300" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('peminjaman.destroy', $item->id_peminjaman) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data peminjaman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-slate-500">
                            Tidak ada data peminjaman pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
