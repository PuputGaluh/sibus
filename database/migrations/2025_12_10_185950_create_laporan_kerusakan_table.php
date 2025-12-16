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
        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->increments('id_laporan');
            $table->unsignedInteger('id_pelapor');
            $table->unsignedInteger('id_bus');
            $table->unsignedInteger('id_kategori');
            $table->unsignedInteger('id_tingkat');
            $table->enum('status_keberangkatan', ['Pool', 'Akan Berangkat', 'Perjalanan']);
            $table->text('keterangan')->nullable();
            $table->dateTime('tanggal_lapor');
            $table->string('foto', 255)->nullable();
            $table->enum('status_proses', ['Dilaporkan', 'Validasi Diproses', 'Dijadwalkan', 'Dalam Perbaikan', 'Selesai']);
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_pelapor')->references('id_user')->on('users');
            $table->foreign('id_bus')->references('id_bus')->on('bus');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_kerusakan');
            $table->foreign('id_tingkat')->references('id_tingkat')->on('tingkat_kerusakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakan');
    }
};
