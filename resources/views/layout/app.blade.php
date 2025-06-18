
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @auth
    @if (Auth::user()->role !== 'admin')
        <script>
            window.location.href = "{{ route('login') }}";
        </script>
    @endif
    @endauth
    <title>@yield('title', 'Sisfo Sarpras') - Sistem Informasi Sarana Prasarana</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
        }
        .sidebar-item {
            transition: all 0.3s;
        }
        .sidebar-item:hover {
            background-color: #4338ca;
            border-radius: 0.375rem;
        }
        .active-nav-link {
            background-color: #4338ca;
            border-radius: 0.375rem;
        }
        .custom-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-indigo-800 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
            <div class="flex items-center justify-center mt-6">
                <div class="flex items-center">
                    <i class="fas fa-boxes text-white text-2xl mr-3"></i>
                    <span class="text-white text-xl font-semibold">SISFO SARPRAS</span>
                </div>
            </div>

            <nav class="mt-10 px-4">
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100 sidebar-item {{ request()->routeIs('dashboard') ? 'active-nav-link' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>

                <a class="flex items-center mt-4 py-2 px-6 text-gray-100 sidebar-item {{ request()->routeIs('kategori.*') ? 'active-nav-link' : '' }}" href="{{ route('kategori.index') }}">
                    <i class="fas fa-tags mr-3"></i>
                    <span>Kategori</span>
                </a>

                <a class="flex items-center mt-4 py-2 px-6 text-gray-100 sidebar-item {{ request()->routeIs('barang.*') ? 'active-nav-link' : '' }}" href="{{ route('barang.index') }}">
                    <i class="fas fa-box mr-3"></i>
                    <span>Barang</span>
                </a>

                <a class="flex items-center mt-4 py-2 px-6 text-gray-100 sidebar-item {{ request()->routeIs('peminjaman.*') ? 'active-nav-link' : '' }}" href="{{ route('peminjaman.index') }}">
                    <i class="fas fa-hand-holding mr-3"></i>
                    <span>Peminjaman</span>
                </a>

                <a class="flex items-center mt-4 py-2 px-6 text-gray-100 sidebar-item {{ request()->routeIs('pengembalian.*') ? 'active-nav-link' : '' }}" href="{{ route('pengembalian.index') }}">
                    <i class="fas fa-undo-alt mr-3"></i>
                    <span>Pengembalian</span>
                </a>


                <div x-data="{ requestMenuOpen: false }" class="relative">
    <button @click="requestMenuOpen = !requestMenuOpen" class="flex items-center justify-between w-full mt-4 py-2 px-6 text-gray-100 sidebar-item {{ request()->routeIs('request.peminjaman.*', 'request.pengembalian.*') ? 'active-nav-link' : '' }}">
        <div class="flex items-center">
            <i class="fas fa-bell mr-3"></i>
            <span>Permintaan</span>
        </div>
        <i class="fas fa-chevron-down text-xs transform transition-transform duration-300" :class="{ 'rotate-180': requestMenuOpen }"></i>
    </button>

    <div x-show="requestMenuOpen" @click.away="requestMenuOpen = false" class="ml-8 mt-1 space-y-2" style="display: none;">
        <a class="flex items-center px-4 py-2 text-sm text-gray-200 hover:bg-indigo-700 rounded {{ request()->routeIs('request.peminjaman.*') ? 'bg-indigo-700' : '' }}"
           href="{{ route('request.peminjaman.index') }}">
            <i class="fas fa-hand-holding mr-2"></i>
            Peminjaman
        </a>

        <a class="flex items-center px-4 py-2 text-sm text-gray-200 hover:bg-indigo-700 rounded {{ request()->routeIs('request.pengembalian.*') ? 'bg-indigo-700' : '' }}"
           href="{{ route('request.pengembalian.index') }}">
            <i class="fas fa-undo-alt mr-2"></i>
            Pengembalian
        </a>
    </div>
</div>

                <a class="flex items-center mt-4 py-2 px-6 text-gray-100 sidebar-item {{ request()->routeIs('laporan.*') ? 'active-nav-link' : '' }}" href="laporan">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Laporan</span>
                </a>


                <div class="border-t border-indigo-700 mt-6 pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center py-2 px-6 text-gray-100 sidebar-item">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex justify-between items-center py-4 px-6 bg-white border-b-4 border-indigo-800">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <div class="flex items-center">
                    <div x-data="{ notificationOpen: false }" class="relative mr-6">
                        <button @click="notificationOpen = !notificationOpen" class="flex mx-4 text-gray-600 focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            @if(isset($notificationCount) && $notificationCount > 0)
                            <span class="absolute top-0 right-0 inline-block w-2 h-2 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"></span>
                            @endif
                        </button>

                        <div x-show="notificationOpen" @click.away="notificationOpen = false" class="fixed inset-0 z-10 w-full h-full" style="display: none;"></div>

                        <div x-show="notificationOpen" class="absolute right-0 z-20 mt-2 overflow-hidden bg-white rounded-md shadow-lg" style="width:20rem; display: none;">
                            <div class="py-2">
                                @if(isset($notifications) && count($notifications) > 0)
                                    @foreach($notifications as $notification)
                                    <a href="#" class="flex items-center px-4 py-3 -mx-2 transition-colors duration-300 transform border-b hover:bg-gray-100">
                                        <div class="mx-3">
                                            <span class="font-semibold text-gray-700">{{ $notification->title }}</span>
                                            <p class="text-sm text-gray-600">{{ $notification->message }}</p>
                                        </div>
                                    </a>
                                    @endforeach
                                @else
                                    <div class="px-4 py-3 text-sm text-gray-600">
                                        Tidak ada notifikasi baru
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 relative focus:outline-none">
                            <div class="w-8 h-8 overflow-hidden rounded-full bg-indigo-800 flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <span class="text-gray-700">{{ auth()->user()->username ?? 'User' }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="fixed inset-0 z-10 w-full h-full" style="display: none;"></div>

                        <div x-show="dropdownOpen" class="absolute right-0 z-20 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-xl" style="display: none;">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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
