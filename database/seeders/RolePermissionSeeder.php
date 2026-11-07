<?php

namespace Database\Seeders;

use App\Models\Customer;
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

        $pelangganRole = Role::firstOrCreate([
            'name' => 'pelanggan',
            'guard_name' => 'web',
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@apotek.test'],
            [
                'name' => 'admin',
                'username' => 'admin',
                'password' => Hash::make('123098'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $kasir = User::updateOrCreate(
            ['email' => 'kasir@apotek.test'],
            [
                'name' => 'kasir',
                'username' => 'kasir',
                'password' => Hash::make('098123'),
                'role' => 'kasir',
                'is_active' => true,
            ]
        );

        $customer = Customer::firstOrCreate(
            ['phone' => '08000000000'],
            [
                'name' => 'Pelanggan Demo',
                'address' => 'Alamat demo',
            ]
        );

        $pelanggan = User::updateOrCreate(
            ['email' => 'pelanggan@apotek.test'],
            [
                'name' => 'pelanggan',
                'password' => Hash::make('123456'),
                'role' => 'pelanggan',
                'customer_id' => $customer->id,
            ]
        );

        $admin->syncRoles([$adminRole]);
        $kasir->syncRoles([$kasirRole]);
        $pelanggan->syncRoles([$pelangganRole]);
    }
}