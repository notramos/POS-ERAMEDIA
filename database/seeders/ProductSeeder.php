<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $unitPcs = Unit::where('name', 'pcs')->first();
        $unitBox = Unit::where('name', 'box')->first();

        Product::insert([
            // Low stock products (< 10) - 5 items
            [
                'name' => 'Indomie Goreng',
                'price' => 3500,
                'stock' => 5,
                'detail' => 'Indomie rasa ayam special',
                'unit_id' => $unitPcs?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kopi Sachet',
                'price' => 2000,
                'stock' => 8,
                'detail' => 'Kopi instan sachets',
                'unit_id' => $unitPcs?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gula Pasir 1kg',
                'price' => 15000,
                'stock' => 3,
                'detail' => 'Gula pasir premium',
                'unit_id' => $unitBox?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mie Sedap',
                'price' => 3000,
                'stock' => 7,
                'detail' => 'Mie instan rasa keris',
                'unit_id' => $unitPcs?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teh Botol',
                'price' => 4000,
                'stock' => 6,
                'detail' => 'Minuman teh segar',
                'unit_id' => $unitPcs?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Normal stock products - 10 items
            [
                'name' => 'Aqua 600ml',
                'price' => 4000,
                'stock' => 50,
                'detail' => 'Air mineral 600ml',
                'unit_id' => $unitPcs?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aqua 1500ml',
                'price' => 8000,
                'stock' => 30,
                'detail' => 'Air mineral 1.5L',
                'unit_id' => $unitPcs?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Roti Tawar',
                'price' => 12000,
                'stock' => 25,
                'detail' => 'Roti tawar regular',
                'unit_id' => $unitPcs?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kopi Luwak',
                'price' => 25000,
                'stock' => 15,
                'detail' => 'Kopi premium',
                'unit_id' => $unitBox?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mie GFK',
                'price' => 5500,
                'stock' => 40,
                'detail' => 'Mie goreng favorit',
                'unit_id' => $unitPcs?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Snack Kerupuk',
                'price' => 5000,
                'stock' => 35,
                'detail' => 'Kerupuk renyah',
                'unit_id' => $unitBox?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Susu UHT',
                'price' => 7000,
                'stock' => 20,
                'detail' => 'Susu rendah lemak',
                'unit_id' => $unitPcs?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Biscuit',
                'price' => 8000,
                'stock' => 45,
                'detail' => 'Biscuit coklat',
                'unit_id' => $unitBox?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Minuman Soda',
                'price' => 6000,
                'stock' => 28,
                'detail' => 'Minuman bersoda',
                'unit_id' => $unitPcs?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kue Kering',
                'price' => 25000,
                'stock' => 12,
                'detail' => 'Kue kering special',
                'unit_id' => $unitBox?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
