<?php

namespace Tests\Feature\Tenant;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Account;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PaymentAccountValidationTest extends TestCase
{
    private const TEST_DATABASE = 'test_payment_account_validation';

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

        Schema::connection('testing_tenant')->dropIfExists('accounts');
        Schema::connection('testing_tenant')->dropIfExists('payment_methods');

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
            $table->boolean('is_payment_capable')->default(false);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();
            $table->boolean('active')->default(1);
            $table->softDeletes();
        });
    }

    protected function tearDown(): void
    {
        Schema::connection('testing_tenant')->dropIfExists('accounts');
        Schema::connection('testing_tenant')->dropIfExists('payment_methods');
        DB::purge('testing_tenant');

        parent::tearDown();
    }

    public function test_branch_cash_account_is_payment_capable(): void
    {
        $account = Account::create([
            'name' => 'Branch Cash',
            'code' => 'branch-cash-1',
            'type' => AccountTypeEnum::BRANCH_CASH->value,
            'branch_id' => 1,
        ]);

        $this->assertTrue($account->fresh()->is_payment_capable);
        $this->assertSame($account->id, Account::assertPaymentCapable($account->id)->id);
    }

    public function test_owner_account_is_payment_capable(): void
    {
        $account = Account::create([
            'name' => 'Owner Account',
            'code' => 'owner-1',
            'type' => AccountTypeEnum::OWNER_ACCOUNT->value,
            'branch_id' => 1,
        ]);

        $this->assertTrue($account->fresh()->is_payment_capable);
    }

    public function test_supplier_account_is_not_payment_capable(): void
    {
        $account = Account::create([
            'name' => 'Supplier #1',
            'code' => 'supplier-1',
            'type' => AccountTypeEnum::SUPPLIER->value,
            'branch_id' => 1,
        ]);

        $this->assertFalse($account->fresh()->is_payment_capable);

        $this->expectException(\RuntimeException::class);
        Account::assertPaymentCapable($account->id);
    }

    public function test_customer_account_is_not_payment_capable(): void
    {
        $account = Account::create([
            'name' => 'Customer #1',
            'code' => 'customer-1',
            'type' => AccountTypeEnum::CUSTOMER->value,
            'branch_id' => 1,
        ]);

        $this->expectException(\RuntimeException::class);
        Account::assertPaymentCapable($account->id);
    }

    public function test_checks_under_collection_control_account_is_not_payment_capable(): void
    {
        $account = Account::create([
            'name' => 'Checks Under Collection',
            'code' => 'checks-under-collection-1',
            'type' => AccountTypeEnum::CHECKS_UNDER_COLLECTION->value,
            'branch_id' => 1,
        ]);

        $this->expectException(\RuntimeException::class);
        Account::assertPaymentCapable($account->id);
    }

    public function test_nominal_accounts_are_not_payment_capable(): void
    {
        foreach ([AccountTypeEnum::SALES, AccountTypeEnum::INVENTORY, AccountTypeEnum::COGS, AccountTypeEnum::ISSUED_CHECKS] as $type) {
            $account = Account::create([
                'name' => $type->value,
                'code' => 'nominal-'.$type->value,
                'type' => $type->value,
                'branch_id' => 1,
            ]);

            $this->assertFalse($account->fresh()->is_payment_capable, "{$type->value} should not be payment capable");
        }
    }

    public function test_null_account_id_is_rejected(): void
    {
        $this->expectException(\RuntimeException::class);
        Account::assertPaymentCapable(null);
    }

    public function test_inactive_payment_capable_account_is_rejected(): void
    {
        $account = Account::create([
            'name' => 'Branch Cash',
            'code' => 'branch-cash-inactive',
            'type' => AccountTypeEnum::BRANCH_CASH->value,
            'branch_id' => 1,
            'active' => 0,
        ]);

        $this->expectException(\RuntimeException::class);
        Account::assertPaymentCapable($account->id);
    }

    public function test_payment_capable_scope_excludes_subsidiary_and_control_accounts(): void
    {
        Account::create(['name' => 'Branch Cash', 'code' => 'pc-branch-cash', 'type' => AccountTypeEnum::BRANCH_CASH->value, 'branch_id' => 1]);
        Account::create(['name' => 'Supplier #1', 'code' => 'pc-supplier', 'type' => AccountTypeEnum::SUPPLIER->value, 'branch_id' => 1]);
        Account::create(['name' => 'Sales', 'code' => 'pc-sales', 'type' => AccountTypeEnum::SALES->value, 'branch_id' => 1]);

        $capable = Account::paymentCapable()->pluck('code')->all();

        $this->assertSame(['pc-branch-cash'], $capable);
    }
}
