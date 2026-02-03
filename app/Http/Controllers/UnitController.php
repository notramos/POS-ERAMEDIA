<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::paginate(5);
        return view('kategori.kategori', compact('units'));
    }

    public function tambah(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);

        $unit = Unit::create([
            'name' => $request->name,
        ]);

        return redirect()->route('unit.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('unit.index')->with('success', 'Unit berhasil dihapus.');
    }
}
