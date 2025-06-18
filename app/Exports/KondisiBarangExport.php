<?php
namespace App\Exports;

use App\Models\Barang;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class KondisiBarangExport implements FromView, WithTitle
{
    public function view(): View
    {
        return view('laporan.cetak.kondisi_barang', [
            'data' => Barang::with('kategori')->get()
        ]);
    }

    public function title(): string
    {
        return 'Laporan Kondisi Barang';
    }
}
?>