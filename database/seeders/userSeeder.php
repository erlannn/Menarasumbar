<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superuser = User::create([
            'name' => 'Erlan',
            'username' => 'erlan',
            'password' => bcrypt('00000000'),
        ]);
        $superuser->assignRole('superuser');

        $admin = User::create([
            'name' => 'Maulana',
            'username' => 'maulana',
            'password' => bcrypt('00000000'),
        ]);
        $admin->assignRole('admin');
    }
}
