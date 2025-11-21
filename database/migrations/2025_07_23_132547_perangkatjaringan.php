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
        Schema::create('perangkatjaringan', function (Blueprint $table) {
            $table->string('Kode_perangkat_jaringan')->primary();
            $table->string('Perangkat');
            $table->string('Jenis_jaringan');
            $table->integer('Jangkauan_sinyal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perangkatjaringan');
    }
};
