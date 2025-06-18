<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    // Get all pengembalian with peminjaman
    public function index(Request $request)
    {
        $query = Pengembalian::with('peminjaman.barang', 'peminjaman.user');

        switch ($request->periode) {
            case 'minggu':
                $query->whereBetween('tanggal_kembali', [
                    Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()
                ]);
                break;
            case 'bulan':
                $query->whereMonth('tanggal_kembali', Carbon::now()->month);
                break;
            case 'tahun':
                $query->whereYear('tanggal_kembali', Carbon::now()->year);
                break;
        }

        $pengembalian = $query->get();

        return view('pengembalian.index', compact('pengembalian'));
    }

    // Create new pengembalian (Request for approval)
    public function store(Request $request)
    {
        $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'tanggal_kembali' => 'required|date',
            'keterangan' => 'nullable',
            'label_status' => 'sometimes|in:selesai,menunggu,penting,ditolak'
        ]);

        return DB::transaction(function () use ($request) {
            $peminjaman = Peminjaman::with('barang')->find($request->id_peminjaman);

            // Check if already returned
            if ($peminjaman->status === 'dikembalikan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang sudah dikembalikan sebelumnya'
                ], 400);
            }

            // Create a request for return
            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $request->id_peminjaman,
                'tanggal_kembali' => $request->tanggal_kembali,
                'keterangan' => $request->keterangan,
                'label_status' => 'menunggu'  // Awaiting approval
            ]);

            return response()->json([
                'success' => true,
                'data' => $pengembalian
            ], 201);
        });
    }

    // Approve or Reject Pengembalian by Admin
    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $pengembalian = Pengembalian::findOrFail($id);
            $peminjaman = $pengembalian->peminjaman;

            // Check if it's already returned
            if ($peminjaman->status === 'dikembalikan') {
                return back()->with('error', 'Barang sudah dikembalikan sebelumnya');
            }

            // Update the pengembalian status to approved
            $pengembalian->update(['label_status' => 'selesai']);

            // Update peminjaman status to 'dikembalikan'
            $peminjaman->update(['status' => 'dikembalikan', 'label_status' => 'selesai']);

            // Update barang stock
            $barang = $peminjaman->barang;
            $barang->increment('tersedia', $peminjaman->jumlah);

            DB::commit();

            return back()->with('success', 'Pengembalian berhasil disetujui dan stok barang diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui pengembalian: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        DB::beginTransaction();
        try {
            $pengembalian = Pengembalian::findOrFail($id);
            $peminjaman = $pengembalian->peminjaman;

            // Update pengembalian status to rejected
            $pengembalian->update(['label_status' => 'ditolak']);

            // Keep peminjaman status as 'dipinjam' if rejected
            $peminjaman->update(['status' => 'dipinjam', 'label_status' => 'menunggu']);

            DB::commit();

            return back()->with('success', 'Pengembalian ditolak');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak pengembalian: ' . $e->getMessage());
        }
    }

    public function barangDipinjam(Request $request)
{
    $user = $request->user();

    $barangDipinjam = Peminjaman::with('barang')
        ->where('id_user', $user->id_user)
        ->where('status', 'dipinjam')
        ->get()
        ->map(function ($item) {
            return [
                'id_barang' => $item->id_barang,
                'nama_barang' => $item->barang->nama_barang,
                'jumlah' => $item->jumlah,
                'tersedia' => $item->barang->tersedia,
                'tanggal_pinjam' => Carbon::parse($item->tanggal_pinjam)->format('d-m-Y'),
                'tanggal_kembali' => Carbon::parse($item->tanggal_kembali)->format('d-m-Y'),
                'keterangan' => $item->keterangan,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $barangDipinjam,
    ]);
}


    // Delete pengembalian
    public function destroy($id)
    {
        $pengembalian = Pengembalian::find($id);

        if (!$pengembalian) {
            return response()->json([
                'success' => false,
                'message' => 'Pengembalian not found'
            ], 404);
        }

        // Revert the return status
        $peminjaman = $pengembalian->peminjaman;
        $peminjaman->update([
            'status' => 'dipinjam',
            'label_status' => 'menunggu'
        ]);

        // Revert barang stock
        $barang = $peminjaman->barang;
        $barang->decrement('jumlah', $peminjaman->jumlah);
        if ($barang->jumlah === 0) {
            $barang->update(['status' => 'tidak tersedia']);
        }

        $pengembalian->delete();

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil dihapus');
    }

    // Request Pengembalian Mobile
    public function mobileStore(Request $request)
{
    $user = $request->user();

    $validator = Validator::make($request->all(), [
        'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
        'tanggal_kembali' => 'required|date',
        'keterangan' => 'nullable|string'
    ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $peminjaman = Peminjaman::where('id_peminjaman', $request->id_peminjaman)
            ->where('id_user', $user->id_user)
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        if ($peminjaman->status === 'dikembalikan') {
            return response()->json([
                'success' => false,
                'message' => 'Barang sudah dikembalikan sebelumnya'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Update status peminjaman
            $peminjaman->update(['status' => 'menunggu_pengembalian', 'label_status' => 'menunggu']);

            // Catat pengembalian
            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'tanggal_kembali' => $request->tanggal_kembali,
                'keterangan' => $request->keterangan ?? '',
                'label_status' => 'menunggu'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengembalian berhasil dicatat',
                'data' => $pengembalian
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pengembalian: ' . $e->getMessage()
            ], 500);
        }
    }
}
