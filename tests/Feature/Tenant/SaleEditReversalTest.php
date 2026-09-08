<?php

namespace Tests\Feature\Tenant;

use App\Repositories\PurchaseRepository;
use App\Repositories\SellRepository;
use App\Services\AccountService;
use App\Services\DiscountService;
use App\Services\ExpenseCategoryService;
use App\Services\PurchaseService;
use App\Services\SellService;
use App\Services\StockService;
use App\Services\TransactionService;
use Mockery;
use Tests\TestCase;

/**
 * SellService::save() and PurchaseService::save() have no reversal-on-edit path: editing a
 * posted sale/purchase would re-post sale_invoice/COGS transactions and remove stock again
 * without ever reversing or returning the original state, double-posting revenue, COGS, and
 * stock deductions. Rather than build a half-finished reversal path, editing is blocked
 * outright — every current caller already only ever creates ($id = null), so this test locks
 * that constraint in.
 */
class SaleEditReversalTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_editing_a_sale_by_id_is_rejected(): void
    {
        $repo = Mockery::mock(SellRepository::class);
        $repo->shouldNotReceive('find');

        $service = new SellService(
            $repo,
            Mockery::mock(StockService::class),
            Mockery::mock(TransactionService::class),
            Mockery::mock(DiscountService::class),
            Mockery::mock(AccountService::class)
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Editing a posted sale is not supported. Refund the incorrect items and create a new sale instead.');

        $service->save(123, ['products' => []]);
    }

    public function test_editing_a_purchase_by_id_is_rejected(): void
    {
        $repo = Mockery::mock(PurchaseRepository::class);
        $repo->shouldNotReceive('find');

        $service = new PurchaseService(
            $repo,
            Mockery::mock(ExpenseCategoryService::class),
            Mockery::mock(StockService::class),
            Mockery::mock(TransactionService::class),
            Mockery::mock(AccountService::class)
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Editing a posted purchase is not supported. Refund the incorrect items and create a new purchase instead.');

        $service->save(456, ['orderProducts' => []]);
    }
}
