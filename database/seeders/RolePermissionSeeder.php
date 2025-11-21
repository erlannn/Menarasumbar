<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'tambah-BTS']);
        Permission::create(['name' => 'edit-BTS']);
        Permission::create(['name' => 'hapus-BTS']);
        Permission::create(['name' => 'lihat-BTS']);

        Permission::create(['name' => 'tambah-pengguna']);
        Permission::create(['name' => 'edit-pengguna']);
        Permission::create(['name' => 'hapus-pengguna']);
        Permission::create(['name' => 'lihat-pengguna']);

        Role::create(['name' => 'superuser']);
        Role::create(['name' => 'admin']);

        $roleSuperuser = Role::findByName('superuser');
        $roleSuperuser->givePermissionTo('tambah-pengguna');
        $roleSuperuser->givePermissionTo('edit-pengguna');
        $roleSuperuser->givePermissionTo('hapus-pengguna');
        $roleSuperuser->givePermissionTo('lihat-pengguna');
        $roleSuperuser->givePermissionTo('tambah-BTS');
        $roleSuperuser->givePermissionTo('edit-BTS');
        $roleSuperuser->givePermissionTo('hapus-BTS');
        $roleSuperuser->givePermissionTo('lihat-BTS');

        $roleAdmin = Role::findByName('admin');
        $roleAdmin-> givePermissionTo('tambah-BTS');
        $roleAdmin-> givePermissionTo('edit-BTS');
        $roleAdmin-> givePermissionTo('hapus-BTS');
        $roleAdmin-> givePermissionTo('lihat-BTS');
    }
}
