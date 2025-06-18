<?
namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;

class BarangTerpopulerExport implements FromView, WithTitle
{
    protected $tahun;

    public function __construct(int $tahun)
    {
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $data = Peminjaman::select('id_barang', DB::raw('COUNT(*) as jumlah_peminjaman'))
            ->with('barang.kategori')
            ->whereYear('tanggal_pinjam', $this->tahun)
            ->groupBy('id_barang')
            ->orderBy('jumlah_peminjaman', 'desc')
            ->get();

        return view('laporan.cetak.barang_terpopuler', [
            'data' => $data,
            'tahun' => $this->tahun
        ]);
    }

    public function title(): string
    {
        return 'Barang Terpopuler Tahun ' . $this->tahun;
    }
}
?>