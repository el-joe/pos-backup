<?php

namespace Tests\Feature\Tenant;

use App\Enums\Tenant\ExpenseTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Expense;
use App\Models\Tenant\ExpenseAmortisationEntry;
use App\Models\Tenant\ExpenseCategory;
use App\Models\Tenant\PaymentMethod;
use App\Models\Tenant\Transaction;
use App\Services\ExpenseService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExpenseLifecycleTest extends TestCase
{
    private const TEST_DATABASE = 'test_expense_lifecycle';
    private const TABLES = [
        'journal_entry_lines', 'journal_entries', 'chart_of_accounts',
        'expense_amortisation_entries', 'transaction_lines', 'transactions',
        'expenses', 'expense_categories', 'accounts', 'payment_methods', 'cash_registers',
    ];

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

        foreach (self::TABLES as $table) {
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
            $table->text('reversal_reason')->nullable();
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

        Schema::connection('testing_tenant')->create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->string('key')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('default')->default(false);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_category_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('payment_account_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->string('type')->default('normal');
            $table->timestamp('accrued_at')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->date('amortisation_start_date')->nullable();
            $table->unsignedInteger('amortisation_months')->nullable();
            $table->date('expense_date')->nullable();
            $table->text('note')->nullable();
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('fixed_asset_entry_type')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('expense_amortisation_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_id')->index();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
            $table->unique(['expense_id', 'period_year', 'period_month'], 'expense_amortisation_period_unique');
        });

        Schema::connection('testing_tenant')->create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('admin_id')->index();
            $table->decimal('total_expenses', 10, 2)->default(0);
            $table->decimal('total_expense_refunds', 10, 2)->default(0);
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
            $table->softDeletes();
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

        \App\Models\Tenant\Contracting\ChartOfAccount::create(['code' => '2050', 'name' => 'Accrued Expenses', 'type' => 'liability']);
        \App\Models\Tenant\Contracting\ChartOfAccount::create(['code' => '1080', 'name' => 'Prepaid Expenses', 'type' => 'asset']);
        \App\Models\Tenant\Contracting\ChartOfAccount::create(['code' => '1050', 'name' => 'VAT Receivable', 'type' => 'asset']);
        \App\Models\Tenant\Contracting\ChartOfAccount::create(['code' => '1010', 'name' => 'Branch Cash', 'type' => 'asset']);
        \App\Models\Tenant\Contracting\ChartOfAccount::create(['code' => '6020', 'name' => 'General Expenses', 'type' => 'expense']);

        PaymentMethod::create(['name' => 'Cash', 'slug' => 'cash']);

        $admin = new class extends \Illuminate\Foundation\Auth\User {
            public $id = 1;
        };
        Auth::guard(TENANT_ADMINS_GUARD)->setUser($admin);
    }

    protected function tearDown(): void
    {
        foreach (self::TABLES as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }
        DB::purge('testing_tenant');

        parent::tearDown();
    }

    private function expenseService(): ExpenseService
    {
        return app(ExpenseService::class);
    }

    private function makeCategory(?string $key = null): ExpenseCategory
    {
        return ExpenseCategory::create([
            'name' => 'Rent',
            'key' => $key,
            'active' => true,
        ]);
    }

    public function test_accrued_expense_is_posted_as_a_liability(): void
    {
        $category = $this->makeCategory();
        $service = $this->expenseService();

        $expense = $service->save(null, [
            'branch_id' => 1,
            'expense_category_id' => $category->id,
            'amount' => 10000,
            'expense_date' => '2026-01-01',
            'type' => ExpenseTypeEnum::ACCRUED->value,
        ]);

        $this->assertNotNull($expense->accrued_at);
        $this->assertNull($expense->settled_at);
        $this->assertEquals(0.0, (float) $expense->total_paid);

        $transaction = Transaction::where('reference_type', Expense::class)->where('reference_id', $expense->id)->first();
        $this->assertNotNull($transaction);
        $this->assertEquals(10000.0, (float) $transaction->amount);

        $accruedAccount = Account::where('type', 'accrued_expenses')->first();
        $this->assertNotNull($accruedAccount);

        $creditLine = $transaction->lines()->where('account_id', $accruedAccount->id)->first();
        $this->assertEquals('credit', $creditLine->type);
        $this->assertEquals(10000.0, (float) $creditLine->amount);
    }

    public function test_settling_an_accrual_pays_the_liability_from_cash(): void
    {
        $category = $this->makeCategory();
        $service = $this->expenseService();

        $expense = $service->save(null, [
            'branch_id' => 1,
            'expense_category_id' => $category->id,
            'amount' => 10000,
            'expense_date' => '2026-01-01',
            'type' => ExpenseTypeEnum::ACCRUED->value,
        ]);

        $expense = $service->payExpense($expense->id, $expense->total);

        $this->assertNotNull($expense->settled_at);
        $this->assertEquals(10000.0, (float) $expense->total_paid);

        $this->expectException(\RuntimeException::class);
        $service->settleAccrual($expense->refresh());
    }

    public function test_prepaid_expense_posts_to_a_prepaid_asset_not_the_expense_account(): void
    {
        $category = $this->makeCategory();
        $service = $this->expenseService();

        $expense = $service->save(null, [
            'branch_id' => 1,
            'expense_category_id' => $category->id,
            'amount' => 1200,
            'expense_date' => '2026-01-01',
            'type' => ExpenseTypeEnum::PREPAID->value,
            'amortisation_start_date' => '2026-01-01',
            'amortisation_months' => 12,
        ]);

        $this->assertEquals(1200.0, (float) $expense->total_paid);

        $prepaidAccount = Account::where('type', 'prepaid_asset')->first();
        $this->assertNotNull($prepaidAccount);

        $expenseAccount = Account::where('type', 'expense')->first();
        $this->assertNull($expenseAccount, 'No expense-account line should be posted at recognition.');
    }

    public function test_amortise_is_idempotent_per_expense_per_period(): void
    {
        $category = $this->makeCategory();
        $service = $this->expenseService();

        $expense = $service->save(null, [
            'branch_id' => 1,
            'expense_category_id' => $category->id,
            'amount' => 1200,
            'expense_date' => '2026-01-01',
            'type' => ExpenseTypeEnum::PREPAID->value,
            'amortisation_start_date' => '2026-01-01',
            'amortisation_months' => 12,
        ]);

        $report1 = $service->amortise(2026, 1, dryRun: false);
        $this->assertSame('posted', $report1[0]['status']);
        $this->assertEquals(100.0, $report1[0]['amount']);
        $this->assertSame(1, ExpenseAmortisationEntry::where('expense_id', $expense->id)->count());

        $report2 = $service->amortise(2026, 1, dryRun: false);
        $this->assertSame('skipped: already posted for period', $report2[0]['status']);
        $this->assertSame(1, ExpenseAmortisationEntry::where('expense_id', $expense->id)->count());
    }

    public function test_deleting_a_taxed_expense_reverses_the_vat_line_and_the_full_amount(): void
    {
        $category = $this->makeCategory();
        $service = $this->expenseService();

        $expense = $service->save(null, [
            'branch_id' => 1,
            'expense_category_id' => $category->id,
            'amount' => 20000,
            'tax_percentage' => 5,
            'expense_date' => '2026-01-01',
            'type' => ExpenseTypeEnum::NORMAL->value,
        ]);

        $this->assertEquals(21000.0, (float) $expense->total_paid);

        $vatAccount = Account::where('type', 'vat_receivable')->first();
        $this->assertNotNull($vatAccount);

        // paid VAT is 1000 before deletion
        $paidVat = (float) DB::connection('testing_tenant')
            ->table('transaction_lines')
            ->where('account_id', $vatAccount->id)
            ->where('type', 'debit')
            ->sum('amount');
        $this->assertEquals(1000.0, $paidVat);

        $service->delete($expense->id);

        // after reversal, the VAT receivable line nets back to zero
        $netVat = (float) DB::connection('testing_tenant')
            ->table('transaction_lines')
            ->where('account_id', $vatAccount->id)
            ->selectRaw("SUM(CASE WHEN type='debit' THEN amount ELSE -amount END) as net")
            ->value('net');
        $this->assertEquals(0.0, $netVat);
    }

    public function test_delete_refuses_an_expense_with_no_posted_transaction(): void
    {
        $category = $this->makeCategory();

        $expense = Expense::create([
            'branch_id' => 1,
            'expense_category_id' => $category->id,
            'amount' => 500,
            'expense_date' => '2026-01-01',
            'type' => ExpenseTypeEnum::NORMAL->value,
        ]);

        $service = $this->expenseService();

        $this->expectException(\RuntimeException::class);
        $service->delete($expense->id);
    }

    public function test_editing_a_posted_expense_is_blocked(): void
    {
        $category = $this->makeCategory();
        $service = $this->expenseService();

        $expense = $service->save(null, [
            'branch_id' => 1,
            'expense_category_id' => $category->id,
            'amount' => 500,
            'expense_date' => '2026-01-01',
            'type' => ExpenseTypeEnum::NORMAL->value,
        ]);

        $this->expectException(\RuntimeException::class);
        $service->save($expense->id, ['amount' => 999]);
    }
}
