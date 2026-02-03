<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier', 'items.product')
                        ->orderBy('purchase_date', 'desc')
                        ->get();
        return view('purchase.purchase', compact('purchases'));
    }

    public function create()
{
    $allSuppliers = Supplier::all();
    $units = Unit::all();
    return view('purchase.create', compact('allSuppliers','units'));
}

public function store(Request $request)
{
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'purchase_date' => 'required|date',
    ]);

    if ($request->filled('new_product.name')) {
        $request->validate([
            'new_product.name' => 'required|string|max:255',
            'new_product.price' => 'required|numeric|min:0',
            'new_product.stock' => 'required|integer|min:1',
            'new_product.unit_id' => 'required|exists:units,id',
            'new_product.detail' => 'required|string',
        ]);
    }

    try {
        DB::beginTransaction();

        $supplierId = $request->supplier_id;
        $itemsToSave = [];
        $totalAmount = 0;

        $newProductId = null;
        if ($request->filled('new_product.name')) {
            $newProduct = Product::create([
                'name' => $request->new_product['name'],
                'price' => $request->new_product['price'],
                'stock' => $request->new_product['stock'],
                'supplier_id' => $supplierId,
                'unit_id' => $request->new_product['unit_id'],
                'detail' => $request->new_product['detail'],
            ]);
            $newProductId = $newProduct->id;
            $itemsToSave[] = [
                'product_id' => $newProductId,
                'quantity' => $request->new_product['stock'],
                'subtotal' => $request->new_product['price'] * $request->new_product['stock']
            ];
            $totalAmount += $itemsToSave[count($itemsToSave) - 1]['subtotal'];
        }

        if ($request->filled('items')) {
            foreach ($request->items as $productId => $data) {
                if (!empty($data['use']) && !empty($data['quantity'])) {
                    $product = Product::findOrFail($productId);
                    $quantity = (int) $data['quantity'];
                    $subtotal = $product->price * $quantity;

                    $itemsToSave[] = [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal
                    ];
                    $totalAmount += $subtotal;

                    $product->stock += $quantity;
                    $product->save();
                }
            }
        }

        if (empty($itemsToSave)) {
            return back()->withErrors(['items' => 'Minimal tambahkan satu produk (baru atau yang sudah ada).']);
        }
        

        $purchase = Purchase::create([
            'supplier_id' => $supplierId,
            'purchase_date' => $request->purchase_date,
            'total_amount' => $totalAmount
        ]);

        
        foreach ($itemsToSave as $item) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal']
            ]);
        }

        DB::commit();

        return redirect()->route('purchase.purchase')->with('success', 'Pembelian berhasil disimpan.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('purchase.purchase')->with('error' , 'Gagal menyimpan pembelian: ' . $e->getMessage());
    }

    } 

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        try {
            DB::beginTransaction();

            foreach ($purchase->items as $item) {
                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();
            }

            $purchase->items()->delete();
            $purchase->delete();

            DB::commit();

            return redirect()->route('purchase.purchase')->with('success', 'Pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('purchase.purchase')->with('error', 'Gagal menghapus pembelian: ' . $e->getMessage());
        }
    }
}
