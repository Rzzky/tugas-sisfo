@extends('layout.app')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
<style>
    .stat-card {
        transition: all 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .dashboard-table th, .dashboard-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 0.25rem;
    }
    .badge-success {
        background-color: #10B981;
        color: white;
    }
    .badge-warning {
        background-color: #F59E0B;
        color: white;
    }
    .badge-danger {
        background-color: #EF4444;
        color: white;
    }
    .badge-info {
        background-color: #3B82F6;
        color: white;
    }
    .dashboard-card {
        border-radius: 0.75rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(209, 213, 219, 0.3);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.3s;
    }
    .dashboard-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(79, 70, 229, 0.2);
    }
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }
    .chart-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #4B5563;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    .chart-title i {
        margin-right: 0.5rem;
        color: #4F46E5;
    }
    .chart-card {
        position: relative;
        overflow: hidden;
    }
    .chart-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #4F46E5, #6366F1, #8B5CF6);
        z-index: 1;
    }
</style>
@endpush

@section('content')
<h1 class="text-3xl font-semibold text-gray-800 mb-6">Dashboard</h1>

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-indigo-800 to-purple-700 text-white rounded-xl p-6 shadow-md mb-8">
    <div class="flex flex-col md:flex-row justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->username }}</h2>
            <p class="text-indigo-100">
                Selamat datang di Sistem Informasi Sarana dan Prasarana. Kelola inventaris, peminjaman, dan pengembalian dengan mudah.
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="text-right">
                <p class="text-indigo-100">{{ now()->format('l, d F Y') }}</p>
                <p class="text-xl font-semibold">{{ now()->format('H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Status Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card bg-white rounded-xl overflow-hidden shadow-md">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Barang</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalBarang }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-blue-600">{{ $barangTersedia }} Tersedia</span> | 
                <span class="text-orange-500">{{ $barangDipinjam }} Dipinjam</span>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-3">
            <a href="{{ route('barang.index') }}" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                <span>Lihat Detail</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <div class="stat-card bg-white rounded-xl overflow-hidden shadow-md">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kategori</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalKategori }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-tags text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-green-600">Kategori Barang</span>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-3">
            <a href="{{ route('kategori.index') }}" class="flex items-center text-sm text-green-600 hover:text-green-800">
                <span>Lihat Detail</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <div class="stat-card bg-white rounded-xl overflow-hidden shadow-md">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Peminjaman Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $peminjamanAktif }}</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-hand-holding text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-purple-600">Sedang Dipinjam</span>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-3">
            <a href="{{ route('peminjaman.index') }}" class="flex items-center text-sm text-purple-600 hover:text-purple-800">
                <span>Lihat Detail</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <div class="stat-card bg-white rounded-xl overflow-hidden shadow-md">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Pengembalian</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $pengembalian }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-undo-alt text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-yellow-600">Barang Dikembalikan</span>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-3">
            <a href="{{ route('pengembalian.index') }}" class="flex items-center text-sm text-yellow-600 hover:text-yellow-800">
                <span>Lihat Detail</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Chart: Monthly Activity -->
    <div class="dashboard-card chart-card p-6">
        <h3 class="chart-title"><i class="fas fa-chart-line"></i>Aktivitas Bulanan</h3>
        <div class="chart-container">
            <canvas id="monthlyActivityChart"></canvas>
        </div>
    </div>

    <!-- Chart: Items per Category -->
    <div class="dashboard-card chart-card p-6">
        <h3 class="chart-title"><i class="fas fa-chart-pie"></i>Barang per Kategori</h3>
        <div class="chart-container">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Peminjaman -->
    <div class="dashboard-card bg-white p-6 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Peminjaman Terbaru</h3>
            <a href="{{ route('peminjaman.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full dashboard-table">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($recentPeminjaman as $pinjam)
                    <tr>
                        <td>{{ $pinjam->barang->nama_barang }}</td>
                        <td>{{ $pinjam->user->username }}</td>
                        <td>
                            @if($pinjam->status === 'dipinjam')
                                <span class="badge badge-info">Dipinjam</span>
                            @elseif($pinjam->status === 'dikembalikan')
                                <span class="badge badge-success">Dikembalikan</span>
                            @elseif($pinjam->status === 'terlambat')
                                <span class="badge badge-danger">Terlambat</span>
                            @else
                                <span class="badge badge-warning">{{ $pinjam->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">Tidak ada data peminjaman terbaru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Expiring Loans -->
    <div class="dashboard-card bg-white p-6 rounded-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Jatuh Tempo 7 Hari Kedepan</h3>
            <a href="{{ route('peminjaman.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full dashboard-table">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($expiringLoans as $loan)
                    <tr>
                        <td>{{ $loan->barang->nama_barang }}</td>
                        <td>{{ $loan->user->username }}</td>
                        <td>
                            @php
                                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($loan->tanggal_kembali), false);
                            @endphp
                            @if($daysLeft < 0)
                                <span class="badge badge-danger">Terlambat {{ abs($daysLeft) }} hari</span>
                            @elseif($daysLeft === 0)
                                <span class="badge badge-warning">Hari Ini</span>
                            @else
                                <span class="badge badge-info">{{ $daysLeft }} hari lagi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">Tidak ada peminjaman yang akan jatuh tempo dalam 7 hari kedepan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Top Borrowed Items -->
    <div class="dashboard-card bg-white p-6 rounded-xl">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Barang Terpopuler</h3>
        <div class="space-y-4">
            @forelse ($topBarang as $index => $item)
                @if($item->barang)
                <div class="flex items-center">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-800 font-bold mr-4">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-gray-800 font-medium">{{ $item->barang->nama_barang }}</h4>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ min(100, ($item->total_peminjaman / max(1, $topBarang->first()->total_peminjaman)) * 100) }}%"></div>
                        </div>
                    </div>
                    <div class="ml-4 text-right">
                        <span class="text-sm font-semibold">{{ $item->total_peminjaman }}</span>
                        <span class="text-xs text-gray-500 block">peminjaman</span>
                    </div>
                </div>
                @endif
            @empty
                <p class="text-gray-500 text-center py-4">Belum ada data peminjaman</p>
            @endforelse
        </div>
    </div>

    <!-- Low Stock Items -->
    <div class="dashboard-card bg-white p-6 rounded-xl">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Stok Menipis (<5)</h3>
        <div class="overflow-x-auto">
            <table class="w-full dashboard-table">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tersedia</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($lowStockItems as $item)
                    <tr>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kategori->nama_kategori }}</td>
                        <td class="text-right">
                            @if($item->tersedia === 0)
                                <span class="badge badge-danger">Habis</span>
                            @elseif($item->tersedia < 3)
                                <span class="badge badge-warning">{{ $item->tersedia }}</span>
                            @else
                                <span class="badge badge-info">{{ $item->tersedia }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">Tidak ada barang dengan stok menipis</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($isAdmin)
<!-- Admin Only Section -->
<div class="dashboard-card bg-white p-6 rounded-xl mb-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">User Terbaru</h3>
        <span class="text-sm font-medium bg-indigo-100 text-indigo-800 py-1 px-3 rounded-full">Total: {{ $userCount }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full dashboard-table">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($latestUsers as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge badge-danger">Admin</span>
                        @else
                            <span class="badge badge-info">{{ ucfirst($user->role) }}</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data user</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    // Global Chart Settings
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.color = '#64748B';
    Chart.defaults.scale.grid.color = 'rgba(226, 232, 240, 0.5)';
    Chart.defaults.plugins.tooltip.titleColor = '#1E293B';
    Chart.defaults.plugins.tooltip.bodyColor = '#334155';
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(255, 255, 255, 0.95)';
    Chart.defaults.plugins.tooltip.borderColor = 'rgba(203, 213, 225, 0.5)';
    Chart.defaults.plugins.tooltip.borderWidth = 1;
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.cornerRadius = 6;
    Chart.defaults.plugins.tooltip.displayColors = false;
    Chart.defaults.plugins.tooltip.mode = 'index';
    Chart.defaults.plugins.tooltip.intersect = false;
    
    // Define gradient for the monthly chart
    const monthlyCtx = document.getElementById('monthlyActivityChart').getContext('2d');
    const peminjamanGradient = monthlyCtx.createLinearGradient(0, 0, 0, 250);
    peminjamanGradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
    peminjamanGradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');
    
    const pengembalianGradient = monthlyCtx.createLinearGradient(0, 0, 0, 250);
    pengembalianGradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
    pengembalianGradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
    
    // Monthly Activity Chart
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
            datasets: [
                {
                    label: 'Peminjaman',
                    data: {!! json_encode(array_column($monthlyData, 'peminjaman')) !!},
                    borderColor: '#6366F1',
                    backgroundColor: peminjamanGradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#6366F1',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Pengembalian',
                    data: {!! json_encode(array_column($monthlyData, 'pengembalian')) !!},
                    borderColor: '#10B981',
                    backgroundColor: pengembalianGradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 8,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return context[0].label + ' ' + new Date().getFullYear();
                        },
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    },
                    border: {
                        dash: [5, 5]
                    }
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kategoriBarchart->pluck('nama_kategori')->toArray()) !!},
            datasets: [{
                label: 'Jumlah Barang',
                data: {!! json_encode($kategoriBarchart->pluck('barang_count')->toArray()) !!},
                backgroundColor: [
                    'rgba(99, 102, 241, 0.8)',  // Indigo
                    'rgba(16, 185, 129, 0.8)',  // Green
                    'rgba(245, 158, 11, 0.8)',  // Amber
                    'rgba(239, 68, 68, 0.8)',   // Red
                    'rgba(59, 130, 246, 0.8)',  // Blue
                    'rgba(139, 92, 246, 0.8)',  // Purple
                    'rgba(236, 72, 153, 0.8)',  // Pink
                ],
                borderColor: '#FFFFFF',
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 10,
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection