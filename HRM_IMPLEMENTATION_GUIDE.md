# HRM System - Implementation Guide

## Overview
A comprehensive Human Resource Management (HRM) system has been integrated into your ERP system. This includes employee management, attendance tracking, leave management, payroll, recruitment, and performance management.

## Created Components

### 1. Enums
- `EmployeeStatusEnum` - Employee statuses (Active, Inactive, On Leave, Probation, Suspended, Terminated, Resigned)
- `EmploymentTypeEnum` - Employment types (Full Time, Part Time, Contract, Temporary, Intern, Freelancer)
- `LeaveTypeEnum` - Leave types (Annual, Sick, Casual, Maternity, Paternity, Unpaid, Emergency, etc.)
- `LeaveStatusEnum` - Leave request statuses (Pending, Approved, Rejected, Cancelled)
- `AttendanceStatusEnum` - Attendance statuses (Present, Absent, Late, Half Day, On Leave, Holiday, Weekend)
- `JobApplicationStatusEnum` - Application statuses (Applied, Screening, Interview Scheduled, Offered, Hired, etc.)
- `PayslipStatusEnum` - Payslip statuses (Draft, Generated, Sent, Paid, Cancelled)
- `PerformanceRatingEnum` - Performance ratings (Outstanding, Exceeds Expectations, Meets Expectations, etc.)

### 2. Database Migrations (20 tables)
1. **departments** - Department/division structure
2. **designations** - Job positions/titles
3. **employees** - Employee master data
4. **employee_documents** - Employee document storage
5. **attendances** - Daily attendance records
6. **leave_types** - Leave type configuration
7. **employee_leave_entitlements** - Leave balances per employee
8. **leave_requests** - Leave applications
9. **salary_structures** - Salary templates
10. **employee_salaries** - Employee salary assignments
11. **payslips** - Monthly payslip generation
12. **job_openings** - Job vacancy postings
13. **job_applications** - Candidate applications
14. **performance_kpis** - KPI definitions
15. **performance_appraisals** - Performance reviews
16. **training_programs** - Training courses
17. **training_participants** - Training enrollments
18. **holidays** - Holiday calendar
19. **shifts** - Work shift definitions
20. **employee_shifts** - Employee shift assignments

### 3. Models (20 Eloquent Models)
All models are located in `app/Models/Tenant/`:
- Department, Designation, Employee, EmployeeDocument
- Attendance, LeaveType, LeaveRequest, EmployeeLeaveEntitlement
- SalaryStructure, EmployeeSalary, Payslip
- JobOpening, JobApplication
- PerformanceKPI, PerformanceAppraisal
- TrainingProgram, TrainingParticipant
- Holiday, Shift, EmployeeShift

### 4. Livewire Components
Located in `app/Livewire/Admin/HRM/`:

#### Employee Management
- `Employees/EmployeesList` - List all employees with filters
- `Employees/AddEditEmployee` - Create/edit employee records
- `Employees/EmployeeDetails` - View employee profile
- `Departments/DepartmentsList` - Manage departments

#### Attendance & Time Tracking
- `Attendance/AttendanceList` - View attendance records
- `Attendance/ClockInOut` - Employee clock-in/out interface

#### Leave Management
- `Leaves/LeaveRequestsList` - Manage leave requests with approval workflow

#### Payroll
- `Payroll/PayslipsList` - Generate and manage payslips

#### Recruitment
- `Recruitment/JobApplicationsList` - Track job applications

#### Performance
- `Performance/AppraisalsList` - Performance appraisal management

### 5. Routes
All HRM routes are prefixed with `/admin/hrm/` and use the `admin.hrm.` namespace:

