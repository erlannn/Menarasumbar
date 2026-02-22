<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan'; 

    protected $fillable = [
        'Kode_kecamatan',
        'Longitude',
        'Latitude',
        'Nama_kecamatan',
        'Jumlah_penduduk',
    ];

    public $timestamps = false; 
}