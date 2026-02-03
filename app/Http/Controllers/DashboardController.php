<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index()
{
    $today = Carbon::today();

    // === Data Card (sudah ada) ===
    $penjualanHariIni = Transaction::whereDate('created_at', $today)->sum('total_price');
    $transaksiHariIni = Transaction::whereDate('created_at', $today)->count();
    $produkTerjual = DB::table('transaction_details')
        ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
        ->whereDate('transactions.created_at', $today)
        ->sum('transaction_details.quantity');
    $totalPelanggan = User::count();
    $produkTersedia = Product::sum('stock');
    $pembeliansHariIni = Purchase::whereDate('purchase_date', $today)->sum('total_amount');

    // === Data Chart: Penjualan & Pembelian ===
    $last7Days = [];
    $salesLast7Days = [];
    $purchasesLast7Days = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i)->toDateString();
        $label = Carbon::now()->subDays($i)->format('d M');
        $last7Days[] = $label;

        // Penjualan
        $sales = Transaction::whereDate('created_at', $date)->sum('total_price');
        $salesLast7Days[] = (float) $sales;

        // Pembelian — GANTI SESUAI NAMA MODEL & KOLOM KAMU!
        $purchases = \App\Models\Purchase::whereDate('purchase_date', $date)->sum('total_amount');
        $purchasesLast7Days[] = (float) $purchases;
    }

    // === Data Bulanan: Penjualan vs Pembelian ===
    $currentYear = Carbon::now()->year;
    $months = [];
    $monthlySales = [];
    $monthlyPurchases = [];

    for ($m = 1; $m <= 12; $m++) {
        $monthName = Carbon::create()->month($m)->format('M');
        $months[] = $monthName;

        // Penjualan bulanan
        $salesCount = Transaction::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $m)
            ->sum('total_price'); 
        $monthlySales[] = (float) $salesCount;

        // Pembelian bulanan
        $purchaseCount = \App\Models\Purchase::whereYear('purchase_date', $currentYear)
            ->whereMonth('purchase_date', $m)
            ->sum('total_amount');
        $monthlyPurchases[] = (float) $purchaseCount;
    }

    return view('dashboard.dashboard', compact(
        'penjualanHariIni',
        'transaksiHariIni',
        'produkTerjual',
        'totalPelanggan',
        'produkTersedia',
        'pembeliansHariIni',
        // Chart data
        'last7Days',
        'salesLast7Days',
        'purchasesLast7Days',
        'months',
        'monthlySales',
        'monthlyPurchases'
    ));
}
}
