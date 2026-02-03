<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    // Kolom yang boleh diisi
    protected $fillable = [
        'total_price',
        'paid_amount',
        'change_amount'
    ];

    // Relasi ke detail transaksi
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public static function createFromRequest(array $items, float $paidAmount)
    {
        return DB::transaction(function () use ($items, $paidAmount) {
            Log::info('Mulai proses transaksi');

            $transactionItems = [];
            $totalAmount = 0;

            // Validasi & hitung item
            foreach ($items as $itemData) {
                $product = Product::with('unit')->findOrFail($itemData['product_id']);
                $unit = $product->unit;

                if (!$unit) {
                    throw new \Exception("Unit tidak ditemukan untuk produk: {$product->name}");
                }

                $quantity = (int) $itemData['quantity'];
                $price = $unit->price_per_unit ?? $product->price;
                $subtotal = $price * $quantity;

                // Cek stok
                if ($product->stock < $quantity) {
                    throw new \Exception("Stok tidak mencukupi untuk produk: {$product->name}");
                }

                $transactionItems[] = [
                    'product' => $product,
                    'unit' => $unit,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];

                $totalAmount += $subtotal;
            }

            // Validasi pembayaran
            if ($paidAmount < $totalAmount) {
                throw new \Exception('Jumlah bayar tidak mencukupi. Total: Rp ' . number_format($totalAmount, 0, ',', '.'));
            }

            $changeAmount = $paidAmount - $totalAmount;

            // Simpan transaksi
            $transaction = self::create([
                'total_price' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
            ]);

            Log::info('Transaksi dibuat', ['id' => $transaction->id]);

            
            foreach ($transactionItems as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'unit_id' => $item['unit']->id,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Kurangi stok
                $item['product']->decrement('stock', $item['quantity']);
                Log::info("Stok dikurangi untuk {$item['product']->name}", ['stok' => $item['product']->fresh()->stock]);
            }

            return $transaction;
        });
    }
}
