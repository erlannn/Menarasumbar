<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PerangkatJaringan;

class PerangkatJaringanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PerangkatJaringan::updateOrInsert(
            ['Kode_perangkat_jaringan' => 'PJ3'],
            [
                'Perangkat' => 'Kathrein 742215',
                'Jenis_jaringan' => '3G',
                'Jangkauan_sinyal' => 4,
            ]
        );
        

        PerangkatJaringan::updateOrInsert(
            ['Kode_perangkat_jaringan' => 'PJ4',],
            [
            'Perangkat' => 'Nokia AirScale Active Antenna',
            'Jenis_jaringan' => '4G',
            'Jangkauan_sinyal' => '3',
        ]);

        PerangkatJaringan::updateOrInsert(
            ['Kode_perangkat_jaringan' => 'PJ5',],
            [
            'Perangkat' => 'Huawei AAU5613',
            'Jenis_jaringan' => '5G',
            'Jangkauan_sinyal' => '2',
        ]);
    }
}
