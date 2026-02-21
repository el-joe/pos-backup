<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Tenant\PermissionSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // PlanSeeder::class,
            // CpanelSeeder::class,
            // BlogSeeder::class,
            // FaqSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
