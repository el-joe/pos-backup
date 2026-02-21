<?php

namespace App\Helpers;

class SaleHelper
{
    static function itemTotal($product, $withRefund = true) {
        $qty = $product['qty'] ?? $product['quantity'] ?? 0;
        $refunded_qty = $withRefund ? ($product['refunded_qty'] ?? 0) : 0;
        $sell_price = $product['sell_price'] ?? $product['price'] ?? 0;
        return numFormat((($qty - $refunded_qty) * $sell_price),3);
    }

    static function subTotal($products, $withRefund = true) {
        return collect($products)->sum(function($product) use ($withRefund) {
            return numFormat(self::itemTotal($product, $withRefund),3);
        });
    }

    static function discountAmount($products, $discount_type = null, $discount_value = 0,$max = 0) {
        $subTotal = self::subTotal($products);
        if($discount_type && $discount_value) {
            if($discount_type == 'fixed') {
                $amount = $discount_value;
                if($subTotal > $max) {
                    return numFormat($amount, 3);
                }
            } else {
                $amount = ($subTotal * $discount_value / 100);

                if($max == 0) {
                    return numFormat($amount, 3);
                }
                if($amount > $max) {
                    return numFormat($max, 3);
                }

                return numFormat($amount, 3);
            }
        }
        return 0;
    }

    static function singleDiscountAmount($product,$products, $discount_type = null, $discount_value = 0,$max = 0) {
        $subTotal = self::subTotal($products,false);
        if($discount_type && $discount_value) {
            $total = self::itemTotal($product);
            $percentageItemFromTotal = $subTotal ? ($total / $subTotal) : 0;
            $allProducts = collect($products->toArray())->map(function($item){
                $item['refunded_qty'] = 0;
                return $item;
            });

            $totalDiscount = self::discountAmount($allProducts, $discount_type, $discount_value,$max);
            return numFormat($totalDiscount * $percentageItemFromTotal,3);
        }
        return 0;
    }

    static function taxAmount($products, $discount_type = null, $discount_value = 0, $tax_percentage = 0,$max = 0) : float {
        $totalItems = collect($products)->sum(fn($q)=> self::itemTotal($q) );
        $totalDiscount = self::discountAmount($products, $discount_type, $discount_value,$max);

        return collect($products)->sum(function($product) use ($tax_percentage,$totalItems,$totalDiscount) {
            $total = self::itemTotal($product);

            $percentageItemFromTotal = $totalItems ? ($total / $totalItems) : 0;

            $totalDiscount = $totalDiscount * $percentageItemFromTotal;

            $total = $total - $totalDiscount;


            if(($product['taxable'] ?? 0) == 1 && $tax_percentage) {
                return numFormat($total * $tax_percentage / 100,3);
            }
            return 0;
        });
    }

    static function singleTaxAmount($product,$products, $discount_type = null, $discount_value = 0, $tax_percentage = 0,$max = 0) {
        if(($product['taxable'] ?? 0) != 1 || !$tax_percentage) return 0;

        $allProducts = collect($products->toArray())->map(function($item){
            $item['refunded_qty'] = 0;
            return $item;
        });

        $total = self::itemTotal($product);

        $totalDiscount = self::singleDiscountAmount($product,$allProducts, $discount_type, $discount_value,$max);

        $total = $total - $totalDiscount;

        return numFormat($total * $tax_percentage / 100,3);
    }

    static function grandTotal($products, $discount_type = null, $discount_value = 0, $tax_percentage = 0, $max_discount_amount = 0)  {
        $subTotal = self::subTotal($products);
        $discount = self::discountAmount($products, $discount_type, $discount_value, $max_discount_amount);
        $tax = self::taxAmount($products, $discount_type, $discount_value, $tax_percentage,$max_discount_amount);
        return numFormat(($subTotal - $discount) + $tax,3);
    }

    static function singleGrandTotal($product,$products, $discount_type = null, $discount_value = 0, $tax_percentage = 0, $max_discount_amount = 0)  {
        $subTotal = self::subTotal([$product]);
        $discount = self::singleDiscountAmount($product,$products, $discount_type, $discount_value, $max_discount_amount);
        $tax = self::singleTaxAmount($product,$products, $discount_type, $discount_value, $tax_percentage,$max_discount_amount);
        return numFormat(($subTotal - $discount) + $tax,3);
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
