<?php

namespace App\Helpers;

class SaleHelper
{
    static function itemTotal($product) : float {
        $qty = $product['qty'] ?? $product['quantity'] ?? 0;
        $refunded_qty = $product['refunded_qty'] ?? 0;
        $sell_price = $product['sell_price'] ?? $product['price'] ?? 0;
        return ($qty - $refunded_qty) * $sell_price;

    }

    static function subTotal($products){
        return collect($products)->sum(function($product){
            return self::itemTotal($product);
        });
    }

    static function discountAmount($products, $discount_type = null, $discount_value = 0) {
        $subTotal = self::subTotal($products);
        if($discount_type && $discount_value) {
            if($discount_type == 'fixed') {
                return $discount_value;
            } else {
                return ($subTotal * $discount_value / 100);
            }
        }
        return 0;
    }

    static function taxAmount($products, $discount_type = null, $discount_value = 0, $tax_percentage = 0) {
        return collect($products)->sum(function($product) use ($discount_type, $discount_value, $tax_percentage) {
            $total = self::itemTotal($product);

            $total -= self::discountAmount([$product], $discount_type, $discount_value);

            if(($product['taxable'] ?? 0) == 1 && $tax_percentage) {
                return ($total * $tax_percentage / 100);
            }
            return 0;
        });
    }

    static function grandTotal($products, $discount_type = null, $discount_value = 0, $tax_percentage = 0) {
        $subTotal = self::subTotal($products);
        $discount = self::discountAmount($products, $discount_type, $discount_value);
        $tax = self::taxAmount($products, $discount_type, $discount_value, $tax_percentage);

        return ($subTotal - $discount) + $tax;
    }
}
