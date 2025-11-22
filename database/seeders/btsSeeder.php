<?php

namespace Database\Seeders;

use App\Models\bts;
use Illuminate\Database\Seeder;


class btsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        bts::create([
            'nama_BTS' => 'T-130',
            'Longitude' => '100.310933',
            'Latitude' => '-0.81376666',
            'Tahun_registrasi' => '2021-10-08',
            'Tahun_berakhir' => '2025-12-08',
            'alamat'=> 'Jl.Padang Sarai No.15',
            'Kode_operator' => 'OP1',
            'Kode_perangkat_jaringan' => 'PJ4',
            'Kode_kecamatan' =>'13.71.11',
        ]);

        bts::create([
            'nama_BTS' => 'T-129',
            'Longitude' => '100.3131',
            'Latitude' => '-0.814744',
            'Tahun_registrasi' => '2023-04-02',
            'Tahun_berakhir' => '2026-04-02',
            'alamat'=> 'Jl.Padang Sarai No.20',
            'Kode_operator' => 'OP1',
            'Kode_perangkat_jaringan' => 'PJ4',
            'Kode_kecamatan' =>'13.71.11',
        ]);
    }
}
