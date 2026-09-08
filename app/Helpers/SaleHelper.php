<?php

namespace App\Helpers;

use App\Enums\DiscountTypeEnum;

class SaleHelper
{
    static function itemTotal($product, $withRefund = true) {
        $qty = $product['qty'] ?? $product['quantity'] ?? 0;
        $refunded_qty = $withRefund ? ($product['refunded_qty'] ?? 0) : 0;
        $sell_price = $product['sell_price'] ?? $product['price'] ?? 0;
        return ($qty - $refunded_qty) * $sell_price;
    }

    static function subTotal($products, $withRefund = true) {
        return collect($products)->sum(fn($product) => self::itemTotal($product, $withRefund));
    }

    // as-issued totals, ignoring refunds entirely
    static function grossItemTotal($product) {
        return self::itemTotal($product, false);
    }

    static function grossSubTotal($products) {
        return self::subTotal($products, false);
    }

    private static function calcDiscount($subTotal, $discount_type = null, $discount_value = 0, $max = 0, $threshold = null) {
        if (!$discount_type || !$discount_value) return 0;
        if ($threshold !== null && $threshold > 0 && $subTotal < $threshold) return 0;

        if ($discount_type == DiscountTypeEnum::FIXED->value) {
            $amount = (float) $discount_value;
            if ($max) $amount = min($amount, $max);
            $amount = min($amount, $subTotal);
            return max(0, $amount);
        }

        // percentage
        $amount = $subTotal * $discount_value / 100;
        if ($max) $amount = min($amount, $max);
        $amount = min($amount, $subTotal);
        return max(0, $amount);
    }

    static function discountAmount($products, $discount_type = null, $discount_value = 0, $max = 0, $threshold = null, $withRefund = true) {
        $subTotal = self::subTotal($products, $withRefund);
        return numFormat(self::calcDiscount($subTotal, $discount_type, $discount_value, $max, $threshold), 2);
    }

    static function grossDiscountAmount($products, $discount_type = null, $discount_value = 0, $max = 0, $threshold = null) {
        return self::discountAmount($products, $discount_type, $discount_value, $max, $threshold, false);
    }

    static function singleDiscountAmount($product, $products, $discount_type = null, $discount_value = 0, $max = 0, $threshold = null) {
        $subTotal = self::subTotal($products, false);
        if ($discount_type && $discount_value) {
            $total = self::itemTotal($product);
            $percentageItemFromTotal = $subTotal ? ($total / $subTotal) : 0;
            $allProducts = collect($products->toArray())->map(function($item){
                $item['refunded_qty'] = 0;
                return $item;
            });

            $totalDiscount = self::calcDiscount($subTotal, $discount_type, $discount_value, $max, $threshold);
            return numFormat($totalDiscount * $percentageItemFromTotal, 2);
        }
        return 0;
    }

    static function taxAmount($products, $discount_type = null, $discount_value = 0, $tax_percentage = 0, $max = 0, $threshold = null, $withRefund = true) : float {
        $totalItems = collect($products)->sum(fn($q) => self::itemTotal($q, $withRefund));
        $subTotal = self::subTotal($products, $withRefund);
        $totalDiscount = self::calcDiscount($subTotal, $discount_type, $discount_value, $max, $threshold);

        $tax = collect($products)->sum(function($product) use ($tax_percentage, $totalItems, $totalDiscount, $withRefund) {
            $total = self::itemTotal($product, $withRefund);

            $percentageItemFromTotal = $totalItems ? ($total / $totalItems) : 0;

            $itemDiscount = $totalDiscount * $percentageItemFromTotal;

            $taxableBase = $total - $itemDiscount;

            if (($product['taxable'] ?? 0) == 1 && $tax_percentage) {
                return $taxableBase * $tax_percentage / 100;
            }
            return 0;
        });

        return numFormat($tax, 2);
    }

    static function grossTaxAmount($products, $discount_type = null, $discount_value = 0, $tax_percentage = 0, $max = 0, $threshold = null) : float {
        return self::taxAmount($products, $discount_type, $discount_value, $tax_percentage, $max, $threshold, false);
    }

    static function singleTaxAmount($product, $products, $discount_type = null, $discount_value = 0, $tax_percentage = 0, $max = 0, $threshold = null) {
        if (($product['taxable'] ?? 0) != 1 || !$tax_percentage) return 0;

        $allProducts = collect($products->toArray())->map(function($item){
            $item['refunded_qty'] = 0;
            return $item;
        });

        $total = self::itemTotal($product);

        $totalDiscount = self::singleDiscountAmount($product, $allProducts, $discount_type, $discount_value, $max, $threshold);

        $taxableBase = $total - $totalDiscount;

        return numFormat($taxableBase * $tax_percentage / 100, 2);
    }

