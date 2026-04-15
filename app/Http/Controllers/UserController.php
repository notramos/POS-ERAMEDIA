<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(10);

        return view('user.user', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,karyawan',
        ]);

        $role = Role::where('name', $request->role)->firstOrFail();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $role->id,
            'password' => Hash::make('password'),
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        Log::info('Updating user: ', $user->toArray());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:admin,karyawan',
            'password' => 'nullable|string|min:6',
        ]);

        $role = Role::where('name', $request->role)->firstOrFail();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $role->id,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        Log::info('Updated user: ', $user->toArray());

        return redirect()->back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Opsional: cegah hapus owner jika hanya ada satu
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
