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
        Schema::create('bts', function (Blueprint $table) {
            $table->id()->autoIncrement()->primary();
            $table->string('nama_BTS');
            $table->string('Longitude');
            $table->string('Latitude');
            $table->date('Tahun_registrasi');
            $table->date('Tahun_berakhir');
            $table->string('alamat');
            $table->string('Kode_operator');
            $table->string('Kode_perangkat_jaringan');
            $table->string('Kode_kecamatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bts');
    }
};
