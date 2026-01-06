<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laporan_perbaikan', function (Blueprint $table) {
            $table->string('gambar_perbaikan')->nullable()->after('deskripsi_pekerjaan_teknisi')->comment('Bukti foto perbaikan yang sudah selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_perbaikan', function (Blueprint $table) {
            $table->dropColumn('gambar_perbaikan');
        });
    }
};
