<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'employee_code',
        'name',
        'email',
        'phone',
        'department_id',
        'team_id',
        'designation_id',
        'manager_id',
        'hire_date',
        'termination_date',
        'status',
        'national_id',
        'bank_name',
        'bank_iban',
        'bank_account_number',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'termination_date' => 'date',
    ];

    public function setPasswordAttribute($value): void
    {
        if ($value === null || trim((string) $value) === '') {
            return;
        }
        $this->attributes['password'] = bcrypt($value);
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->withTrashed();
    }

    public function team()
    {
        return $this->belongsTo(Team::class)->withTrashed();
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class)->withTrashed();
    }

    public function manager()
    {
        return $this->belongsTo(self::class, 'manager_id')->withTrashed();
    }

    public function directReports()
    {
        return $this->hasMany(self::class, 'manager_id');
    }

    public function contracts()
    {
        return $this->hasMany(EmployeeContract::class);
    }

    public function activeContract()
    {
        return $this->hasOne(EmployeeContract::class)->where('is_active', true);
    }

    public function scopeFilter($q, $filters = [])
    {
        return $q
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->whereAny(['employee_code', 'name', 'email', 'phone'], 'like', "%{$search}%");
            })
            ->when($filters['status'] ?? null, function ($q, $status) {
                if ($status !== 'all') {
                    $q->where('status', $status);
                }
            });
    }
}
