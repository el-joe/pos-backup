<?php

namespace Database\Seeders;

use App\Enums\LeaveTypeEnum;
use App\Models\Tenant\Department;
use App\Models\Tenant\Designation;
use App\Models\Tenant\LeaveType;
use App\Models\Tenant\Shift;
use App\Models\Tenant\Holiday;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HRMSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedDepartments();
        $this->seedDesignations();
        $this->seedLeaveTypes();
        $this->seedShifts();
        $this->seedHolidays();
    }

    private function seedDepartments()
    {
        $departments = [
            ['name' => 'Human Resources', 'ar_name' => 'الموارد البشرية'],
            ['name' => 'Finance & Accounting', 'ar_name' => 'المالية والمحاسبة'],
            ['name' => 'Information Technology', 'ar_name' => 'تكنولوجيا المعلومات'],
            ['name' => 'Sales & Marketing', 'ar_name' => 'المبيعات والتسويق'],
            ['name' => 'Operations', 'ar_name' => 'العمليات'],
            ['name' => 'Customer Service', 'ar_name' => 'خدمة العملاء'],
            ['name' => 'Administration', 'ar_name' => 'الإدارة'],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['name' => $dept['name']],
                [
                    'ar_name' => $dept['ar_name'],
                    'active' => true,
                ]
            );
        }
    }

    private function seedDesignations()
    {
        $designations = [
            // Management
            ['name' => 'Chief Executive Officer', 'ar_name' => 'الرئيس التنفيذي'],
            ['name' => 'Chief Financial Officer', 'ar_name' => 'المدير المالي'],
            ['name' => 'Chief Technology Officer', 'ar_name' => 'مدير التكنولوجيا'],
            ['name' => 'Department Manager', 'ar_name' => 'مدير قسم'],
            ['name' => 'Team Leader', 'ar_name' => 'قائد فريق'],

            // HR
            ['name' => 'HR Manager', 'ar_name' => 'مدير الموارد البشرية'],
            ['name' => 'HR Specialist', 'ar_name' => 'أخصائي موارد بشرية'],
            ['name' => 'Recruiter', 'ar_name' => 'موظف توظيف'],

            // Finance
            ['name' => 'Accountant', 'ar_name' => 'محاسب'],
            ['name' => 'Financial Analyst', 'ar_name' => 'محلل مالي'],

            // IT
            ['name' => 'Software Engineer', 'ar_name' => 'مهندس برمجيات'],
            ['name' => 'System Administrator', 'ar_name' => 'مدير نظم'],
            ['name' => 'IT Support Specialist', 'ar_name' => 'أخصائي دعم فني'],

            // Sales
            ['name' => 'Sales Manager', 'ar_name' => 'مدير مبيعات'],
            ['name' => 'Sales Executive', 'ar_name' => 'موظف مبيعات'],
            ['name' => 'Marketing Manager', 'ar_name' => 'مدير تسويق'],

            // Operations
            ['name' => 'Operations Manager', 'ar_name' => 'مدير عمليات'],
            ['name' => 'Warehouse Manager', 'ar_name' => 'مدير مستودع'],

            // Customer Service
            ['name' => 'Customer Service Representative', 'ar_name' => 'ممثل خدمة عملاء'],

            // General
            ['name' => 'Administrative Assistant', 'ar_name' => 'مساعد إداري'],
            ['name' => 'Receptionist', 'ar_name' => 'موظف استقبال'],
        ];

        foreach ($designations as $designation) {
            Designation::updateOrCreate(
                ['name' => $designation['name']],
                [
                    'ar_name' => $designation['ar_name'],
                    'active' => true,
                ]
            );
        }
    }

    private function seedLeaveTypes()
    {
        $leaveTypes = [
            [
                'name' => 'Annual Leave',
                'ar_name' => 'إجازة سنوية',
                'days_per_year' => 21,
                'is_paid' => true,
                'carry_forward' => true,
                'max_carry_forward_days' => 7,
                'color' => 'primary',
            ],
            [
                'name' => 'Sick Leave',
                'ar_name' => 'إجازة مرضية',
                'days_per_year' => 15,
                'is_paid' => true,
                'carry_forward' => false,
                'requires_document' => true,
                'color' => 'warning',
            ],
            [
                'name' => 'Casual Leave',
                'ar_name' => 'إجازة عارضة',
                'days_per_year' => 7,
                'is_paid' => true,
                'carry_forward' => false,
                'max_consecutive_days' => 3,
                'color' => 'info',
            ],
            [
                'name' => 'Maternity Leave',
                'ar_name' => 'إجازة أمومة',
                'days_per_year' => 90,
                'is_paid' => true,
                'carry_forward' => false,
                'requires_document' => true,
                'color' => 'success',
            ],
            [
                'name' => 'Paternity Leave',
                'ar_name' => 'إجازة أبوة',
                'days_per_year' => 3,
                'is_paid' => true,
                'carry_forward' => false,
                'color' => 'success',
            ],
            [
                'name' => 'Emergency Leave',
                'ar_name' => 'إجازة طارئة',
                'days_per_year' => 5,
                'is_paid' => true,
                'carry_forward' => false,
                'color' => 'danger',
            ],
            [
                'name' => 'Unpaid Leave',
                'ar_name' => 'إجازة بدون راتب',
                'days_per_year' => 0,
                'is_paid' => false,
                'carry_forward' => false,
                'color' => 'secondary',
            ],
            [
                'name' => 'Hajj Leave',
                'ar_name' => 'إجازة حج',
                'days_per_year' => 15,
                'is_paid' => true,
                'carry_forward' => false,
                'min_days_notice' => 30,
                'color' => 'primary',
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::updateOrCreate(
                ['name' => $leaveType['name']],
                $leaveType
            );
        }
    }

    private function seedShifts()
    {
        $shifts = [
            [
                'name' => 'Morning Shift',
                'ar_name' => 'الوردية الصباحية',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'working_hours' => 480, // 8 hours in minutes
                'break_duration' => 60, // 1 hour lunch break
                'grace_period' => 15, // 15 minutes late allowance
                'working_days' => [1, 2, 3, 4, 5], // Monday to Friday
            ],
            [
                'name' => 'Evening Shift',
                'ar_name' => 'الوردية المسائية',
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'working_hours' => 480,
                'break_duration' => 60,
                'grace_period' => 15,
                'working_days' => [1, 2, 3, 4, 5],
            ],
            [
                'name' => 'Night Shift',
                'ar_name' => 'الوردية الليلية',
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'working_hours' => 480,
                'break_duration' => 60,
                'grace_period' => 15,
                'working_days' => [1, 2, 3, 4, 5],
            ],
            [
                'name' => 'Flexible Shift',
                'ar_name' => 'وردية مرنة',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'working_hours' => 480,
                'break_duration' => 60,
                'grace_period' => 30,
                'working_days' => [1, 2, 3, 4, 5],
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::updateOrCreate(
                ['name' => $shift['name']],
                $shift
            );
        }
    }

    private function seedHolidays()
    {
        $year = Carbon::now()->year;

        $holidays = [
            ['name' => 'New Year', 'ar_name' => 'رأس السنة الميلادية', 'date' => "$year-01-01"],
            ['name' => 'Labor Day', 'ar_name' => 'عيد العمال', 'date' => "$year-05-01"],
            ['name' => 'Eid al-Fitr (Day 1)', 'ar_name' => 'عيد الفطر (اليوم الأول)', 'date' => "$year-04-10", 'days' => 3],
            ['name' => 'Eid al-Adha (Day 1)', 'ar_name' => 'عيد الأضحى (اليوم الأول)', 'date' => "$year-06-16", 'days' => 4],
            ['name' => 'Islamic New Year', 'ar_name' => 'رأس السنة الهجرية', 'date' => "$year-07-07"],
            ['name' => 'National Day', 'ar_name' => 'اليوم الوطني', 'date' => "$year-09-23"],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['name' => $holiday['name'], 'date' => $holiday['date']],
                [
                    'ar_name' => $holiday['ar_name'],
                    'year' => $year,
                    'days' => $holiday['days'] ?? 1,
                    'is_recurring' => true,
                    'active' => true,
                ]
            );
        }
    }
}
