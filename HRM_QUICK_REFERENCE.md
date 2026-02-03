# HRM System - Quick Reference

## Directory Structure

```
app/
├── Enums/
│   ├── EmployeeStatusEnum.php
│   ├── EmploymentTypeEnum.php
│   ├── LeaveTypeEnum.php
│   ├── LeaveStatusEnum.php
│   ├── AttendanceStatusEnum.php
│   ├── JobApplicationStatusEnum.php
│   ├── PayslipStatusEnum.php
│   └── PerformanceRatingEnum.php
│
├── Livewire/Admin/HRM/
│   ├── Employees/
│   │   ├── EmployeesList.php
│   │   ├── AddEditEmployee.php
│   │   └── EmployeeDetails.php
│   ├── Departments/
│   │   └── DepartmentsList.php
│   ├── Attendance/
│   │   ├── AttendanceList.php
│   │   └── ClockInOut.php
│   ├── Leaves/
│   │   └── LeaveRequestsList.php
│   ├── Payroll/
│   │   └── PayslipsList.php
│   ├── Recruitment/
│   │   └── JobApplicationsList.php
│   └── Performance/
│       └── AppraisalsList.php
│
├── Models/Tenant/
│   ├── Department.php
│   ├── Designation.php
│   ├── Employee.php
│   ├── EmployeeDocument.php
│   ├── Attendance.php
│   ├── LeaveType.php
│   ├── LeaveRequest.php
│   ├── EmployeeLeaveEntitlement.php
│   ├── SalaryStructure.php
│   ├── EmployeeSalary.php
│   ├── Payslip.php
│   ├── JobOpening.php
│   ├── JobApplication.php
│   ├── PerformanceKPI.php
│   ├── PerformanceAppraisal.php
│   ├── TrainingProgram.php
│   ├── TrainingParticipant.php
│   ├── Holiday.php
│   ├── Shift.php
│   └── EmployeeShift.php
│
└── Services/HRM/
    ├── LeaveEntitlementService.php
    └── PayrollService.php

database/
├── migrations/
│   ├── 2026_02_03_000001_create_hrm_departments_table.php
│   ├── 2026_02_03_000002_create_hrm_designations_table.php
│   ├── 2026_02_03_000003_create_hrm_employees_table.php
│   ├── 2026_02_03_000004_create_hrm_employee_documents_table.php
│   ├── 2026_02_03_000005_create_hrm_attendance_table.php
│   ├── 2026_02_03_000006_create_hrm_leave_types_table.php
│   ├── 2026_02_03_000007_create_hrm_employee_leave_entitlements_table.php
│   ├── 2026_02_03_000008_create_hrm_leave_requests_table.php
│   ├── 2026_02_03_000009_create_hrm_salary_structures_table.php
│   ├── 2026_02_03_000010_create_hrm_employee_salaries_table.php
│   ├── 2026_02_03_000011_create_hrm_payslips_table.php
│   ├── 2026_02_03_000012_create_hrm_job_openings_table.php
│   ├── 2026_02_03_000013_create_hrm_job_applications_table.php
│   ├── 2026_02_03_000014_create_hrm_performance_kpis_table.php
│   ├── 2026_02_03_000015_create_hrm_performance_appraisals_table.php
│   ├── 2026_02_03_000016_create_hrm_training_programs_table.php
│   ├── 2026_02_03_000017_create_hrm_training_participants_table.php
│   ├── 2026_02_03_000018_create_hrm_holidays_table.php
│   ├── 2026_02_03_000019_create_hrm_shifts_table.php
│   └── 2026_02_03_000020_create_hrm_employee_shifts_table.php
│
└── seeders/
    └── HRMSeeder.php

lang/
├── en/
│   └── hrm.php
└── ar/
    └── hrm.php
```

## Commands to Run

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Initial Data
```bash
php artisan db:seed --class=HRMSeeder
```

### 3. Clear Cache (if needed)
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Common Tasks

### Add New Employee
```php
use App\Models\Tenant\Employee;
use App\Services\HRM\LeaveEntitlementService;

$employee = Employee::create([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john.doe@example.com',
    'department_id' => 1,
    'designation_id' => 1,
    'branch_id' => 1,
    'employment_type' => 'full_time',
    'joining_date' => now(),
    'status' => 'active',
]);

// Initialize leave entitlements
$leaveService = new LeaveEntitlementService();
$leaveService->initializeLeaveEntitlements($employee);
```

### Generate Monthly Payslips
```php
use App\Services\HRM\PayrollService;
use App\Models\Tenant\Employee;

$payrollService = new PayrollService();
$employees = Employee::where('status', 'active')->get();

foreach ($employees as $employee) {
    $payrollService->generatePayslip($employee, 2026, 2);
}
```