```php
// Employee Management
GET /admin/hrm/employees - List employees
GET /admin/hrm/employees/create - Create employee
GET /admin/hrm/employees/{id}/edit - Edit employee
GET /admin/hrm/employees/{id}/details - Employee details

// Departments
GET /admin/hrm/departments - Manage departments

// Attendance
GET /admin/hrm/attendance - Attendance list
GET /admin/hrm/clock-in-out - Clock in/out

// Leaves
GET /admin/hrm/leave-requests - Leave requests

// Payroll
GET /admin/hrm/payslips - Payslips

// Recruitment
GET /admin/hrm/job-applications - Job applications

// Performance
GET /admin/hrm/appraisals - Performance appraisals
```

### 6. Translations
Complete English and Arabic translations in:
- `lang/en/hrm.php`
- `lang/ar/hrm.php`

### 7. Account Types for Payroll
Added to `AccountTypeEnum`:
- SALARY_PAYABLE - Salaries to be paid
- SALARY_EXPENSE - Salary expenses
- EMPLOYEE_BENEFITS - Employee benefits
- PAYROLL_TAX_PAYABLE - Payroll taxes
- EMPLOYEE_LOANS - Loans to employees
- EMPLOYEE_ADVANCES - Salary advances

## Installation Steps

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Initial Data (Optional)
Create seeders for:
- Default departments (HR, Finance, IT, Sales, etc.)
- Default leave types (Annual, Sick, Casual, etc.)
- Default shifts (Morning, Evening, Night)

### 3. Set Permissions
Ensure HRM permissions are added to your permission system:
- hrm.view
- hrm.employees.manage
- hrm.attendance.manage
- hrm.leaves.approve
- hrm.payroll.manage
- hrm.recruitment.manage
- hrm.performance.manage

### 4. Create Accounts for Payroll
Create default accounts for payroll in the accounting system:
- Salary Payable Account
- Salary Expense Account
- Employee Benefits Account
- Payroll Tax Payable Account

## Usage Guide

### Employee Onboarding
1. Navigate to HRM → Employees
2. Click "Add Employee"
3. Fill in personal details, employment details, and bank information
4. Assign to department, designation, and branch
5. Set employment type and joining date
6. Upload required documents

### Attendance Management
1. Employees can clock in/out via HRM → Clock In/Out
2. HR can view all attendance via HRM → Attendance
3. Approve/edit attendance records as needed
4. Track late arrivals and early departures

### Leave Management
1. Employees apply for leave via Leave Requests
2. Managers/HR approve or reject requests
3. Leave balances are automatically updated
4. Leave entitlements are tracked per year

### Payroll Processing
1. Navigate to HRM → Payslips
2. Select month and year
3. Click "Generate Payslips"
4. System automatically:
   - Calculates working days based on attendance
   - Applies salary structure (basic + allowances - deductions)
   - Factors in absences and leaves
   - Generates payslips for all active employees
5. Review and approve payslips
6. Process payments

### Recruitment
1. Create job openings under Recruitment
2. Receive applications
3. Track candidates through pipeline
4. Schedule interviews
5. Make offers and hire

### Performance Management
1. Define KPIs for departments/designations
2. Create appraisal cycles
3. Managers evaluate employees
4. Track scores and ratings
5. Link to training needs

## Features

### Core Employee Management
- ✅ Employee profiles with complete information
- ✅ Department and designation hierarchy
- ✅ Document management
- ✅ Organizational structure
- ✅ Branch-wise employee management

### Attendance & Time Tracking
- ✅ Clock-in/clock-out system
- ✅ Working hours calculation
- ✅ Overtime tracking
- ✅ Late arrival tracking
- ✅ IP and location tracking

### Leave Management
- ✅ Multiple leave types
- ✅ Leave entitlements per employee
- ✅ Carry forward functionality
- ✅ Approval workflow
- ✅ Leave balance tracking
- ✅ Half-day leave support

### Payroll & Compensation
- ✅ Salary structures
- ✅ Allowances and deductions
- ✅ Automated payslip generation
- ✅ Attendance-based calculation
- ✅ Integration with accounting

