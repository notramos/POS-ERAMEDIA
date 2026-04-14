<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek apakah request dari JavaScript (AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Login berhasil',
                    'role' => Auth::user()->role->name,
                ]);
            }

            return redirect()->route('dashboard');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
