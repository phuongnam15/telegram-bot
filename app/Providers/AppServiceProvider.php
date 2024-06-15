<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\_Transaction\TransactionService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton("app.transactions", function () {
            return new TransactionService();
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
