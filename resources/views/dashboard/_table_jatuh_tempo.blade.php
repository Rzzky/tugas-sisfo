<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-slate-400">
        <thead class="text-xs text-slate-300 uppercase bg-slate-700">
            <tr><th scope="col" class="px-4 py-3">Barang</th><th scope="col" class="px-4 py-3">Peminjam</th><th scope="col" class="px-4 py-3">Jatuh Tempo</th></tr>
        </thead>
        <tbody>
            @forelse ($items as $loan)
            <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                <td class="px-4 py-3 font-medium text-slate-200">{{ $loan->barang->nama_barang }}</td>
                <td class="px-4 py-3">{{ $loan->user->username }}</td>
                <td class="px-4 py-3">
                    @php $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($loan->tanggal_kembali), false); @endphp
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $daysLeft < 0 ? 'bg-red-900 text-red-300' : 'bg-yellow-900 text-yellow-300' }}">
                        {{ $daysLeft < 0 ? 'Terlambat '.abs($daysLeft).' hari' : ($daysLeft === 0 ? 'Hari Ini' : $daysLeft.' hari lagi') }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center py-4 text-slate-500">Tidak ada peminjaman jatuh tempo.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>