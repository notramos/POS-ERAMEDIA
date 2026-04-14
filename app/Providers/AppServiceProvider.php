<?php

namespace App\Providers;

use App\Contracts\DashboardServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\Contracts\PurchaseServiceInterface;
use App\Contracts\TransactionServiceInterface;
use App\Services\DashboardService;
use App\Services\ProductService;
use App\Services\PurchaseService;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
        $this->app->bind(PurchaseServiceInterface::class, PurchaseService::class);
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
    }

    public function boot(): void
    {
        //
    }
}
