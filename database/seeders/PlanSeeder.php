<?php

namespace Database\Seeders;

use App\Enums\PlanFeaturesEnum;
use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [];

        $plans[] = [
            'name' => 'Basic',
            'price_month' => 0,
            'price_year' => 0,
            'features' => [
                PlanFeaturesEnum::BRANCHES->value => [
                    'limit' => 1,
                    'status' => true,
                    'description' => '1'
                ],
                PlanFeaturesEnum::ADMINS->value => [
                    'limit' => 1,
                    'status' => true,
                    'description' => '1'
                ],
                PlanFeaturesEnum::PRODUCTS->value => [
                    'limit' => 100,
                    'status' => true,
                    'description' => 'Up to 100'
                ],
                PlanFeaturesEnum::POS->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::INVENTORY->value => [
                    'status' => false
                ],
                PlanFeaturesEnum::SALES->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::PURCHASES->value => [
                    'status' => false
                ],
                PlanFeaturesEnum::DOUBLE_ENTRY_ACCOUNTING->value => [
                    'status' => false
                ],
                PlanFeaturesEnum::BASIC_REPORTS->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::ADVANCED_REPORTS->value => [
                    'status' => false
                ],
                PlanFeaturesEnum::DISCOUNTS->value => [
                    'status' => false
                ],
                PlanFeaturesEnum::TAXES->value => [
                    'status' => false
                ],
                PlanFeaturesEnum::CUSTOMER_SUPPORT->value => [
                    'status' => true,
                    'description' => 'Email Support'
                ],
            ],
            'slug' => 'basic',
            'active' => true,
            'icon' => 'bi bi-star',
        ];

        $plans[] = [
            'name' => 'Pro',
            'price_month' => 19,
            'price_year' => 190,
            'features' => [
                PlanFeaturesEnum::BRANCHES->value => [
                    'limit' => 5,
                    'status' => true,
                    'description' => '5'
                ],
                PlanFeaturesEnum::ADMINS->value => [
                    'limit' => 10,
                    'status' => true,
                    'description' => '10'
                ],
                PlanFeaturesEnum::PRODUCTS->value => [
                    'limit' => 1000,
                    'status' => true,
                    'description' => '1000'
                ],
                PlanFeaturesEnum::POS->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::INVENTORY->value => [
                    'status' => true,
                    'description' => 'Advanced'
                ],
                PlanFeaturesEnum::SALES->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::PURCHASES->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::DOUBLE_ENTRY_ACCOUNTING->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::BASIC_REPORTS->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::ADVANCED_REPORTS->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::DISCOUNTS->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::TAXES->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::CUSTOMER_SUPPORT->value => [
                    'status' => true,
                    'description' => 'Standard'
                ],
            ],
            'slug' => 'pro',
            'active' => true,
            'icon' => 'bi bi-lightning-charge-fill',
        ];

        // Enterprise Plan
        $plans[] = [
            'name' => 'Enterprise',
            'price_month' => 39,
            'price_year' => 390,
            'features' => [
                PlanFeaturesEnum::BRANCHES->value => [
                    'limit' => null,
                    'status' => true,
                    'description' => 'Unlimited'
                ],
                PlanFeaturesEnum::ADMINS->value => [
                    'limit' => null,
                    'status' => true,
                    'description' => 'Unlimited'
                ],
                PlanFeaturesEnum::PRODUCTS->value => [
                    'limit' => null,
                    'status' => true,
                    'description' => 'Unlimited'
                ],
                PlanFeaturesEnum::POS->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::INVENTORY->value => [
                    'status' => true,
                    'description' => 'Professional'
                ],
                PlanFeaturesEnum::SALES->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::PURCHASES->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::DOUBLE_ENTRY_ACCOUNTING->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::BASIC_REPORTS->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::ADVANCED_REPORTS->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::DISCOUNTS->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::TAXES->value => [
                    'status' => true
                ],
                PlanFeaturesEnum::CUSTOMER_SUPPORT->value => [
                    'status' => true,
                    'description' => 'VIP'
                ],
            ],
            'slug' => 'enterprise',
            'active' => true,
            'icon' => 'bi bi-gem',
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
