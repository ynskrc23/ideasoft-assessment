<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Services\Product\ProductServiceInterface;
use App\Services\Product\ProductService;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Customer\CustomerRepository;
use App\Services\Customer\CustomerServiceInterface;
use App\Services\Customer\CustomerService;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Services\Order\OrderServiceInterface;
use App\Services\Order\OrderService;
use App\Services\DiscountRules\DiscountService;
use App\Services\DiscountRules\TenPercentOverThousandRule;
use App\Services\DiscountRules\BuyFiveGetOneFreeRule;
use App\Services\DiscountRules\TwentyPercentCheapestCategoryOneRule;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);

        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(CustomerServiceInterface::class, CustomerService::class);

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);

        $this->app->bind(DiscountService::class, function ($app) {
            return new DiscountService([
                new TenPercentOverThousandRule(),
                new BuyFiveGetOneFreeRule(),
                new TwentyPercentCheapestCategoryOneRule()
            ]);
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
