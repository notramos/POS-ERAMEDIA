<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // [PERBAIKAN 1] Menggunakan latest()->get() agar produk terbaru muncul di atas.
        $products = Product::latest()->get(); 
        
        if ($request->ajax()) {
            // [PERBAIKAN 2] Mengirim JSON dengan kunci 'products' agar sesuai dengan JavaScript.
            return response()->json([
                'products' => $products
            ]);
        }
        
        // Kode ini sudah benar untuk memuat halaman pertama kali.
        return view('product.index', compact('products'));
    }

    /**
     * Menampilkan form untuk membuat resource baru.
     */
    public function create()
    {
        // Tidak digunakan dalam aplikasi AJAX ini
    }

    /**
     * Menyimpan resource baru ke dalam storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        Product::create($request->all());

        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan!']);
    }

    /**
     * Menampilkan resource yang spesifik.
     */
    public function show(Product $product)
    {
        // Tidak digunakan dalam aplikasi AJAX ini
    }

    /**
     * Menampilkan form untuk mengedit resource yang spesifik.
     */
    public function edit(Product $product)
    {
        // Tidak digunakan dalam aplikasi AJAX ini
    }

    /**
     * Memperbarui resource yang ada di dalam storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $product->update($request->all());

        return response()->json(['success' => true, 'message' => 'Produk berhasil diperbarui!']);
    }

    /**
     * Menghapus resource dari storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus produk.'], 500);
        }
    }
}
