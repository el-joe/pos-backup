<?php

namespace Tests\Feature\Tenant;

use App\Enums\AccountTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CashRegisterVarianceTest extends TestCase
{
    private const TEST_DATABASE = 'test_cash_register_variance';

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

        foreach (['transaction_lines', 'transactions', 'accounts', 'payment_methods'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }

        Schema::connection('testing_tenant')->create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('active')->default(true);
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
            $table->timestamps();
            $table->boolean('active')->default(1);
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->text('note')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('transaction_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('account_id');
            $table->string('type');
            $table->decimal('amount', 12, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        $admin = new class extends \Illuminate\Foundation\Auth\User {
            public $id = 1;
        };
        Auth::guard(TENANT_ADMINS_GUARD)->setUser($admin);
    }

    protected function tearDown(): void
    {
        foreach (['transaction_lines', 'transactions', 'accounts', 'payment_methods'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }
        DB::purge('testing_tenant');

        parent::tearDown();
    }

    private function transactionService(): TransactionService
    {
        return new TransactionService(new TransactionRepository(new \App\Models\Tenant\Transaction()));
    }

    public function test_shortage_debits_cash_over_short_and_credits_branch_cash(): void
    {
        $service = $this->transactionService();

        // discrepancy negative => shortage
        $transaction = $service->createCashOverShortTransaction([
            'branch_id' => 1,
            'amount' => -50.00,
        ]);

        $this->assertSame(TransactionTypeEnum::CASH_OVER_SHORT->value, $transaction->type->value);
        $this->assertEquals(50.00, $transaction->amount);

        $cashOverShort = Account::where('type', AccountTypeEnum::CASH_OVER_SHORT->value)->where('branch_id', 1)->first();
        $branchCash = Account::where('type', AccountTypeEnum::BRANCH_CASH->value)->where('branch_id', 1)->first();

        $debitLine = $transaction->lines()->where('type', 'debit')->first();
        $creditLine = $transaction->lines()->where('type', 'credit')->first();

        $this->assertSame($cashOverShort->id, $debitLine->account_id);
        $this->assertSame($branchCash->id, $creditLine->account_id);
    }

    public function test_overage_debits_branch_cash_and_credits_cash_over_short(): void
    {
        $service = $this->transactionService();

        // discrepancy positive => overage
        $transaction = $service->createCashOverShortTransaction([
            'branch_id' => 2,
            'amount' => 30.00,
        ]);

        $cashOverShort = Account::where('type', AccountTypeEnum::CASH_OVER_SHORT->value)->where('branch_id', 2)->first();
        $branchCash = Account::where('type', AccountTypeEnum::BRANCH_CASH->value)->where('branch_id', 2)->first();

        $debitLine = $transaction->lines()->where('type', 'debit')->first();
        $creditLine = $transaction->lines()->where('type', 'credit')->first();

        $this->assertSame($branchCash->id, $debitLine->account_id);
        $this->assertSame($cashOverShort->id, $creditLine->account_id);
    }
}
