<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\Validator;
use App\Events\PeminjamanDiajukan;
use App\Notifications\PeminjamanNotification;

class PeminjamanController extends Controller
{
    // Get all peminjaman with user and barang
    public function index(Request $request)
    {
        $peminjaman = Peminjaman::with(['user', 'barang']);

        // Filter periode
        if ($request->has('periode') && $request->periode !== 'all') {
            switch ($request->periode) {
                case 'minggu':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    break;
                case 'bulan':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
                case 'tahun':
                    $start = Carbon::now()->startOfYear();
                    $end = Carbon::now()->endOfYear();
                    break;
                default:
                    $start = null;
                    $end = null;
            }

            if ($start && $end) {
                $peminjaman->whereBetween('tanggal_pinjam', [$start, $end]);
            }
        }

        $peminjaman = $peminjaman->get();

        return view('peminjaman.index', compact('peminjaman'));
    }


    // Create new peminjaman
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'id_barang' => 'required|exists:barang,id_barang',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'label_status' => 'sometimes|in:selesai,menunggu,penting'
        ]);

        return DB::transaction(function () use ($request) {
            $barang = Barang::find($request->id_barang);

            // Check barang availability
            if ($barang->status !== 'tersedia' || $barang->jumlah < $request->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang tidak tersedia atau stok tidak mencukupi'
                ], 400);
            }

            $peminjaman = Peminjaman::create([
                'id_user' => $request->id_user,
                'id_barang' => $request->id_barang,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'status' => 'dipinjam',
                'label_status' => $request->label_status ?? 'menunggu'
            ]);

            // Update barang stock
            $barang->decrement('jumlah', $request->jumlah);
            if ($barang->jumlah === 0) {
                $barang->update(['status' => 'tidak tersedia']);
            }

            return response()->json([
                'success' => true,
                'data' => $peminjaman->load(['user', 'barang'])
            ], 201);
        });
    }

    // Get single peminjaman
    public function show($id)
{
    $peminjaman = Peminjaman::with(['user', 'barang.kategori'])->findOrFail($id);
    return view('peminjaman.show', compact('peminjaman'));
}

    // Update peminjaman
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman not found'
            ], 404);
        }

        $request->validate([
            'id_user' => 'sometimes|exists:users,id_user',
            'id_barang' => 'sometimes|exists:barang,id_barang',
            'jumlah' => 'sometimes|integer|min:1',
            'tanggal_pinjam' => 'sometimes|date',
            'status' => 'sometimes|in:dipinjam,dikembalikan',
            'label_status' => 'sometimes|in:selesai,menunggu,penting'
        ]);

        $peminjaman->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $peminjaman->load(['user', 'barang'])
        ]);
    }

    // Delete peminjaman
    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman not found'
            ], 404);
        }

        // Return the items if still borrowed
        if ($peminjaman->status === 'dipinjam') {
            $barang = $peminjaman->barang;
            $barang->increment('jumlah', $peminjaman->jumlah);

            if ($barang->jumlah > 0 && $barang->status === 'tidak tersedia') {
                $barang->update(['status' => 'tersedia']);
            }
        }

        $peminjaman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman deleted successfully'
        ]);
    }

    public function kembalikan($id)
{
    $peminjaman = Peminjaman::findOrFail($id);

    if ($peminjaman->status === 'dipinjam') {
        DB::transaction(function () use ($peminjaman) {
            // Membuat request pengembalian
            Pengembalian::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'tanggal_kembali' => now(),
                'label_status' => 'menunggu'
            ]);

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'menunggu_pengembalian',
                'label_status' => 'menunggu'
            ]);
        });

        return redirect()->route('peminjaman.index')
               ->with('success', 'Permintaan pengembalian berhasil diajukan. Menunggu persetujuan admin.');
    }

    return redirect()->back()->with('error', 'Barang tidak dapat dikembalikan.');
}
    public function approve($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);
        $barang = $peminjaman->barang;

        if ($barang->jumlah < $peminjaman->jumlah) {
            return back()->with('error', 'Stok barang tidak mencukupi');
        }

        DB::transaction(function () use ($peminjaman, $barang) {
            $peminjaman->update(['status' => 'dipinjam']);

            $barang->decrement('jumlah', $peminjaman->jumlah);
            $barang->decrement('tersedia', $peminjaman->jumlah);

            if ($barang->jumlah <= 0) {
                $barang->update(['status' => 'tidak tersedia']);
            }
        });

        return back()->with('success', 'Peminjaman telah disetujui.');
    }

    public function reject($id)
{
    $peminjaman = Peminjaman::findOrFail($id);

    if ($peminjaman->status !== 'menunggu') {
        return back()->with('error', 'Peminjaman ini sudah diproses.');
    }

    $peminjaman->update([
        'status' => 'ditolak',
        'label_status' => 'selesai'
    ]);

    return back()->with('success', 'Peminjaman ditolak.');
}

