@extends('layouts.app')

@section('title', 'Laporan')

@if (!Auth::check())
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif

@auth
    @if (Auth::user()->level !== 'admin')
        <script>
            window.location.href = "{{ route('login') }}";
        </script>
    @endif
@endauth

@section('content')
<div class="container-fluid px-6 py-6">
    <h1 class="text-white text-3xl font-semibold mb-1">Laporan</h1>
    <p class="text-gray-400 mb-8">Lihat dan unduh laporan sarana prasarana</p>

    <!-- Period Selection -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-white font-medium">Pilih Periode Laporan</h2>
        </div>
        <div>
            <form method="GET" action="{{ route('laporan') }}">
                <div class="relative">
                    <select name="periode" onchange="this.form.submit()"
                            class="bg-black border border-gray-700/50 text-gray-300 py-2 px-4 pr-8 rounded-lg leading-tight focus:outline-none focus:border-blue-500">
                        <option value="all" {{ $periode === 'all' ? 'selected' : '' }}>Semua Periode</option>
                        <option value="minggu" {{ $periode === 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan" {{ $periode === 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="tahun" {{ $periode === 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="mb-6">
        <div class="flex border-b border-gray-700">
            <button onclick="showTab('stok')"
                    class="tab-button px-4 py-2 text-white font-medium border-b-2 border-blue-500">
                Stok Barang
            </button>
            <button onclick="showTab('peminjaman')"
                    class="tab-button px-4 py-2 text-gray-400 font-medium border-b-2 border-transparent hover:text-white">
                Peminjaman
            </button>
            <button onclick="showTab('pengembalian')"
                    class="tab-button px-4 py-2 text-gray-400 font-medium border-b-2 border-transparent hover:text-white">
                Pengembalian
            </button>
        </div>
    </div>

    <!-- Tab Contents -->
    <div class="space-y-8">
        <!-- Stok Barang Tab -->
        <div id="stok-tab" class="tab-content">
            <div class="rounded-lg p-5 border border-gray-700/50 mb-6">
                <h2 class="text-white font-medium mb-4">Stok Barang</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Tren Stok Barang -->
                    <div>
                        <h3 class="text-white font-medium mb-2">Tren Stok Barang</h3>
                        <p class="text-gray-400 text-sm mb-4">Perkembangan stok barang dalam 6 bulan terakhir</p>
                        <div class="chart-container h-64">
                            <canvas id="trenStokChart"></canvas>
                        </div>
                    </div>

                    <!-- Distribusi Kategori -->
                    <div>
                        <h3 class="text-white font-medium mb-2">Distribusi Kategori</h3>
                        <p class="text-gray-400 text-sm mb-4">Persentase barang berdasarkan kategori</p>
                        <div class="flex justify-center items-center h-64">
                            <div class="w-64 h-64">
                                <canvas id="kategoriChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laporan Stok Barang -->
                <h3 class="text-white font-medium mb-4">Laporan Stok Barang</h3>
                <p class="text-gray-400 text-sm mb-4">Ringkasan stok barang berdasarkan kategori</p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Total Barang</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tersedia</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Dipinjam</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Persentase Tersedia</th>
                            </tr>
                        </thead>
                        <tbody class=" divide-y divide-gray-700">
                            @foreach($stokBarang['detail'] as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">STK{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['kategori'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['total_barang'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['tersedia'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['dipinjam'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['persentase_tersedia'] }}%</td>
                            </tr>
                            @endforeach
                            <tr class="">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $stokBarang['total']['total_barang'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $stokBarang['total']['tersedia'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $stokBarang['total']['dipinjam'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $stokBarang['total']['persentase_tersedia'] }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Peminjaman Tab -->
        <div id="peminjaman-tab" class="tab-content hidden">
            <div class="rounded-lg p-5 border border-gray-700/50 mb-6">
                <h2 class="text-white font-medium mb-4">Laporan Peminjaman</h2>

                <!-- Tren Peminjaman -->
                <div class="mb-8">
                    <h3 class="text-white font-medium mb-2">Tren Peminjaman</h3>
                    <p class="text-gray-400 text-sm mb-4">Jumlah peminjaman per bulan pada {{ $dateRange['label'] }}</p>
                    <div class="chart-container h-64">
                        <canvas id="peminjamanChart"></canvas>
                    </div>
                </div>

                <!-- Ringkasan Peminjaman Terakhir -->
                <h3 class="text-white font-medium mb-4">Peminjaman Terakhir</h3>
                <p class="text-gray-400 text-sm mb-4">10 transaksi peminjaman terakhir</p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Barang</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Jumlah</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tanggal Pinjam</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class=" divide-y divide-gray-700">
                            @forelse($ringkasanPeminjaman as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">PMJ-{{ $item['id'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['barang'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['jumlah'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['tanggal'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $item['status'] == 'Dikembalikan' ? 'bg-green-900 text-green-300' : 'bg-blue-900 text-blue-300' }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-400">
                                    Tidak ada data peminjaman untuk periode ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pengembalian Tab -->
        <div id="pengembalian-tab" class="tab-content hidden">
            <div class="rounded-lg p-5 border border-gray-700/50 mb-6">
                <h2 class="text-white font-medium mb-4">Laporan Pengembalian</h2>

                <!-- Status Pengembalian -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-white font-medium mb-2">Ketepatan Waktu</h3>
                        <p class="text-gray-400 text-sm mb-4">Persentase pengembalian tepat waktu</p>

                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-white">Tepat Waktu</span>
                                    <span class="text-sm font-medium text-white">{{ $statusPengembalian['tepat_waktu'] }}%</span>
                                </div>
                                <div class="w-full  rounded-full h-2.5">
                                    <div class="bg-green-500 h-2.5 rounded-full"
                                         style="width: {{ $statusPengembalian['tepat_waktu'] }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-white">Terlambat</span>
                                    <span class="text-sm font-medium text-white">{{ $statusPengembalian['terlambat'] }}%</span>
                                </div>
                                <div class="w-full  rounded-full h-2.5">
                                    <div class="bg-red-500 h-2.5 rounded-full"
                                         style="width: {{ $statusPengembalian['terlambat'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi Pengembalian -->
                    <div>
                        <h3 class="text-white font-medium mb-2">Kondisi Barang</h3>
                        <p class="text-gray-400 text-sm mb-4">Persentase kondisi barang saat dikembalikan</p>

                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-white">Baik</span>
                                    <span class="text-sm font-medium text-white">{{ $kondisiPengembalian['baik'] }}%</span>
                                </div>
                                <div class="w-full  rounded-full h-2.5">
                                    <div class="bg-green-500 h-2.5 rounded-full"
                                         style="width: {{ $kondisiPengembalian['baik'] }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-white">Rusak Ringan</span>
                                    <span class="text-sm font-medium text-white">{{ $kondisiPengembalian['rusak_ringan'] }}%</span>
                                </div>
                                <div class="w-full  rounded-full h-2.5">
                                    <div class="bg-yellow-500 h-2.5 rounded-full"
                                         style="width: {{ $kondisiPengembalian['rusak_ringan'] }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-white">Rusak Berat</span>
                                    <span class="text-sm font-medium text-white">{{ $kondisiPengembalian['rusak_berat'] }}%</span>
                                </div>
                                <div class="w-full  rounded-full h-2.5">
                                    <div class="bg-red-500 h-2.5 rounded-full"
                                         style="width: {{ $kondisiPengembalian['rusak_berat'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengembalian Terakhir -->
                <h3 class="text-white font-medium mb-4">Pengembalian Terakhir</h3>
                <p class="text-gray-400 text-sm mb-4">10 transaksi pengembalian terakhir</p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Barang</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Jumlah</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tanggal Kembali</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody class=" divide-y divide-gray-700">
                            @forelse($ringkasanPengembalian as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">PMB-{{ $item['id'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['barang'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['jumlah'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item['tanggal'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    <span class="px-2 py-1 rounded-full text-xs
                                        {{ $item['kondisi'] == 'Baik' ? 'bg-green-900 text-green-300' :
                                          ($item['kondisi'] == 'Rusak Ringan' ? 'bg-yellow-900 text-yellow-300' : 'bg-red-900 text-red-300') }}">
                                        {{ $item['kondisi'] }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-400">
                                    Tidak ada data pengembalian untuk periode ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-gray-500 text-sm">
        © {{ date('Y') }} Sisfo Sarpras. Hak Cipta Dilindungi.
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Tab functionality
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Show selected tab content
        document.getElementById(tabName + '-tab').classList.remove('hidden');

        // Update active tab button
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('text-white', 'border-blue-500');
            button.classList.add('text-gray-400', 'border-transparent');
        });

        event.currentTarget.classList.remove('text-gray-400', 'border-transparent');
        event.currentTarget.classList.add('text-white', 'border-blue-500');

        // Redraw charts when tab is shown
        if (tabName === 'peminjaman' && typeof peminjamanChart !== 'undefined') {
            peminjamanChart.update();
        }
        if (tabName === 'pengembalian' && typeof pengembalianChart !== 'undefined') {
            pengembalianChart.update();
        }
    }

    // Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Tren Stok Barang Chart
        const trenStokCtx = document.getElementById('trenStokChart').getContext('2d');
        new Chart(trenStokCtx, {
            type: 'line',
            data: {
                labels: @json($trenStok['labels']),
                datasets: [{
                    label: 'Total Stok',
                    data: @json($trenStok['data']),
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 0.9)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)'
                        },
                        ticks: {
                            color: '#9ca3af'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#9ca3af'
                        }
                    }
                }
            }
        });

        // Kategori Distribution Chart
        const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
        new Chart(kategoriCtx, {
            type: 'doughnut',
            data: {
                labels: @json($kategoriLabels),
                datasets: [{
                    data: @json($kategoriData),
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#6366F1', '#EC4899',
                        '#F97316', '#8B5CF6', '#14B8A6', '#F43F5E', '#0EA5E9'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#9ca3af',
                            boxWidth: 12,
                            padding: 16
                        }
                    }
                }
            }
        });

        // Peminjaman Chart
        const peminjamanCtx = document.getElementById('peminjamanChart').getContext('2d');
        const peminjamanChart = new Chart(peminjamanCtx, {
            type: 'bar',
            data: {
                labels: @json($peminjamanData['labels']),
                datasets: [{
                    label: 'Peminjaman',
                    data: @json($peminjamanData['data']),
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 0.9)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Peminjaman: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)'
                        },
                        ticks: {
                            color: '#9ca3af',
                            stepSize: 1,
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#9ca3af'
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Pengembalian Chart
        const pengembalianCtx = document.getElementById('pengembalianChart').getContext('2d');
        const pengembalianChart = new Chart(pengembalianCtx, {
            type: 'bar',
            data: {
                labels: @json(array_column($pengembalianData, 'day')),
                datasets: [{
                    label: 'Pengembalian',
                    data: @json(array_column($pengembalianData, 'count')),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(16, 185, 129, 0.9)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)'
                        },
                        ticks: {
                            color: '#9ca3af',
                            stepSize: 1,
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#9ca3af'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .chart-container {
        position: relative;
    }

    .tab-button {
        transition: all 0.2s ease;
    }

    .tab-button:hover {
        color: white !important;
    }

    /* Style untuk select box */
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 1em;
    }
</style>
@endsection
