<?php

namespace App\Http\Controllers;

use App\Contracts\PurchaseServiceInterface;
use App\Models\Supplier;
use App\Models\SupplierItem;
use App\Models\Unit;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected PurchaseServiceInterface $purchaseService;

    public function __construct(PurchaseServiceInterface $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    protected function getService()
    {
        return $this->purchaseService;
    }

    public function index(Request $request)
    {
        $filters = $request->query();
        $purchases = $this->purchaseService->getAll($filters);

        return view('purchase.purchase', compact('purchases'));
    }

    public function create()
    {
        $allSuppliers = $this->purchaseService->getSuppliers();
        $units = Unit::all();

        return view('purchase.create', compact('allSuppliers', 'units'));
    }

    public function getSupplierItems(Supplier $supplier)
    {
        $supplierItems = $supplier->supplierItems()->with('unit')->get();
        $units = Unit::all();

        $html = view('purchase.partials.supplier-items', compact('supplierItems', 'units', 'supplier'))->render();

        return response()->json(['html' => $html]);
    }

    public function destroySupplierItem(SupplierItem $supplierItem)
    {
        try {
            $supplierItem->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'purchase_date' => 'required|date',
            ]);

            $this->purchaseService->create($validated + [
                'items' => $request->items ?? [],
                'new_items' => $request->new_items ?? [],
            ]);

            return redirect()->route('purchase.purchase')->with('success', 'Pembelian berhasil disimpan!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->purchaseService->delete($id);

            return redirect()->route('purchase.purchase')->with('success', 'Pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('purchase.purchase')->with('error', $e->getMessage());
        }
    }
}
