<?php

namespace Tests\Feature\Tenant;

use App\Repositories\PurchaseRepository;
use App\Services\AccountService;
use App\Services\ExpenseCategoryService;
use App\Services\PurchaseService;
use App\Services\StockService;
use App\Services\TransactionService;
use Mockery;
use Tests\TestCase;

/**
 * A taxed, discounted, freighted purchase must capitalise inventory net of tax, recognise input
 * VAT exactly once (as a receivable, never also inside inventory), and treat a trade discount as
 * a reduction of inventory cost rather than income — see prompt 12.
 */
class PurchaseVatAndCostingTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function service(): PurchaseService
    {
        return new PurchaseService(
            Mockery::mock(PurchaseRepository::class),
            Mockery::mock(ExpenseCategoryService::class),
            Mockery::mock(StockService::class),
            Mockery::mock(TransactionService::class),
            Mockery::mock(AccountService::class)
        );
    }

    private function costPurchaseLines(PurchaseService $service, array $orderProducts, string $discountType, float $discountValue, string $discountClassification, float $expensesTotal, bool $capitaliseExpenses): array
    {
        $method = new \ReflectionMethod(PurchaseService::class, 'costPurchaseLines');
        $method->setAccessible(true);

        return $method->invoke($service, $orderProducts, $discountType, $discountValue, $discountClassification, $expensesTotal, $capitaliseExpenses);
    }

    public function test_inventory_cost_excludes_tax_and_is_net_of_a_trade_discount(): void
    {
        $service = $this->service();

        // 100 units @ 20 purchase price, 10% tax, no line discount; 10% order-level trade
        // discount; 50 of freight (not capitalised by default).
        $orderProducts = [[
            'id' => 1,
            'unit_id' => 1,
            'qty' => 100,
            'purchase_price' => 20,
            'discount_percentage' => 0,
            'tax_percentage' => 10,
            'sell_price' => 0,
        ]];

        $lines = $this->costPurchaseLines($service, $orderProducts, 'percentage', 10, 'trade', 50.0, false);

        $line = $lines[0];
        $goodsTotal = 100 * 20; // 2000, tax-exclusive
        $expectedDiscount = $goodsTotal * 0.10; // 200
        $expectedNetTotal = $goodsTotal - $expectedDiscount; // 1800, still tax-exclusive

        $this->assertEquals(20.0, $line['unit_cost_net'], 'unit_cost_net must exclude tax');
        $this->assertEquals($expectedNetTotal, $line['unit_cost_final'] * $line['qty'], '', 0.0001);

        // Tax never enters the inventory value, and freight is not folded in unless the
        // tenant opts into capitalising purchase expenses.
        $this->assertLessThan($goodsTotal, $line['unit_cost_final'] * $line['qty']);
    }

    public function test_settlement_discount_leaves_inventory_cost_uncapitalised(): void
    {
        $service = $this->service();

        $orderProducts = [[
            'id' => 1,
            'unit_id' => 1,
            'qty' => 10,
            'purchase_price' => 100,
            'discount_percentage' => 0,
            'tax_percentage' => 0,
            'sell_price' => 0,
        ]];

        $lines = $this->costPurchaseLines($service, $orderProducts, 'percentage', 5, 'settlement', 0.0, false);

        // A settlement (early-payment) discount is finance income, not a cost reduction —
        // inventory keeps its full gross cost.
        $this->assertEquals(1000.0, $lines[0]['unit_cost_final'] * $lines[0]['qty']);
    }

    public function test_capitalising_expenses_adds_freight_pro_rata_to_inventory_cost(): void
    {
        $service = $this->service();

        $orderProducts = [
            ['id' => 1, 'unit_id' => 1, 'qty' => 10, 'purchase_price' => 100, 'discount_percentage' => 0, 'tax_percentage' => 0, 'sell_price' => 0],
            ['id' => 2, 'unit_id' => 1, 'qty' => 10, 'purchase_price' => 300, 'discount_percentage' => 0, 'tax_percentage' => 0, 'sell_price' => 0],
        ];

        $lines = $this->costPurchaseLines($service, $orderProducts, 'fixed', 0, 'trade', 400.0, true);

        // Goods total is 1000 + 3000 = 4000; line 1 is 25% of it, so it absorbs 25% of the 400
        // freight (100), landing at 1100 total for its 10 units.
        $this->assertEqualsWithDelta(1100.0, $lines[0]['unit_cost_final'] * $lines[0]['qty'], 0.0001);
        $this->assertEqualsWithDelta(3300.0, $lines[1]['unit_cost_final'] * $lines[1]['qty'], 0.0001);
    }

    public function test_trade_discount_produces_no_purchase_discount_income_line(): void
    {
        $service = $this->service();

        // createPurchaseDiscountLine() must not touch the database for a trade discount — the
        // reduction already lives inside the inventory line, so posting an income line too
        // would double count it.
        $line = $service->createPurchaseDiscountLine(['branch_id' => 1, 'discount_amount' => 200, 'discount_classification' => 'trade']);

        $this->assertNull($line);
    }

    public function test_capitalised_expenses_are_not_also_posted_as_an_expense_line(): void
    {
        $service = $this->service();

        $lines = $service->createExpenseLine([
            'branch_id' => 1,
            'capitalise_expenses' => true,
            'expenses' => [['amount' => 500, 'expense_category_id' => null]],
        ]);

        $this->assertSame([], $lines);
    }
}
