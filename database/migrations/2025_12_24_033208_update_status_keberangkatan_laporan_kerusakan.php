<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE laporan_kerusakan 
            MODIFY status_keberangkatan ENUM(
                'Pool',
                'Awal Dinas',
                'Perjalanan',
                'Akhir Dinas'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE laporan_kerusakan 
            MODIFY status_keberangkatan ENUM(
                'Pool',
                'Akan Berangkat',
                'Perjalanan'
            ) NOT NULL
        ");
    }
};
