<?php

namespace App\Models\Tenant;

use App\Enums\PurchaseStatusEnum;
use App\Enums\RefundStatusEnum;
use App\Helpers\PurchaseHelper;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id','branch_id','ref_no','order_date','paid_amount','status',
        'discount_type' , 'discount_value' , 'tax_id' , 'tax_percentage',
        'is_deferred','inventory_received_at'
    ];

    protected $casts = [
        'status' => PurchaseStatusEnum::class,
        'is_deferred' => 'boolean',
        'inventory_received_at' => 'datetime',
    ];

    function supplier(){
        return $this->belongsTo(User::class,'supplier_id')->withTrashed()
            ->where('users.type','supplier');
    }

    function expenses() {
        return $this->morphMany(Expense::class, 'model')->withTrashed();
    }

    function branch() {
        return $this->belongsTo(Branch::class,'branch_id')->withTrashed();
    }

    function purchaseItems() {
        return $this->hasMany(PurchaseItem::class,'purchase_id');
    }

    function orderPayments(){
        return $this->morphMany(OrderPayment::class,'payable');
    }

    function transactions() {
        return $this->morphMany(Transaction::class,'reference');
    }

    function getItemsTotalAmountAttribute() {
        return $this->purchaseItems->sum(fn($q)=>$q->total_after_tax);
    }

    function getTotalAmountAttribute() {
        $totalItems = $this->items_total_amount;
        $subTotal = PurchaseHelper::calcSubTotal($totalItems, $this->expenses_total_amount);
        $discountAmount = PurchaseHelper::calcDiscount($subTotal,$this->discount_type,$this->discount_value);
        $totalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($subTotal,$discountAmount);
        $taxAmount = PurchaseHelper::calcTax($totalAfterDiscount,$this->tax_percentage);

        return PurchaseHelper::calcGrandTotal($totalAfterDiscount,$taxAmount);
    }

    // ---- Refunded Functions

    function getRefundedItemsTotalAmountAttribute() {
        return $this->purchaseItems->sum(fn($q)=>$q->refunded_total_after_tax);
    }

    function getRefundedTotalAmountAttribute() {
        $totalItems = $this->refunded_items_total_amount;
        $subTotal = PurchaseHelper::calcSubTotal($totalItems, 0);
        $discountAmount = PurchaseHelper::calcDiscount($subTotal,$this->discount_type,$this->discount_value);
        $totalAfterDiscount = PurchaseHelper::calcTotalAfterDiscount($subTotal,$discountAmount);
        $taxAmount = PurchaseHelper::calcTax($totalAfterDiscount,$this->tax_percentage);

        return PurchaseHelper::calcGrandTotal($totalAfterDiscount,$taxAmount);
    }

    function getExpensesTotalAmountAttribute() {
        return $this->expenses->sum('amount');
    }

    function getDueAmountAttribute() {
        return $this->total_amount - $this->paid_amount;
    }

    function getRefundStatusAttribute() {
        $refundedQty = $this->purchaseItems->sum('refunded_qty');
        $totalQty = $this->purchaseItems->sum('qty');

        if ($refundedQty === 0) {
            return RefundStatusEnum::NO_REFUND;
        } elseif ($refundedQty < $totalQty) {
            return RefundStatusEnum::PARTIAL_REFUND;
        } else {
            return RefundStatusEnum::FULL_REFUND;
        }
    }

    function scopeFilter($q, array $filters = []) {
        return $q->when($filters['id'] ?? false , fn($q,$id) => $q->where('id',$id) )
            ->when($filters['ref_no'] ?? false , fn($q,$ref_no) => $q->where('ref_no','like', '%'.$ref_no.'%') )
            ->when($filters['supplier_id'] ?? false , fn($q,$supplier_id) => $q->where('supplier_id',$supplier_id) )
            ->when($filters['branch_id'] ?? false , fn($q,$branch_id) => $q->where('branch_id',$branch_id) )
            ->when($filters['status'] ?? false , fn($q,$status) => $q->where('status',$status) )
            ->when(array_key_exists('is_deferred', $filters), fn($q)=> $q->where('is_deferred', (bool)$filters['is_deferred']))
            ->when(isset($filters['due_filter']) && in_array($filters['due_filter'], ['paid', 'unpaid'], true), function($q) use ($filters) {
                $unitCostAfterDiscountExpr = '(purchase_items.purchase_price - (purchase_items.purchase_price * COALESCE(purchase_items.discount_percentage,0) / 100))';
                $unitAmountAfterTaxExpr = '('.$unitCostAfterDiscountExpr.' + ('.$unitCostAfterDiscountExpr.' * COALESCE(purchase_items.tax_percentage,0) / 100))';
                $actualQtyExpr = '(purchase_items.qty - COALESCE(purchase_items.refunded_qty,0))';
                $totalAfterTaxExpr = '('.$unitAmountAfterTaxExpr.' * '.$actualQtyExpr.')';

                $itemsTotalExpr = '(SELECT COALESCE(SUM('.$totalAfterTaxExpr.'),0) FROM purchase_items WHERE purchase_items.purchase_id = purchases.id)';
                $expensesTotalExpr = 'COALESCE((SELECT SUM(amount) FROM expenses WHERE expenses.model_type = \'".Purchase::class."\' AND expenses.model_id = purchases.id),0)';
                $subTotalExpr = '('.$itemsTotalExpr.' + '.$expensesTotalExpr.')';

                $discountExpr = '(CASE WHEN discount_type = \'percentage\' THEN ('.$subTotalExpr.' * discount_value / 100) ELSE discount_value END)';
                $totalAfterDiscountExpr = '('.$subTotalExpr.' - '.$discountExpr.')';
                $taxExpr = '('.$totalAfterDiscountExpr.' * tax_percentage / 100)';
                $totalAmountExpr = '('.$totalAfterDiscountExpr.' + '.$taxExpr.')';

                $dueExpr = '(COALESCE('.$totalAmountExpr.',0) - COALESCE(paid_amount,0))';
                if (($filters['due_filter'] ?? 'all') === 'paid') {
                    $q->whereRaw("{$dueExpr} <= 0.01");
                } else {
                    $q->whereRaw("{$dueExpr} > 0.01");
                }
            })
            ->when(array_key_exists('inventory_received', $filters), function($q) use ($filters){
                return (bool)$filters['inventory_received']
                    ? $q->whereNotNull('inventory_received_at')
                    : $q->whereNull('inventory_received_at');
            })
            ->when($filters['date_from'] ?? false , fn($q,$date_from) => $q->whereDate('order_date','>=',$date_from) )
            ->when($filters['date_to'] ?? false , fn($q,$date_to) => $q->whereDate('order_date','<=',$date_to) );
    }
}
