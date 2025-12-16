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
        Schema::create('laporan_perbaikan', function (Blueprint $table) {
            $table->increments('id_perbaikan');

            $table->unsignedInteger('id_laporan');
            $table->unsignedInteger('id_dispatcher');
            $table->unsignedInteger('id_teknisi');

            $table->dateTime('tanggal_validasi')->nullable();
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi', 'Mendesak']);
            $table->text('catatan')->nullable();

            $table->dateTime('tanggal_mulai_estimasi')->nullable();
            $table->dateTime('tanggal_selesai_estimasi')->nullable();

            // Diisi oleh teknisi
            $table->text('deskripsi_pekerjaan_teknisi')->nullable();
            $table->dateTime('tanggal_selesai_aktual')->nullable();

            $table->enum('status_perbaikan', [
                'Pending',
                'In Progress',
                'Menunggu Validasi',
                'Selesai'
            ])->default('Pending');

            $table->timestamps();

            $table->foreign('id_laporan')
                ->references('id_laporan')->on('laporan_kerusakan');

            $table->foreign('id_dispatcher')
                ->references('id_user')->on('users');

            $table->foreign('id_teknisi')
                ->references('id_user')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_perbaikan');
    }
};
