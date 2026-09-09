<?php

namespace Tests\Feature\Tenant;

use App\Models\Tenant\Account;
use App\Models\Tenant\Product;
use App\Models\Tenant\Stock;
use App\Models\Tenant\StockTaking;
use App\Repositories\ProductRepository;
use App\Repositories\StockRepository;
use App\Repositories\StockTakingRepository;
use App\Repositories\TransactionRepository;
use App\Services\LedgerBridgeService;
use App\Services\ProductService;
use App\Services\StockService;
use App\Services\StockTakingService;
use App\Services\TransactionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class StockTakingAdjustmentTest extends TestCase
{
    private const TEST_DATABASE = 'test_stock_taking_adjustment';

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

        $tables = ['transaction_lines', 'transactions', 'accounts', 'payment_methods', 'admins', 'stock_taking_products', 'stock_takings', 'stocks', 'products', 'files'];
        foreach ($tables as $table) {
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

        Schema::connection('testing_tenant')->create('stock_takings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->date('date')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('stock_taking_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_taking_id')->index();
            $table->unsignedBigInteger('product_id');
            $table->decimal('current_qty', 10, 2)->default(0);
            $table->decimal('actual_qty', 10, 2)->default(0);
            $table->unsignedBigInteger('stock_id')->nullable();
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->boolean('returned')->default(false);
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('type');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('is_payment_capable')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->text('note')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('transaction_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->index();
            $table->unsignedBigInteger('account_id')->index();
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->enum('type', ['debit', 'credit']);
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $admin = \App\Models\Tenant\Admin::create([
            'name' => 'Test Admin',
            'email' => 'test-admin@example.com',
            'password' => bcrypt('password'),
        ]);
        \Illuminate\Support\Facades\Auth::guard(TENANT_ADMINS_GUARD)->login($admin);
    }

    protected function tearDown(): void
    {
        $tables = ['transaction_lines', 'transactions', 'accounts', 'payment_methods', 'admins', 'stock_taking_products', 'stock_takings', 'stocks', 'products', 'files'];
        foreach ($tables as $table) {
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

    private function stockTakingService(StockService $stockService): StockTakingService
    {
        // LedgerBridge (system-B journal projection) is out of scope here — TransactionService's
        // own balance guard is what this test verifies, so the bridge is stubbed out.
        $ledgerBridge = Mockery::mock(LedgerBridgeService::class);
        $ledgerBridge->shouldReceive('post')->andReturnNull();

        $transactionService = new TransactionService(new TransactionRepository(new \App\Models\Tenant\Transaction()), $ledgerBridge);

        return new StockTakingService(
            new StockTakingRepository(new StockTaking()),
            $stockService,
            $transactionService,
            Mockery::mock(\App\Services\PurchaseService::class),
            Mockery::mock(\App\Services\SellService::class),
        );
    }

    private function assertBalanced(int $transactionId): void
    {
        $lines = DB::connection('testing_tenant')->table('transaction_lines')->where('transaction_id', $transactionId)->get();
        $debit = $lines->where('type', 'debit')->sum('amount');
        $credit = $lines->where('type', 'credit')->sum('amount');

        $this->assertEqualsWithDelta((float) $debit, (float) $credit, 0.01);
        $this->assertGreaterThan(0, (float) $debit);
    }

    public function test_shortage_posts_a_balanced_entry_and_reduces_stock(): void
    {
        $product = Product::create(['name' => 'Shortage Product', 'sku' => 'TEST-ST-SHORT', 'unit_id' => 1]);
        $stockService = $this->stockService();

        $stock = $stockService->addStock($product->id, 1, 100, sellPrice: 0, unitCost: 20, branchId: 1);

        $service = $this->stockTakingService($stockService);

        $st = $service->save(null, [
            'branch_id' => 1,
            'date' => now()->toDateString(),
            'stocks' => [[
                'product_id' => $product->id,
                'unit_id' => 1,
                'current_stock' => 100,
                'stock_id' => $stock->id,
                'unit_cost' => 999, // client-supplied and stale — must be ignored in favour of the stock's real cost
            ]],
            'countedStock' => [
                $product->id => [1 => 90], // 10 short
            ],
        ]);

        $this->assertNull($st->approved_at);

        $service->approve($st->id, 1);
        $st->refresh();

        $this->assertNotNull($st->approved_at);
        $this->assertEquals(1, $st->approved_by);

        $stock->refresh();
        $this->assertEquals(90, (float) $stock->qty);
        // valued at the stock's real weighted-average cost (20), not the stale client value
        $this->assertEqualsWithDelta(1800, (float) $stock->total_value, 0.01);

        $transactionId = DB::connection('testing_tenant')->table('transactions')
            ->where('reference_type', StockTaking::class)->where('reference_id', $st->id)->value('id');
        $this->assertNotNull($transactionId);
        $this->assertBalanced($transactionId);

        $shortageAccount = Account::default('inventory_shortage', 'inventory_shortage', 1);
        $shortageAmount = DB::connection('testing_tenant')->table('transaction_lines')
            ->where('transaction_id', $transactionId)->where('account_id', $shortageAccount->id)->value('amount');
        $this->assertEqualsWithDelta(200, (float) $shortageAmount, 0.01); // 10 units * 20

        // approving twice must not double-adjust stock or post a second transaction
        $service->approve($st->id, 1);
        $stock->refresh();
        $this->assertEquals(90, (float) $stock->qty);
        $count = DB::connection('testing_tenant')->table('transactions')
            ->where('reference_type', StockTaking::class)->where('reference_id', $st->id)->count();
        $this->assertEquals(1, $count);
    }

    public function test_overage_posts_a_balanced_entry_to_inventory_gain_and_increases_stock(): void
    {
        $product = Product::create(['name' => 'Overage Product', 'sku' => 'TEST-ST-OVER', 'unit_id' => 1]);
        $stockService = $this->stockService();

        $stock = $stockService->addStock($product->id, 1, 50, sellPrice: 0, unitCost: 8, branchId: 1);

        $service = $this->stockTakingService($stockService);

        $st = $service->save(null, [
            'branch_id' => 1,
            'date' => now()->toDateString(),
            'stocks' => [[
                'product_id' => $product->id,
                'unit_id' => 1,
                'current_stock' => 50,
                'stock_id' => $stock->id,
                'unit_cost' => 8,
            ]],
            'countedStock' => [
                $product->id => [1 => 60], // 10 over
            ],
        ]);

        $service->approve($st->id, 2);
        $st->refresh();
        $stock->refresh();

        $this->assertEquals(60, (float) $stock->qty);
        $this->assertEqualsWithDelta(480, (float) $stock->total_value, 0.01);

        $transactionId = DB::connection('testing_tenant')->table('transactions')
            ->where('reference_type', StockTaking::class)->where('reference_id', $st->id)->value('id');
        $this->assertNotNull($transactionId);
        $this->assertBalanced($transactionId);

        $gainAccount = Account::default('inventory_gain', 'inventory_gain', 1);
        $gainAmount = DB::connection('testing_tenant')->table('transaction_lines')
            ->where('transaction_id', $transactionId)->where('account_id', $gainAccount->id)->where('type', 'credit')->value('amount');
        $this->assertEqualsWithDelta(80, (float) $gainAmount, 0.01); // 10 units * 8

        // Inventory Gain must never be posted through COGS
        $cogsAccount = Account::default('COGS', 'cogs', 1);
        $cogsLineExists = DB::connection('testing_tenant')->table('transaction_lines')
            ->where('transaction_id', $transactionId)->where('account_id', $cogsAccount->id)->exists();
        $this->assertFalse($cogsLineExists);
    }
}
