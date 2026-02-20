<?php

namespace App\Services;

use App\Models\Plan;

class PlanPricingService
{
    public function calculate(Plan $plan, string $period = 'month', int $systemsCount = 1): array
    {
        $normalizedPeriod = $period === 'year' ? 'year' : 'month';
        $basePrice = (float) ($normalizedPeriod === 'year' ? $plan->price_year : $plan->price_month);

        $planDiscountPercent = max(0, min(100, (float) ($plan->discount_percent ?? 0)));
        $planDiscountAmount = round($basePrice * ($planDiscountPercent / 100), 2);
        $afterPlanDiscount = max(0, $basePrice - $planDiscountAmount);

        $multiSystemDiscountPercent = $systemsCount > 1
            ? max(0, min(100, (float) ($plan->multi_system_discount_percent ?? 0)))
            : 0.0;
        $multiSystemDiscountAmount = round($afterPlanDiscount * ($multiSystemDiscountPercent / 100), 2);

        $totalDiscountAmount = round($planDiscountAmount + $multiSystemDiscountAmount, 2);
        $finalPrice = max(0, round($basePrice - $totalDiscountAmount, 2));

        $freeTrialMonths = max(0, (int) ($plan->free_trial_months ?? 0));

        return [
            'period' => $normalizedPeriod,
            'base_price' => round($basePrice, 2),
            'plan_discount_percent' => $planDiscountPercent,
            'plan_discount_amount' => $planDiscountAmount,
            'multi_system_discount_percent' => $multiSystemDiscountPercent,
            'multi_system_discount_amount' => $multiSystemDiscountAmount,
            'total_discount_amount' => $totalDiscountAmount,
            'final_price' => $finalPrice,
            'systems_count' => max(1, $systemsCount),
            'free_trial_months' => $freeTrialMonths,
            'is_free_trial' => $freeTrialMonths > 0,
        ];
    }

    public function cycleMonths(string $period): int
    {
        return $period === 'year' ? 12 : 1;
    }
}
