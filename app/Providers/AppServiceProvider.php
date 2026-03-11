<?php

namespace App\Providers;

use App\Services\BranchService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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

        Paginator::defaultView($defaultLayout === 'tenant-tailwind-gemini'
            ? 'vendor.pagination.tenant-tailwind-gemini'
            : 'vendor.pagination.default5');

        Paginator::defaultSimpleView($defaultLayout === 'tenant-tailwind-gemini'
            ? 'vendor.pagination.simple-tailwind'
            : 'vendor.pagination.simple-default');

        // share layout to all views
        View::share('defaultLayout', function () use ($defaultLayout) {
            return $defaultLayout;
        });

        Blade::directive('adminCan', function ($expression) {
            return "<?php if(adminCan($expression)): ?>";
        });

        Blade::directive('endadminCan', function () {
            return "<?php endif; ?>";
        });

    }
}
