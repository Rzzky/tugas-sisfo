@extends('layout.app')
@section('title', 'Manajemen User')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-slate-200">Daftar User</h2>
        <a href="{{ route('users.create') }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
            <i class="fas fa-plus mr-2"></i> Tambah User Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 text-green-200 border border-green-500/30 rounded-lg p-4 mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-500/20 text-red-200 border border-red-500/30 rounded-lg p-4 mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-700 text-slate-300 uppercase">
                <tr>
                    <th class="py-3 px-4 text-left">Nama</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-center">Role</th>
                    <th class="py-3 px-4 text-left">Tanggal Bergabung</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-slate-400">
                @forelse ($users as $user)
                    <tr class="border-b border-slate-700 hover:bg-slate-900/50">
                        <td class="py-3 px-4">{{ $user->name }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4 text-center">
                            @if ($user->role == 'admin')
                                <span class="px-2 py-1 text-xs font-semibold text-red-200 bg-red-500/20 rounded-full">Admin</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-cyan-200 bg-cyan-500/20 rounded-full">User</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center space-x-4">
                                {{-- PERBAIKAN DI SINI --}}
                                <a href="{{ route('users.edit', $user->id_user) }}" class="text-yellow-400 hover:text-yellow-300" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>

                                {{-- PERBAIKAN DI SINI --}}
                                @if (auth()->id() !== $user->id_user)
                                    <form action="{{ route('users.destroy', $user->id_user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user \'{{ $user->name }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Tidak ada data user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection