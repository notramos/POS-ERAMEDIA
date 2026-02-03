<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 2)->get();
        return view('user.user',compact('users')); // Ganti sesuai nama view kamu
    }

    public function getData()
    {
        $karyawan = User::where('role_id', 2)
            ->select('id', 'name', 'email',)
            ->get();

        return response()->json($karyawan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => 2,
            'password' => Hash::make('password'), // password default
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil ditambahkan.',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role_id', 2)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil diperbarui.',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::where('role_id', 2)->findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil dihapus.'
        ]);
    }
}
