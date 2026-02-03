<?php

namespace App\Models\Tenant;

use App\Enums\EmployeeStatusEnum;
use App\Enums\EmploymentTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'date_of_birth',
        'gender',
        'national_id',
        'passport_number',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'department_id',
        'designation_id',
        'branch_id',
        'manager_id',
        'employment_type',
        'joining_date',
        'probation_end_date',
        'confirmation_date',
        'resignation_date',
        'termination_date',
        'termination_reason',
        'status',
        'bank_name',
        'account_number',
        'account_holder_name',
        'ifsc_code',
        'swift_code',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'photo',
        'bio',
        'social_links',
        'user_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'probation_end_date' => 'date',
        'confirmation_date' => 'date',
        'resignation_date' => 'date',
        'termination_date' => 'date',
        'social_links' => 'array',
        'employment_type' => EmploymentTypeEnum::class,
        'status' => EmployeeStatusEnum::class,
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveEntitlements(): HasMany
    {
        return $this->hasMany(EmployeeLeaveEntitlement::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(EmployeeSalary::class);
    }

    public function currentSalary(): HasMany
    {
        return $this->hasMany(EmployeeSalary::class)->where('is_current', true);
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function currentShift(): HasMany
    {
        return $this->hasMany(EmployeeShift::class)->where('is_current', true);
    }

    public function appraisals(): HasMany
    {
        return $this->hasMany(PerformanceAppraisal::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (!$employee->employee_code) {
                $employee->employee_code = 'EMP-' . str_pad((Employee::max('id') + 1), 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