public function requestList()
{
    $peminjaman = Peminjaman::with('user', 'barang')
    ->where('label_status', 'menunggu')
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

    return view('peminjaman.request', compact('peminjaman'));
}


    // List Peminjaman User Mobile
    public function mobileList(Request $request)
{
    $user = $request->user();
    $peminjaman = Peminjaman::with(['barang.kategori'])
        ->where('id_user', $user->id_user)
        ->whereIn('status', ['dipinjam', 'dikembalikan'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($item) {
            return [
                'id_peminjaman' => $item->id_peminjaman,
                'id_user' => $item->id_user,
                'id_barang' => $item->id_barang,
                'jumlah' => $item->jumlah,
                'tanggal_pinjam' => $item->tanggal_pinjam,
                'tanggal_kembali' => $item->tanggal_kembali,
                'status' => $item->status,
                'label_status' => $item->label_status,
                'keterangan' => $item->keterangan,
                'barang' => [
                    'nama_barang' => $item->barang->nama_barang,
                    'kode_barang' => $item->barang->kode_barang,
                    'gambar' => $item->barang->gambar ? asset('storage/' . $item->barang->gambar) : null
                ]
            ];
        });

    // Ambil semua barang yang telah dipinjam oleh user lain
    $barangDipinjamLainnya = Peminjaman::with(['barang.kategori'])
        ->where('id_user', '!=', $user->id_user)
        ->where('status', 'dipinjam')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($item) {
            return [
                'id_peminjaman' => $item->id_peminjaman,
                'id_user' => $item->id_user,
                'id_barang' => $item->id_barang,
                'jumlah' => $item->jumlah,
                'tanggal_pinjam' => $item->tanggal_pinjam,
                'tanggal_kembali' => $item->tanggal_kembali,
                'status' => $item->status,
                'label_status' => $item->label_status,
                'keterangan' => $item->keterangan,
                'barang' => [
                    'nama_barang' => $item->barang->nama_barang,
                    'kode_barang' => $item->barang->kode_barang,
                    'gambar' => $item->barang->gambar ? asset('storage/' . $item->barang->gambar) : null
                ]
            ];
        });

    return response()->json([
        'success' => true,
        'data' => [
            'user' => $peminjaman->toArray(),
            'lainnya' => $barangDipinjamLainnya->toArray()
        ]
    ]);
}

    // Buat Peminjaman Mobile
    public function mobileStore(Request $request)
{
    $user = $request->user();

    $validator = Validator::make($request->all(), [
        'id_barang' => 'required|exists:barang,id_barang',
        'jumlah' => 'required|integer|min:1',
        'tanggal_pinjam' => 'required|date|after_or_equal:today',
        'keterangan' => 'nullable|string|max:255'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();
    try {
        $peminjaman = Peminjaman::create([
            'id_user' => $user->id_user,  // <-- perbaikan di sini
            'id_barang' => $request->id_barang,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'keterangan' => $request->keterangan,
            'status' => 'dipinjam',
            'label_status' => 'menunggu'
        ]);

        // sementara comment event dulu jika error masih terjadi
        // event(new PeminjamanDiajukan($peminjaman));

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Permintaan peminjaman berhasil diajukan. Menunggu persetujuan admin.',
            'data' => $peminjaman
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengajukan peminjaman: ' . $e->getMessage()
        ], 500);
    }
}
public function riwayat(Request $request)
    {
        $riwayatPeminjaman = Peminjaman::with(['user', 'barang']);

        // Filter periode
        if ($request->has('periode') && $request->periode !== 'all') {
            switch ($request->periode) {
                case 'minggu':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    break;
                case 'bulan':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
                case 'tahun':
                    $start = Carbon::now()->startOfYear();
                    $end = Carbon::now()->endOfYear();
                    break;
                default:
                    $start = null;
                    $end = null;
            }
            if ($start && $end) {
                $riwayatPeminjaman->whereBetween('tanggal_pinjam', [$start, $end]);
            }
        }

        // Filter status
        if ($request->has('status') && $request->status !== 'all') {
            $riwayatPeminjaman->where('status', $request->status);
        }

        $riwayatPeminjaman = $riwayatPeminjaman->get();
        return response()->json([
            'success' => true,
            'data' => $riwayatPeminjaman
        ]);
    }
}
