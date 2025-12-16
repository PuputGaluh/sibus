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
        Schema::create('tingkat_kerusakan', function (Blueprint $table) {
            $table->increments('id_tingkat');
            $table->enum('nama_tingkat', ['Ringan', 'Sedang', 'Tinggi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tingkat_kerusakan');
    }
};
