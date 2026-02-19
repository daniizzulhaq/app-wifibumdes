<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Pelanggan;
use App\Observers\PelangganObserver;

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
        // Gunakan Bootstrap untuk pagination
        Paginator::useBootstrapFive();
        // Atau jika menggunakan Bootstrap 4:
        // Paginator::useBootstrap();
        Pelanggan::observe(PelangganObserver::class);
    }
}