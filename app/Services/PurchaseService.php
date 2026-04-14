<?php

namespace App\Services;

use App\Contracts\PurchaseServiceInterface;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\SupplierItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseService implements PurchaseServiceInterface
{
    public function getAll(array $filters)
    {
        $query = Purchase::with('supplier')->orderBy('purchase_date', 'desc');

        if (! empty($filters['start_date'])) {
            $query->whereDate('purchase_date', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $query->whereDate('purchase_date', '<=', $filters['end_date']);
        }

        return $query->paginate(10);
    }

    public function getSuppliers()
    {
        return Supplier::all();
    }

    public function getSupplierItems(int $supplierId)
    {
        return SupplierItem::where('supplier_id', $supplierId)->with('unit')->get();
    }

    public function create(array $data): Purchase
    {
        DB::beginTransaction();

        try {
            $supplierId = $data['supplier_id'];
            $itemsToSave = [];
            $totalAmount = 0;

            foreach ($data['items'] ?? [] as $itemId => $item) {
                if (! empty($item['use']) && ! empty($item['quantity'])) {
                    $supplierItem = SupplierItem::findOrFail($itemId);
                    $quantity = (int) $item['quantity'];
                    $subtotal = $supplierItem->price * $quantity;
                    $totalAmount += $subtotal;

                    $supplierItem->increment('stok', $quantity);

                    $itemsToSave[] = [
                        'supplier_item_id' => $supplierItem->id,
                        'product_name' => $supplierItem->name,
                        'price' => $supplierItem->price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                        'unit_id' => $supplierItem->unit_id,
                    ];
                }
            }

            foreach ($data['new_items'] ?? [] as $item) {
                if (! empty($item['name']) && ! empty($item['price']) && ! empty($item['quantity'])) {
                    $supplierItem = SupplierItem::firstOrCreate(
                        ['supplier_id' => $supplierId, 'name' => trim($item['name'])],
                        ['stok' => $item['quantity'], 'price' => $item['price'], 'unit_id' => $item['unit_id'] ?? null]
                    );

                    if ($supplierItem->price != $item['price']) {
                        $supplierItem->update(['price' => $item['price']]);
                    }

                    $quantity = (int) $item['quantity'];
                    if (! $supplierItem->wasRecentlyCreated) {
                        $supplierItem->increment('stok', $quantity);
                    }

                    $subtotal = $supplierItem->price * $quantity;
                    $totalAmount += $subtotal;

                    $itemsToSave[] = [
                        'supplier_item_id' => $supplierItem->id,
                        'product_name' => $supplierItem->name,
                        'price' => $supplierItem->price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                        'unit_id' => $supplierItem->unit_id,
                    ];
                }
            }

            if (empty($itemsToSave)) {
                throw new \Exception('Minimal satu produk harus dipilih atau diisi.');
            }

            $purchase = Purchase::create([
                'supplier_id' => $supplierId,
                'purchase_date' => $data['purchase_date'],
                'total_amount' => $totalAmount,
            ]);

            foreach ($itemsToSave as $item) {
                $purchase->items()->create($item);
            }

            DB::commit();

            return $purchase;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase creation failed: '.$e->getMessage());
            throw $e;
        }
    }

    public function delete($id): void
    {
        $purchase = Purchase::findOrFail($id);

        DB::beginTransaction();

        try {
            $purchase->items()->delete();
            $purchase->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
