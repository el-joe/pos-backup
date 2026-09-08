<?php

namespace Tests\Feature\Tenant;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Account;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AccountDefaultIdempotencyTest extends TestCase
{
    private const TEST_DATABASE = 'test_account_idempotency';

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

    public function test_calling_default_five_times_yields_one_row(): void
    {
        for ($i = 0; $i < 5; $i++) {
            Account::default('Checks Under Collection', AccountTypeEnum::CHECKS_UNDER_COLLECTION->value, 1, 'check');
        }

        $this->assertSame(
            1,
            Account::where('type', AccountTypeEnum::CHECKS_UNDER_COLLECTION->value)->where('branch_id', 1)->count()
        );
    }

    public function test_calling_default_with_different_names_still_yields_one_row(): void
    {
        Account::default('Inventory', AccountTypeEnum::INVENTORY->value, 2);
        Account::default('Stock', AccountTypeEnum::INVENTORY->value, 2);
        Account::default('Warehouse Inventory', AccountTypeEnum::INVENTORY->value, 2);

        $this->assertSame(
            1,
            Account::where('type', AccountTypeEnum::INVENTORY->value)->where('branch_id', 2)->count()
        );
    }

    public function test_default_and_default_for_payment_method_slug_resolve_to_same_account(): void
    {
        $a = Account::default('Issued Checks', AccountTypeEnum::ISSUED_CHECKS->value, 3);
        $b = Account::defaultForPaymentMethodSlug('Issued Checks', AccountTypeEnum::ISSUED_CHECKS->value, 3, 'check');

        $this->assertSame($a->id, $b->id);
        $this->assertSame(
            1,
            Account::where('type', AccountTypeEnum::ISSUED_CHECKS->value)->where('branch_id', 3)->count()
        );
    }

    public function test_different_branches_get_distinct_accounts(): void
    {
        $a = Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, 10);
        $b = Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, 20);

        $this->assertNotSame($a->id, $b->id);
    }
}
