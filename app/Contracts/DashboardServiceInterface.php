<?php

namespace App\Contracts;

interface DashboardServiceInterface
{
    public function getSummary(array $filters): array;

    public function getSalesChart(array $filters): array;

    public function getProductChart(int $year): array;

    public function getTopProducts(int $limit = 10): array;

    public function getMonthlySalesTrend(int $months = 2): array;

    public function getMonthlySalesTrendFiltered(?string $period, ?string $start, ?string $end): array;
}
