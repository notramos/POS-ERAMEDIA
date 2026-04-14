<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = [];
        $paymentMethods = ['tunai', 'qris'];

        for ($i = 0; $i < 15; $i++) {
            $totalPrice = rand(10000, 150000);
            $discount = rand(0, 1) ? rand(500, 2000) : 0;
            $paidAmount = $totalPrice - $discount + rand(0, 10000);
            $changeAmount = max(0, $paidAmount - ($totalPrice - $discount));
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

            $transactions[] = [
                'total_price' => $totalPrice,
                'discount' => $discount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
                'created_at' => now()->subDays(rand(0, 10))->subMinutes(rand(0, 1439)),
                'updated_at' => now()->subDays(rand(0, 10))->subMinutes(rand(0, 1439)),
            ];
        }

        DB::table('transactions')->insert($transactions);
    }
}
