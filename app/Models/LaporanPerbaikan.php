<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPerbaikan extends Model
{
    protected $table = 'laporan_perbaikan';
    protected $primaryKey = 'id_perbaikan';

    protected $fillable = [
        'id_laporan',
        'id_dispatcher',
        'id_teknisi',
        'tanggal_validasi',
        'prioritas',
        'catatan',
        'tanggal_mulai_estimasi',
        'tanggal_selesai_estimasi',
        'deskripsi_pekerjaan_teknisi',
        'tanggal_selesai_aktual',
        'status_perbaikan'
    ];

    /* ================= RELASI ================= */

    public function laporanKerusakan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan');
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'id_dispatcher', 'id_user');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'id_teknisi', 'id_user');
    }
}
