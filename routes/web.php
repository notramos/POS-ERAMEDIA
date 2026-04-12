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
use App\Http\Controllers\SupplerController;

    Route::get('/', function () {
        return redirect()->route('login'); 
    });

    // Auth Routes
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::get('/register', [RegisterController::class, 'index'])->name('auth.register');
    Route::post('/register', [RegisterController::class, 'register'])->name('auth.register.post');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Dashboard route
    

    // Routes accessible by owner AND karyawan (cashier + sales)
    Route::middleware(['role:owner,karyawan'])->group(function () {
        // dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Cashier
        Route::get('/kasir/search-products', [TransactionController::class, 'searchProducts'])->name('kasir.search');
        Route::get('/kasir', [TransactionController::class, 'index'])->name('kasir.index');
        Route::post('/kasir', [TransactionController::class, 'store'])->name('kasir.store');

        // Sales / Transactions
        Route::get('/transactions', [TransactionController::class, 'list'])->name('transactions.list');
        Route::get('/transaksi/{transaction}', [TransactionController::class, 'show'])->name('kasir.detail');
        Route::get('/transaksi/{transaction}/struk', [TransactionController::class, 'receipt'])->name('transaction.receipt');
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('kasir.delete');
    });

    // Owner-only routes (master data / admin)
    Route::middleware(['role:owner'])->group(function () {
        
        // Products
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/products/supplier-items/{supplierId}', [ProductController::class, 'getSupplierItems']);

        // Units / Categories
        Route::post('/kategories', [UnitController::class, 'tambah'])->name('unit.tambah');
        Route::get('/kategories', [UnitController::class, 'index'])->name('unit.index');
        Route::put('/kategories/{kategories}', [UnitController::class, 'update']);
        Route::delete('/kategories/{kategories}', [UnitController::class, 'destroy'])->name('unit.destroy');

        // User management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Suppliers
        Route::get('/supplier', [SupplerController::class, 'index'])->name('supplier.supplier');
        Route::post('/supplier', [SupplerController::class, 'store'])->name('suppliers.store');
        Route::put('/supplier/{supplier}', [SupplerController::class, 'update'])->name('suppliers.update');
        Route::delete('/supplier/{supplier}', [SupplerController::class, 'destroy'])->name('suppliers.destroy');

        // Purchases
        Route::get('/purchases', [App\Http\Controllers\PurchaseController::class, 'index'])->name('purchase.purchase');
        Route::get('/purchases/create', [App\Http\Controllers\PurchaseController::class, 'create'])->name('pembelian.create');
        Route::post('/purchases', [App\Http\Controllers\PurchaseController::class, 'store'])->name('pembelian.store');
        Route::delete('/purchases/{purchase}', [App\Http\Controllers\PurchaseController::class, 'destroy'])->name('pembelian.destroy');
    });