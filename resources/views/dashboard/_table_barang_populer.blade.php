{{-- resources/views/dashboard/_table_barang_populer.blade.php --}}
<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-slate-400">
        <thead class="text-xs text-slate-300 uppercase bg-slate-700">
            <tr>
                <th scope="col" class="px-4 py-3">Barang</th>
                <th scope="col" class="px-4 py-3 text-center">Total Pinjam</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
            <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                <td class="px-4 py-3 font-medium text-slate-200">
                    {{-- Akses nama barang melalui relasi 'barang' --}}
                    {{ $item->barang->nama_barang ?? 'Barang tidak ditemukan' }}
                </td>
                <td class="px-4 py-3 text-center font-bold">
                    {{-- Gunakan 'total_peminjaman' sesuai query di controller --}}
                    {{ $item->total_peminjaman }}x
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center py-4 text-slate-500">
                    Belum ada barang yang dipinjam.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>