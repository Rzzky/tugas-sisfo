<?php
namespace App\Exports;

use App\Models\Pengembalian;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class PengembalianBulananExport implements FromView, WithTitle
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
        $data = Pengembalian::with(['peminjaman.user', 'peminjaman.barang'])
            ->whereYear('tanggal_kembali', $this->tahun)
            ->whereMonth('tanggal_kembali', $this->bulan)
            ->get();
        
        return view('laporan.cetak.pengembalian_bulanan', [
            'data' => $data,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
    
    public function title(): string
    {
        return 'Pengembalian Bulan ' . $this->bulan . ' ' . $this->tahun;
    }
}
?>