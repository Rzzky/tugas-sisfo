<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-slate-400">
        <thead class="text-xs text-slate-300 uppercase bg-slate-700">
            <tr><th scope="col" class="px-4 py-3">Barang</th><th scope="col" class="px-4 py-3">Peminjam</th><th scope="col" class="px-4 py-3">Status</th></tr>
        </thead>
        <tbody>
            @forelse ($items as $pinjam)
            <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                <td class="px-4 py-3 font-medium text-slate-200">{{ $pinjam->barang->nama_barang }}</td>
                <td class="px-4 py-3">{{ $pinjam->user->username }}</td>
                <td class="px-4 py-3">
                     <span class="px-2 py-1 text-xs font-medium rounded-full {{ ['dipinjam' => 'bg-indigo-900 text-indigo-300', 'dikembalikan' => 'bg-green-900 text-green-300', 'menunggu' => 'bg-yellow-900 text-yellow-300', 'ditolak' => 'bg-red-900 text-red-300', 'menunggu_pengembalian' => 'bg-cyan-900 text-cyan-300'][$pinjam->status] ?? 'bg-slate-700 text-slate-300' }}">
                        {{ str_replace('_', ' ', Str::ucfirst($pinjam->status)) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center py-4 text-slate-500">Tidak ada data peminjaman terbaru.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>