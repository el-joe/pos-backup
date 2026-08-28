<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(
            ['slug' => 'monthly'],
            [
                'name' => 'Monthly Plan',
                'name_en' => 'Monthly Plan',
                'name_ar' => 'الخطة الشهرية',
                'price_month' => 99.00,
                'price_year' => 0,
                'active' => true,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'annual'],
            [
                'name' => 'Annual Plan',
                'name_en' => 'Annual Plan',
                'name_ar' => 'الخطة السنوية',
                'price_month' => 0,
                'price_year' => 990.00,
                'active' => true,
            ]
        );
    }
}
