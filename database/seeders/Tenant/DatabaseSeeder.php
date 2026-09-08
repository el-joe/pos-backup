<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Root seeder run against every new tenant database (see config/tenancy.php
     * seeder_parameters and TenancyServiceProvider's TenantCreated pipeline).
     */
    public function run(): void
    {
        $this->call([
            ChartOfAccountsSeeder::class,
        ]);
    }
}