### Check Leave Balance
```php
use App\Services\HRM\LeaveEntitlementService;

$leaveService = new LeaveEntitlementService();
$hasBalance = $leaveService->hasLeaveBalance($employee, $leaveTypeId, $days, $year);
```

### Clock In/Out
```php
use App\Models\Tenant\Attendance;

// Clock In
Attendance::create([
    'employee_id' => $employeeId,
    'branch_id' => $branchId,
    'date' => now()->toDateString(),
    'clock_in' => now(),
    'clock_in_ip' => request()->ip(),
    'status' => 'present',
]);

// Clock Out
$attendance = Attendance::where('employee_id', $employeeId)
    ->whereDate('date', today())
    ->first();

$attendance->update([
    'clock_out' => now(),
    'clock_out_ip' => request()->ip(),
    'working_hours' => now()->diffInMinutes($attendance->clock_in),
]);
```

## API Usage Examples

### Get Employee with Relations
```php
$employee = Employee::with([
    'department',
    'designation',
    'branch',
    'manager',
    'currentSalary',
    'leaveEntitlements',
    'documents',
])->find($id);
```

### Get Attendance for Month
```php
$attendances = Attendance::where('employee_id', $employeeId)
    ->whereYear('date', 2026)
    ->whereMonth('date', 2)
    ->get();
```

### Get Pending Leave Requests
```php
$pendingLeaves = LeaveRequest::where('status', 'pending')
    ->with(['employee', 'leaveType'])
    ->orderBy('start_date')
    ->get();
```

## Important Notes

1. **Employee Code** is auto-generated in format: `EMP-000001`
2. **Payslip Number** format: `PAY-202602-0001`
3. **Application Number** format: `APP-20260203-0001`
4. **All models use soft deletes** - use `->withTrashed()` to include deleted records
5. **Bilingual support** - Use `ar_name` fields for Arabic translations
6. **Pro-rated leave calculation** - Leave days are automatically calculated based on joining date
7. **Leave carry forward** - Processed automatically at year end (requires cron job)
8. **Overtime calculation** - 1.5x basic hourly rate by default

## Workflow Examples

### Leave Request Workflow
1. Employee applies for leave via `LeaveRequestsList`
2. System checks leave balance
3. Manager/HR receives notification
4. Approve/reject leave
5. Leave balance automatically updated
6. Attendance marked as "on_leave" for those dates

### Payroll Workflow
1. Generate payslips for all employees monthly
2. System calculates based on attendance
3. Review and approve payslips
4. Mark as paid after payment processing
5. Accounting entry created automatically

### Recruitment Workflow
1. Create job opening
2. Receive applications
3. Screen candidates
4. Schedule interviews
5. Make offer
6. Onboard successful candidate
7. Convert to employee record

## Customization Points

### Salary Structure
Modify in `SalaryStructure` model:
```php
'allowances' => [
    ['name' => 'Housing Allowance', 'amount' => 1000, 'type' => 'fixed'],
    ['name' => 'Transport Allowance', 'amount' => 500, 'type' => 'fixed'],
    ['name' => 'Performance Bonus', 'amount' => 10, 'type' => 'percentage'],
]

'deductions' => [
    ['name' => 'Social Insurance', 'amount' => 5, 'type' => 'percentage'],
    ['name' => 'Tax', 'amount' => 10, 'type' => 'percentage'],
]
```

### Overtime Calculation
Modify in `PayrollService`:
```php
// Change overtime rate from 1.5x to 2x
$overtimeRate = $hourlyRate * 2.0;
```

### Leave Carry Forward
Modify in `LeaveEntitlementService`:
```php
$carryForwardDays = min(
    $prev->remaining_days,
    $leaveType->max_carry_forward_days ?? $prev->remaining_days
);
```

## Permissions Required

Add these permissions to your system:
- `hrm.view`
- `hrm.employees.view`
- `hrm.employees.create`
- `hrm.employees.edit`
- `hrm.employees.delete`
- `hrm.attendance.view`
- `hrm.attendance.manage`
- `hrm.leaves.view`
- `hrm.leaves.approve`
- `hrm.payroll.view`
- `hrm.payroll.generate`
- `hrm.payroll.approve`
- `hrm.recruitment.view`
- `hrm.recruitment.manage`
- `hrm.performance.view`
- `hrm.performance.manage`

## Support & Maintenance

### Regular Tasks
1. **Monthly**: Generate payslips
2. **Yearly**: Process leave carry forward
3. **Daily**: Monitor attendance
4. **Weekly**: Review pending leave requests

### Backup Recommendations
- Daily database backups
- Document storage backups
- Payslip archive retention

### Performance Optimization
- Index on employee_id, date fields
- Cache department/designation lists
- Paginate large employee lists
- Archive old payslips (> 2 years)
