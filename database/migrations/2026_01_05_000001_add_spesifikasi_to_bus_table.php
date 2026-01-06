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
        Schema::table('bus', function (Blueprint $table) {
            $table->string('kapasitas_penumpang')->nullable()->after('deskripsi');
            $table->string('sistem_penggerak')->nullable()->after('kapasitas_penumpang');
            $table->string('jenis_baterai')->nullable()->after('sistem_penggerak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bus', function (Blueprint $table) {
            $table->dropColumn('kapasitas_penumpang');
            $table->dropColumn('sistem_penggerak');
            $table->dropColumn('jenis_baterai');
        });
    }
};
