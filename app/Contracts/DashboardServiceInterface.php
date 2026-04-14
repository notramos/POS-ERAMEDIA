<?php

namespace App\Contracts;

interface DashboardServiceInterface
{
    public function getSummary(array $filters): array;

    public function getSalesChart(array $filters): array;

    public function getProductChart(int $year): array;
}
