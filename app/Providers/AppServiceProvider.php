<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Category; 

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
    public function boot() 
    {
        Paginator::useBootstrapFive(); // Paksa pakai style Bootstrap 5
        // Bagikan data kategori ke view partials.store-navbar
        view()->composer(['partials.store-navbar', 'partials.store-footer'], function ($view) {
            $topCategories = \App\Models\Category::withCount('products')
                ->orderBy('products_count', 'desc')
                ->take(3)
                ->get();

            $view->with('topCategories', $topCategories);
        });
    }
}
