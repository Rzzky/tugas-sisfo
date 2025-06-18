<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;
    protected $table = 'pengembalian';
    protected $primaryKey = 'id_pengembalian';

    protected $fillable = ['id_peminjaman', 'tanggal_kembali', 'keterangan', 'label_status'];

    public function peminjaman() {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function barang()
{
    return $this->hasOneThrough(
        Barang::class,
        Peminjaman::class,
        'id_peminjaman', // Foreign key on peminjaman table
        'id_barang',     // Foreign key on barang table
        'id_peminjaman', // Local key on pengembalian table
        'id_barang'      // Local key on peminjaman table
    );
}
}
