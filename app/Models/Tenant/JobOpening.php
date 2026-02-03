<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOpening extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_title',
        'ar_job_title',
        'job_code',
        'department_id',
        'designation_id',
        'branch_id',
        'description',
        'ar_description',
        'requirements',
        'ar_requirements',
        'vacancies',
        'employment_type',
        'min_salary',
        'max_salary',
        'experience_required',
        'education_required',
        'opening_date',
        'closing_date',
        'is_active',
        'is_published',
        'hiring_manager_id',
        'created_by',
    ];

    protected $casts = [
        'min_salary' => 'decimal:2',
        'max_salary' => 'decimal:2',
        'opening_date' => 'date',
        'closing_date' => 'date',
        'is_active' => 'boolean',
        'is_published' => 'boolean',
    ];

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

    public function hiringManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'hiring_manager_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (!$job->job_code) {
                $job->job_code = 'JOB-' . str_pad((JobOpening::max('id') + 1), 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
