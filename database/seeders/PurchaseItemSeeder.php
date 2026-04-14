<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseItemSeeder extends Seeder
{
    public function run(): void
    {
        $purchases = DB::table('purchases')->pluck('id');
        $supplierItems = DB::table('supplier_items')->pluck('id');

        if ($purchases->isEmpty() || $supplierItems->isEmpty()) {
            $this->command->warn('Purchases or supplier_items table is empty. Skip purchase_item seeding.');

            return;
        }

        $items = [];

        foreach ($purchases as $index => $purchaseId) {
            $itemCount = min(rand(1, 3), $supplierItems->count());
            if ($itemCount == 0) {
                continue;
            }
            $selectedItems = $supplierItems->random($itemCount);

            foreach ($selectedItems as $itemId) {
                $qty = rand(5, 20);
                $price = DB::table('supplier_items')->where('id', $itemId)->value('price') ?? 5000;
                $subtotal = $qty * $price;

                $items[] = [
                    'purchase_id' => $purchaseId,
                    'supplier_item_id' => $itemId,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($items)) {
            DB::table('purchase_items')->insert($items);
        }
    }
}