### Recruitment (ATS)
- ✅ Job posting management
- ✅ Application tracking
- ✅ Interview scheduling
- ✅ Candidate pipeline
- ✅ Resume storage
- ✅ Onboarding workflow

### Performance Management
- ✅ KPI/KRA definitions
- ✅ Performance appraisals
- ✅ Rating system
- ✅ 360-degree feedback support
- ✅ Goal setting

### Training & Development
- ✅ Training program management
- ✅ Participant enrollment
- ✅ Attendance tracking
- ✅ Assessment scores
- ✅ Certificate management

## Next Steps (To Implement)

### 1. Create Blade Views
You need to create Blade views for each Livewire component in `resources/views/livewire/admin/hrm/`:
- employees/employees-list.blade.php
- employees/add-edit-employee.blade.php
- employees/employee-details.blade.php
- departments/departments-list.blade.php
- attendance/attendance-list.blade.php
- attendance/clock-in-out.blade.php
- leaves/leave-requests-list.blade.php
- payroll/payslips-list.blade.php
- recruitment/job-applications-list.blade.php
- performance/appraisals-list.blade.php

### 2. Create HRM Reports
Implement reports in `app/Livewire/Admin/HRM/Reports/`:
- AttendanceSummaryReport
- LeaveSummaryReport
- PayrollSummaryReport
- EmployeeTurnoverReport
- RecruitmentReport
- PerformanceReport

### 3. Add Sidebar Menu Items
Update your sidebar configuration to include HRM menu:
```php
[
    'title' => 'HRM',
    'icon' => 'users',
    'children' => [
        ['title' => 'Employees', 'route' => 'admin.hrm.employees.list'],
        ['title' => 'Departments', 'route' => 'admin.hrm.departments.list'],
        ['title' => 'Attendance', 'route' => 'admin.hrm.attendance.list'],
        ['title' => 'Leave Requests', 'route' => 'admin.hrm.leave-requests.list'],
        ['title' => 'Payslips', 'route' => 'admin.hrm.payslips.list'],
        ['title' => 'Recruitment', 'route' => 'admin.hrm.job-applications.list'],
        ['title' => 'Performance', 'route' => 'admin.hrm.appraisals.list'],
    ]
]
```

### 4. Implement Additional Features
- Email notifications for leave approvals
- SMS alerts for clock-in/out
- Biometric integration
- Employee self-service portal
- Manager dashboard
- Payslip email distribution
- Tax calculation (if needed)
- Benefits management
- Employee loan tracking
- Expense reimbursement

### 5. Add Validations and Business Rules
- Prevent overlapping leave requests
- Validate leave balance before approval
- Enforce probation period rules
- Salary increment history
- Performance-based bonuses

### 6. Create Seeders
```bash
php artisan make:seeder HRMSeeder
```

Seed default data:
- Leave types
- Departments
- Designations
- Shifts
- Holidays

## Technical Notes

### Relationships
- Employee belongs to Department, Designation, Branch, Manager (self-referencing)
- Department has many Employees, can have parent Department
- Leave Request belongs to Employee and LeaveType
- Payslip belongs to Employee and EmployeeSalary
- All models use soft deletes for data integrity

### Auto-generated Codes
- Employee Code: EMP-000001
- Payslip Number: PAY-202602-0001
- Application Number: APP-20260203-0001
- Appraisal Number: APR-2026-00001
- Job Code: JOB-000001

### Localization
All models support bilingual fields (ar_name, ar_description) and automatically display the correct language based on app locale.

### Security
- All routes are protected by AdminAuthMiddleware
- Branch-level data isolation (where applicable)
- User activity can be tracked via audit logs

## Support

For any questions or issues with the HRM system implementation, refer to:
- Laravel Documentation: https://laravel.com/docs
- Livewire Documentation: https://livewire.laravel.com/docs
- Multi-tenancy Package: https://tenancyforlaravel.com/docs

## License

This HRM system is part of your proprietary ERP system.
