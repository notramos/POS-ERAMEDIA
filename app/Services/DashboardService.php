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

    public function getTopProducts(int $limit = 10): array
    {
        $topProducts = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->selectRaw('products.id, products.name, SUM(transaction_details.quantity) as total_terjual, SUM(transaction_details.subtotal) as total_pendapatan')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_terjual')
            ->limit($limit)
            ->get();

        return [
            'products' => $topProducts->pluck('name')->toArray(),
            'quantities' => $topProducts->pluck('total_terjual')->toArray(),
            'revenues' => $topProducts->pluck('total_pendapatan')->toArray(),
        ];
    }

    public function getMonthlySalesTrend(int $months = 2): array
    {
        return $this->getMonthlySalesTrendFiltered(null, null, null, $months);
    }

    public function getMonthlySalesTrendFiltered(?string $period, ?string $start, ?string $end, int $fallbackMonths = 2): array
    {
        $now = Carbon::now();
        $query = Transaction::query();
        $groupFormat = '%Y-%m';
        $labelFormat = 'M Y';

        // Period filter takes precedence
        if ($period) {
            switch ($period) {
                case 'week':
                    $startDate = $now->copy()->startOfWeek();
                    $endDate = $now->copy()->endOfWeek();
                    $groupFormat = '%Y-%m-%d';
                    $labelFormat = 'd M';
                    break;
                case 'month':
                    $startDate = $now->copy()->startOfMonth();
                    $endDate = $now->copy()->endOfMonth();
                    $groupFormat = '%Y-%m-%d';
                    $labelFormat = 'd M';
                    break;
                case 'year':
                    $startDate = $now->copy()->startOfYear();
                    $endDate = $now->copy()->endOfYear();
                    $groupFormat = '%Y-%m';
                    $labelFormat = 'M Y';
                    break;
                default:
                    $startDate = $now->copy()->subMonths($fallbackMonths - 1)->startOfMonth();
                    $endDate = $now->copy()->endOfMonth();
            }
        } elseif ($start && $end) {
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();

            // Determine grouping based on range length
            $diffDays = $startDate->diffInDays($endDate);
            if ($diffDays <= 31) {
                $groupFormat = '%Y-%m-%d';
                $labelFormat = 'd M';
            } elseif ($diffDays <= 365) {
                $groupFormat = '%Y-%m';
                $labelFormat = 'M Y';
            } else {
                $groupFormat = '%Y';
                $labelFormat = 'Y';
            }
        } else {
            // Default: last N months
            $startDate = $now->copy()->subMonths($fallbackMonths - 1)->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
        }

        $data = $query->selectRaw("strftime('{$groupFormat}', created_at) as period, SUM(total_price) as total")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period')
            ->toArray();

        // Generate labels and data based on grouping format
        $labels = [];
        $dataTrend = [];

        if ($groupFormat === '%Y-%m-%d') {
            // Daily: iterate each day in range
            $periodObj = CarbonPeriod::create($startDate, $endDate);
            foreach ($periodObj as $date) {
                $key = $date->format('Y-m-d');
                $labels[] = $date->format($labelFormat);
                $dataTrend[] = (float) ($data[$key] ?? 0);
            }
        } elseif ($groupFormat === '%Y') {
            // Yearly grouping
            $startYear = (int) $startDate->format('Y');
            $endYear = (int) $endDate->format('Y');
            for ($y = $startYear; $y <= $endYear; $y++) {
                $key = (string) $y;
                $labels[] = $key;
                $dataTrend[] = (float) ($data[$key] ?? 0);
            }
        } else {
            // Monthly grouping (%Y-%m) - default for period=year and default/custom longer ranges
            $periodObj = CarbonPeriod::create($startDate->startOfMonth(), '1 month', $endDate->startOfMonth());
            foreach ($periodObj as $date) {
                $key = $date->format('Y-m');
                $labels[] = $date->format($labelFormat);
                $dataTrend[] = (float) ($data[$key] ?? 0);
            }
        }

        return [
            'labels' => $labels,
            'data' => $dataTrend,
        ];
    }
}
