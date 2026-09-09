<?php

namespace Tests\Feature\Tenant;

use App\Models\Tenant\Product;
use App\Models\Tenant\Stock;
use App\Models\Tenant\StockTransfer;
use App\Repositories\ProductRepository;
use App\Repositories\StockRepository;
use App\Services\ProductService;
use App\Services\StockService;
use App\Services\StockTransferService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class StockTransferValuationTest extends TestCase
{
    private const TEST_DATABASE = 'test_stock_transfer_valuation';

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

        foreach (['stock_transfer_items', 'stock_transfers', 'stocks', 'products', 'files'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }

        Schema::connection('testing_tenant')->create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();
            $table->string('key')->nullable();
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->unique();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('unit_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->decimal('qty', 10, 3)->default(0);
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->decimal('total_value', 18, 4)->default(0);
            $table->decimal('sell_price', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_branch_id');
            $table->unsignedBigInteger('to_branch_id');
            $table->date('transfer_date')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('expense_paid_branch_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('stock_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_transfer_id')->index();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('unit_id');
            $table->decimal('qty', 10, 2);
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->decimal('sell_price', 15, 4)->default(0);
            $table->boolean('update_prices')->default(false);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        foreach (['stock_transfer_items', 'stock_transfers', 'stocks', 'products', 'files'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }
        DB::purge('testing_tenant');

        Mockery::close();

        parent::tearDown();
    }

    private function stockService(): StockService
    {
        return new StockService(new StockRepository(new Stock()), new ProductService(new ProductRepository(new Product())));
    }

    public function test_transfer_moves_exact_value_and_preserves_total_across_branches(): void
    {
        $product = Product::create([
            'name' => 'Transfer Product',
            'sku' => 'TEST-TRANSFER-1',
            'unit_id' => 1,
        ]);

        $stockService = $this->stockService();

        // Branch A: 100 units @ cost 15
        $stockService->addStock($product->id, 1, 100, sellPrice: 0, unitCost: 15, branchId: 1);
        // Branch B: 2000 units @ cost 10 (must not be overwritten by A's cost)
        $stockService->addStock($product->id, 1, 2000, sellPrice: 0, unitCost: 10, branchId: 2);

        $totalValueBefore = Stock::where('product_id', $product->id)->sum('total_value');

        $transferService = new StockTransferService(
            repo: Mockery::mock(\App\Repositories\StockTransferRepository::class),
            stockService: $stockService,
            transactionService: Mockery::mock(\App\Services\TransactionService::class),
            branchService: Mockery::mock(\App\Services\BranchService::class),
            purchaseService: Mockery::mock(\App\Services\PurchaseService::class),
            productService: Mockery::mock(\App\Services\ProductService::class),
            expenseCategoryService: Mockery::mock(\App\Services\ExpenseCategoryService::class),
        );

        $stockTransfer = StockTransfer::create([
            'from_branch_id' => 1,
            'to_branch_id' => 2,
            'transfer_date' => now(),
            'status' => 'completed',
        ]);

        // Transfer 100 units from A to B
        $transferService->saveItem($stockTransfer, [
            'product_id' => $product->id,
            'unit_id' => 1,
            'qty' => 100,
        ]);

        $fromStock = Stock::where('product_id', $product->id)->where('branch_id', 1)->first();
        $toStock = Stock::where('product_id', $product->id)->where('branch_id', 2)->first();

        // Source branch is fully drained and must move exactly 1,500 of value
        $this->assertEquals(0, (float) $fromStock->qty);
        $this->assertEquals(0, (float) $fromStock->total_value);

        // Destination branch received 100 units at the source's cost (15), not its own — its
        // existing 2000 units @ 10 must not be overwritten by the incoming cost.
        $this->assertEquals(2100, (float) $toStock->qty);
        $this->assertEqualsWithDelta(21500, (float) $toStock->total_value, 0.01);

        $totalValueAfter = Stock::where('product_id', $product->id)->sum('total_value');
        $this->assertEqualsWithDelta($totalValueBefore, $totalValueAfter, 0.01);

        $item = $stockTransfer->items()->first();
        $this->assertEqualsWithDelta(15.0, (float) $item->unit_cost, 0.0001);
    }
}
