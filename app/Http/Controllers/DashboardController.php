<?php

namespace App\Http\Controllers;

use App\Contracts\DashboardServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected DashboardServiceInterface $dashboardService;

    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    protected function getService()
    {
        return $this->dashboardService;
    }

    public function index(Request $request)
    {
        $filters = [
            'start' => $request->get('start'),
            'end' => $request->get('end'),
        ];

        $summary = $this->dashboardService->getSummary($filters);
        $salesChart = $this->dashboardService->getSalesChart($filters);

        $tahunDipilih = $request->get('tahun_produk', date('Y'));
        $tahunList = range(date('Y'), date('Y') - 5);
        $productChart = $this->dashboardService->getProductChart((int) $tahunDipilih);
        Log::info('Dashboard data', [
            'summary' => $summary,
            'salesChart' => $salesChart,
            'productChart' => $productChart,
        ]);

        return view('dashboard.dashboard', array_merge(
            $summary,
            $salesChart,
            $productChart,
            [
                'start' => $filters['start'],
                'end' => $filters['end'],
                'tahun_dipilih' => $tahunDipilih,
                'tahun_list' => $tahunList,
            ]
        ));
    }
}
