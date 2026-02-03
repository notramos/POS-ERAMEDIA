<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Product;

class ProductSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat data supplier
        $suppliers = Supplier::factory()->count(5)->create();

        // Buat data produk (3–6 produk per supplier)
        foreach ($suppliers as $supplier) {
            Product::factory()
                ->count(rand(3, 6))
                ->create([
                    'supplier_id' => $supplier->id
                ]);
        }

        $this->command->info('✅ ' . Supplier::count() . ' supplier dan ' . Product::count() . ' produk berhasil di-seed.');
    }
}
