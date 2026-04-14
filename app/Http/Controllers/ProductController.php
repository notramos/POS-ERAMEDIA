<?php

namespace App\Http\Controllers;

use App\Contracts\ProductServiceInterface;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    protected function getService()
    {
        return $this->productService;
    }

    public function index(Request $request)
    {
        $filters = $request->query();
        $products = $this->productService->getAll($filters);
        $suppliers = Supplier::all();

        return view('product.product', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'item_name' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:1',
                'detail' => 'nullable|string',
            ]);

            $this->productService->create($validated);

            return back()->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'detail' => 'nullable|string',
            ]);

            $this->productService->update($id, $validated);

            return back()->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->productService->delete($id);

            return back()->with('success', 'Produk dihapus dan stok dikembalikan ke supplier.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getSupplierItems($supplierId)
    {
        $items = $this->productService->getSupplierItems($supplierId);

        return response()->json($items);
    }
}
