<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $table = 'operator'; 
    protected $fillable = [
        'Kode_operator',
        'operator',
    ];

    public $timestamps = false; 
}