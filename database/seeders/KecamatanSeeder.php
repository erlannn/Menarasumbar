<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kecamatan;


class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.00',
            'Longitude' => '100.36056',
            'Latitude' => '-0.95556',
            'Nama_kecamatan' => 'Padang',
        ]);

        Kecamatan::create([
            'Kode_kecamatan' => '13.71.01',
            'Longitude' => '100.377704',
            'Latitude' => '-0.975225',
            'Nama_kecamatan' => 'Padang Selatan',
        ]);
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.02',
            'Longitude' => '100.37607',
            'Latitude' => '-0.947219',
            'Nama_kecamatan' => 'Padang Timur',
        ]);
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.03',
            'Longitude' => '100.353901',
            'Latitude' => '-0.932754',
            'Nama_kecamatan' => 'Padang Barat',
        ]);
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.04',
            'Longitude' => '100.357263',
            'Latitude' => '-0.918663',
            'Nama_kecamatan' => 'Padang Utara',
        ]);
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.05',
            'Longitude' => '100.412783',
            'Latitude' => '-1.046625',
            'Nama_kecamatan' => 'Bungus Teluk Kabung',
        ]);
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.06',
            'Longitude' => '100.402708',
            'Latitude' => '-0.97897',
            'Nama_kecamatan' => 'Lubuk Begalung',
        ]);
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.07',
            'Longitude' => '100.421825',
            'Latitude' => '-0.955091',
            'Nama_kecamatan' => 'Lubuk Kilangan',
        ]);
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.08',
            'Longitude' => '100.520556',
            'Latitude' => '-0.889444',
            'Nama_kecamatan' => 'Pauh',
        ]);
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.09',
            'Longitude' => '100.400177',
            'Latitude' => '-0.928794',
            'Nama_kecamatan' => 'Kuranji',
        ]);
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.10',
            'Longitude' => '100.37795',
            'Latitude' => '-0.896831',
            'Nama_kecamatan' => 'Nanggalo',
        ]);
        Kecamatan::create([
            'Kode_kecamatan' => '13.71.11',
            'Longitude' => '100.37795',
            'Latitude' => '-0.896831',
            'Nama_kecamatan' => 'Koto Tangah',
        ]);
    }
}
