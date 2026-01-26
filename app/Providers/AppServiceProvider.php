<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // This project uses Bootstrap (not Tailwind) for frontend styling.
        // Use Bootstrap pagination views to avoid "double" pagination blocks.
        Paginator::useBootstrapFive();
    }
}
