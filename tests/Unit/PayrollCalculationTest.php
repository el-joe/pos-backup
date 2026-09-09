<?php

namespace Tests\Unit;

use App\Models\Tenant\AttendanceLog;
use App\Models\Tenant\AttendanceSheet;
use App\Models\Tenant\Employee;
use App\Models\Tenant\EmployeeContract;
use App\Models\Tenant\LeaveRequest;
use App\Models\Tenant\LeaveType;
use App\Models\Tenant\PayrollComponent;
use App\Services\Hrm\PayrollCalculationService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * These tests run against a dedicated mysql database (created on demand, see
 * setUp()) so they don't depend on / mutate a configured tenant database. This
 * repo has no sqlite driver available and no existing tenant test scaffolding,
 * so each test file provisions its own minimal schema for just the tables it
 * needs. tenant() resolves to null outside of a tenancy context, so
 * tenantSetting() falls back to its defaults (26-day divisor, 0% statutory
 * rates) unless a test explicitly seeds the `settings` table.
 */
class PayrollCalculationTest extends TestCase
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
    }

    protected function tearDown(): void
    {
        $this->dropSchema();
        parent::tearDown();
    }

    protected function dropSchema(): void
    {
        Schema::dropIfExists('payroll_components');
        Schema::dropIfExists('attendance_logs');
        Schema::dropIfExists('attendance_sheets');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_types');
        Schema::dropIfExists('employee_contracts');
        Schema::dropIfExists('employees');
    }

    protected function createSchema(): void
    {
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
    }

    protected function makeEmployee(array $overrides = []): Employee
    {
        return Employee::create(array_merge([
            'employee_code' => 'EMP-' . uniqid(),
            'name' => 'Test Employee',
            'email' => uniqid() . '@example.com',
            'status' => 'active',
            'password' => 'secret',
        ], $overrides));
    }

    protected function makeContract(Employee $employee, float $basicSalary, string $start = '2020-01-01'): EmployeeContract
    {
        return EmployeeContract::create([
            'employee_id' => $employee->id,
            'basic_salary' => $basicSalary,
            'start_date' => $start,
            'is_active' => true,
        ]);
    }

    /**
     * The headline regression: one approved UNPAID leave day must be deducted exactly
     * once. Before the fix, the same day was deducted both as "unpaid leave" AND as
     * "absence" (because the employee predictably has no attendance log on a leave day),
     * effectively docking two days' pay for a single day off.
     */
    public function test_one_unpaid_leave_day_is_deducted_exactly_once(): void
    {
        $employee = $this->makeEmployee(['hire_date' => '2019-01-01']);
        $this->makeContract($employee, 2600); // daily rate = 2600 / 26 = 100

        $leaveType = LeaveType::create(['name' => 'Unpaid', 'is_paid' => false]);

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => '2026-03-10',
            'end_date' => '2026-03-10',
            'days' => 1,
            'status' => 'approved',
        ]);

        $service = app(PayrollCalculationService::class);
        $result = $service->calculateForEmployee($employee, 3, 2026);

        $unpaidLines = collect($result['lines'])->where('type', 'unpaid_leave_deduction');
        $absenceLines = collect($result['lines'])->where('type', 'absence_deduction');

        $this->assertCount(1, $unpaidLines, 'Expected exactly one unpaid-leave deduction line.');
        $this->assertCount(0, $absenceLines, 'A day already accounted for as unpaid leave must not also be counted as an absence.');

        $dailyRate = 2600 / 26;
        $this->assertEqualsWithDelta(-$dailyRate, $unpaidLines->first()['amount'], 0.01);

        // Gross stays at the full basic salary; only the one day's pay is deducted from net.
        $this->assertEqualsWithDelta(2600, $result['gross'], 0.01);
        $this->assertEqualsWithDelta(2600 - $dailyRate, $result['netPay'], 0.01);
    }

    /**
     * Mirror bug: an approved PAID leave day must never be treated as an absence
     * (no attendance log on a paid-leave day should not dock pay).
     */
    public function test_approved_paid_leave_day_is_not_counted_as_absence(): void
    {
        $employee = $this->makeEmployee(['hire_date' => '2019-01-01']);
        $this->makeContract($employee, 2600);

        $leaveType = LeaveType::create(['name' => 'Annual', 'is_paid' => true]);

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => '2026-03-10',
            'end_date' => '2026-03-10',
            'days' => 1,
            'status' => 'approved',
        ]);

        $service = app(PayrollCalculationService::class);
        $result = $service->calculateForEmployee($employee, 3, 2026);

        $deductionLines = collect($result['lines'])->whereIn('type', ['absence_deduction', 'unpaid_leave_deduction']);

        $this->assertCount(0, $deductionLines, 'Approved paid leave must not generate any deduction.');
        $this->assertEqualsWithDelta(2600, $result['netPay'], 0.01);
    }

    /**
     * A genuine absence (approved attendance sheet, no log / explicit "absent" log,
     * and NOT covered by any leave request) is still deducted once.
     */
    public function test_genuine_absence_without_leave_is_deducted(): void
    {
        $employee = $this->makeEmployee(['hire_date' => '2019-01-01']);
        $this->makeContract($employee, 2600);

        AttendanceSheet::create([
            'date' => '2026-03-11',
            'status' => 'approved',
        ]);
        // No attendance log created for the employee on this sheet => absent.

        $service = app(PayrollCalculationService::class);
        $result = $service->calculateForEmployee($employee, 3, 2026);

        $absenceLines = collect($result['lines'])->where('type', 'absence_deduction');
        $this->assertCount(1, $absenceLines);

        $dailyRate = 2600 / 26;
        $this->assertEqualsWithDelta(-$dailyRate, $absenceLines->first()['amount'], 0.01);
    }

    /**
     * A present day (attendance log status=present on an approved sheet) generates
     * no deduction at all.
     */
    public function test_present_day_generates_no_deduction(): void
    {
        $employee = $this->makeEmployee(['hire_date' => '2019-01-01']);
        $this->makeContract($employee, 2600);

        $sheet = AttendanceSheet::create([
            'date' => '2026-03-12',
            'status' => 'approved',
        ]);

        AttendanceLog::create([
            'attendance_sheet_id' => $sheet->id,
            'employee_id' => $employee->id,
            'status' => 'present',
        ]);

        $service = app(PayrollCalculationService::class);
        $result = $service->calculateForEmployee($employee, 3, 2026);

        $deductionLines = collect($result['lines'])->whereIn('type', ['absence_deduction', 'unpaid_leave_deduction']);
        $this->assertCount(0, $deductionLines);
        $this->assertEqualsWithDelta(2600, $result['netPay'], 0.01);
    }

    /** Proration for a mid-month joiner. */
    public function test_mid_month_joiner_is_prorated(): void
    {
        // 2026-03 has 31 days; employee joins on the 16th => 16 employed days.
        $employee = $this->makeEmployee(['hire_date' => '2026-03-16']);
        $this->makeContract($employee, 3100, '2026-03-16'); // 3100 / 31 = 100/day if calendar basis were used

        $service = app(PayrollCalculationService::class);
        $result = $service->calculateForEmployee($employee, 3, 2026);

        $basicLine = collect($result['lines'])->firstWhere('type', 'basic');
        $this->assertNotNull($basicLine);
        // 16 employed days out of 31 in the month, at the (default 26-day divisor) daily rate.
        $dailyRate = 3100 / 26;
        $expected = round($dailyRate * 16, 2);
        $this->assertEqualsWithDelta($expected, $basicLine['amount'], 0.05);
        $this->assertLessThan(3100, $basicLine['amount']);
    }

    /** Half-day (fractional) unpaid leave must deduct exactly half a day's pay, not a whole day. */
    public function test_half_day_unpaid_leave_deducts_half_day(): void
    {
        $employee = $this->makeEmployee(['hire_date' => '2019-01-01']);
        $this->makeContract($employee, 2600);

        $leaveType = LeaveType::create(['name' => 'Unpaid', 'is_paid' => false]);

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => '2026-03-10',
            'end_date' => '2026-03-10',
            'days' => 0.5,
            'status' => 'approved',
        ]);

        $service = app(PayrollCalculationService::class);
        $result = $service->calculateForEmployee($employee, 3, 2026);

        $unpaidLines = collect($result['lines'])->where('type', 'unpaid_leave_deduction');
        $this->assertCount(1, $unpaidLines);

        $dailyRate = 2600 / 26;
        $this->assertEqualsWithDelta(-$dailyRate * 0.5, $unpaidLines->first()['amount'], 0.01);
    }

    /** Configurable earning components (e.g. allowance) add to gross via payroll_components. */
    public function test_allowance_component_adds_to_gross(): void
    {
        $employee = $this->makeEmployee(['hire_date' => '2019-01-01']);
        $this->makeContract($employee, 2600);

        PayrollComponent::create([
            'employee_id' => $employee->id,
            'type' => 'allowance',
            'amount' => 200,
            'description' => 'Transport Allowance',
            'is_recurring' => true,
        ]);

        $service = app(PayrollCalculationService::class);
        $result = $service->calculateForEmployee($employee, 3, 2026);

        $this->assertEqualsWithDelta(2800, $result['gross'], 0.01);
        $this->assertEqualsWithDelta(2800, $result['netPay'], 0.01);
    }
}
