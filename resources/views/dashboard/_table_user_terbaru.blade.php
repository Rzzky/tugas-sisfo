<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-slate-400">
        <thead class="text-xs text-slate-300 uppercase bg-slate-700">
            <tr><th scope="col" class="px-4 py-3">Username</th><th scope="col" class="px-4 py-3">Email</th></tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                <td class="px-4 py-3 font-medium text-slate-200">{{ $user->username }}</td>
                <td class="px-4 py-3">{{ $user->email }}</td>
            </tr>
            @empty
            <tr><td colspan="2" class="text-center py-4 text-slate-500">Tidak ada user baru.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>