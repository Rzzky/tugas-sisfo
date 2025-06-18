<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengembalian;

class RequestPeminjamanController extends Controller
{
    public function index()
    {
        // Mengambil permintaan peminjaman dan pengembalian yang menunggu persetujuan
        $requests = Peminjaman::with(['user', 'barang'])
            ->where('label_status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $pengembalianRequests = Pengembalian::with('peminjaman.user', 'peminjaman.barang')
            ->where('label_status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('request.request_peminjaman', compact('requests'));
    }

    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('barang')->findOrFail($id);

            // Validasi stok
            if ($peminjaman->barang->tersedia < $peminjaman->jumlah) {
                return back()->with('error', 'Stok barang tidak mencukupi');
            }

            $peminjaman->update([
                'status' => 'dipinjam',
                'label_status' => 'selesai'
            ]);

            // Update stok barang
            $peminjaman->barang->decrement('tersedia', $peminjaman->jumlah);

            DB::commit();

            return back()->with('success', 'Peminjaman disetujui dan stok berkurang');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui peminjaman: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'ditolak',
            'label_status' => 'ditolak'
        ]);

        return back()->with('success', 'Peminjaman ditolak');
    }
}
