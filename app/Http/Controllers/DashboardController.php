<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Penjualan Hari Ini
        $penjualanHariIni = Transaction::whereDate('created_at', $today)
            ->sum('total_price');

        // 2. Transaksi Hari Ini
        $transaksiHariIni = Transaction::all()->count();

        // 3. Produk Terjual Hari Ini
        $produkTerjual = DB::table('transactions')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->sum('transaction_details.quantity');

        // 4. Total Pelanggan
        $totalPelanggan = User::count();

        return view('home', compact(
            'penjualanHariIni',
            'transaksiHariIni',
            'produkTerjual',
            'totalPelanggan'
        ));
    }
}
