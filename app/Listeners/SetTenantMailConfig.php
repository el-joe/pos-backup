<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Stancl\Tenancy\Events\TenancyInitialized;

class SetTenantMailConfig
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenancyInitialized $event)
    {
        $tenant = tenant();

        if (!$tenant) return;

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
    }
}
