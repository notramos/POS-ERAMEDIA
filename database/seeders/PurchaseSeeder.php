<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = DB::table('suppliers')->pluck('id');

        if ($suppliers->isEmpty()) {
            $this->command->warn('Suppliers table is empty. Skip purchase seeding.');

            return;
        }

        DB::table('purchases')->insert([
            [
                'supplier_id' => $suppliers->first(),
                'purchase_date' => now()->subDays(10),
                'total_amount' => 350000,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'supplier_id' => $suppliers->get(1) ?? $suppliers->first(),
                'purchase_date' => now()->subDays(7),
                'total_amount' => 250000,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'supplier_id' => $suppliers->get(2) ?? $suppliers->first(),
                'purchase_date' => now()->subDays(5),
                'total_amount' => 180000,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'supplier_id' => $suppliers->get(3) ?? $suppliers->first(),
                'purchase_date' => now()->subDays(3),
                'total_amount' => 420000,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'supplier_id' => $suppliers->get(4) ?? $suppliers->first(),
                'purchase_date' => now()->subDay(),
                'total_amount' => 150000,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);
    }
}
