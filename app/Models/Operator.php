<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $table = 'operator'; // tambahkan ini
    protected $fillable = [
        'Kode_operator',
        'operator',
    ];

    public $timestamps = false; // karena kamu tidak pakai created_at / updated_at
}