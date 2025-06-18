@extends('layout.app')

@section('title', 'Permintaan Pengembalian')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-slate-200">Permintaan Pengembalian</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-700 text-slate-300 uppercase">
                <tr>
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Peminjam</th>
                    <th class="py-3 px-4 text-left">Barang</th>
                    <th class="py-3 px-4 text-left">Tgl Pengembalian</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-slate-400">
                @forelse($requests as $index => $request)
                    <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                        <td class="px-4 py-3">{{ $requests->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-200">{{ $request->peminjaman->user->username ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $request->peminjaman->barang->nama_barang ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($request->tanggal_kembali)->isoFormat('DD MMM YYYY') }}</td>
                        <td class="px-4 py-3 text-center">
                             <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-900 text-blue-300">
                                {{ $request->label_status == 'menunggu' ? 'Menunggu Persetujuan' : Str::title($request->label_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center space-x-2">
                                <form action="{{ route('request.pengembalian.approve', $request->id_pengembalian) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyetujui pengembalian ini?')">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 text-xs" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                 <form action="{{ route('request.pengembalian.reject', $request->id_pengembalian) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menolak permintaan ini?')">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="px-3 py-1 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition duration-300 text-xs" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-500">
                            Tidak ada permintaan pengembalian saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Pagination Links --}}
    <div class="mt-6">
        {{ $requests->links() }}
    </div>
</div>
@endsection
