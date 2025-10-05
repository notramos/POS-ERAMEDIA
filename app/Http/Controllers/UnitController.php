<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('unit.index', compact('units'));
    }

    public function tambah(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);

        $unit = Unit::create([
            'name' => $request->name,
            // tambahkan field lain jika perlu
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil ditambahkan.',
            'unit' => $unit
        ]);
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('unit.index')->with('success', 'Unit berhasil dihapus.');
    }
}
