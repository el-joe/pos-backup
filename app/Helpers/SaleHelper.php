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

    static function discountAmount($products, $discount_type = null, $discount_value = 0,$max = 0) {
        $subTotal = self::subTotal($products);
        if($discount_type && $discount_value) {
            if($discount_type == 'fixed') {
                $amount = $discount_value;
                if($subTotal > $max) {
                    return $amount;
                }
                return 0;
            } else {
                $amount = ($subTotal * $discount_value / 100);
                if($max == 0) {
                    return $amount;
                }
                if($amount > $max) {
                    return $max;
                }
            }
        }
        return 0;
    }

    static function taxAmount($products, $discount_type = null, $discount_value = 0, $tax_percentage = 0,$max = 0) : float {
        $totalItems = collect($products)->sum(fn($q)=> self::itemTotal($q) );
        $totalDiscount = self::discountAmount($products, $discount_type, $discount_value,$max);
        return collect($products)->sum(function($product) use ($products,$discount_type, $discount_value, $tax_percentage,$max,$totalItems,$totalDiscount) {
            $total = self::itemTotal($product);

            $percentageItemFromTotal = $totalItems ? ($total / $totalItems) : 0;

            $totalDiscount = $totalDiscount * ($percentageItemFromTotal / 100);

            $total = $total - $totalDiscount;


            if(($product['taxable'] ?? 0) == 1 && $tax_percentage) {
                return ($total * $tax_percentage / 100);
            }
            return 0;
        });
    }

    static function grandTotal($products, $discount_type = null, $discount_value = 0, $tax_percentage = 0, $max_discount_amount = 0) : float {
        $subTotal = self::subTotal($products);
        $discount = self::discountAmount($products, $discount_type, $discount_value, $max_discount_amount);
        $tax = self::taxAmount($products, $discount_type, $discount_value, $tax_percentage,$max_discount_amount);

        return ($subTotal - $discount) + $tax;
    }

    static function getGrandTotalQuery(){
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

  -- total discount (fixed or rate with/without max)
  (CASE
     WHEN d.type = 'fixed' THEN
       CASE WHEN COALESCE(ss.sale_subtotal,0) > COALESCE(d.max_discount_amount,0) THEN COALESCE(d.value,0) ELSE 0 END

     WHEN d.type = 'rate' THEN
       CASE
         WHEN COALESCE(d.max_discount_amount,0) = 0 THEN COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100
         WHEN COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100 > COALESCE(d.max_discount_amount,0) THEN COALESCE(d.max_discount_amount,0)
         ELSE COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100
       END

     ELSE 0
   END) AS discount,

  -- tax: taxable subtotal minus its share of the discount, then * tax_percentage
  (CASE WHEN COALESCE(s.tax_percentage,0) > 0 THEN
     (
       GREATEST(0,
         COALESCE(ss.taxable_subtotal,0)
         - (
             (CASE
                WHEN d.type = 'fixed' THEN CASE WHEN COALESCE(ss.sale_subtotal,0) > COALESCE(d.max_discount_amount,0) THEN COALESCE(d.value,0) ELSE 0 END
                WHEN d.type = 'rate' THEN
                  CASE
                    WHEN COALESCE(d.max_discount_amount,0) = 0 THEN COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100
                    WHEN COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100 > COALESCE(d.max_discount_amount,0) THEN COALESCE(d.max_discount_amount,0)
                    ELSE COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100
                  END
                ELSE 0
             END)
             * (CASE WHEN COALESCE(ss.sale_subtotal,0) > 0 THEN COALESCE(ss.taxable_subtotal,0) / COALESCE(ss.sale_subtotal,0) ELSE 0 END)
           )
       )
     ) * (COALESCE(s.tax_percentage,0) / 100)
   ELSE 0 END) AS tax,

  -- grand total = subtotal - discount + tax
  (
    COALESCE(ss.sale_subtotal,0)
    - (CASE
        WHEN d.type = 'fixed' THEN CASE WHEN COALESCE(ss.sale_subtotal,0) > COALESCE(d.max_discount_amount,0) THEN COALESCE(d.value,0) ELSE 0 END
        WHEN d.type = 'rate' THEN
          CASE
            WHEN COALESCE(d.max_discount_amount,0) = 0 THEN COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100
            WHEN COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100 > COALESCE(d.max_discount_amount,0) THEN COALESCE(d.max_discount_amount,0)
            ELSE COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100
          END
        ELSE 0
      END)
    + (CASE WHEN COALESCE(s.tax_percentage,0) > 0 THEN
         (
           GREATEST(0,
             COALESCE(ss.taxable_subtotal,0)
             - (
                 (CASE
                    WHEN d.type = 'fixed' THEN CASE WHEN COALESCE(ss.sale_subtotal,0) > COALESCE(d.max_discount_amount,0) THEN COALESCE(d.value,0) ELSE 0 END
                    WHEN d.type = 'rate' THEN
                      CASE
                        WHEN COALESCE(d.max_discount_amount,0) = 0 THEN COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100
                        WHEN COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100 > COALESCE(d.max_discount_amount,0) THEN COALESCE(d.max_discount_amount,0)
                        ELSE COALESCE(ss.sale_subtotal,0) * COALESCE(d.value,0) / 100
                      END
                    ELSE 0
                 END)
                 * (CASE WHEN COALESCE(ss.sale_subtotal,0) > 0 THEN COALESCE(ss.taxable_subtotal,0) / COALESCE(ss.sale_subtotal,0) ELSE 0 END)
               )
           )
         ) * (COALESCE(s.tax_percentage,0) / 100)
       ELSE 0 END)
  ) AS grand_total

FROM sales s
LEFT JOIN discounts d ON d.id = s.discount_id
LEFT JOIN sale_summaries ss ON ss.sale_id = s.id;";
    }
}
