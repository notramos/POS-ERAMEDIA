<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('supplier');

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $products = $query->paginate(10);
        $suppliers = Supplier::all();

        return view('product.product', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'item_name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'detail' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $supplierItem = SupplierItem::where('supplier_id', $request->supplier_id)
                ->where('name', $request->item_name)
                ->firstOrFail();

            Log::info('Found supplier item: ', $supplierItem->toArray());

            // Ensure we use 'stock' from request consistently
            if ($supplierItem->stok < $request->stock) {
                Log::warning('Insufficient stock in supplier item', [
                    'requested' => $request->stock,
                    'available' => $supplierItem->stok,
                ]);
                // Kembalikan sebagai validation error + session error agar modal terbuka ulang dan toast muncul
                Log::info('Returning insufficient stock validation', ['requested' => $request->stock, 'available' => $supplierItem->stok]);
                return back()
                    ->withErrors(['stock' => "Stok tidak mencukupi. Tersedia: {$supplierItem->stok}"])
                    ->withInput()
                    ->with('error', "Stok tidak mencukupi. Tersedia: {$supplierItem->stok}");
            }

            // Kurangi stok di supplier
            $supplierItem->decrement('stok', $request->stock);

            Product::create([
                'name' => $supplierItem->name,
                'price' => $request->price,
                'stock' => $request->stock,
                'supplier_id' => $supplierItem->supplier_id,
                'unit_id' => $supplierItem->unit_id,
                'detail' => $request->detail,
            ]);
            Log::info('Product created successfully');
            DB::commit();
            return back()->with('success', 'Produk berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product store error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan produk.');
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'detail' => 'nullable|string',
        ]);

        // Hitung selisih stok (product uses 'stock' attribute)
        $oldStock = $product->stock;
        $newStock = $request->stock;
        $diff = $newStock - $oldStock;

        if ($diff > 0) {
            // Tambah stok di product → ambil dari supplier
            $supplierItem = SupplierItem::where('supplier_id', $product->supplier_id)
                ->where('name', $product->name)
                ->first();
            if ($supplierItem && $supplierItem->stok >= $diff) {
                $supplierItem->decrement('stok', $diff);
            } else {
                return back()->withErrors(['stock' => 'Stok di supplier tidak mencukupi untuk penambahan.']);
            }
        } elseif ($diff < 0) {
            // Kurangi stok di product → kembalikan ke supplier
            $supplierItem = SupplierItem::where('supplier_id', $product->supplier_id)
                ->where('name', $product->name)
                ->first();
            if ($supplierItem) {
                $supplierItem->increment('stok', abs($diff));
            }
        }

        $product->update([
            'price' => $request->price,
            'stock' => $newStock,
            'detail' => $request->detail,
        ]);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            $supplierItem = SupplierItem::where('supplier_id', $product->supplier_id)
                ->where('name', $product->name)
                ->first();

            if ($supplierItem) {
                $supplierItem->increment('stok', $product->stock);
            }

            $product->delete();

            DB::commit();
            return back()->with('success', 'Produk dihapus dan stok dikembalikan ke supplier.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product delete error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus produk.');
        }
    }

    public function getSupplierItems($supplierId)
    {
        $items = SupplierItem::where('supplier_id', $supplierId)
                    ->where('stok', '>', 0)
                    ->with('unit')
                    ->get()
                    ->map(fn($item) => [
                        'name' => $item->name,
                        'purchase_price' => $item->price,
                        'stock' => $item->stok,
                        'unit_id' => $item->unit_id,
                        'unit_name' => $item->unit?->name,
                    ]);
        return response()->json($items);
    }
}