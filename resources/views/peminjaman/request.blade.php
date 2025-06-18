@extends('layouts.app')

@section('title', 'Permintaan Peminjaman')

@section('content')
<div class="px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Permintaan Peminjaman</h1>
        <p class="text-gray-400">Daftar pengguna yang mengajukan peminjaman barang</p>
    </div>

    <div class="rounded-lg border border-gray-700/50">
        <table class="min-w-full text-gray-300">
            <thead>
                <tr class="border-b border-gray-700/50 text-left">
                    <th class="px-6 py-3">Nama User</th>
                    <th class="px-6 py-3">Barang</th>
                    <th class="px-6 py-3">Tanggal Pinjam</th>
                    <th class="px-6 py-3">Keterangan</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $data)
                <tr class="hover:bg-navy-700/50 transition duration-150">
                    <td class="px-6 py-4">{{ $data->user->name }}</td>
                    <td class="px-6 py-4">{{ $data->barang->nama_barang }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ $data->keterangan }}</td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('peminjaman.approve', $data->id_peminjaman) }}" method="POST" class="inline-block">
                            @csrf
                            <button class="text-green-500 hover:text-green-400" onclick="return confirm('Setujui peminjaman?')">Setujui</button>
                        </form>
                        <form action="{{ route('peminjaman.reject', $data->id_peminjaman) }}" method="POST" class="inline-block ml-3">
                            @csrf
                            <button class="text-red-500 hover:text-red-400" onclick="return confirm('Tolak peminjaman?')">Tolak</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-400">Tidak ada permintaan peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
