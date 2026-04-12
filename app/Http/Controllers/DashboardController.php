<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier; // Pastikan model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Dashboard Request:', $request->all());
        // === FILTER TANGGAL ===
    $start = $request->get('start');
    $end = $request->get('end');

    if ($start && $end) {
        $startDate = Carbon::parse($start)->startOfDay();
        $endDate = Carbon::parse($end)->endOfDay();
        $isFiltered = true;
    } else {
        $startDate = null;
        $endDate = null;
        $isFiltered = false;
    }
    Log::info('Filter Status', [
    'start' => $start,
    'end' => $end,
    'startDate' => $startDate,
    'endDate' => $endDate,
    'isFiltered' => $isFiltered,
]);

    // === TOTAL UTAMA ===
    $total_penjualan = $isFiltered 
        ? Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_price')
        : Transaction::sum('total_price');

    $total_pembelian = $isFiltered 
        ? Purchase::whereBetween('purchase_date', [$startDate, $endDate])->sum('total_amount')
        : Purchase::sum('total_amount');

    $total_supplier = Supplier::count();
    $produk_tersedia = Product::sum('stock');

    // === GRAFIK 1: PENJUALAN VS PEMBELIAN (HARIAN) ===
    if ($isFiltered) {
        // Ambil semua hari dalam rentang
        $period = CarbonPeriod::create($startDate, $endDate);
        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->toDateString();
        }
    } else {
        // Ambil semua tanggal unik dari transaksi & pembelian
        $saleDates = Transaction::selectRaw('DATE(created_at) as date')
            ->groupBy('date')->pluck('date')->toArray();
        $purchaseDates = Purchase::selectRaw('DATE(purchase_date) as date')
            ->groupBy('date')->pluck('date')->toArray();
        $dates = array_values(array_unique(array_merge($saleDates, $purchaseDates)));
        sort($dates); // urutkan dari lama ke baru
    }

    $label_harian = [];
    $penjualan_harian = [];
    $pembelian_harian = [];

    foreach ($dates as $dateStr) {
        $label_harian[] = Carbon::parse($dateStr)->format('d M Y');
        $penjualan_harian[] = (float) Transaction::whereDate('created_at', $dateStr)
            ->when($isFiltered, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->sum('total_price');
        $pembelian_harian[] = (float) Purchase::whereDate('purchase_date', $dateStr)
            ->when($isFiltered, fn($q) => $q->whereBetween('purchase_date', [$startDate, $endDate]))
            ->sum('total_amount');
    }

    // === GRAFIK 2: PRODUK TERJUAL PER BULAN (DENGAN DROPDOWN TAHUN) ===
    $tahun_dipilih = $request->get('tahun_produk', date('Y'));
    $tahun_list = range(date('Y'), date('Y') - 5); // 6 tahun terakhir

    $bulan_produk = [];
    $data_produk = [];
    for ($m = 1; $m <= 12; $m++) {
        $bulan_produk[] = Carbon::create()->month($m)->format('M');
        $qty = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereYear('transactions.created_at', $tahun_dipilih)
            ->whereMonth('transactions.created_at', $m)
            ->sum('transaction_details.quantity');
        $data_produk[] = (int) $qty;
    }

    Log::info('Grafik 1 Data', [
    'dates' => $dates,
    'label_harian' => $label_harian,
    'penjualan_harian' => $penjualan_harian,
    'pembelian_harian' => $pembelian_harian,
]);
    return view('dashboard.dashboard', compact(
        'total_penjualan',
        'total_pembelian',
        'total_supplier',
        'produk_tersedia',
        // Grafik 1
        'label_harian',
        'penjualan_harian',
        'pembelian_harian',
        'start',
        'end',
        // Grafik 2
        'tahun_dipilih',
        'tahun_list',
        'bulan_produk',
        'data_produk'
    ));
    }
}