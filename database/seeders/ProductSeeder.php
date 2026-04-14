<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Indomie Goreng',
                'price' => 3500,
                'stock' => 100,
                'detail' => 'Indomie rasa ayam special',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aqua 600ml',
                'price' => 4000,
                'stock' => 200,
                'detail' => 'Air mineral 600ml',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Roti Tawar',
                'price' => 12000,
                'stock' => 50,
                'detail' => 'Roti tawar regular',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
