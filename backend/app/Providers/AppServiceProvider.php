<?php

namespace App\Providers;

use App\Services\CategoryService;
use App\Services\CategoryServiceInterface;
use App\Services\InvoiceService;
use App\Services\InvoiceServiceInterface;
use App\Services\MonthlySaleService;
use App\Services\MonthlySaleServiceInterface;
use App\Services\PlanService;
use App\Services\PlanServiceInterface;
use App\Services\RequestService;
use App\Services\RequestServiceInterface;
use App\Services\ServiceJobItemService;
use App\Services\ServiceJobItemServiceInterface;
use App\Services\ServiceTypeService;
use App\Services\ServiceTypeServiceInterface;
use App\Services\ShopService;
use App\Services\ShopServiceInterface;
use App\Services\StatsService;
use App\Services\StatsServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RequestServiceInterface::class, RequestService::class);
        $this->app->bind(InvoiceServiceInterface::class, InvoiceService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(\App\Services\ItemServiceInterface::class, \App\Services\ItemService::class);
        $this->app->bind(\App\Services\CustomerServiceInterface::class, \App\Services\CustomerService::class);
        $this->app->bind(\App\Services\StockMovementServiceInterface::class, \App\Services\StockMovementService::class);
        $this->app->bind(\App\Services\ServiceJobServiceInterface::class, \App\Services\ServiceJobService::class);
        $this->app->singleton(ServiceJobItemServiceInterface::class, ServiceJobItemService::class);
        $this->app->singleton(ShopServiceInterface::class, ShopService::class);
        $this->app->singleton(PlanServiceInterface::class, PlanService::class);
        $this->app->singleton(StatsServiceInterface::class, StatsService::class);
        $this->app->singleton(MonthlySaleServiceInterface::class, MonthlySaleService::class);
        $this->app->singleton(ServiceTypeServiceInterface::class, ServiceTypeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
