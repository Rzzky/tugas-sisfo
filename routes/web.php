<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\RequestPeminjamanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestPengembalianController;


// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class)->middleware('admin');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Barang Routes
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id_barang}', [BarangController::class, 'show'])->name('barang.show');
    Route::get('/barang/{id_barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id_barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id_barang}', [BarangController::class, 'destroy'])->name('barang.destroy');


    // Kategori Routes
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{kategori}', [KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Peminjaman Routes
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::put('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])
     ->name('peminjaman.kembalikan');

    // Route::get('/admin/peminjaman/requests', [RequestPeminjamanController::class, 'index']);
    // Route::post('/admin/peminjaman/{id}/approve', [RequestPeminjamanController::class, 'approve']);
    // Route::post('/admin/peminjaman/{id}/reject', [RequestPeminjamanController::class, 'reject']);

// Route for peminjaman requests
Route::get('/request-peminjaman', [RequestPeminjamanController::class, 'index'])->name('request.peminjaman.index');
Route::post('/request-peminjaman/{id}/approve', [RequestPeminjamanController::class, 'approve'])->name('request.peminjaman.approve');
Route::post('/request-peminjaman/{id}/reject', [RequestPeminjamanController::class, 'reject'])->name('request.peminjaman.reject');

// Route for pengembalian requests
Route::get('/request-pengembalian', [RequestPengembalianController::class, 'index'])->name('request.pengembalian.index');
Route::post('/request-pengembalian/{id}/approve', [RequestPengembalianController::class, 'approve'])->name('request.pengembalian.approve');
Route::post('/request-pengembalian/{id}/reject', [RequestPengembalianController::class, 'reject'])->name('request.pengembalian.reject');




    // Pengembalian Routes
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/create', [PengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('/pengembalian', [PengembalianController::class, 'store'])->name('pengembalian.store');
    Route::get('/pengembalian/{pengembalian}', [PengembalianController::class, 'show'])->name('pengembalian.show');
    Route::get('/pengembalian/{pengembalian}/edit', [PengembalianController::class, 'edit'])->name('pengembalian.edit');
    Route::put('/pengembalian/{pengembalian}', [PengembalianController::class, 'update'])->name('pengembalian.update');
    Route::delete('/pengembalian/{pengembalian}', [PengembalianController::class, 'destroy'])->name('pengembalian.destroy');
    // Laporan Routes
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/cetak', [App\Http\Controllers\LaporanController::class, 'cetak'])->name('laporan.cetak');
});

