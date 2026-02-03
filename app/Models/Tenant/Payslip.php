<?php

namespace App\Models\Tenant;

use App\Enums\PayslipStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payslip_number',
        'employee_id',
        'employee_salary_id',
        'year',
        'month',
        'payment_date',
        'basic_salary',
        'allowances',
        'total_allowances',
        'deductions',
        'total_deductions',
        'working_days',
        'present_days',
        'absent_days',
        'leave_days',
        'holidays',
        'overtime_hours',
        'overtime_amount',
        'gross_salary',
        'net_salary',
        'status',
        'remarks',
        'generated_by',
        'approved_by',
        'approved_at',
        'account_id',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'allowances' => 'array',
        'deductions' => 'array',
        'approved_at' => 'datetime',
        'status' => PayslipStatusEnum::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function employeeSalary(): BelongsTo
    {
        return $this->belongsTo(EmployeeSalary::class);
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'generated_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payslip) {
            if (!$payslip->payslip_number) {
                $payslip->payslip_number = 'PAY-' . date('Ym') . '-' . str_pad((Payslip::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->max('id') + 1), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
