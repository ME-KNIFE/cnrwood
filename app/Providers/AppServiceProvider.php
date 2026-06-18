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
        // Phase 7A: use Tailwind pagination on public Blade pages.
        // (Filament uses its own pagination internally, so this is safe.)
        Paginator::useTailwind();
    }
}
