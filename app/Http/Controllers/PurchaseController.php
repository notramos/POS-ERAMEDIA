<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\SupplierItem;
use App\Models\PurchaseItem;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('supplier')->orderBy('purchase_date', 'desc');

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        $purchases = $query->paginate(10); // 10 per halaman

        return view('purchase.purchase', compact('purchases'));
    }

    public function create()
    {
        $allSuppliers = Supplier::all();
        $units = Unit::all();
        $selectedSupplierId = old('supplier_id');
        $supplierItems = $selectedSupplierId 
            ? SupplierItem::where('supplier_id', $selectedSupplierId)->with('unit')->get()
            : collect();

    return view('purchase.create', compact('allSuppliers', 'units', 'supplierItems', 'selectedSupplierId'));
    }

    public function store(Request $request)
    {
            Log::info('Store Purchase Request: ', $request->all());

            $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            ]);

        try {
            DB::beginTransaction();

            $supplierId = $request->supplier_id;
            $itemsToSave = [];
            $totalAmount = 0;

            // === 1. Barang existing dari supplier_items ===
            if ($request->filled('items')) {
                foreach ($request->items as $itemId => $data) {
                    if (!empty($data['use']) && !empty($data['quantity'])) {
                        $supplierItem = SupplierItem::findOrFail($itemId);
                        $quantity = (int) $data['quantity'];
                        $subtotal = $supplierItem->price * $quantity;
                        $totalAmount += $subtotal;

                        // Tambah stok di supplier_item sesuai quantity yang dibeli
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
            }

            // === 2. Barang baru → simpan ke supplier_items otomatis ===
            if ($request->filled('new_items')) {
                foreach ($request->new_items as $item) {
                    if (!empty($item['name']) && !empty($item['price']) && !empty($item['quantity'])) {
                        // Cek atau buat di supplier_items
                        $supplierItem = SupplierItem::firstOrCreate(
                            ['supplier_id' => $supplierId, 'name' => trim($item['name'])],
                            [
                                'stok' => $item['quantity'],
                                'price' => $item['price'],
                                'unit_id' => $item['unit_id'] ?? null,
                            ]
                        );

                        // Update harga jika berbeda
                        if ($supplierItem->price != $item['price']) {
                            $supplierItem->update(['price' => $item['price']]);
                        }

                        $quantity = (int) $item['quantity'];

                        // Jika barang sudah ada sebelumnya, tambahkan stok sesuai quantity yang dibeli
                        if (!$supplierItem->wasRecentlyCreated) {
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
            }

            if (empty($itemsToSave)) {
                return back()->withErrors(['items' => 'Minimal satu produk harus dipilih atau diisi.']);
            }

            // Simpan pembelian
            $purchase = Purchase::create([
                'supplier_id' => $supplierId,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $totalAmount,
            ]);

            foreach ($itemsToSave as $item) {
                $purchase->items()->create($item);
            }

            DB::commit();
            return redirect()->route('purchase.purchase')->with('success', 'Pembelian berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan pembelian. Silakan coba lagi.');
        }

    } 

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        try {
            DB::beginTransaction();

            // foreach ($purchase->items as $item) {
            //     $product = $item->supplier;
            //     $product->stok -= $item->quantity;
            //     $product->save();
            // }

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
