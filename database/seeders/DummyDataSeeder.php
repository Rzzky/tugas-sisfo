<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus gambar dummy jika ada
        Storage::disk('public')->deleteDirectory('barang-images');

        // Buat beberapa kategori
        $kategoriElektronik = Kategori::create([
            'nama_kategori' => 'Elektronik',
            'deksripsi' => 'Barang-barang elektronik'
        ]);

        $kategoriAlatTulis = Kategori::create([
            'nama_kategori' => 'Alat Tulis',
            'deksripsi' => 'Barang-barang alat tulis'
        ]);

        $kategoriFurniture = Kategori::create([
            'nama_kategori' => 'Furniture',
            'deksripsi' => 'Barang-barang mebel dan furniture'
        ]);

        // Buat user admin dan pengguna biasa
        $admin = User::create([
            'username' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $user = User::create([
            'username' => 'kijok',
            'email' => 'kijok@gmail.com',
            'password' => Hash::make(value: 'kijok123'),
            'role' => 'user',
        ]);

        // Buat barang dengan field lengkap
        $barang1 = Barang::create([
            'kode_barang' => 'BRG001',
            'nama_barang' => 'Laptop ASUS ROG',
            'id_kategori' => $kategoriElektronik->id_kategori,
            'jumlah' => 10,
            'tersedia' => 8,
            'dipinjam' => 2,
            'kondisi' => 'Baik',
            'lokasi' => 'Lab Komputer A1',
            'status' => 'tersedia',
            'keterangan' => 'Laptop gaming untuk desain grafis',

        ]);

        $barang2 = Barang::create([
            'kode_barang' => 'BRG002',
            'nama_barang' => 'Printer Canon MX497',
            'id_kategori' => $kategoriElektronik->id_kategori,
            'jumlah' => 5,
            'tersedia' => 0,
            'dipinjam' => 5,
            'kondisi' => 'Rusak Ringan',
            'lokasi' => 'Gudang Peralatan',
            'status' => 'tidak tersedia',
            'keterangan' => 'Printer warna, perlu penggantian tinta',

        ]);

        $barang3 = Barang::create([
            'kode_barang' => 'BRG003',
            'nama_barang' => 'Meja Kerja Kayu',
            'id_kategori' => $kategoriFurniture->id_kategori,
            'jumlah' => 8,
            'tersedia' => 7,
            'dipinjam' => 1,
            'kondisi' => 'Baik',
            'lokasi' => 'Lab Multimedia',
            'status' => 'tersedia',
            'keterangan' => 'Meja kerja dengan laci penyimpanan',

       ]);

        $barang4 = Barang::create([
            'kode_barang' => 'BRG004',
            'nama_barang' => 'Pulpen Pilot',
            'id_kategori' => $kategoriAlatTulis->id_kategori,
            'jumlah' => 50,
            'tersedia' => 50,
            'dipinjam' => 0,
            'kondisi' => 'Baik',
            'lokasi' => 'Gudang Alat Tulis',
            'status' => 'tersedia',
            'keterangan' => 'Pulpen tinta hitam, 0.5mm',

        ]);

        // Buat peminjaman
        // Buat peminjaman - pastikan id_user dan id_barang valid
$peminjaman1 = Peminjaman::create([
    'id_user' => '2', // gunakan ID user yang sudah dibuat
    'id_barang' => $barang1->id_barang, // gunakan ID barang yang valid
    'jumlah' => 1,
    'status' => 'menunggu',
    'tanggal_pinjam' => now()->subDays(3),
    'tanggal_kembali' => now()->addDays(7),
    'label_status' => 'Menunggu',
    'keterangan' => 'Untuk keperluan proyek desain'
]);

$peminjaman2 = Peminjaman::create([
    'id_user' => '2', // gunakan ID user yang sama atau buat user baru
    'id_barang' => $barang3->id_barang,
    'jumlah' => 2,
    'status' => 'diKembalikan',
    'tanggal_pinjam' => now()->subDays(10),
    'tanggal_kembali' => now()->subDays(2),
    'label_status' => 'Selesai',
    'keterangan' => 'Untuk acara seminar'
]);

        // Buat pengembalian
        Pengembalian::create([
            'id_peminjaman' => '1',
            'tanggal_kembali' => now()->subDays(2),
            'keterangan' => 'Baik, tidak ada kerusakan',
            'label_status' => 'Selesai'
        ]);
    }

    /**
     * Generate dummy image path (simulasi upload gambar)
     */
    private function generateDummyImage($filename)
    {
        $sourcePath = public_path('dummy-images/' . $filename);
        $destPath = 'barang-images/' . $filename;

        if (file_exists($sourcePath)) {
            Storage::disk('public')->put($destPath, file_get_contents($sourcePath));
            return $destPath;
        }

        return null;
    }
}
