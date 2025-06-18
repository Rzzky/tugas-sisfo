<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestPeminjamanController extends Controller
{
    /**
     * Menampilkan daftar permintaan peminjaman yang masih menunggu.
     */
    public function index()
    {
        $requests = Peminjaman::with(['user', 'barang'])
            ->where('status', 'menunggu') // Filter berdasarkan status peminjaman
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('request.request_peminjaman', compact('requests'));
    }

    /**
     * Menyetujui permintaan peminjaman.
     */
    public function approve(Peminjaman $peminjaman)
    {
        // Gunakan transaction untuk memastikan semua query berhasil atau tidak sama sekali
        DB::beginTransaction();
        try {
            $barang = $peminjaman->barang;

            // Validasi stok sekali lagi sebelum proses
            if ($barang->tersedia < $peminjaman->jumlah) {
                DB::rollBack();
                return back()->with('error', 'Stok barang tidak mencukupi untuk disetujui.');
            }

            // PERBAIKAN: Update status peminjaman menjadi 'dipinjam'
            $peminjaman->status = 'dipinjam';
            $peminjaman->save();

            // Update stok barang: kurangi yang tersedia, tambah yang dipinjam
            $barang->tersedia -= $peminjaman->jumlah;
            $barang->dipinjam += $peminjaman->jumlah;
            $barang->save();

            DB::commit();

            return back()->with('success', 'Peminjaman disetujui dan stok barang telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Menolak permintaan peminjaman.
     */
    public function reject(Peminjaman $peminjaman)
    {
        // Cukup ubah statusnya menjadi ditolak
        $peminjaman->update(['status' => 'ditolak']);

        return back()->with('success', 'Peminjaman telah ditolak.');
    }
}
