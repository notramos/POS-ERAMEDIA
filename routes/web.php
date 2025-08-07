<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login'); // ke /login
});

// Auth routes
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/register', [RegisterController::class, 'index'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// OWNER Only
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/units', [UnitController::class, 'index'])->name('unit.index');
});

// OWNER + KARYAWAN
Route::middleware(['auth', 'role:owner,karyawan'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{id}', [LaporanController::class, 'show']);
    Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir.index');
    Route::get('/transaksi/{transaction}', [TransactionController::class, 'show'])->name('kasir.detail');
    Route::post('/kasir', [TransactionController::class, 'store'])->name('kasir.store');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');
});
