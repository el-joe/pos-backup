<?php

namespace Tests\Feature\Tenant;

use App\Models\Tenant\FixedAsset;
use App\Repositories\FixedAssetRepository;
use App\Repositories\TransactionRepository;
use App\Services\DepreciationService;
use App\Services\FixedAssetService;
use App\Services\LedgerBridgeService;
use App\Services\TransactionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FixedAssetPostingTest extends TestCase
{
    private const TEST_DATABASE = 'test_fixed_asset_posting';

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

        foreach (['journal_entry_lines', 'journal_entries', 'chart_of_accounts', 'fixed_asset_depreciation_entries', 'fixed_assets', 'transaction_lines', 'transactions', 'accounts', 'payment_methods'] as $table) {
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
            $table->boolean('is_payment_capable')->default(false);
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
            $table->unsignedBigInteger('reversed_by_transaction_id')->nullable();
            $table->string('reversal_reason')->nullable();
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('transaction_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('type');
            $table->decimal('amount', 12, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('code');
            $table->string('name');
            $table->date('purchase_date')->nullable();
            $table->decimal('cost', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('salvage_value', 15, 2)->default(0);
            $table->decimal('accumulated_depreciation', 15, 2)->default(0);
            $table->date('last_depreciated_on')->nullable();
            $table->timestamp('disposed_at')->nullable();
            $table->decimal('disposal_proceeds', 15, 2)->nullable();
            $table->unsignedInteger('useful_life_months')->default(0);
            $table->decimal('depreciation_rate', 8, 4)->nullable();
            $table->string('depreciation_basis')->nullable();
            $table->string('depreciation_method')->default('straight_line');
            $table->date('depreciation_start_date')->nullable();
            $table->string('status')->default('active');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('fixed_asset_depreciation_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixed_asset_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('transaction_id')->nullable()->index();
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('accumulated_depreciation_after', 15, 2)->default(0);
            $table->timestamps();
            $table->unique(['fixed_asset_id', 'period_year', 'period_month'], 'fa_depreciation_period_unique');
        });

        Schema::connection('testing_tenant')->create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->string('referenceable_type')->nullable();
            $table->unsignedBigInteger('referenceable_id')->nullable();
            $table->date('date')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('draft');
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_entry_id')->index();
            $table->unsignedBigInteger('account_id')->index();
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        \App\Models\Tenant\Contracting\ChartOfAccount::create(['code' => '1110', 'name' => 'Fixed Asset Cost', 'type' => 'asset']);
        \App\Models\Tenant\Contracting\ChartOfAccount::create(['code' => '2100', 'name' => 'Fixed Assets Payable', 'type' => 'liability']);

        $admin = new class extends \Illuminate\Foundation\Auth\User {
            public $id = 1;
            public $branch_id = 1;
        };
        Auth::guard(TENANT_ADMINS_GUARD)->setUser($admin);
    }

    protected function tearDown(): void
    {
        foreach (['journal_entry_lines', 'journal_entries', 'chart_of_accounts', 'fixed_asset_depreciation_entries', 'fixed_assets', 'transaction_lines', 'transactions', 'accounts', 'payment_methods'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }
        DB::purge('testing_tenant');

        parent::tearDown();
    }

    private function fixedAssetService(): FixedAssetService
    {
        $transactionService = new TransactionService(new TransactionRepository(new \App\Models\Tenant\Transaction()), new LedgerBridgeService());

        return new FixedAssetService(
            new FixedAssetRepository(new FixedAsset()),
            $transactionService,
            new DepreciationService($transactionService)
        );
    }

    public function test_zero_cost_asset_is_rejected_not_silently_created(): void
    {
        $service = $this->fixedAssetService();

        $this->expectException(\RuntimeException::class);

        $service->save(null, [
            'branch_id' => 1,
            'code' => 'FA-ZERO-'.uniqid(),
            'name' => 'Zero cost asset',
            'cost' => 0,
            'status' => FixedAsset::STATUS_ACTIVE,
            'depreciation_basis' => 'useful_life',
            'useful_life_months' => 12,
        ]);
    }

    public function test_positive_cost_asset_is_posted_to_the_ledger(): void
    {
        $service = $this->fixedAssetService();

        $asset = $service->save(null, [
            'branch_id' => 1,
            'code' => 'FA-POST-'.uniqid(),
            'name' => 'Posted asset',
            'cost' => 1000,
            'status' => FixedAsset::STATUS_ACTIVE,
            'depreciation_basis' => 'useful_life',
            'useful_life_months' => 12,
        ]);

        $this->assertSame(1, $asset->transactions()->count());
        $this->assertEquals(1000, (float) $asset->transactions()->first()->amount);
    }

    public function test_editing_cost_after_posting_is_blocked(): void
    {
        $service = $this->fixedAssetService();

        $asset = $service->save(null, [
            'branch_id' => 1,
            'code' => 'FA-EDIT-'.uniqid(),
            'name' => 'Edit test asset',
            'cost' => 1000,
            'status' => FixedAsset::STATUS_ACTIVE,
            'depreciation_basis' => 'useful_life',
            'useful_life_months' => 12,
        ]);

        $this->expectException(\RuntimeException::class);

        $service->save($asset->id, [
            'branch_id' => 1,
            'code' => $asset->code,
            'name' => $asset->name,
            'cost' => 2000,
            'status' => FixedAsset::STATUS_ACTIVE,
            'depreciation_basis' => 'useful_life',
            'useful_life_months' => 12,
        ]);
    }

    public function test_cost_cannot_be_set_below_paid_amount(): void
    {
        $service = $this->fixedAssetService();

        $asset = $service->save(null, [
            'branch_id' => 1,
            'code' => 'FA-PAID-'.uniqid(),
            'name' => 'Paid asset',
            'cost' => 1000,
            'paid_amount' => 500,
            'status' => FixedAsset::STATUS_ACTIVE,
            'depreciation_basis' => 'useful_life',
            'useful_life_months' => 12,
        ]);

        $this->expectException(\RuntimeException::class);

        $service->save($asset->id, [
            'branch_id' => 1,
            'code' => $asset->code,
            'name' => $asset->name,
            'cost' => 300,
            'status' => FixedAsset::STATUS_ACTIVE,
            'depreciation_basis' => 'useful_life',
            'useful_life_months' => 12,
        ]);
    }
}
