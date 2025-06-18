<?php
namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class PeminjamanBulananExport implements FromView, WithTitle
{
    protected $bulan;
    protected $tahun;

    public function __construct(int $bulan, int $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $data = Peminjaman::with(['user', 'barang'])
            ->whereYear('tanggal_pinjam', $this->tahun)
            ->whereMonth('tanggal_pinjam', $this->bulan)
            ->get();
        
        return view('laporan.cetak.peminjaman_bulanan', [
            'data' => $data,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }

    public function title(): string
    {
        return 'Peminjaman Bulan ' . $this->bulan . ' ' . $this->tahun;
    }
}
?>