<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::latest()->paginate(5);
        $units = Unit::latest()->get();
        $suppliers = Supplier::all();

        // Kode ini sudah benar untuk memuat halaman pertama kali.
        return view('product.product', compact('products', 'units','suppliers'));
    }

    public function update(Request $request, Product $product)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit_id' => 'required|exists:units,id',
            'detail' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

          $product->update([
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
            'supplier_id' => $request->supplier_id,
            'unit_id' => $request->unit_id,
            'detail' => $request->detail,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus resource dari storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
