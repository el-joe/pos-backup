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
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => env('MAIL_HOST', 'smtp.hostinger.com'),
            'mail.mailers.smtp.port' => env('MAIL_PORT', 587),
            'mail.mailers.smtp.username' => env('MAIL_USERNAME', 'youssef@codefanz.com'),
            'mail.mailers.smtp.password' => env('MAIL_PASSWORD', 'Anaeljoe@1230'),
            'mail.mailers.smtp.encryption' => env('MAIL_ENCRYPTION', 'TLS'),
            'mail.from.address' => env('MAIL_FROM_ADDRESS', 'no-reply@codefanz.com'),
            'mail.from.name' => env('MAIL_FROM_NAME', 'Mohaaseb'),
        ]);

        $defaultLayout = defaultLayout();

        Paginator::defaultView('vendor.pagination.default' . ($defaultLayout == 'hud' ? '5' : ''));

        // share layout to all views
        View::share('defaultLayout', function () use ($defaultLayout) {
            return $defaultLayout;
        });
    }
}
