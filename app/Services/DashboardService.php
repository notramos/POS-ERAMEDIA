<?php

namespace App\Services;

use App\Contracts\DashboardServiceInterface;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardService implements DashboardServiceInterface
{
    public function getSummary(array $filters): array
    {
        $isFiltered = ! empty($filters['start']) && ! empty($filters['end']);

        $startDate = $isFiltered ? Carbon::parse($filters['start'])->startOfDay() : null;
        $endDate = $isFiltered ? Carbon::parse($filters['end'])->endOfDay() : null;

        $totalPenjualan = $isFiltered
            ? Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_price')
            : Transaction::sum('total_price');

        $totalPembelian = $isFiltered
            ? Purchase::whereBetween('purchase_date', [$startDate, $endDate])->sum('total_amount')
            : Purchase::sum('total_amount');

        return [
            'total_penjualan' => $totalPenjualan,
            'total_pembelian' => $totalPembelian,
            'total_supplier' => Supplier::count(),
            'produk_tersedia' => Product::sum('stock'),
        ];
    }

    public function getSalesChart(array $filters): array
    {
        $isFiltered = ! empty($filters['start']) && ! empty($filters['end']);

        $startDate = $isFiltered ? Carbon::parse($filters['start'])->startOfDay() : null;
        $endDate = $isFiltered ? Carbon::parse($filters['end'])->endOfDay() : null;

        $salesQuery = Transaction::selectRaw("strftime('%Y-%m-%d', created_at) as date, SUM(total_price) as total")
            ->when($isFiltered, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $purchaseQuery = Purchase::selectRaw("strftime('%Y-%m-%d', purchase_date) as date, SUM(total_amount) as total")
            ->when($isFiltered, fn ($q) => $q->whereBetween('purchase_date', [$startDate, $endDate]))
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        if ($isFiltered) {
            $period = CarbonPeriod::create($startDate, $endDate);
            $dates = [];
            foreach ($period as $date) {
                $dates[] = $date->toDateString();
            }
        } else {
            $dates = array_values(array_unique(array_merge(array_keys($salesQuery), array_keys($purchaseQuery))));
            sort($dates);
        }

        $labelHarian = [];
        $penjualanHarian = [];
        $pembelianHarian = [];

        foreach ($dates as $dateStr) {
            $labelHarian[] = Carbon::parse($dateStr)->format('d M Y');
            $penjualanHarian[] = (float) ($salesQuery[$dateStr] ?? 0);
            $pembelianHarian[] = (float) ($purchaseQuery[$dateStr] ?? 0);
        }

        return [
            'label_harian' => $labelHarian,
            'penjualan_harian' => $penjualanHarian,
            'pembelian_harian' => $pembelianHarian,
        ];
    }

    public function getProductChart(int $year): array
    {
        $data = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->selectRaw("strftime('%m', transactions.created_at) as month, SUM(transaction_details.quantity) as total")
            ->whereYear('transactions.created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $bulanProduk = [];
        $dataProduk = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthKey = str_pad($m, 2, '0', STR_PAD_LEFT);
            $bulanProduk[] = Carbon::create()->month($m)->format('M');
            $dataProduk[] = (int) ($data[$monthKey] ?? 0);
        }

        return [
            'bulan_produk' => $bulanProduk,
            'data_produk' => $dataProduk,
        ];
    }
}
