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
            'password' => bcrypt('12345678'),
        ]);
        $superuser->assignRole('superuser');

        $admin = User::create([
            'name' => 'Maulana',
            'username' => 'maulana',
            'password' => bcrypt('maulana11'),
        ]);
        $admin->assignRole('admin');
    }
}
