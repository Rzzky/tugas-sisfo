<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'id_kategori',
        'jumlah',
        'tersedia',
        'dipinjam',
        'kondisi',
        'lokasi',
        'status',
        'keterangan',
        'foto',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'tersedia' => 'integer',
        'dipinjam' => 'integer'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_barang', 'id_barang');
    }
}
