<?php

namespace App\Services;

use App\Contracts\ProductServiceInterface;
use App\Models\Product;
use App\Models\SupplierItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService implements ProductServiceInterface
{
    public function getAll(array $filters)
    {
        $query = Product::with('supplier');

        if (! empty($filters['search'])) {
            $query->where('name', 'LIKE', "%{$filters['search']}%");
        }

        return $query->paginate(10);
    }

    public function create(array $data): Product
    {
        DB::beginTransaction();

        try {
            $supplierItem = SupplierItem::where('supplier_id', $data['supplier_id'])
                ->where('name', $data['item_name'])
                ->firstOrFail();

            if ($supplierItem->stok < $data['stock']) {
                throw new \Exception("Stok tidak mencukupi. Tersedia: {$supplierItem->stok}");
            }

            $supplierItem->decrement('stok', $data['stock']);

            $product = Product::where('supplier_id', $data['supplier_id'])
                ->where('name', $supplierItem->name)
                ->first();

            if ($product) {
                $product->increment('stock', $data['stock']);
                if (! empty($data['price'])) {
                    $product->update([
                        'price' => $data['price'],
                        'detail' => $data['detail'] ?? $product->detail,
                    ]);
                }
            } else {
                Product::create([
                    'name' => $supplierItem->name,
                    'price' => $data['price'],
                    'stock' => $data['stock'],
                    'supplier_id' => $supplierItem->supplier_id,
                    'unit_id' => $supplierItem->unit_id,
                    'detail' => $data['detail'] ?? '',
                ]);
            }

            DB::commit();

            return $product ?? Product::where('supplier_id', $data['supplier_id'])
                ->where('name', $supplierItem->name)->first();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation failed: '.$e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $oldStock = $product->stock;
        $newStock = $data['stock'];
        $diff = $newStock - $oldStock;

        if ($diff === 0) {
            $product->update([
                'price' => $data['price'],
                'stock' => $newStock,
                'detail' => $data['detail'] ?? $product->detail,
            ]);

            return $product;
        }

        DB::beginTransaction();

        try {
            $supplierItem = SupplierItem::where('supplier_id', $product->supplier_id)
                ->where('name', $product->name)
                ->first();

            if ($diff > 0) {
                if (! $supplierItem || $supplierItem->stok < $diff) {
                    throw new \Exception('Stok di supplier tidak mencukupi.');
                }
                $supplierItem->decrement('stok', $diff);
            } elseif ($diff < 0) {
                if ($supplierItem) {
                    $supplierItem->increment('stok', abs($diff));
                }
            }

            $product->update([
                'price' => $data['price'],
                'stock' => $newStock,
                'detail' => $data['detail'] ?? $product->detail,
            ]);

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id): void
    {
        $product = Product::findOrFail($id);

        DB::beginTransaction();

        try {
            $supplierItem = SupplierItem::where('supplier_id', $product->supplier_id)
                ->where('name', $product->name)
                ->first();

            if ($supplierItem) {
                $supplierItem->increment('stok', $product->stock);
            }

            $product->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getSupplierItems(int $supplierId)
    {
        return SupplierItem::where('supplier_id', $supplierId)
            ->where('stok', '>', 0)
            ->with('unit')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'purchase_price' => $item->price,
                'stock' => $item->stok,
                'unit_id' => $item->unit_id,
                'unit_name' => $item->unit?->name,
            ]);
    }

    public function checkExistence(int $supplierId, string $itemName): bool
    {
        return Product::where('supplier_id', $supplierId)
            ->where('name', $itemName)
            ->exists();
    }
}
