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
        'branch_id',
        'sales_threshold',
        'deleted_at'
    ];

    function history() {
        return $this->hasMany(DiscountHistory::class,'discount_id');
    }


    function scopeValid($q) {
        $q->where('active', 1)
            ->where(function($q) {
                $q->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', date('Y-m-d'));
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', date('Y-m-d'));
            });
    }

    function scopeFilter($q,$filters = []){
        return $q->when($filters['search'] ?? null, function($q,$search){
            $q->whereAny(['name','code'], 'like', "%$search%");
        })
        ->when(isset($filters['active']), function($q) use ($filters){
            if($filters['active'] == 'all') return $q;
            $q->where('active', $filters['active']);
        })->when(isset($filters['start_date']) || isset($filters['end_date']), function($q) use ($filters){
            if(isset($filters['start_date'])) {
                $q->whereDate('start_date', '>=', $filters['start_date']);
            }
            if(isset($filters['end_date'])) {
                $q->whereDate('end_date', '<=', $filters['end_date']);
            }
        });
    }
}
