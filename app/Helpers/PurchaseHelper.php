<?php

namespace App\Helpers;

class PurchaseHelper
{
    static function calcSubtotal($orderProductsTotal, $expensesTotal) {
        return $orderProductsTotal + $expensesTotal;
    }

    static function calcDiscount($subTotal = 0,$discountType = null,$discountValue = 0) {
        $total = $subTotal;
        $discountType = $discountType  ?? null;
        $discountAmount = 0;
        if($discountType == 'fixed') {
            $discountAmount = $discountValue ?? 0;
        } elseif($discountType == 'percentage') {
            $discountAmount = ($total * ($discountValue ?? 0) / 100);
        }
        return $discountAmount;
    }

    static function calcTotalAfterDiscount($subTotal = 0, $discountAmount = 0) {
        return $subTotal - $discountAmount;
    }

    static function calcTax($totalAfterDiscount = 0, $taxRate = 0) {
        $total = $totalAfterDiscount;
        return ($total * ($taxRate ?? 0) / 100);
    }

    static function calcGrandTotal($totalAfterDiscount = 0, $taxAmount = 0) {
        return $totalAfterDiscount + $taxAmount;
    }
}
