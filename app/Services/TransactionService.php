<?php

namespace App\Services;

use App\Contracts\TransactionServiceInterface;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService implements TransactionServiceInterface
{
    public function createFromRequest(array $items, ?float $paidAmount, string $paymentMethod, float $discount = 0): Transaction
    {
        DB::beginTransaction();

        try {
            $totalPrice = 0;
            $details = [];

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $subtotal = $product->price * $quantity;

                $totalPrice += $subtotal;

                $details[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'unit_id' => $product->unit_id,
                ];

                $product->decrement('stock', $quantity);
            }

            $finalPrice = $totalPrice - $discount;
            $changeAmount = $paidAmount ? max(0, $paidAmount - $finalPrice) : 0;

            $transaction = Transaction::create([
                'total_price' => $totalPrice,
                'discount' => $discount,
                'paid_amount' => $paidAmount ?? $finalPrice,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
            ]);

            foreach ($details as $detail) {
                $transaction->details()->create($detail);
            }

            DB::commit();

            return $transaction->fresh(['details.product', 'details.unit']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction creation failed: '.$e->getMessage());
            throw $e;
        }
    }

    public function searchProducts(string $query, int $limit = 100)
    {
        if (strlen($query) < 2) {
            return collect();
        }

        return Product::with('unit')
            ->where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    public function getAll(array $filters)
    {
        $query = Transaction::with('details.product')->orderBy('created_at', 'desc');

        if (! empty($filters['search'])) {
            $keyword = $filters['search'];
            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%{$keyword}%")
                    ->orWhereHas('details.product', fn ($pq) => $pq->where('name', 'like', "%{$keyword}%"));
            });
        }

        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                "{$filters['start_date']} 00:00:00",
                "{$filters['end_date']} 23:59:59",
            ]);
        }

        return $query->paginate(10);
    }

    public function delete($id): void
    {
        $transaction = Transaction::findOrFail($id);

        DB::beginTransaction();

        try {
            foreach ($transaction->details as $detail) {
                $detail->product->increment('stock', $detail->quantity);
            }
            $transaction->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
