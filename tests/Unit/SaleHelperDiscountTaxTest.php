<?php

namespace Tests\Unit;

use App\Helpers\SaleHelper;
use PHPUnit\Framework\TestCase;

class SaleHelperDiscountTaxTest extends TestCase
{
    private function product($qty, $sellPrice, $taxable = 0, $refundedQty = 0): array
    {
        return [
            'qty' => $qty,
            'sell_price' => $sellPrice,
            'taxable' => $taxable,
            'refunded_qty' => $refundedQty,
        ];
    }

    public function test_no_discount(): void
    {
        $products = [$this->product(2, 100)];
        $this->assertEquals(0, SaleHelper::discountAmount($products, null, 0, 0));
        $this->assertEquals(200.00, SaleHelper::grandTotal($products, null, 0, 0, 0));
    }

    public function test_percentage_under_cap(): void
    {
        $products = [$this->product(2, 100)]; // subtotal 200
        // 10% of 200 = 20, cap 100 -> not hit
        $discount = SaleHelper::discountAmount($products, 'percentage', 10, 100);
        $this->assertEquals(20.00, $discount);
    }

    public function test_percentage_over_cap(): void
    {
        $products = [$this->product(2, 100)]; // subtotal 200
        // 50% of 200 = 100, cap 30 -> capped at 30
        $discount = SaleHelper::discountAmount($products, 'percentage', 50, 30);
        $this->assertEquals(30.00, $discount);
    }

    public function test_fixed_under_threshold_not_applied(): void
    {
        $products = [$this->product(1, 50)]; // subtotal 50
        // threshold 100, subtotal 50 < threshold -> discount not eligible
        $discount = SaleHelper::discountAmount($products, 'fixed', 20, 0, 100);
        $this->assertEquals(0, $discount);
    }

    public function test_fixed_over_threshold_applies(): void
    {
        $products = [$this->product(3, 100)]; // subtotal 300
        // threshold 100, subtotal 300 >= threshold -> discount applies
        $discount = SaleHelper::discountAmount($products, 'fixed', 20, 0, 100);
        $this->assertEquals(20.00, $discount);
    }

    public function test_fixed_exceeding_subtotal_is_capped_at_subtotal(): void
    {
        $products = [$this->product(1, 50)]; // subtotal 50
        $discount = SaleHelper::discountAmount($products, 'fixed', 500, 0, null);
        $this->assertEquals(50.00, $discount);

        $grandTotal = SaleHelper::grandTotal($products, 'fixed', 500, 0, 0, null);
        $this->assertEquals(0.00, $grandTotal);
        $this->assertGreaterThanOrEqual(0, $grandTotal);
    }

    public function test_mixed_taxable_and_non_taxable_basket(): void
    {
        $products = [
            $this->product(1, 100, 1), // taxable
            $this->product(1, 100, 0), // not taxable
        ];
        // no discount, 10% tax only applies to the taxable 100
        $tax = SaleHelper::taxAmount($products, null, 0, 10, 0);
        $this->assertEquals(10.00, $tax);

        // with a 20% discount applied proportionally across the whole basket (subtotal 200 -> discount 40)
        // taxable share = 100/200 = 0.5, so taxable base after discount = 100 - 20 = 80, tax = 8
        $tax = SaleHelper::taxAmount($products, 'percentage', 20, 10, 0);
        $this->assertEquals(8.00, $tax);
    }

    public function test_fully_refunded_line_contributes_nothing_net_of_refunds(): void
    {
        $products = [$this->product(5, 100, 1, 5)]; // fully refunded qty

        // net-of-refunds (default withRefund = true) totals are zero
        $this->assertEquals(0, SaleHelper::subTotal($products));
        $this->assertEquals(0.00, SaleHelper::grandTotal($products, 'fixed', 50, 0, 10));

        // gross (as-issued) totals still reflect the original sale
        $this->assertEquals(500, SaleHelper::grossSubTotal($products));
        $this->assertEquals(500.00, SaleHelper::grossGrandTotal($products, null, 0, 0, 0));
    }

    public function test_discount_never_makes_grand_total_negative(): void
    {
        $products = [$this->product(1, 10)];
        $grandTotal = SaleHelper::grandTotal($products, 'fixed', 1000, 0, 0);
        $this->assertEquals(0.00, $grandTotal);
    }

    public function test_null_threshold_means_always_eligible(): void
    {
        $products = [$this->product(1, 10)];
        $discount = SaleHelper::discountAmount($products, 'fixed', 5, 0, null);
        $this->assertEquals(5.00, $discount);
    }
}
