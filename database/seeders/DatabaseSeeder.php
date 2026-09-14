<?php

namespace Database\Seeders;

use App\Models\Item;
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

        $items = [
            ['item_code' => 'MED-001', 'name' => 'Paracetamol 500mg', 'category' => 'Tablet', 'selling_price' => 5000, 'stock' => 1250],
            ['item_code' => 'MED-002', 'name' => 'Amoxicillin 250mg', 'category' => 'Kapsul', 'selling_price' => 12000, 'stock' => 15],
            ['item_code' => 'MED-003', 'name' => 'OBH Combi Plus 100ml', 'category' => 'Sirup', 'selling_price' => 18500, 'stock' => 45],
            ['item_code' => 'ALM-001', 'name' => 'Masker Medis 3-Ply', 'category' => 'Alat Kesehatan', 'selling_price' => 25000, 'stock' => 0],
            ['item_code' => 'MED-004', 'name' => 'Vitamin C 1000mg', 'category' => 'Suplemen', 'selling_price' => 45000, 'stock' => 120],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(['item_code' => $item['item_code']], $item);
        }
    }
}
