<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Product;

class ProductUnitSupplierSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama (opsional, hati-hati di production!)
        Supplier::query()->delete();
        Unit::query()->delete();
        Product::query()->delete();

        // 1. Buat Satuan (Units)
        $units = Unit::insert([
            ['name' => 'Pcs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pack', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kotak', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Buah', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lusin', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $unitIds = Unit::pluck('id')->toArray();

        // 2. Buat Supplier
        $suppliers = [];
        for ($i = 1; $i <= 5; $i++) {
            $suppliers[] = [
                'name' => "Supplier " . $i,
                'address' => "Alamat Supplier " . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Supplier::insert($suppliers);
        $supplierIds = Supplier::pluck('id')->toArray();

        // 3. Buat Produk
        $products = [];
        $faker = \Faker\Factory::create('id_ID');

        foreach ($supplierIds as $supplierId) {
            $jumlahProduk = rand(3, 6);
            for ($j = 0; $j < $jumlahProduk; $j++) {
                $products[] = [
                    'name' => $faker->word,
                    'supplier_id' => $supplierId,
                    'unit_id' => $faker->randomElement($unitIds),
                    'price' => $faker->randomFloat(2, 1000, 200000),
                    'stock' => $faker->numberBetween(10, 500),
                    'detail' => $faker->sentence,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Product::insert($products);

        $this->command->info('✅ Seeder selesai:');
        $this->command->info('   - ' . count($supplierIds) . ' supplier');
        $this->command->info('   - ' . count($unitIds) . ' satuan');
        $this->command->info('   - ' . count($products) . ' produk');
    }
}