@extends('layout.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease-in-out;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        background-color: #1e293b; /* slate-800 */
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl p-6 md:p-8 shadow-lg mb-8">
    <div class="flex flex-col md:flex-row justify-between items-center">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold mb-1">Selamat Datang, {{ auth()->user()->username }}!</h2>
            <p class="text-indigo-200">Kelola inventaris, peminjaman, dan pengembalian dengan mudah.</p>
        </div>
        <div class="mt-4 md:mt-0 text-right">
            <p class="text-lg font-semibold">{{ now()->format('H:i') }}</p>
            <p class="text-sm text-indigo-200">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card bg-slate-800 rounded-xl p-6 flex items-center border border-slate-700">
        <div class="p-4 bg-blue-500/20 text-blue-400 rounded-full mr-4">
            <i class="fas fa-boxes-stacked fa-2x"></i>
        </div>
        <div>
            <p class="text-sm text-slate-400">Total Barang</p>
            <h3 class="text-3xl font-bold text-slate-100">{{ $totalBarang }}</h3>
        </div>
    </div>
    <div class="stat-card bg-slate-800 rounded-xl p-6 flex items-center border border-slate-700">
        <div class="p-4 bg-green-500/20 text-green-400 rounded-full mr-4">
            <i class="fas fa-check-circle fa-2x"></i>
        </div>
        <div>
            <p class="text-sm text-slate-400">Barang Tersedia</p>
            <h3 class="text-3xl font-bold text-slate-100">{{ $barangTersedia }}</h3>
        </div>
    </div>
    <div class="stat-card bg-slate-800 rounded-xl p-6 flex items-center border border-slate-700">
        <div class="p-4 bg-yellow-500/20 text-yellow-400 rounded-full mr-4">
            <i class="fas fa-arrow-circle-up fa-2x"></i>
        </div>
        <div>
            <p class="text-sm text-slate-400">Barang Dipinjam</p>
            <h3 class="text-3xl font-bold text-slate-100">{{ $barangDipinjam }}</h3>
        </div>
    </div>
    <div class="stat-card bg-slate-800 rounded-xl p-6 flex items-center border border-slate-700">
        <div class="p-4 bg-purple-500/20 text-purple-400 rounded-full mr-4">
            <i class="fas fa-tags fa-2x"></i>
        </div>
        <div>
            <p class="text-sm text-slate-400">Total Kategori</p>
            <h3 class="text-3xl font-bold text-slate-100">{{ $totalKategori }}</h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
    <div class="lg:col-span-3 bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-slate-200 mb-4">Aktivitas Bulanan (6 Bulan Terakhir)</h3>
        <div class="chart-container">
            <canvas id="monthlyActivityChart"></canvas>
        </div>
    </div>
    <div class="lg:col-span-2 bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-slate-200 mb-4">Barang per Kategori</h3>
        <div class="chart-container">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-slate-200">Peminjaman Terbaru</h3>
            <a href="{{ route('peminjaman.index') }}" class="text-sm text-indigo-400 hover:underline font-medium">Lihat Semua</a>
        </div>
        @include('dashboard._table_peminjaman', ['items' => $recentPeminjaman])
    </div>
    <div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-slate-200">Peminjaman Akan Jatuh Tempo</h3>
            <a href="{{ route('peminjaman.index') }}" class="text-sm text-indigo-400 hover:underline font-medium">Lihat Semua</a>
        </div>
        @include('dashboard._table_jatuh_tempo', ['items' => $expiringLoans])
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-slate-200 mb-4">User Terbaru</h3>
        @include('dashboard._table_user_terbaru', ['users' => $latestUsers])
    </div>
    <div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-slate-200 mb-4">Barang Terpopuler</h3>
        @include('dashboard._table_barang_populer', ['items' => $topBarang])
    </div>
    <div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-slate-200 mb-4">Stok Menipis (&lt;5)</h3>
        @include('dashboard._table_stok_menipis', ['items' => $lowStockItems])
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.color = '#94a3b8'; // slate-400 for dark mode

    // Monthly Activity Chart
    const monthlyCtx = document.getElementById('monthlyActivityChart')?.getContext('2d');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
                datasets: [{
                    label: 'Peminjaman',
                    data: {!! json_encode(array_column($monthlyData, 'peminjaman')) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.7)', // indigo-500
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1, borderRadius: 4,
                }, {
                    label: 'Pengembalian',
                    data: {!! json_encode(array_column($monthlyData, 'pengembalian')) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.7)', // green-500
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1, borderRadius: 4,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0, color: '#94a3b8' }, grid: { color: 'rgba(255, 255, 255, 0.1)' } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                },
                plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8' } } }
            }
        });
    }

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart')?.getContext('2d');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($kategoriBarchart->pluck('nama_kategori')->toArray()) !!},
                datasets: [{
                    label: 'Jumlah Barang',
                    data: {!! json_encode($kategoriBarchart->pluck('barang_count')->toArray()) !!},
                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6'],
                    borderColor: '#1f2937', // slate-800
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'right', labels: { color: '#94a3b8' } } }
            }
        });
    }
});
</script>
@endpush