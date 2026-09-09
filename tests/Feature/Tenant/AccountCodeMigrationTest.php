<?php

namespace Tests\Feature\Tenant;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AccountCodeMigrationTest extends TestCase
{
    private const TEST_DATABASE = 'test_account_code_migration';

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

        foreach (['transaction_lines', 'order_payments', 'checks', 'accounts', 'payment_methods'] as $table) {
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
            $table->string('code');
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

        Schema::connection('testing_tenant')->create('transaction_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->index();
            $table->string('type');
            $table->decimal('amount', 15, 4);
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->index();
            $table->unsignedBigInteger('counterparty_account_id')->nullable()->index();
            $table->decimal('amount', 15, 4);
            $table->boolean('refunded')->default(false);
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collected_account_id')->nullable()->index();
            $table->unsignedBigInteger('cleared_account_id')->nullable()->index();
            $table->decimal('amount', 15, 4)->default(0);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        foreach (['transaction_lines', 'order_payments', 'checks', 'accounts', 'payment_methods'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }
        DB::purge('testing_tenant');

        parent::tearDown();
    }

    private function runMigration()
    {
        $migration = require base_path('database/migrations/tenant/2026_09_08_165000_migrate_system_account_codes.php');
        $migration->up();

        return $migration;
    }

    public function test_migration_rewrites_codes_preserves_balances_and_makes_default_idempotent(): void
    {
        $inventoryId = DB::table('accounts')->insertGetId([
            'name' => 'Inventory',
            'code' => 'inventory',
            'model_type' => Branch::class,
            'model_id' => 1,
            'type' => AccountTypeEnum::INVENTORY->value,
            'branch_id' => 1,
            'active' => 1,
        ]);

        $ownerId = DB::table('accounts')->insertGetId([
            'name' => 'Owner Account',
            'code' => 'owner_account',
            'model_type' => Branch::class,
            'model_id' => null,
            'type' => AccountTypeEnum::OWNER_ACCOUNT->value,
            'branch_id' => null,
            'active' => 1,
        ]);

        // Duplicate group: survivor (lowest id) has zero lines, loser has a balance to preserve.
        $survivorId = DB::table('accounts')->insertGetId([
            'name' => 'Issued Checks',
            'code' => 'issued_checks',
            'model_type' => Branch::class,
            'model_id' => 1,
            'type' => AccountTypeEnum::ISSUED_CHECKS->value,
            'branch_id' => 1,
            'active' => 1,
        ]);

        $loserId = DB::table('accounts')->insertGetId([
            'name' => 'Issued Checks (dup)',
            'code' => 'issued_checks-41',
            'model_type' => Branch::class,
            'model_id' => 1,
            'type' => AccountTypeEnum::ISSUED_CHECKS->value,
            'branch_id' => 1,
            'active' => 1,
        ]);

        DB::table('transaction_lines')->insert([
            'account_id' => $inventoryId,
            'type' => 'debit',
            'amount' => 1000,
        ]);
        DB::table('transaction_lines')->insert([
            'account_id' => $loserId,
            'type' => 'credit',
            'amount' => 50000,
        ]);
        DB::table('checks')->insert([
            'collected_account_id' => $loserId,
            'cleared_account_id' => null,
            'amount' => 50000,
        ]);

        $accountCountBefore = DB::table('accounts')->count();
        $inventoryBalanceBefore = $this->accountBalance($inventoryId);

        $this->runMigration();

        // Survivor absorbed the loser's line and check.
        $this->assertSame(-50000.0, $this->accountBalance($survivorId));
        $this->assertSame(
            $survivorId,
            DB::table('checks')->where('id', 1)->value('collected_account_id')
        );

        // Loser soft-deleted, code untouched, never hard-deleted.
        $loser = DB::table('accounts')->where('id', $loserId)->first();
        $this->assertNotNull($loser);
        $this->assertNotNull($loser->deleted_at);
        $this->assertSame('issued_checks-41', $loser->code);

        // Codes rewritten to the deterministic format.
        $this->assertSame('inventory-branch-1', DB::table('accounts')->where('id', $inventoryId)->value('code'));
        $this->assertSame('issued_checks-branch-1', DB::table('accounts')->where('id', $survivorId)->value('code'));
        // NULL branch_id account is untouched.
        $this->assertSame('owner_account', DB::table('accounts')->where('id', $ownerId)->value('code'));

        // Balances unchanged.
        $this->assertSame($inventoryBalanceBefore, $this->accountBalance($inventoryId));

        // Only the soft-deleted loser leaves the active row count unchanged.
        $this->assertSame($accountCountBefore, DB::table('accounts')->count());
        $this->assertSame(
            $accountCountBefore - 1,
            DB::table('accounts')->whereNull('deleted_at')->count()
        );

        // Account::default() now resolves the existing row instead of inserting a new one.
        $before = DB::table('accounts')->count();
        $resolved = Account::default('Inventory', AccountTypeEnum::INVENTORY->value, 1);
        $this->assertSame($inventoryId, $resolved->id);
        $this->assertSame($before, DB::table('accounts')->count());

        $resolvedIssued = Account::default('Issued Checks', AccountTypeEnum::ISSUED_CHECKS->value, 1, 'check');
        $this->assertSame($survivorId, $resolvedIssued->id);
    }

    public function test_migration_handles_mismatched_code_and_type(): void
    {
        // Historical rows whose code never matched their type (e.g. real ids 16/19)
        // must be matched on (model_type, type, branch_id), never on the old code.
        $id = DB::table('accounts')->insertGetId([
            'name' => 'Fixed Assets Payable',
            'code' => 'fixed_assets_payable',
            'model_type' => Branch::class,
            'model_id' => 1,
            'type' => AccountTypeEnum::LONGTERM_LIABILITY->value,
            'branch_id' => 1,
            'active' => 1,
        ]);

        $this->runMigration();

        $this->assertSame('longterm_liability-branch-1', DB::table('accounts')->where('id', $id)->value('code'));
    }

    private function accountBalance(int $accountId): float
    {
        $debit = (float) DB::table('transaction_lines')->where('account_id', $accountId)->where('type', 'debit')->sum('amount');
        $credit = (float) DB::table('transaction_lines')->where('account_id', $accountId)->where('type', 'credit')->sum('amount');

        return $debit - $credit;
    }
}
