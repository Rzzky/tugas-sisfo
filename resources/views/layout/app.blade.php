<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sisfo Sarpras') - Sistem Informasi Sarana Prasarana</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .sidebar-item a.active { background-color: #4f46e5; /* indigo-600 */ color: #ffffff; font-weight: 500; }
        .sidebar-item a:not(.active):hover { background-color: #312e81; /* indigo-800 */ }
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="antialiased bg-slate-900 text-slate-300">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-slate-900">
        <aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-slate-800 text-slate-300 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0 border-r border-slate-700">
            <div class="flex items-center justify-center mt-6">
                <div class="flex items-center">
                    <i class="fas fa-boxes-stacked text-white text-2xl mr-3"></i>
                    <span class="text-white text-xl font-semibold">SISFO SARPRAS</span>
                </div>
            </div>

            <nav class="mt-10 px-4 space-y-2">
                <div class="sidebar-item">
                    <a class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt w-6 text-center"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </div>

                <p class="px-4 pt-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Manajemen</p>
                <div class="sidebar-item">
                    <a class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                        <i class="fas fa-box w-6 text-center"></i>
                        <span class="ml-3">Barang</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                        <i class="fas fa-tags w-6 text-center"></i>
                        <span class="ml-3">Kategori</span>
                    </a>
                </div>

                <p class="px-4 pt-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Transaksi</p>
                <div class="sidebar-item">
                    <a class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                        <i class="fas fa-hand-holding-hand w-6 text-center"></i>
                        <span class="ml-3">Peminjaman</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}" href="{{ route('pengembalian.index') }}">
                        <i class="fas fa-undo-alt w-6 text-center"></i>
                        <span class="ml-3">Pengembalian</span>
                    </a>
                </div>
                
                <div x-data="{ requestMenuOpen: {{ request()->routeIs('request.*') ? 'true' : 'false' }} }" class="sidebar-item">
                    <a @click.prevent="requestMenuOpen = !requestMenuOpen" href="#" class="flex items-center justify-between py-2.5 px-4 rounded-lg transition duration-200">
                        <span class="flex items-center">
                            <i class="fas fa-bell w-6 text-center"></i>
                            <span class="ml-3">Permintaan</span>
                        </span>
                        <i class="fas fa-chevron-down text-xs transform transition-transform duration-300" :class="{ 'rotate-180': requestMenuOpen }"></i>
                    </a>
                    <div x-show="requestMenuOpen" x-cloak class="pl-8 py-2 space-y-2">
                        <a href="{{ route('request.peminjaman.index') }}" class="block text-sm py-2 px-4 rounded-md {{ request()->routeIs('request.peminjaman.*') ? 'text-white font-semibold' : 'text-slate-400' }} hover:text-white">Peminjaman</a>
                        <a href="{{ route('request.pengembalian.index') }}" class="block text-sm py-2 px-4 rounded-md {{ request()->routeIs('request.pengembalian.*') ? 'text-white font-semibold' : 'text-slate-400' }} hover:text-white">Pengembalian</a>
                    </div>
                </div>

                <p class="px-4 pt-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Lainnya</p>
                <div class="sidebar-item">
                    <a class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                        <i class="fas fa-chart-pie w-6 text-center"></i>
                        <span class="ml-3">Laporan</span>
                    </a>
                </div>

                {{-- AREA KHUSUS ADMIN --}}
                @if (auth()->user() && auth()->user()->role == 'admin')
                <div class="pt-4 mt-4 border-t border-slate-700">
                    <p class="px-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Admin Area</p>
                    <div class="sidebar-item">
                        {{-- INI BAGIAN YANG DIPERBAIKI --}}
                        <a class="flex items-center py-2.5 px-4 rounded-lg transition duration-200 {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="fas fa-users-cog w-6 text-center"></i>
                            <span class="ml-3">Manajemen User</span>
                        </a>
                    </div>
                </div>
                @endif
                
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center py-3 px-6 bg-slate-800 border-b border-slate-700 shadow-sm">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-400 focus:outline-none lg:hidden"><i class="fas fa-bars text-xl"></i></button>
                    <h1 class="text-xl font-semibold text-slate-200 ml-4">@yield('title', 'Dashboard')</h1>
                </div>

                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-x-3 relative focus:outline-none">
                        <div class="w-10 h-10 overflow-hidden rounded-full bg-indigo-600 flex items-center justify-center"><span class="text-white font-bold text-lg">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span></div>
                        <div class="text-left hidden md:block">
                            <p class="text-sm font-semibold text-slate-200">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-slate-400">{{ auth()->user()->role ?? 'Role' }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-xs text-slate-400 hidden md:block"></i>
                    </button>
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-cloak class="absolute right-0 z-20 w-48 mt-2 py-2 bg-slate-700 rounded-md shadow-xl border border-slate-600">
                        <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-indigo-600 hover:text-white"><i class="fas fa-user-circle w-6"></i> Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-slate-300 hover:bg-indigo-600 hover:text-white"><i class="fas fa-sign-out-alt w-6"></i> Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-900">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-900/50 border border-green-700 text-green-300 p-4 mb-4 rounded-lg" role="alert"><p>{{ session('success') }}</p></div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-900/50 border border-red-700 text-red-300 p-4 mb-4 rounded-lg" role="alert"><p>{{ session('error') }}</p></div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-900/50 border border-red-700 text-red-300 p-4 mb-4 rounded-lg">
                            <p class="font-bold">Terjadi Kesalahan:</p>
                            <ul class="list-disc ml-5 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>