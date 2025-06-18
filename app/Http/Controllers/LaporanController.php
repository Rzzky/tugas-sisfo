<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeminjamanBulananExport;
use App\Exports\PengembalianBulananExport;
use App\Exports\BarangTerpopulerExport;
use App\Exports\KondisiBarangExport;

class LaporanController extends Controller
{
    public function index()
    {
        $tahunIni = date('Y');
        $totalBarang = Barang::count();
        $barangTersedia = Barang::sum('tersedia');
        $barangDipinjam = Barang::sum('dipinjam');
        $totalPeminjaman = Peminjaman::whereYear('tanggal_pinjam', $tahunIni)->count();
        $totalPengembalian = Pengembalian::whereYear('tanggal_kembali', $tahunIni)->count();

        // Data Grafik
        $barangTerpopuler = Peminjaman::select('id_barang', DB::raw('COUNT(*) as jumlah_peminjaman'))
            ->with('barang')
            ->whereYear('tanggal_pinjam', $tahunIni)
            ->groupBy('id_barang')
            ->orderBy('jumlah_peminjaman', 'desc')
            ->limit(5)
            ->get();

        $kondisiBarang = Barang::select('kondisi', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kondisi')
            ->get();

        $dataPeminjaman = Peminjaman::select(
                DB::raw("DATE_FORMAT(tanggal_pinjam, '%b') as bulan"),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereYear('tanggal_pinjam', $tahunIni)
            ->groupBy('bulan')
            ->orderByRaw("MONTH(tanggal_pinjam)")
            ->get();

        $dataPengembalian = Pengembalian::select(
                DB::raw("DATE_FORMAT(tanggal_kembali, '%b') as bulan"),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereYear('tanggal_kembali', $tahunIni)
            ->groupBy('bulan')
            ->orderByRaw("MONTH(tanggal_kembali)")
            ->get();

        return view('laporan.index', compact(
            'totalBarang', 'barangTersedia', 'barangDipinjam', 'totalPeminjaman', 'totalPengembalian',
            'tahunIni', 'barangTerpopuler', 'kondisiBarang', 'dataPeminjaman', 'dataPengembalian'
        ));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|string',
            'format' => 'required|in:pdf,excel',
            'bulan' => 'nullable|integer|between:1,12',
            'tahun' => 'nullable|integer',
        ]);

        $jenis = $request->jenis_laporan;
        $format = $request->format;
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $namaFile = $jenis . '_' . $bulan . '_' . $tahun . '.xlsx';

        // Logika untuk Excel
        if ($format == 'excel') {
            switch ($jenis) {
                case 'peminjaman_bulanan':
                    return Excel::download(new PeminjamanBulananExport($bulan, $tahun), $namaFile);
                case 'pengembalian_bulanan':
                    return Excel::download(new PengembalianBulananExport($bulan, $tahun), $namaFile);
                case 'barang_terpopuler':
                    return Excel::download(new BarangTerpopulerExport($tahun), 'barang_terpopuler_'.$tahun.'.xlsx');
                case 'kondisi_barang':
                    return Excel::download(new KondisiBarangExport(), 'laporan_kondisi_barang.xlsx');
                default:
                    return back()->with('error', 'Jenis laporan tidak valid.');
            }
        }
        
        // Logika untuk PDF (yang sudah ada)
        $viewName = 'laporan.cetak.' . $jenis;
        $data = [];

        switch ($jenis) {
            case 'peminjaman_bulanan':
                $data = Peminjaman::with(['user', 'barang'])->whereYear('tanggal_pinjam', $tahun)->whereMonth('tanggal_pinjam', $bulan)->get();
                break;
            case 'pengembalian_bulanan':
                $data = Pengembalian::with(['peminjaman.user', 'peminjaman.barang'])->whereYear('tanggal_kembali', $tahun)->whereMonth('tanggal_kembali', $bulan)->get();
                break;
            case 'barang_terpopuler':
                 $data = Peminjaman::select('id_barang', DB::raw('COUNT(*) as jumlah_peminjaman'))
                    ->with('barang.kategori')
                    ->whereYear('tanggal_pinjam', $tahun)
                    ->groupBy('id_barang')
                    ->orderBy('jumlah_peminjaman', 'desc')->get();
                break;
            case 'kondisi_barang':
                $data = Barang::with('kategori')->get();
                break;
            default:
                return back()->with('error', 'Jenis laporan tidak valid.');
        }

        if (!view()->exists($viewName)) {
            return back()->with('error', 'Template cetak tidak ditemukan.');
        }
        
        return view($viewName, compact('data', 'bulan', 'tahun'));
    }
}
