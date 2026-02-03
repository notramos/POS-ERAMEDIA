<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
class SupplerController extends Controller
{
    public function index()
    {
        try{
            $suppliers = Supplier::paginate(10);
            return view('supplier.supplier', compact('suppliers'));
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'error dalam mengambil data.');        
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        Supplier::create($request->all());

        return redirect()->route('supplier.supplier')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function update(Request $request, Supplier $supplier)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $supplier->update($request->all());

        return redirect()->route('supplier.supplier')->with('success', 'Supplier berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('supplier.supplier')->with('success', 'Supplier berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('supplier.supplier')->with('error', 'Gagal menghapus supplier: ' . $e->getMessage());
        }
    }
}
