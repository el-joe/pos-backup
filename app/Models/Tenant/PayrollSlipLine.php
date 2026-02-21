<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class PayrollSlipLine extends Model
{
    protected $fillable = [
        'payroll_slip_id',
        'type',
        'amount',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function slip()
    {
        return $this->belongsTo(PayrollSlip::class, 'payroll_slip_id');
    }
}
