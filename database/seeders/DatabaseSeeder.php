<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            btsSeeder::class,
            KecamatanSeeder::class,
            PerangkatJaringanSeeder::class,
            OperatorSeeder::class,
            PerangkatJaringanSeeder::class,
            RolePermissionSeeder::class,
            userSeeder::class,
            // Tambahkan seeder lain di sini
        ]);
    }
}
