<?php

namespace App\Helpers;

use App\Enums\DiscountTypeEnum;

class PurchaseHelper
{
    static function calcSubtotal($orderProductsTotal, $expensesTotal) {
        return $orderProductsTotal + $expensesTotal;
    }

    static function calcDiscount($discountableBase = 0, $discountType = null, $discountValue = 0, $max = 0, $threshold = null) {
        $discountType = $discountType ?? null;
        $discountValue = $discountValue ?? 0;

        if (!$discountType || !$discountValue) return 0;
        if ($threshold !== null && $threshold > 0 && $discountableBase < $threshold) return 0;

        if ($discountType == DiscountTypeEnum::FIXED->value) {
            $amount = (float) $discountValue;
        } elseif ($discountType == DiscountTypeEnum::PERCENTAGE->value) {
            $amount = $discountableBase * $discountValue / 100;
        } else {
            return 0;
        }

        if ($max) $amount = min($amount, $max);
        $amount = min($amount, $discountableBase);

        return max(0, $amount);
    }

    static function calcTotalAfterDiscount($subTotal = 0, $discountAmount = 0) {
        return $subTotal - $discountAmount;
    }

    static function calcTax($totalAfterDiscount = 0, $taxRate = 0) {
        $total = $totalAfterDiscount;
        return ($total * ($taxRate ?? 0) / 100);
    }

    static function calcGrandTotal($totalAfterDiscount = 0, $taxAmount = 0) {
        return max(0, $totalAfterDiscount + $taxAmount);
    }
}
