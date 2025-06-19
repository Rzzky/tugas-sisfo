<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Count summary data
        $totalBarang = Barang::count();
        $totalKategori = Kategori::count();
        $barangTersedia = Barang::sum('tersedia');
        $barangDipinjam = Barang::sum('dipinjam');

        // Get active loan count
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();

        // Get returned items count
        $pengembalian = Pengembalian::count();

        // Get top 5 most borrowed items
        $topBarang = Peminjaman::select('id_barang', DB::raw('COUNT(*) as total_peminjaman'))
            ->with('barang')
            ->groupBy('id_barang')
            ->orderBy('total_peminjaman', 'desc')
            ->limit(5)
            ->get();

        // Get recent loans (latest 5)
        $recentPeminjaman = Peminjaman::with(['user', 'barang'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get loans expiring soon (next 7 days)
        $today = Carbon::now()->format('Y-m-d');
        $weekLater = Carbon::now()->addDays(7)->format('Y-m-d');

        $expiringLoans = Peminjaman::with(['user', 'barang'])
            ->where('status', 'dipinjam')
            ->whereBetween('tanggal_kembali', [$today, $weekLater])
            ->orderBy('tanggal_kembali', 'asc')
            ->limit(5)
            ->get();

        // Items per category chart data
        $kategoriBarchart = Kategori::withCount('barang')
            ->orderBy('barang_count', 'desc')
            ->get();

        // Monthly activity chart data (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $year = $month->format('Y');
            $startDate = Carbon::create($year, $month->format('m'), 1)->format('Y-m-d');
            $endDate = Carbon::create($year, $month->format('m'), $month->daysInMonth)->format('Y-m-d');

            $peminjaman = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])->count();
            $pengembalian = Pengembalian::whereBetween('tanggal_kembali', [$startDate, $endDate])->count();

            $monthlyData[] = [
                'month' => $monthName,
                'peminjaman' => $peminjaman,
                'pengembalian' => $pengembalian
            ];
        }

        // Low stock items (less than 5 available)
        $lowStockItems = Barang::where('tersedia', '<', 5)
            ->where('status', 'tersedia')
            ->with('kategori')
            ->limit(5)
            ->get();

        // Check if user is admin
        $isAdmin = auth()->user()->role === 'admin';

        // If admin, get additional data
        $userCount = 0;
        $latestUsers = [];

        if ($isAdmin) {
            $userCount = User::count();
            $latestUsers = User::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard', compact(
            'totalBarang',
            'totalKategori',
            'barangTersedia',
            'barangDipinjam',
            'peminjamanAktif',
            'pengembalian',
            'topBarang',
            'recentPeminjaman',
            'expiringLoans',
            'kategoriBarchart',
            'monthlyData',
            'lowStockItems',
            'isAdmin',
            'userCount',
            'latestUsers'
        ));
    }
}
