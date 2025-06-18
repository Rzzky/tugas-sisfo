@extends('layout.app')

@section('title', 'Permintaan Peminjaman')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-slate-200">Permintaan Peminjaman</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-700 text-slate-300 uppercase">
                <tr>
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Peminjam</th>
                    <th class="py-3 px-4 text-left">Barang</th>
                    <th class="py-3 px-4 text-center">Jumlah</th>
                    <th class="py-3 px-4 text-left">Tanggal Pinjam</th>
                    <th class="py-3 px-4 text-center">Diajukan</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-slate-400">
                @forelse($requests as $index => $request)
                    <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                        <td class="px-4 py-3">{{ $requests->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-200">{{ $request->user->username ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $request->barang->nama_barang ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-center">{{ $request->jumlah }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($request->tanggal_pinjam)->isoFormat('DD MMM YY') }}</td>
                        <td class="px-4 py-3 text-center">{{ $request->created_at ? $request->created_at->diffForHumans() : 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center space-x-2">
                                <form action="{{ route('request.peminjaman.approve', $request->id_peminjaman) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyetujui peminjaman ini?')">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 text-xs" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('request.peminjaman.reject', $request->id_peminjaman) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menolak peminjaman ini?')">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="px-3 py-1 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition duration-300 text-xs" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                <a href="{{ route('peminjaman.show', $request->id_peminjaman) }}" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 text-xs" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-slate-500">
                            Tidak ada permintaan peminjaman saat ini.
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
