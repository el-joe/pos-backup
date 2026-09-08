<?php

namespace Tests\Feature\Tenant;

use App\Models\Tenant\Sale;
use App\Models\Tenant\SaleItem;
use App\Repositories\SellRepository;
use App\Services\AccountService;
use App\Services\DiscountService;
use App\Services\SellService;
use App\Services\StockService;
use App\Services\TransactionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class RefundValidationTest extends TestCase
{
    private const TEST_DATABASE = 'test_refund_validation';

    protected function setUp(): void
    {
        parent::setUp();

        DB::connection('mysql')->statement('CREATE DATABASE IF NOT EXISTS `'.self::TEST_DATABASE.'`');

        config(['database.connections.testing_tenant' => array_merge(
            config('database.connections.mysql'),
            ['database' => self::TEST_DATABASE]
        )]);
        config(['database.default' => 'testing_tenant']);
        DB::purge('testing_tenant');

        foreach (['sale_items', 'sales'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }

        Schema::connection('testing_tenant')->create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamp('order_date')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->decimal('tax_percentage', 10, 2)->default(0);
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 15, 2)->default(0);
            $table->decimal('max_discount_amount', 15, 2)->nullable();
            $table->decimal('sales_threshold', 15, 2)->nullable();
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->timestamp('due_date')->nullable();
            $table->boolean('is_deferred')->default(false);
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('inventory_delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('unit_id')->index();
            $table->integer('qty')->default(0);
            $table->boolean('taxable')->default(true);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('sell_price', 10, 2);
            $table->integer('refunded_qty')->default(0);
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        foreach (['sale_items', 'sales'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }
        DB::purge('testing_tenant');

        Mockery::close();

        parent::tearDown();
    }

    private function sellService(): SellService
    {
        return new SellService(
            new SellRepository(new Sale()),
            Mockery::mock(StockService::class),
            Mockery::mock(TransactionService::class),
            Mockery::mock(DiscountService::class),
            Mockery::mock(AccountService::class)
        );
    }

    private function makeSaleWithItem(int $qty, int $refundedQty): SaleItem
    {
        $sale = Sale::create([
            'customer_id' => 1,
            'branch_id' => 1,
            'invoice_number' => 'INV-TEST-1',
            'order_date' => now(),
        ]);

        return $sale->saleItems()->create([
            'product_id' => 1,
            'unit_id' => 1,
            'qty' => $qty,
            'taxable' => 0,
            'unit_cost' => 10,
            'sell_price' => 20,
            'refunded_qty' => $refundedQty,
        ]);
    }

    public function test_refunding_more_than_the_remaining_quantity_is_rejected(): void
    {
        $saleItem = $this->makeSaleWithItem(qty: 5, refundedQty: 0);

        $service = $this->sellService();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Refund quantity exceeds the refundable quantity for this item.');

        $service->refundSaleItem($saleItem->id, 10);
    }

    public function test_refunding_an_already_partially_refunded_item_beyond_what_remains_is_rejected(): void
    {
        $saleItem = $this->makeSaleWithItem(qty: 5, refundedQty: 3);

        $service = $this->sellService();

        $this->expectException(\RuntimeException::class);

        // only 2 remain refundable
        $service->refundSaleItem($saleItem->id, 3);
    }

    public function test_zero_or_negative_refund_quantity_is_rejected(): void
    {
        $saleItem = $this->makeSaleWithItem(qty: 5, refundedQty: 0);

        $service = $this->sellService();

        $this->expectException(\RuntimeException::class);

        $service->refundSaleItem($saleItem->id, 0);
    }
}
