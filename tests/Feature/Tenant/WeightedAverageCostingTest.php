<?php

namespace Tests\Feature\Tenant;

use App\Models\Tenant\Product;
use App\Models\Tenant\Stock;
use App\Repositories\ProductRepository;
use App\Repositories\StockRepository;
use App\Services\ProductService;
use App\Services\StockService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class WeightedAverageCostingTest extends TestCase
{
    private const TEST_DATABASE = 'test_weighted_average_costing';

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

        foreach (['stocks', 'products', 'files'] as $table) {
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
    }

    protected function tearDown(): void
    {
        foreach (['stocks', 'products', 'files'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }
        DB::purge('testing_tenant');

        parent::tearDown();
    }

    private function stockService(): StockService
    {
        return new StockService(new StockRepository(new Stock()), new ProductService(new ProductRepository(new Product())));
    }

    public function test_weighted_average_cost_after_two_purchases_and_a_sale(): void
    {
        $product = Product::create([
            'name' => 'Test Product',
            'sku' => 'TEST-WAC-1',
            'unit_id' => 1,
        ]);

        $service = $this->stockService();

        // buy 100 @ 10
        $service->addStock($product->id, 1, 100, sellPrice: 0, unitCost: 10, branchId: 1);
        // buy 100 @ 20
        $stock = $service->addStock($product->id, 1, 100, sellPrice: 0, unitCost: 20, branchId: 1);

        $this->assertEquals(200, (float) $stock->qty);
        $this->assertEquals(15.0, (float) $stock->unit_cost);
        $this->assertEquals(3000, (float) $stock->total_value);

        // sell 50 — cost must be 15.00
        $stock = $service->removeFromStock($product->id, 1, 50, branchId: 1);

        $this->assertEquals(150, (float) $stock->qty);
        $this->assertEquals(15.0, (float) $stock->unit_cost);
        $this->assertEquals(2250, (float) $stock->total_value);
    }

    public function test_zero_cost_receipt_does_not_overwrite_existing_average(): void
    {
        $product = Product::create([
            'name' => 'Test Product 2',
            'sku' => 'TEST-WAC-2',
            'unit_id' => 1,
        ]);

        $service = $this->stockService();

        $service->addStock($product->id, 1, 10, sellPrice: 0, unitCost: 25, branchId: 1);
        // zero-cost receipt (e.g. a purchase row with purchase_price = 0)
        $stock = $service->addStock($product->id, 1, 5, sellPrice: 0, unitCost: 0, branchId: 1);

        $this->assertEquals(15, (float) $stock->qty);
        $this->assertEquals(25.0, (float) $stock->unit_cost);
        $this->assertEquals(375, (float) $stock->total_value);
    }

    public function test_negative_stock_is_blocked_by_default(): void
    {
        $product = Product::create([
            'name' => 'Test Product 3',
            'sku' => 'TEST-WAC-3',
            'unit_id' => 1,
        ]);

        $service = $this->stockService();
        $service->addStock($product->id, 1, 5, sellPrice: 0, unitCost: 10, branchId: 1);

        $this->expectException(\RuntimeException::class);
        $service->removeFromStock($product->id, 1, 10, branchId: 1);
    }
}
