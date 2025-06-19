@extends('layout.app')
@section('title', 'Edit User')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 md:p-8 border border-slate-700">
    <h2 class="text-2xl font-semibold text-slate-200 mb-6">Formulir Edit User: {{ $user->name }}</h2>
    
    {{-- PERBAIKAN PADA BARIS ACTION DI BAWAH INI --}}
    <form action="{{ route('users.update', $user->id_user) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT') {{-- Method spoofing untuk update --}}
        
        <div>
            <label for="username" class="block text-sm font-medium text-slate-300">Username <span class="text-red-500">*</span></label>
            <input type="text" name="username" id="username" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm text-slate-200" value="{{ old('username', $user->username) }}" required>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-300">Alamat Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm text-slate-200" value="{{ old('email', $user->email) }}" required>
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-slate-300">Role <span class="text-red-500">*</span></label>
            <select name="role" id="role" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm text-slate-200" required>
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="pt-4 border-t border-slate-700">
            <p class="text-sm text-slate-400">Kosongkan password jika tidak ingin mengubahnya.</p>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-300">Password Baru</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm text-slate-200">
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm text-slate-200">
        </div>

        <div class="flex justify-end space-x-3 pt-6 border-t border-slate-700">
            <a href="{{ route('users.index') }}" class="bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium py-2 px-4 rounded-lg">Batal</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg">
                Update User
            </button>
        </div>
    </form>
</div>
@endsection