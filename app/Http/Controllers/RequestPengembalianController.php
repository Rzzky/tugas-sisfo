<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestPengembalianController extends Controller
{
    public function index()
{
    $requests = Pengembalian::with(['peminjaman', 'peminjaman.barang', 'peminjaman.user'])
        ->where('label_status', 'menunggu')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('request.request_pengembalian', compact('requests'));
}

public function approve($id)
{
    DB::beginTransaction();
    try {
        $pengembalian = Pengembalian::findOrFail($id);
        $peminjaman = $pengembalian->peminjaman;

        // Validasi status
        if ($peminjaman->status !== 'menunggu_pengembalian') {
            throw new \Exception('Status peminjaman tidak valid');
        }

        // Update status pengembalian
        $pengembalian->update([
            'label_status' => 'selesai',
            'tanggal_kembali' => now()
        ]);

        // Update status peminjaman
        $peminjaman->update([
            'status' => 'dikembalikan',
            'label_status' => 'selesai',
            'tanggal_kembali' => now()
        ]);

        // Update stok barang
        $barang = $peminjaman->barang;
        $barang->increment('jumlah', $peminjaman->jumlah);
        $barang->increment('tersedia', $peminjaman->jumlah);

        // Update status barang
        if ($barang->status === 'tidak tersedia') {
            $barang->update(['status' => 'tersedia']);
        }

        DB::commit();

        return back()->with('success', 'Pengembalian disetujui dan stok diperbarui');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
    }
}

public function reject($id)
{
    DB::beginTransaction();
    try {
        $pengembalian = Pengembalian::findOrFail($id);
        $peminjaman = $pengembalian->peminjaman;

        // Update status
        $pengembalian->update(['label_status' => 'ditolak']);
        $peminjaman->update([
            'status' => 'dipinjam',
            'label_status' => 'selesai'
        ]);

        DB::commit();

        return back()->with('success', 'Pengembalian ditolak');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal menolak: ' . $e->getMessage());
    }
}
}
