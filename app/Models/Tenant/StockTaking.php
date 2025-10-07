<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class StockTaking extends Model
{
    protected $fillable = [
        'branch_id',
        'date',
        'note',
        'created_by'
    ];

    public function products()
    {
        return $this->hasMany(StockTakingProduct::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
