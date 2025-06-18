@extends('layout.app')

@section('title', 'Permintaan Pengembalian')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Permintaan Pengembalian</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Peminjam</th>
                    <th class="py-3 px-4 text-left">Barang</th>
                    <th class="py-3 px-4 text-left">Tanggal Kembali</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @foreach($requests as $index => $request)
                    <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $request->peminjaman->user->username ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $request->peminjaman->barang->nama_barang ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($request->tanggal_kembali)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $request->label_status == 'menunggu' ? 'Menunggu Persetujuan' : ucfirst($request->label_status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <form action="{{ route('request.pengembalian.approve', $request->id_pengembalian) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengembalian ini?')">
                                @csrf
                                @method('POST')
                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition duration-300 text-xs">
                                    <i class="fas fa-check mr-1"></i>Setujui
                                </button>
                            </form>

                            <form action="{{ route('request.pengembalian.reject', $request->id_pengembalian) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengembalian ini?')">
                                @csrf
                                @method('POST')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition duration-300 text-xs">
                                    <i class="fas fa-times mr-1"></i>Tolak
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

