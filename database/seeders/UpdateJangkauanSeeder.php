<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateJangkauanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('perangkatjaringan')
            ->where('Jenis_jaringan', '3G')
            ->update(['Jangkauan_sinyal' => 0.6]);

        DB::table('perangkatjaringan')
            ->where('Jenis_jaringan', '4G')
            ->update(['Jangkauan_sinyal' => 0.5]);

        DB::table('perangkatjaringan')
            ->where('Jenis_jaringan', '5G')
            ->update(['Jangkauan_sinyal' => 0.4]);

        echo "✓ Jangkauan_sinyal updated successfully!";
        
        DB::table('perangkatjaringan')
            ->select('Kode_perangkat_jaringan', 'Perangkat', 'Jenis_jaringan', 'Jangkauan_sinyal')
            ->get()
            ->each(function ($item) {
                echo "Kode: {$item->Kode_perangkat_jaringan} | Jenis: {$item->Jenis_jaringan} | Jangkauan: {$item->Jangkauan_sinyal} km\n";
            });
    }
}
