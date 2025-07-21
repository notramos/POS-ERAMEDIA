<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', [AuthController::class, 'index'])->name('auth.index');
Route::get('/register', [RegisterController::class, 'index'])->name('auth.register');

// Digunakan saat pertama kali membuka halaman dan saat me-refresh data.
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/units', [UnitController::class, 'index'])->name('unit.index');

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/{id}', [LaporanController::class, 'show']);


Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir.index');
Route::get('/transaksi/{transaction}', [TransactionController::class, 'show'])->name('kasir.detail');
Route::post('/kasir', [TransactionController::class, 'store'])->name('kasir.store');
Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');
