<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-slate-400">
        <thead class="text-xs text-slate-300 uppercase bg-slate-700">
            <tr><th scope="col" class="px-4 py-3">Barang</th><th scope="col" class="px-4 py-3 text-center">Stok</th></tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
            <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                <td class="px-4 py-3 font-medium text-slate-200">{{ $item->nama_barang }}</td>
                <td class="px-4 py-3 text-center font-bold text-yellow-400">{{ $item->tersedia }}</td>
            </tr>
            @empty
            <tr><td colspan="2" class="text-center py-4 text-slate-500">Tidak ada barang dengan stok menipis.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>