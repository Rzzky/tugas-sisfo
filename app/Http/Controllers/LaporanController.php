<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // Data untuk laporan barang terpopuler
        $barangTerpopuler = Peminjaman::select('id_barang', DB::raw('COUNT(*) as jumlah_peminjaman'))
            ->with('barang')
            ->groupBy('id_barang')
            ->orderBy('jumlah_peminjaman', 'desc')
            ->limit(10)
            ->get();

        // Data untuk tingkat peminjaman per bulan dalam tahun ini
        $tahunIni = Carbon::now()->year;
        $dataPeminjaman = $this->getDataBulanan(Peminjaman::class, 'tanggal_pinjam', $tahunIni);

        // Data untuk tingkat pengembalian per bulan dalam tahun ini
        $dataPengembalian = $this->getDataBulanan(Pengembalian::class, 'tanggal_kembali', $tahunIni);

        // Data untuk kondisi keseluruhan barang
        $kondisiBarang = Barang::select('kondisi', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kondisi')
            ->get();

        // Data statistik keseluruhan
        $totalBarang = Barang::count();
        $totalPeminjaman = Peminjaman::whereYear('tanggal_pinjam', $tahunIni)->count();
        $totalPengembalian = Pengembalian::whereYear('tanggal_kembali', $tahunIni)->count();
        $barangDipinjam = Barang::sum('dipinjam');
        $barangTersedia = Barang::sum('tersedia');

        return view('laporan.index', compact(
            'barangTerpopuler',
            'dataPeminjaman',
            'dataPengembalian',
            'kondisiBarang',
            'totalBarang',
            'totalPeminjaman',
            'totalPengembalian',
            'barangDipinjam',
            'barangTersedia',
            'tahunIni'
        ));
    }

    private function getDataBulanan($model, $tanggalField, $tahun)
    {
        $data = [];
        $namaBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Inisialisasi data bulan dengan nilai 0
        foreach ($namaBulan as $index => $bulan) {
            $data[$index + 1] = [
                'bulan' => $bulan,
                'jumlah' => 0
            ];
        }

        // Ambil data dari database
        $results = $model::select(
            DB::raw('MONTH(' . $tanggalField . ') as bulan'),
            DB::raw('COUNT(*) as jumlah')
        )
        ->whereYear($tanggalField, $tahun)
        ->groupBy('bulan')
        ->get();

        // Update data bulan dengan nilai dari database
        foreach ($results as $result) {
            $data[$result->bulan]['jumlah'] = $result->jumlah;
        }

        return array_values($data);
    }

    public function cetak(Request $request)
    {
        $jenisLaporan = $request->jenis_laporan;
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        switch ($jenisLaporan) {
            case 'barang_terpopuler':
                $data = Peminjaman::select('id_barang', DB::raw('COUNT(*) as jumlah_peminjaman'))
                    ->with('barang')
                    ->groupBy('id_barang')
                    ->orderBy('jumlah_peminjaman', 'desc')
                    ->limit(10)
                    ->get();
                return view('laporan.cetak.barang_terpopuler', compact('data', 'bulan', 'tahun'));

            case 'peminjaman_bulanan':
                $data = Peminjaman::whereMonth('tanggal_pinjam', $bulan)
                    ->whereYear('tanggal_pinjam', $tahun)
                    ->with(['barang', 'user'])
                    ->get();
                return view('laporan.cetak.peminjaman_bulanan', compact('data', 'bulan', 'tahun'));

            case 'pengembalian_bulanan':
                $data = Pengembalian::whereMonth('tanggal_kembali', $bulan)
                    ->whereYear('tanggal_kembali', $tahun)
                    ->with(['peminjaman.barang', 'peminjaman.user'])
                    ->get();
                return view('laporan.cetak.pengembalian_bulanan', compact('data', 'bulan', 'tahun'));

            case 'kondisi_barang':
                $data = Barang::with('kategori')->get();
                return view('laporan.cetak.kondisi_barang', compact('data'));

            default:
                return redirect()->route('laporan.index')->with('error', 'Jenis laporan tidak valid');
        }
    }
}
