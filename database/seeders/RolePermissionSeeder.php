<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $kasirRole = Role::firstOrCreate([
            'name' => 'kasir',
            'guard_name' => 'web',
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@apotek.test'],
            [
                'name' => 'admin',
                'password' => Hash::make('123098'),
                'role' => 'admin',
            ]
        );

        $kasir = User::updateOrCreate(
            ['email' => 'kasir@apotek.test'],
            [
                'name' => 'kasir',
                'password' => Hash::make('098123'),
                'role' => 'kasir',
            ]
        );

        $admin->syncRoles([$adminRole]);
        $kasir->syncRoles([$kasirRole]);
    }
}