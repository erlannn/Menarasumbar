<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerangkatJaringan extends Model
{

    protected $table = 'perangkatjaringan'; // tambahkan ini
    protected $fillable = [
        'Kode_perangkat_jaringan',
        'Perangkat',
        'Jenis_jaringan',
        'Jangkauan_sinyal',
    ];

    public $timestamps = false; // karena kamu tidak pakai created_at / updated_at
}
