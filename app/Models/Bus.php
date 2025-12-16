<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $table = 'bus'; 
    protected $primaryKey = 'id_bus';
    public $timestamps = true;

    protected $fillable = [
        'nama_bus'
    ];

    // ================= RELASI =================

    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_bus');
    }
}

