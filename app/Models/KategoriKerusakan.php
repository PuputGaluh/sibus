<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKerusakan extends Model
{
    protected $table = 'kategori_kerusakan';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori'
    ];

    /**
     * Relasi ke laporan kerusakan
     * 1 kategori bisa dipakai banyak laporan
     */
    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_kategori');
    }
}
