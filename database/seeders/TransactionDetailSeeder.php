<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionDetailSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = DB::table('transactions')->pluck('id');
        $products = DB::table('products')->pluck('id');
        $units = DB::table('units')->pluck('id');

        if ($transactions->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Transactions or products table is empty. Skip transaction_detail seeding.');

            return;
        }

        $unitId = $units->first();
        $details = [];

        foreach ($transactions as $transactionId) {
            $itemCount = min(rand(1, 4), $products->count());
            if ($itemCount == 0) {
                continue;
            }
            $selectedProducts = $products->random($itemCount);

            foreach ($selectedProducts as $productId) {
                $qty = rand(1, 5);
                $price = DB::table('products')->where('id', $productId)->value('price') ?? 5000;
                $subtotal = $qty * $price;

                $details[] = [
                    'transaction_id' => $transactionId,
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($details)) {
            DB::table('transaction_details')->insert($details);
        }
    }
}
