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
        'change_amount',
        'payment_method',
        'discount',
    ];

    // Relasi ke detail transaksi
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Membuat transaksi dari request
    public static function createFromRequest(array $items, float $paidAmount, string $paymentMethod, float $discount)
    {
        return DB::transaction(function () use ($items, $paidAmount, $paymentMethod, $discount) {
        $transactionItems = [];
        $subtotal = 0;

        foreach ($items as $itemData) {
            $product = Product::with('unit')->findOrFail($itemData['product_id']);
            $unit = $product->unit;

            if (!$unit) {
                throw new \Exception("Unit tidak ditemukan untuk produk: {$product->name}");
            }

            $quantity = (int) $itemData['quantity'];
            $price = $unit->price_per_unit ?? $product->price;
            $itemSubtotal = $price * $quantity;

            if ($product->stock < $quantity) {
                throw new \Exception("Stok tidak mencukupi untuk produk: {$product->name}");
            }

            $transactionItems[] = [
                'product' => $product,
                'unit' => $unit,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $itemSubtotal,
            ];

            $subtotal += $itemSubtotal;
        }

        // Terapkan diskon
        $totalAmount = max(0, $subtotal - $discount);
        
        if ($paidAmount < $totalAmount) {
            throw new \Exception('Jumlah bayar tidak mencukupi. Total: Rp ' . number_format($totalAmount, 0, ',', '.'));
        }

        $changeAmount = $paidAmount - $totalAmount;

        // Simpan transaksi dengan diskon
        $transaction = self::create([
            'total_price' => $totalAmount,
            'discount' => $discount,
            'paid_amount' => $paidAmount,
            'change_amount' => $changeAmount,
            'payment_method' => $paymentMethod,
        ]);

        foreach ($transactionItems as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product']->id,
                'unit_id' => $item['unit']->id,
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);

            $item['product']->decrement('stock', $item['quantity']);
            // Log::info("Discount diterapkan: Rp " . number_format($discount, 0, ',', '.'));
            // Log::info("Stok dikurangi untuk {$item['product']->name}", ['stok' => $item['product']->fresh()->stock]);
        }

        return $transaction;
    });
    }
}
