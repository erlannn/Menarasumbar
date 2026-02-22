<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public $timestamps = false;

    public function up(): void
    {
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->string('Kode_kecamatan')->primary();
            $table->string('Longitude');
            $table->string('Latitude');
            $table->string('Nama_kecamatan');
            $table->string('Jumlah_penduduk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
    }
};
