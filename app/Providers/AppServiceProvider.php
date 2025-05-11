<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use App\Http\Repositories\Interfaces\ProductRepoInterface;
use App\Http\Repositories\Eloquent\ProductRepository;
use App\Http\Repositories\Interfaces\ImportRepoInterface;
use App\Http\Repositories\Eloquent\ImportRepository;
use App\Http\Repositories\Interfaces\CustomerRepoInterface;
use App\Http\Repositories\Eloquent\CustomerRepository;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(ProductRepoInterface::class, ProductRepository::class);
        $this->app->bind(CustomerRepoInterface::class, CustomerRepository::class);
        $this->app->bind(ImportRepoInterface::class, ImportRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap(); // <- Thêm dòng này

        

        //
    }
}
