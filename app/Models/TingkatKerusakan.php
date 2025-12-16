<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TingkatKerusakan extends Model
{
    protected $table = 'tingkat_kerusakan';
    protected $primaryKey = 'id_tingkat';

    protected $fillable = [
        'nama_tingkat'
    ];

    /**
     * Relasi ke laporan kerusakan
     * 1 tingkat bisa dipakai banyak laporan
     */
    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_tingkat');
    }
}
