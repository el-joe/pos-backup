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
                'type' => 'monthly',
                'price' => 99.00,
                'active' => true,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'annual'],
            [
                'name' => 'Annual Plan',
                'name_en' => 'Annual Plan',
                'name_ar' => 'الخطة السنوية',
                'type' => 'yearly',
                'price' => 990.00,
                'active' => true,
            ]
        );
    }
}
