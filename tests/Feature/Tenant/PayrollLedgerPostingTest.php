<?php

namespace Tests\Feature\Tenant;

use App\Enums\PayrollRunStatusEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Employee;
use App\Models\Tenant\EmployeeContract;
use App\Models\Tenant\PayrollRun;
use App\Models\Tenant\Transaction;
use App\Services\Hrm\PayrollRunService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * End-to-end coverage of the two-stage payroll ledger posting
 * (approve() = accrual, pay() = settlement) against a real mysql schema,
 * isolated from any configured tenant database via a dedicated connection.
 */
class PayrollLedgerPostingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $database = env('PAYROLL_TEST_DB_DATABASE', 'payroll_test');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', 3306);
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');

        // Create the dedicated test database on demand (no fixture/CI setup required).
        Config::set('database.connections.payroll_test_server', [
            'driver' => 'mysql',
            'host' => $host,
            'port' => $port,
            'database' => '',
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8mb4',
        ]);
        DB::purge('payroll_test_server');
        DB::connection('payroll_test_server')->statement("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        Config::set('database.connections.payroll_test', [
            'driver' => 'mysql',
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ]);
        Config::set('database.default', 'payroll_test');
        DB::purge('payroll_test');

        $this->dropSchema();
        $this->createSchema();

        // Several unrelated models in this codebase (TransactionLine, AuditLog) assume an
        // authenticated tenant admin is present even in non-HTTP contexts. That's the same
        // "admin() is null in console/queue context" class of bug this task's brief calls out
        // for PayrollRunService specifically; logging in a stub admin here keeps this test
        // focused on payroll ledger posting rather than also fixing those unrelated call sites.
        $admin = \App\Models\Tenant\Admin::create(['type' => 'admin']);
        auth(TENANT_ADMINS_GUARD)->login($admin);
    }

    protected function tearDown(): void
    {
        $this->dropSchema();
        parent::tearDown();
    }

    protected function dropSchema(): void
    {
        foreach ([
            'audit_logs', 'admins',
            'payroll_components', 'attendance_logs', 'attendance_sheets', 'leave_requests', 'leave_types',
            'payroll_slip_lines', 'payroll_slips', 'payroll_runs',
            'employee_contracts', 'employees',
            'transaction_lines', 'transactions', 'accounts', 'payment_methods', 'branches',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }

    protected function createSchema(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->index()->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedBigInteger('payment_method_id')->nullable()->index();
            $table->string('model_type')->nullable()->index();
            $table->unsignedBigInteger('model_id')->nullable()->index();
            $table->string('type');
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->timestamps();
            $table->boolean('active')->default(1);
            $table->softDeletes();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->text('note')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('transaction_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->index();
            $table->unsignedBigInteger('account_id')->index();
            $table->enum('type', ['debit', 'credit']);
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('status')->default('active');
            $table->string('national_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('admin');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('action');
            $table->json('description')->nullable();
            $table->timestamps();
        });

        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('yearly_allowance', 10, 2)->default(0);
            $table->boolean('is_paid')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('days', 10, 2)->default(0);
            $table->string('reason')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('attendance_sheets', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_sheet_id')->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->timestamp('clock_in_at')->nullable();
            $table->timestamp('clock_out_at')->nullable();
            $table->string('status')->default('present');
            $table->string('source')->nullable();
            $table->timestamps();
        });

        Schema::create('payroll_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->index();
            $table->string('type');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->boolean('is_recurring')->default(true);
            $table->unsignedTinyInteger('month')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->string('status')->default('draft');
            $table->decimal('total_payout', 15, 2)->default(0);
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('approved_transaction_id')->nullable();
            $table->timestamps();
            $table->unique(['month', 'year']);
        });

        Schema::create('payroll_slips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_run_id')->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->decimal('gross_pay', 15, 2)->default(0);
            $table->decimal('net_pay', 15, 2)->default(0);
            $table->timestamps();
            $table->unique(['payroll_run_id', 'employee_id']);
        });

        Schema::create('payroll_slip_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_slip_id')->index();
            $table->string('type');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    protected function makeBranch(): Branch
    {
        return Branch::create(['name' => 'Main', 'phone' => '000', 'email' => 'b@example.com']);
    }

    protected function makeEmployee(Branch $branch, float $basicSalary): Employee
    {
        $employee = Employee::create([
            'employee_code' => 'EMP-' . uniqid(),
            'name' => 'Test Employee',
            'email' => uniqid() . '@example.com',
            'status' => 'active',
            'password' => 'secret',
            'branch_id' => $branch->id,
            'hire_date' => '2020-01-01',
        ]);

        EmployeeContract::create([
            'employee_id' => $employee->id,
            'basic_salary' => $basicSalary,
            'start_date' => '2020-01-01',
            'is_active' => true,
        ]);

        return $employee;
    }

    public function test_approve_then_pay_posts_two_stage_ledger_entries(): void
    {
        $branch = $this->makeBranch();
        $this->makeEmployee($branch, 2600);

        $run = PayrollRun::create([
            'branch_id' => $branch->id,
            'month' => 3,
            'year' => 2026,
            'status' => PayrollRunStatusEnum::APPROVED->value,
        ]);

        $service = app(PayrollRunService::class);
        $service->generateSlips($run);
        $service->approve($run->fresh(), $branch->id);

        $run->refresh();
        $this->assertNotNull($run->approved_transaction_id, 'Approval must post an accrual transaction.');
        $this->assertNull($run->transaction_id, 'Payment transaction must not exist until pay() is called.');
        $this->assertEquals(PayrollRunStatusEnum::APPROVED->value, $run->status->value, 'approve() must not change status to paid.');

        $accrualTransaction = Transaction::with('lines')->find($run->approved_transaction_id);
        $this->assertNotNull($accrualTransaction);

        $debitTotal = round((float) $accrualTransaction->lines->where('type', 'debit')->sum('amount'), 2);
        $creditTotal = round((float) $accrualTransaction->lines->where('type', 'credit')->sum('amount'), 2);
        $this->assertEqualsWithDelta($debitTotal, $creditTotal, 0.01, 'Accrual entry must balance (debits == credits).');

        $salariesPayableAccount = Account::where('type', 'salaries_payable')->first();
        $this->assertNotNull($salariesPayableAccount, 'Salaries Payable account must be created on accrual.');

        // Not yet paid -> pay() should succeed.
        $service->pay($run->fresh(), $branch->id);

        $run->refresh();
        $this->assertEquals(PayrollRunStatusEnum::PAID->value, $run->status->value);
        $this->assertNotNull($run->transaction_id);

        $paymentTransaction = Transaction::with('lines')->find($run->transaction_id);
        $paymentDebitTotal = round((float) $paymentTransaction->lines->where('type', 'debit')->sum('amount'), 2);
        $paymentCreditTotal = round((float) $paymentTransaction->lines->where('type', 'credit')->sum('amount'), 2);
        $this->assertEqualsWithDelta($paymentDebitTotal, $paymentCreditTotal, 0.01, 'Payment entry must balance.');
        $this->assertEqualsWithDelta((float) $run->total_payout, $paymentDebitTotal, 0.01);

        // The payment DR's Salaries Payable and CR's the branch cash account (default payment account).
        $branchCashAccount = Account::where('type', 'branch_cash')->first();
        $this->assertNotNull($branchCashAccount);
        $this->assertTrue($paymentTransaction->lines->contains(fn($l) => $l->account_id === $salariesPayableAccount->id && $l->type === 'debit'));
        $this->assertTrue($paymentTransaction->lines->contains(fn($l) => $l->account_id === $branchCashAccount->id && $l->type === 'credit'));
    }

    public function test_pay_before_approve_is_rejected(): void
    {
        $branch = $this->makeBranch();
        $this->makeEmployee($branch, 2600);

        $run = PayrollRun::create([
            'branch_id' => $branch->id,
            'month' => 4,
            'year' => 2026,
            'status' => PayrollRunStatusEnum::APPROVED->value,
        ]);

        $service = app(PayrollRunService::class);
        $service->generateSlips($run);

        $this->expectException(\RuntimeException::class);
        $service->pay($run->fresh(), $branch->id);
    }

    public function test_approve_cannot_double_post(): void
    {
        $branch = $this->makeBranch();
        $this->makeEmployee($branch, 2600);

        $run = PayrollRun::create([
            'branch_id' => $branch->id,
            'month' => 5,
            'year' => 2026,
            'status' => PayrollRunStatusEnum::APPROVED->value,
        ]);

        $service = app(PayrollRunService::class);
        $service->generateSlips($run);
        $service->approve($run->fresh(), $branch->id);

        $this->expectException(\RuntimeException::class);
        $service->approve($run->fresh(), $branch->id);
    }

    public function test_deleting_a_posted_run_is_blocked(): void
    {
        $branch = $this->makeBranch();
        $this->makeEmployee($branch, 2600);

        $run = PayrollRun::create([
            'branch_id' => $branch->id,
            'month' => 6,
            'year' => 2026,
            'status' => PayrollRunStatusEnum::APPROVED->value,
        ]);

        $service = app(PayrollRunService::class);
        $service->generateSlips($run);
        $service->approve($run->fresh(), $branch->id);

        $this->expectException(\RuntimeException::class);
        $service->delete($run->id);
    }

    public function test_reversing_a_posted_run_allows_deletion(): void
    {
        $branch = $this->makeBranch();
        $this->makeEmployee($branch, 2600);

        $run = PayrollRun::create([
            'branch_id' => $branch->id,
            'month' => 7,
            'year' => 2026,
            'status' => PayrollRunStatusEnum::APPROVED->value,
        ]);

        $service = app(PayrollRunService::class);
        $service->generateSlips($run);
        $service->approve($run->fresh(), $branch->id);

        $service->reverse($run->fresh(), $branch->id);

        $run->refresh();
        $this->assertNull($run->transaction_id);
        $this->assertNull($run->approved_transaction_id);
        $this->assertEquals(PayrollRunStatusEnum::DRAFT->value, $run->status->value);

        $this->assertTrue((bool) $service->delete($run->id));
    }

    public function test_generate_slips_is_scoped_by_branch(): void
    {
        $branchA = $this->makeBranch();
        $branchB = Branch::create(['name' => 'Second', 'phone' => '111', 'email' => 'b2@example.com']);

        $this->makeEmployee($branchA, 2600);
        $this->makeEmployee($branchB, 3000);

        $run = PayrollRun::create([
            'branch_id' => $branchA->id,
            'month' => 8,
            'year' => 2026,
            'status' => PayrollRunStatusEnum::APPROVED->value,
        ]);

        $service = app(PayrollRunService::class);
        $service->generateSlips($run);

        $this->assertEquals(1, \App\Models\Tenant\PayrollSlip::where('payroll_run_id', $run->id)->count(), 'Only the run branch employee should get a slip.');
    }
}
