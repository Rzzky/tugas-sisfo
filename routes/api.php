<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RequestPeminjamanController;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes
    // Auth Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Barang Routes
    Route::get('/barang', [BarangController::class, 'apiList']);
    Route::get('/barang/{id}', [BarangController::class, 'show']);
    Route::get('/barang/kategori/{kategori_id}', [BarangController::class, 'getByKategori']);
    Route::get('/barang/search/{keyword}', [BarangController::class, 'search']);

    // Peminjaman Routes
    Route::get('/peminjaman', [PeminjamanController::class, 'index']);
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show']);
    Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update']);
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy']);
    Route::post('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan']);
    Route::get('/peminjaman/user/{user_id}', [PeminjamanController::class, 'getUserPeminjaman']);
    Route::post('/peminjaman/request', [PeminjamanController::class, 'requestPeminjaman']);
    Route::put('/peminjaman/{id}/status', [PeminjamanController::class, 'updateStatus']);

    // Kategori Routes
    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::get('/kategori/{id}', [KategoriController::class, 'show']);

    // Pengembalian Routes
    Route::get('/pengembalian', [PengembalianController::class, 'index']);
    Route::get('/pengembalian/{id}', [PengembalianController::class, 'show']);
    Route::post('/pengembalian', [PengembalianController::class, 'store']);
    Route::get('/pengembalian/user/{user_id}', [PengembalianController::class, 'getUserPengembalian']);

    // Dashboard Routes
    Route::get('/dashboard/stats', [PeminjamanController::class, 'getDashboardStats']);
    Route::get('/dashboard/recent-activities', [PeminjamanController::class, 'getRecentActivities']);

    Route::prefix('mobile')->group(function () {
        // Auth
        Route::post('/login', [AuthController::class, 'mobileLogin']);
        Route::post('/register', [AuthController::class, 'mobileRegister']);
        Route::post('/logout', [AuthController::class, 'mobileLogout'])->middleware('auth:sanctum');

        // Barang
        Route::get('/barang', [BarangController::class, 'mobileList'])->middleware('auth:sanctum');

        // Peminjaman
        Route::get('/peminjaman/mobile-list', [PeminjamanController::class, 'mobileList'])->middleware('auth:sanctum');
        Route::post('/peminjaman', [PeminjamanController::class, 'mobileStore'])->middleware('auth:sanctum');
        Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->middleware('auth:sanctum');
        // Pengembalian
        Route::post('/pengembalian', [PengembalianController::class, 'mobileStore'])
         ->middleware('auth:sanctum');

        Route::get('/barang-dipinjam', [PengembalianController::class, 'barangDipinjam'])
         ->middleware('auth:sanctum');
});

    Route::middleware(['auth', 'admin'])->group(function() {
        Route::get('peminjaman/requests', [RequestPeminjamanController::class, 'index']);
        Route::post('peminjaman/{id}/approve', [RequestPeminjamanController::class, 'approve']);
        Route::post('peminjaman/{id}/reject', [RequestPeminjamanController::class, 'reject']);
    });
