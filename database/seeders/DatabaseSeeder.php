<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kasir@apotek.test'],
            [
                'name' => 'kasir',
                'password' => bcrypt('098123'),
                'role' => 'kasir',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@apotek.test'],
            [
                'name' => 'admin',
                'password' => bcrypt('123098'),
                'role' => 'admin',
            ]
        );
    }
}
