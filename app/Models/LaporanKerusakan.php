<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    protected $table = 'laporan_kerusakan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_pelapor',
        'id_bus',
        'id_kategori',
        'id_tingkat',
        'status_keberangkatan',
        'keterangan',
        'tanggal_lapor',
        'foto',
        'status_proses'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'id_bus');
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'id_pelapor');
    }

    public function kategori()
    {
        return $this->belongsTo(
            KategoriKerusakan::class,
            'id_kategori',
        );
    }

    public function tingkat()
    {
        return $this->belongsTo(
            TingkatKerusakan::class,
            'id_tingkat',
        );
    }
}
