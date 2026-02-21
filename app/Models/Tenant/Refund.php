<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    // create boot method for created by
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Assuming you have an auth system in place
            if (admin()?->id) {
                $model->created_by = admin()->id;
            }
        });
    }
    protected $fillable = [
        'branch_id',
        'order_type',
        'order_id',
        'reason',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(RefundItem::class);
    }

    public function order()
    {
        return $this->morphTo();
    }

    function getTotalAttribute(){
        return $this->items->sum(function($item){
            return $item->qty * ($item->refundable?->unit_amount_after_tax ?? 0);
        });
    }
}
