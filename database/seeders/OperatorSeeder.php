<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Operator;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Operator::create([
            'Kode_operator' => 'OP1',
            'Operator' => 'Telkomsel',
        ]);

        Operator::create([
            'Kode_operator' => 'OP2',
            'Operator' => 'Indosat',
        ]);
    }
}
