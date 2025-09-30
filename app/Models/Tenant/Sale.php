<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id','branch_id','invoice_number','order_date',
        'tax_id','tax_percentage','discount_id','discount_type','discount_value','paid_amount'
    ];

    public function saleItems() {
        return $this->hasMany(SaleItem::class);
    }

    public function customer() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    static function generateInvoiceNumber() {
        $last = self::latest()->first();
        if($last) {
            return 'INV-'.str_pad((int)str_replace('INV-','',$last->invoice_number) + 1, 6, '0', STR_PAD_LEFT);
        }
        return 'INV-000001';
    }
}
