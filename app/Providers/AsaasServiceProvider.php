<?php

namespace App\Providers;

use App\Api\Asaas\Customers;
use App\Api\Asaas\Payments;
use Illuminate\Support\ServiceProvider;

class AsaasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Customers::class, fn() => new Customers(config('asaas.access_token'), config('asaas.host')));
        $this->app->singleton(Payments::class, fn() => new Payments(config('asaas.access_token'), config('asaas.host')));
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
