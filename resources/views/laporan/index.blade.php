@extends('layout.app')

@section('title', 'Laporan')

@section('content')
<div class="space-y-8">
    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-slate-800 rounded-xl p-6 flex items-center border border-slate-700">
            <div class="p-4 bg-indigo-500/20 text-indigo-400 rounded-full mr-4"><i class="fas fa-box fa-2x"></i></div>
            <div>
                <p class="text-sm text-slate-400">Total Aset Barang</p>
                <h3 class="text-3xl font-bold text-slate-100">{{ $totalBarang }}</h3>
            </div>
        </div>
        <div class="bg-slate-800 rounded-xl p-6 flex items-center border border-slate-700">
            <div class="p-4 bg-green-500/20 text-green-400 rounded-full mr-4"><i class="fas fa-hand-holding-hand fa-2x"></i></div>
            <div>
                <p class="text-sm text-slate-400">Peminjaman Tahun {{ $tahunIni }}</p>
                <h3 class="text-3xl font-bold text-slate-100">{{ $totalPeminjaman }}</h3>
            </div>
        </div>
        <div class="bg-slate-800 rounded-xl p-6 flex items-center border border-slate-700">
            <div class="p-4 bg-blue-500/20 text-blue-400 rounded-full mr-4"><i class="fas fa-undo-alt fa-2x"></i></div>
            <div>
                <p class="text-sm text-slate-400">Pengembalian Tahun {{ $tahunIni }}</p>
                <h3 class="text-3xl font-bold text-slate-100">{{ $totalPengembalian }}</h3>
            </div>
        </div>
    </div>

    <!-- Bagian Cetak Laporan -->
    <div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <h3 class="text-lg font-semibold text-slate-200 mb-4">Buat Laporan</h3>
        <form action="{{ route('laporan.cetak') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @csrf
            <div class="md:col-span-2">
                <label class="block text-slate-400 text-sm font-medium mb-2" for="jenis_laporan">Jenis Laporan</label>
                <select id="jenis_laporan" name="jenis_laporan" class="w-full bg-slate-900 border border-slate-600 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <option value="">Pilih Jenis Laporan</option>
                    <option value="peminjaman_bulanan">Peminjaman Bulanan</option>
                    <option value="pengembalian_bulanan">Pengembalian Bulanan</option>
                    <option value="barang_terpopuler">Barang Terpopuler</option>
                    <option value="kondisi_barang">Kondisi Semua Barang</option>
                </select>
            </div>
            <div>
                <label class="block text-slate-400 text-sm font-medium mb-2" for="bulan">Bulan</label>
                <select id="bulan" name="bulan" class="w-full bg-slate-900 border border-slate-600 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-slate-400 text-sm font-medium mb-2" for="tahun">Tahun</label>
                <select id="tahun" name="tahun" class="w-full bg-slate-900 border border-slate-600 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @for($i = date('Y'); $i >= date('Y')-5; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" name="format" value="pdf" formtarget="_blank" class="w-full inline-flex justify-center items-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-file-pdf mr-2"></i> PDF
                </button>
                <button type="submit" name="format" value="excel" class="w-full inline-flex justify-center items-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-file-excel mr-2"></i> Excel
                </button>
            </div>
        </form>
    </div>

    <!-- Grafik-grafik -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-200 mb-4">Peminjaman vs Pengembalian {{ $tahunIni }}</h3>
            <div class="h-64"><canvas id="bulananChart"></canvas></div>
        </div>
        <div class="lg:col-span-2 bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-200 mb-4">Kondisi Barang</h3>
            <div class="h-64"><canvas id="kondisiBarangChart"></canvas></div>
        </div>
    </div>
</div>

@push('scripts')
{{-- ... (script untuk chart tetap sama, tapi dengan warna yang disesuaikan) ... --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.color = '#94a3b8';

    // ... (Script untuk menonaktifkan input tetap sama) ...
    document.getElementById('jenis_laporan').addEventListener('change', function() {
        const jenisLaporan = this.value;
        const bulanInput = document.getElementById('bulan');
        const tahunInput = document.getElementById('tahun');

        bulanInput.disabled = false;
        tahunInput.disabled = false;

        if (jenisLaporan === 'kondisi_barang') {
            bulanInput.disabled = true;
            tahunInput.disabled = true;
        } else if (jenisLaporan === 'barang_terpopuler') {
            bulanInput.disabled = true;
        }
    }).dispatchEvent(new Event('change'));

    // Grafik Kondisi
    new Chart(document.getElementById('kondisiBarangChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kondisiBarang->pluck('kondisi')) !!},
            datasets: [{
                data: {!! json_encode($kondisiBarang->pluck('jumlah')) !!},
                backgroundColor: ['rgba(34, 197, 94, 0.7)', 'rgba(234, 179, 8, 0.7)', 'rgba(239, 68, 68, 0.7)'],
                borderColor: '#1f2937',
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top', labels: {color: '#94a3b8'} } } }
    });

    // Grafik Bulanan
    new Chart(document.getElementById('bulananChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($dataPeminjaman)->pluck('bulan')) !!},
            datasets: [{
                label: 'Peminjaman',
                data: {!! json_encode(collect($dataPeminjaman)->pluck('jumlah')) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderRadius: 4,
            }, {
                label: 'Pengembalian',
                data: {!! json_encode(collect($dataPengembalian)->pluck('jumlah')) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                borderRadius: 4,
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0, color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.1)' } }, x: { ticks: { color: '#94a3b8' }, grid: { display: false } } }, plugins: { legend: { labels: { color: '#94a3b8' } } } }
    });
</script>
@endpush
@endsection
