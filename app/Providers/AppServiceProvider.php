<?php

namespace App\Providers;

use App\Services\PriceService;
use App\Interfaces\PriceInterface;
use App\Services\PriceDiscountService;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\ProductRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
 

    public function register(): void
{
    $discount = config('app.discount');
    $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

    $this->app->bind(PriceInterface::class, function () use ($discount) {
        if ($discount !== null) {
            return new PriceDiscountService($discount);
        }
        return new PriceService();
    });
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
