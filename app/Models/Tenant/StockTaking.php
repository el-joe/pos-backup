<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTaking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'date',
        'note',
        'created_by',
        'deleted_at'
    ];

    public function products()
    {
        return $this->hasMany(StockTakingProduct::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    function scopeFilter($q,$filter = []) {
        return $q->when($filter['branch_id'] ?? null, function($q) use ($filter) {
            $q->where('branch_id', $filter['branch_id']);
        })->when($filter['date_from'] ?? null, function($q) use ($filter) {
            $q->whereDate('date', '>=', $filter['date_from']);
        })->when($filter['date_to'] ?? null, function($q) use ($filter) {
            $q->whereDate('date', '<=', $filter['date_to']);
        })->when($filter['id'] ?? null, function($q) use ($filter) {
            $q->where('id', $filter['id']);
        });
    }
}
