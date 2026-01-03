<?php

namespace App\Providers;

use App\Services\BranchService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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

        $defaultLayout = defaultLayout();

        Paginator::defaultView('vendor.pagination.default' . ($defaultLayout == 'hud' ? '5' : ''));

        // share layout to all views
        View::share('defaultLayout', function () use ($defaultLayout) {
            return $defaultLayout;
        });
    }
}
