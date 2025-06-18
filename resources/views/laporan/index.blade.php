@extends('layout.app')

@section('title', 'Laporan')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
@endpush

@section('content')
<h2 class="text-2xl font-semibold text-gray-800 mb-6">Laporan Inventaris</h2>

<!-- Statistik Utama -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75 text-white mr-4">
                <i class="fas fa-box fa-2x"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Barang</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalBarang }}</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex justify-between text-sm">
                <span class="text-green-500">Tersedia: {{ $barangTersedia }}</span>
                <span class="text-red-500">Dipinjam: {{ $barangDipinjam }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-600 bg-opacity-75 text-white mr-4">
                <i class="fas fa-hand-holding fa-2x"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Peminjaman {{ $tahunIni }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalPeminjaman }}</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="text-sm text-gray-600">
                <i class="fas fa-chart-line"></i> Data tahun {{ $tahunIni }}
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-600 bg-opacity-75 text-white mr-4">
                <i class="fas fa-undo-alt fa-2x"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Pengembalian {{ $tahunIni }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalPengembalian }}</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="text-sm text-gray-600">
                <i class="fas fa-chart-line"></i> Data tahun {{ $tahunIni }}
            </div>
        </div>
    </div>
</div>

<!-- Bagian Cetak Laporan -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Cetak Laporan</h3>

    <form action="{{ route('laporan.cetak') }}" method="POST" target="_blank" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @csrf
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="jenis_laporan">Jenis Laporan</label>
            <select id="jenis_laporan" name="jenis_laporan" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Pilih Jenis Laporan</option>
                <option value="barang_terpopuler">Barang Terpopuler</option>
                <option value="peminjaman_bulanan">Peminjaman Bulanan</option>
                <option value="pengembalian_bulanan">Pengembalian Bulanan</option>
                <option value="kondisi_barang">Kondisi Barang</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="bulan">Bulan</label>
            <select id="bulan" name="bulan" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="tahun">Tahun</label>
            <select id="tahun" name="tahun" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @for($i = date('Y'); $i >= date('Y')-5; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <i class="fas fa-print mr-2"></i> Cetak Laporan
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Grafik Barang Terpopuler -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Barang Terpopuler</h3>
        <canvas id="barangTerpopulerChart" height="250"></canvas>
    </div>

    <!-- Grafik Kondisi Barang -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Kondisi Barang</h3>
        <canvas id="kondisiBarangChart" height="250"></canvas>
    </div>
</div>

<!-- Grafik Peminjaman dan Pengembalian Bulanan -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tingkat Peminjaman & Pengembalian {{ $tahunIni }}</h3>
    <canvas id="bulananChart" height="100"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    // Data untuk grafik
    const barangLabels = {!! json_encode($barangTerpopuler->pluck('barang.nama_barang')) !!};
    const barangData = {!! json_encode($barangTerpopuler->pluck('jumlah_peminjaman')) !!};

    const kondisiLabels = {!! json_encode($kondisiBarang->pluck('kondisi')) !!};
    const kondisiData = {!! json_encode($kondisiBarang->pluck('jumlah')) !!};

    const bulanLabels = {!! json_encode(collect($dataPeminjaman)->pluck('bulan')) !!};
    const peminjamanData = {!! json_encode(collect($dataPeminjaman)->pluck('jumlah')) !!};
    const pengembalianData = {!! json_encode(collect($dataPengembalian)->pluck('jumlah')) !!};

    // Grafik Barang Terpopuler
    const barangTerpopulerCtx = document.getElementById('barangTerpopulerChart').getContext('2d');
    new Chart(barangTerpopulerCtx, {
        type: 'bar',
        data: {
            labels: barangLabels,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: barangData,
                backgroundColor: 'rgba(67, 56, 202, 0.7)',
                borderColor: 'rgba(67, 56, 202, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Grafik Kondisi Barang
    const kondisiBarangCtx = document.getElementById('kondisiBarangChart').getContext('2d');
    new Chart(kondisiBarangCtx, {
        type: 'pie',
        data: {
            labels: kondisiLabels,
            datasets: [{
                data: kondisiData,
                backgroundColor: [
                    'rgba(34, 197, 94, 0.7)',
                    'rgba(234, 179, 8, 0.7)',
                    'rgba(239, 68, 68, 0.7)',
                    'rgba(107, 114, 128, 0.7)'
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(234, 179, 8, 1)',
                    'rgba(239, 68, 68, 1)',
                    'rgba(107, 114, 128, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Grafik Peminjaman dan Pengembalian Bulanan
    const bulananCtx = document.getElementById('bulananChart').getContext('2d');
    new Chart(bulananCtx, {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [
                {
                    label: 'Peminjaman',
                    data: peminjamanData,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.2,
                    fill: true
                },
                {
                    label: 'Pengembalian',
                    data: pengembalianData,
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    tension: 0.2,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Script untuk mengaktifkan/menonaktifkan input bulan/tahun berdasarkan jenis laporan
    document.getElementById('jenis_laporan').addEventListener('change', function() {
        const jenisLaporan = this.value;
        const bulanInput = document.getElementById('bulan');
        const tahunInput = document.getElementById('tahun');

        // Reset
        bulanInput.disabled = false;
        tahunInput.disabled = false;

        if (jenisLaporan === 'kondisi_barang' || jenisLaporan === 'barang_terpopuler') {
            bulanInput.disabled = true;
            if (jenisLaporan === 'kondisi_barang') {
                tahunInput.disabled = true;
            }
        }
    });
</script>
@endpush
@endsection