    static function grandTotal($products, $discount_type = null, $discount_value = 0, $tax_percentage = 0, $max_discount_amount = 0, $threshold = null, $withRefund = true) {
        $subTotal = self::subTotal($products, $withRefund);
        $discount = self::calcDiscount($subTotal, $discount_type, $discount_value, $max_discount_amount, $threshold);
        $tax = self::taxAmount($products, $discount_type, $discount_value, $tax_percentage, $max_discount_amount, $threshold, $withRefund);
        return numFormat(max(0, ($subTotal - $discount) + $tax), 2);
    }

    static function grossGrandTotal($products, $discount_type = null, $discount_value = 0, $tax_percentage = 0, $max_discount_amount = 0, $threshold = null) {
        return self::grandTotal($products, $discount_type, $discount_value, $tax_percentage, $max_discount_amount, $threshold, false);
    }

    static function singleGrandTotal($product, $products, $discount_type = null, $discount_value = 0, $tax_percentage = 0, $max_discount_amount = 0, $threshold = null)  {
        $subTotal = self::subTotal([$product]);
        $discount = self::singleDiscountAmount($product, $products, $discount_type, $discount_value, $max_discount_amount, $threshold);
        $tax = self::singleTaxAmount($product, $products, $discount_type, $discount_value, $tax_percentage, $max_discount_amount, $threshold);
        return numFormat(max(0, ($subTotal - $discount) + $tax), 2);
    }

    static function getGrandTotalQuery(){
        $discountExpr = "(CASE
            WHEN s.discount_type = 'fixed' THEN
                LEAST(
                    COALESCE(ss.sale_subtotal,0),
                    CASE WHEN COALESCE(s.max_discount_amount,0) > 0 THEN LEAST(COALESCE(s.discount_value,0), s.max_discount_amount) ELSE COALESCE(s.discount_value,0) END
                )
            WHEN s.discount_type = 'percentage' THEN
                LEAST(
                    COALESCE(ss.sale_subtotal,0),
                    CASE
                        WHEN COALESCE(s.max_discount_amount,0) = 0 THEN COALESCE(ss.sale_subtotal,0) * COALESCE(s.discount_value,0) / 100
                        WHEN COALESCE(ss.sale_subtotal,0) * COALESCE(s.discount_value,0) / 100 > s.max_discount_amount THEN s.max_discount_amount
                        ELSE COALESCE(ss.sale_subtotal,0) * COALESCE(s.discount_value,0) / 100
                    END
                )
            ELSE 0
        END)";

        // eligibility: threshold null/0 => always eligible, else subtotal must meet threshold
        $eligibleDiscountExpr = "(CASE WHEN COALESCE(s.sales_threshold,0) > 0 AND COALESCE(ss.sale_subtotal,0) < s.sales_threshold THEN 0 ELSE {$discountExpr} END)";

        $taxBaseExpr = "GREATEST(0,
            COALESCE(ss.taxable_subtotal,0)
            - ({$eligibleDiscountExpr}) * (CASE WHEN COALESCE(ss.sale_subtotal,0) > 0 THEN COALESCE(ss.taxable_subtotal,0) / COALESCE(ss.sale_subtotal,0) ELSE 0 END)
        )";

        $taxExpr = "(CASE WHEN COALESCE(s.tax_percentage,0) > 0 THEN ({$taxBaseExpr}) * (COALESCE(s.tax_percentage,0) / 100) ELSE 0 END)";

        $grandTotalExpr = "GREATEST(0, COALESCE(ss.sale_subtotal,0) - ({$eligibleDiscountExpr}) + ({$taxExpr}))";

        return "WITH sale_summaries AS (
                SELECT
                    sale_id,
                    SUM( (qty - COALESCE(refunded_qty,0)) * sell_price ) AS sale_subtotal,
                    SUM( qty - COALESCE(refunded_qty,0) ) AS total_items,
                    SUM( CASE WHEN taxable = 1 THEN (qty - COALESCE(refunded_qty,0)) * sell_price ELSE 0 END ) AS taxable_subtotal
                FROM sale_items
                GROUP BY sale_id
            )
SELECT
  s.id AS sale_id,
  COALESCE(ss.total_items, 0) AS total_items,
  COALESCE(ss.sale_subtotal, 0) AS sub_total,
  ROUND({$eligibleDiscountExpr}, 2) AS discount,
  ROUND({$taxExpr}, 2) AS tax,
  ROUND({$grandTotalExpr}, 2) AS grand_total

FROM sales s
LEFT JOIN sale_summaries ss ON ss.sale_id = s.id;";
    }
}
