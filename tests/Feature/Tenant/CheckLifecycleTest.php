<?php

namespace Tests\Feature\Tenant;

use App\Enums\CheckDirectionEnum;
use App\Enums\CheckStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Check;
use App\Models\Tenant\Sale;
use App\Services\CheckService;
use App\Services\PurchaseService;
use App\Services\SellService;
use App\Services\TransactionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

/**
 * Prompt 15: bounce() must restore AR *and* reverse the sub-ledger (order_payments +
 * sales.paid_amount), through SellService::addPayment(reverse: true) so there is one code
 * path for un-paying a sale, not two. It must also work from COLLECTED (bank already
 * credited, then returned it), reversing against the account that was actually credited
 * rather than the Checks Under Collection control account.
 */
class CheckLifecycleTest extends TestCase
{
    private const TEST_DATABASE = 'test_check_lifecycle';

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

        foreach (['checks', 'order_payments', 'branches', 'users', 'accounts'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }

        Schema::connection('testing_tenant')->create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payable_type')->nullable();
            $table->unsignedBigInteger('payable_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('counterparty_account_id')->nullable();
            $table->decimal('amount', 15, 4)->default(0);
            $table->boolean('refunded')->default(false);
            $table->string('note')->nullable();
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('direction')->index();
            $table->string('status')->index();
            $table->morphs('payable');
            $table->unsignedBigInteger('order_payment_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->nullable()->index();
            $table->decimal('amount', 15, 4);
            $table->decimal('bank_charge', 15, 4)->nullable();
            $table->string('check_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->date('check_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('collected_account_id')->nullable()->index();
            $table->unsignedBigInteger('cleared_account_id')->nullable()->index();
            $table->timestamp('collected_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamp('cleared_at')->nullable();
            $table->timestamp('represented_at')->nullable();
            $table->unsignedBigInteger('replaced_by_check_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function tearDown(): void
    {
        foreach (['checks', 'order_payments', 'branches', 'users', 'accounts'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }
        DB::purge('testing_tenant');
        Mockery::close();

        parent::tearDown();
    }

    private function makeService(?SellService $sellService = null, ?PurchaseService $purchaseService = null): CheckService
    {
        return new CheckService(
            Mockery::mock(TransactionService::class),
            $sellService ?? Mockery::mock(SellService::class),
            $purchaseService ?? Mockery::mock(PurchaseService::class)
        );
    }

    public function test_bounce_from_under_collection_reverses_sale_subledger_via_add_payment(): void
    {
        $orderPayment = \App\Models\Tenant\OrderPayment::on('testing_tenant')->create([
            'payable_type' => Sale::class,
            'payable_id' => 501,
            'account_id' => 77,
            'amount' => 250,
        ]);

        $check = Check::on('testing_tenant')->create([
            'branch_id' => 1,
            'direction' => CheckDirectionEnum::RECEIVED->value,
            'status' => CheckStatusEnum::UNDER_COLLECTION->value,
            'payable_type' => Sale::class,
            'payable_id' => 501,
            'order_payment_id' => $orderPayment->id,
            'customer_id' => 9,
            'amount' => 250,
            'check_number' => 'CHK-1',
            'bank_name' => 'Test Bank',
            'due_date' => now(),
        ]);

        $sellService = Mockery::mock(SellService::class);
        $sellService->shouldReceive('addPayment')
            ->once()
            ->withArgs(function ($sellId, $data, $reverse, $meta) use ($check) {
                return $sellId === 501
                    && $reverse === true
                    && $data['payments'][0]['account_id'] === 77
                    && (float)$data['payments'][0]['amount'] === 250.0
                    && $data['customer_id'] === 9
                    && $meta['type'] === TransactionTypeEnum::CHECK_BOUNCE->value
                    && $meta['reference_type'] === Check::class
                    && $meta['reference_id'] === $check->id;
            })
            ->andReturn(null);

        $service = $this->makeService($sellService);
        $result = $service->bounce($check->id);

        $this->assertSame(CheckStatusEnum::BOUNCED->value, $result->status);
        $this->assertNotNull($result->bounced_at);
    }

    public function test_bounce_from_collected_reverses_against_collected_account_not_control_account(): void
    {
        $orderPayment = \App\Models\Tenant\OrderPayment::on('testing_tenant')->create([
            'payable_type' => Sale::class,
            'payable_id' => 502,
            'account_id' => 77,
            'amount' => 100,
        ]);

        $check = Check::on('testing_tenant')->create([
            'branch_id' => 1,
            'direction' => CheckDirectionEnum::RECEIVED->value,
            'status' => CheckStatusEnum::COLLECTED->value,
            'payable_type' => Sale::class,
            'payable_id' => 502,
            'order_payment_id' => $orderPayment->id,
            'customer_id' => 9,
            'amount' => 100,
            'check_number' => 'CHK-2',
            'bank_name' => 'Test Bank',
            'due_date' => now(),
            'collected_account_id' => 55,
            'collected_at' => now(),
        ]);

        $sellService = Mockery::mock(SellService::class);
        $sellService->shouldReceive('addPayment')
            ->once()
            ->withArgs(function ($sellId, $data, $reverse) {
                // Must reverse against the collected account (55), never the
                // original check-tender account (77) used before collection.
                return $data['payments'][0]['account_id'] === 55 && $reverse === true;
            })
            ->andReturn(null);

        $service = $this->makeService($sellService);
        $result = $service->bounce($check->id);

        $this->assertSame(CheckStatusEnum::BOUNCED->value, $result->status);
    }

    public function test_bounce_rejects_a_check_that_already_bounced(): void
    {
        $check = Check::on('testing_tenant')->create([
            'branch_id' => 1,
            'direction' => CheckDirectionEnum::RECEIVED->value,
            'status' => CheckStatusEnum::BOUNCED->value,
            'payable_type' => Sale::class,
            'payable_id' => 503,
            'customer_id' => 9,
            'amount' => 100,
            'check_number' => 'CHK-3',
            'bank_name' => 'Test Bank',
            'due_date' => now(),
        ]);

        $service = $this->makeService();

        $this->expectException(\RuntimeException::class);
        $service->bounce($check->id);
    }

    public function test_check_creation_requires_amount_check_number_bank_and_due_date(): void
    {
        $this->expectException(\RuntimeException::class);

        Check::on('testing_tenant')->create([
            'branch_id' => 1,
            'direction' => CheckDirectionEnum::RECEIVED->value,
            'status' => CheckStatusEnum::UNDER_COLLECTION->value,
            'payable_type' => Sale::class,
            'payable_id' => 999,
            'customer_id' => 9,
            'amount' => 0,
            'check_number' => 'CHK-4',
            'bank_name' => 'Test Bank',
            'due_date' => now(),
        ]);
    }
}
