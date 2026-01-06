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
            // Rename sistem_penggerak to kapasitas_baterai
            $table->renameColumn('sistem_penggerak', 'kapasitas_baterai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bus', function (Blueprint $table) {
            // Rename back to sistem_penggerak
            $table->renameColumn('kapasitas_baterai', 'sistem_penggerak');
        });
    }
};
