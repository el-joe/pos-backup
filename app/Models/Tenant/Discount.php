<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use SoftDeletes;
    public $fillable = [
        'name',
        'code',
        'type',
        'value',
        'max_discount_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'active',
    ];
}
