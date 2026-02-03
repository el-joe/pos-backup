<?php

namespace Database\Seeders;

use Database\Seeders\Tenant\PermissionSeeder;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            HRMSeeder::class,
        ]);
    }
}
