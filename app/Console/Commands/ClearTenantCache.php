<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearTenantCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear tenant caches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \App\Models\Tenant::all()->each(function ($tenant) {
            tenancy()->initialize($tenant);
            cache()->driver('file')->clear();
            tenancy()->end();
        });

        $this->info('Tenant caches cleared');
    }
}
