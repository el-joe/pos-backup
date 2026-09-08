<?php

namespace Tests\Feature\Tenant;

use App\Models\Tenant\FixedAsset;
use App\Repositories\TransactionRepository;
use App\Services\DepreciationService;
use App\Services\TransactionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DepreciationTest extends TestCase
{
    private const TEST_DATABASE = 'test_depreciation';

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
        \App\Models\Tenant\Contracting\ChartOfAccount::create(['code' => '1120', 'name' => 'Accumulated Depreciation', 'type' => 'asset']);
        \App\Models\Tenant\Contracting\ChartOfAccount::create(['code' => '6040', 'name' => 'Depreciation Expense', 'type' => 'expense']);

        $admin = new class extends \Illuminate\Foundation\Auth\User {
            public $id = 1;
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

    private function depreciationService(): DepreciationService
    {
        return new DepreciationService(new TransactionService(new TransactionRepository(new \App\Models\Tenant\Transaction()), new \App\Services\LedgerBridgeService()));
    }

    private function makeAsset(array $overrides = []): FixedAsset
    {
        return FixedAsset::create(array_merge([
            'branch_id' => 1,
            'code' => 'FA-TEST-'.uniqid(),
            'name' => 'Test Asset',
            'purchase_date' => '2026-01-01',
            'cost' => 1200,
            'salvage_value' => 0,
            'useful_life_months' => 12,
            'depreciation_method' => FixedAsset::METHOD_STRAIGHT_LINE,
            'depreciation_start_date' => '2026-01-01',
            'status' => FixedAsset::STATUS_ACTIVE,
        ], $overrides));
    }

    public function test_monthly_charge_straight_line(): void
    {
        $asset = $this->makeAsset();
        $service = $this->depreciationService();

        $this->assertEquals(100.0, $service->monthlyCharge($asset));
    }

    public function test_run_for_period_is_idempotent(): void
    {
        $asset = $this->makeAsset();
        $service = $this->depreciationService();

        $report1 = $service->runForPeriod(2026, 1, dryRun: false);
        $this->assertSame('posted', $report1[0]['status']);
        $this->assertEquals(100.0, $report1[0]['amount']);

        $asset->refresh();
        $this->assertEquals(100.0, (float) $asset->accumulated_depreciation);
        $this->assertSame(1, \App\Models\Tenant\FixedAssetDepreciationEntry::where('fixed_asset_id', $asset->id)->count());

        // running the same period again must not double-post
        $report2 = $service->runForPeriod(2026, 1, dryRun: false);
        $this->assertSame('skipped: already posted for period', $report2[0]['status']);

        $asset->refresh();
        $this->assertEquals(100.0, (float) $asset->accumulated_depreciation);
        $this->assertSame(1, \App\Models\Tenant\FixedAssetDepreciationEntry::where('fixed_asset_id', $asset->id)->count());
    }

    public function test_conflicting_depreciation_basis_is_rejected(): void
    {
        $asset = $this->makeAsset(['useful_life_months' => 12, 'depreciation_rate' => 10]);
        $service = $this->depreciationService();

        $this->expectException(\InvalidArgumentException::class);
        $service->monthlyCharge($asset);
    }

    public function test_zero_cost_asset_is_rejected(): void
    {
        $asset = $this->makeAsset(['cost' => 0]);
        $service = $this->depreciationService();

        $this->expectException(\InvalidArgumentException::class);
        $service->monthlyCharge($asset);
    }

    public function test_dry_run_does_not_post(): void
    {
        $asset = $this->makeAsset();
        $service = $this->depreciationService();

        $report = $service->runForPeriod(2026, 1, dryRun: true);
        $this->assertSame('would post', $report[0]['status']);

        $asset->refresh();
        $this->assertEquals(0.0, (float) $asset->accumulated_depreciation);
        $this->assertSame(0, \App\Models\Tenant\FixedAssetDepreciationEntry::count());
    }
}
