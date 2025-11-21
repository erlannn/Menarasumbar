<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'Kecamatan'; // tambahkan ini

    protected $fillable = [
        'Kode_kecamatan',
        'Longitude',
        'Latitude',
        'Nama_kecamatan'
    ];

    public $timestamps = false; // karena kamu tidak pakai created_at / updated_at
}