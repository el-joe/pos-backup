<?php

namespace App\Models\Tenant;

use App\Enums\RefundStatusEnum;
use App\Helpers\SaleHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    protected $fillable = [
        'customer_id','branch_id','invoice_number','order_date','created_by',
        'tax_id','tax_percentage','discount_id','discount_type','discount_value',
        'paid_amount','max_discount_amount','due_date',
        'is_deferred','inventory_delivered_at'
    ];

    protected $casts = [
        'is_deferred' => 'boolean',
        'inventory_delivered_at' => 'datetime',
    ];

    // boot method to update discount max value to 0 if null
    protected static function booted()
    {
        static::creating(function ($sale) {
            $sale->created_by = admin()->id ?? null;

            if(!empty($sale->discount_id)){
                $discount = Discount::find($sale->discount_id);
                if($discount) {
                    if($discount->type == 'fixed'){
                        $sale->max_discount_amount = $discount->sales_threshold ?? 0;
                    }else{
                        $sale->max_discount_amount = $discount->max_discount_amount ?? 0;
                    }
                }
            }
        });
    }

    public function saleItems() {
        return $this->hasMany(SaleItem::class);
    }

    function orderPayments(){
        return $this->morphMany(OrderPayment::class,'payable');
    }

    public function customer() {
        return $this->belongsTo(User::class,'customer_id')->withTrashed();
    }

    public function branch() {
        return $this->belongsTo(Branch::class)->withTrashed();
    }

    function tax() {
        return $this->belongsTo(Tax::class,'tax_id')->withTrashed();
    }

    function createdBy() {
        return $this->belongsTo(Admin::class,'created_by')->withTrashed();
    }

    function transactions() {
        return $this->morphMany(Transaction::class, 'reference');
    }

    function expenses() {
        return $this->morphMany(Expense::class, 'model')->withTrashed();
    }

    static function generateInvoiceNumber() {
        $last = self::latest()->first();
        if($last) {
            return 'INV-'.str_pad((int)str_replace('INV-','',$last->invoice_number) + 1, 6, '0', STR_PAD_LEFT);
        }
        return 'INV-000001';
    }

    function getSubTotalAttribute() : float {
        return (clone $this->saleItems)->sum(function($item) {
            $total = SaleHelper::itemTotal($item);

            return $total;
        });
    }

    function getDiscountAmountAttribute() {
        $discount = (clone $this->saleItems)->map(function($item){
            return $item->total_discount_amount;
        })->sum();
        return $discount;

        // return SaleHelper::discountAmount($this->saleItems, $this->discount_type, $this->discount_value, $this->max_discount_amount);
    }

    function getTaxAmountAttribute() {
        $tax = (clone $this->saleItems)->map(function($item){
            return SaleHelper::singleTaxAmount($item, (clone $this->saleItems), $this->discount_type, $this->discount_value, $this->tax_percentage, $this->max_discount_amount);
        })->sum();

        return $tax;

        // return SaleHelper::taxAmount($this->saleItems, $this->discount_type, $this->discount_value, $this->tax_percentage);
    }

    function getGrandTotalAmountAttribute() {
        $grandTotal = (clone $this->saleItems)->map(function($item){
            return SaleHelper::singleGrandTotal($item, (clone $this->saleItems), $this->discount_type, $this->discount_value, $this->tax_percentage, $this->max_discount_amount);
        })->sum();

        return $grandTotal;
    }

    function getNetTotalAmountAttribute() {
        $netTotal = $this->grand_total_amount - $this->due_amount - $this->expenses->sum('amount');

        return $netTotal;
    }

    function getDueAmountAttribute() {
        $dueAmount = $this->grand_total_amount - $this->paid_amount;
        if($dueAmount < .01) $dueAmount = 0;
        return numFormat($dueAmount, 3);
    }

    function scopeFilter($q,$filters = []) {
        return $q->when($filters['branch_id'] ?? null, fn($q,$v)=> $q->where('branch_id',$v))
            ->when($filters['customer_id'] ?? null, fn($q,$v)=> $q->where('customer_id',$v))
            ->when($filters['from_date'] ?? null, fn($q,$v)=> $q->whereDate('order_date','>=',$v))
            ->when($filters['to_date'] ?? null, fn($q,$v)=> $q->whereDate('order_date','<=',$v))
            ->when(array_key_exists('is_deferred', $filters), fn($q)=> $q->where('is_deferred', (bool)$filters['is_deferred']))
            ->when(isset($filters['due_filter']) && in_array($filters['due_filter'], ['paid', 'unpaid'], true), function($q) use ($filters) {
                $saleItemsSummary = DB::table('sale_items')
                    ->selectRaw("sale_id,
                        SUM((qty - COALESCE(refunded_qty,0)) * sell_price) AS sale_subtotal,
                        SUM(CASE WHEN taxable = 1 THEN (qty - COALESCE(refunded_qty,0)) * sell_price ELSE 0 END) AS taxable_subtotal
                    ")
                    ->groupBy('sale_id');

                $q->leftJoinSub($saleItemsSummary, 'si', fn($join) => $join->on('si.sale_id', '=', 'sales.id'));

                $discountExpr = "(CASE
                    WHEN sales.discount_type = 'fixed' THEN
                        CASE WHEN COALESCE(si.sale_subtotal,0) > COALESCE(sales.max_discount_amount,0) THEN COALESCE(sales.discount_value,0) ELSE 0 END
                    WHEN sales.discount_type = 'percentage' THEN
                        CASE
                            WHEN COALESCE(sales.max_discount_amount,0) = 0 THEN COALESCE(si.sale_subtotal,0) * COALESCE(sales.discount_value,0) / 100
                            WHEN COALESCE(si.sale_subtotal,0) * COALESCE(sales.discount_value,0) / 100 > COALESCE(sales.max_discount_amount,0) THEN COALESCE(sales.max_discount_amount,0)
                            ELSE COALESCE(si.sale_subtotal,0) * COALESCE(sales.discount_value,0) / 100
                        END
                    ELSE 0
                END)";

                $taxBaseExpr = "GREATEST(0,
                    COALESCE(si.taxable_subtotal,0)
                    - ({$discountExpr}) * (CASE WHEN COALESCE(si.sale_subtotal,0) > 0 THEN COALESCE(si.taxable_subtotal,0) / COALESCE(si.sale_subtotal,0) ELSE 0 END)
                )";

                $taxExpr = "(CASE WHEN COALESCE(sales.tax_percentage,0) > 0 THEN ({$taxBaseExpr}) * (COALESCE(sales.tax_percentage,0) / 100) ELSE 0 END)";

                $grandTotalExpr = "(COALESCE(si.sale_subtotal,0) - ({$discountExpr}) + ({$taxExpr}))";
                $dueExpr = "({$grandTotalExpr} - COALESCE(sales.paid_amount,0))";

                if (($filters['due_filter'] ?? 'all') === 'paid') {
                    $q->whereRaw("{$dueExpr} <= 0.01");
                } else {
                    $q->whereRaw("{$dueExpr} > 0.01");
                }
            })
            ->when(array_key_exists('inventory_delivered', $filters), function($q) use ($filters){
                return (bool)$filters['inventory_delivered']
                    ? $q->whereNotNull('inventory_delivered_at')
                    : $q->whereNull('inventory_delivered_at');
            })
            ->when($filters['search'] ?? null, function($q,$v){
                $q->where(function($q) use ($v) {
                    $q->where('invoice_number','like','%'.$v.'%')
                        ->orWhereHas('customer',fn($q)=> $q->where('name','like','%'.$v.'%'));
                });
            })
            ->when($filters['id'] ?? null, fn($q,$v)=> $q->where('id',$v));
    }

    function getRefundStatusAttribute() {
        $totalItems = (clone $this->saleItems)->sum('qty');
        $refundedItems = (clone $this->saleItems)->sum('refunded_qty');

        if ($refundedItems === 0) {
            return RefundStatusEnum::NO_REFUND;
        } elseif ($refundedItems < $totalItems) {
            return RefundStatusEnum::PARTIAL_REFUND;
        } else {
            return RefundStatusEnum::FULL_REFUND;
        }
    }

    // ---- Refund Functions ---- //
    function salesItemsRefunded(){
        $saleItems = clone $this->saleItems;
        return $saleItems->filter(fn($q)=>$q->refunded_qty > 0)->map(function($item){
            $item = $item->toArray();
            return [
                ...$item,
                'qty' => $item['refunded_qty'],
                'refunded_qty' => 0,
            ];
        });
    }
    function getRefundedSubTotalAttribute() : float {
        $refundedItems = $this->salesItemsRefunded();
        $subTotal = (clone $refundedItems)->sum(function($item) {
            $total = SaleHelper::itemTotal($item);

            return $total;
        });

        return $subTotal;
    }

    function getRefundedDiscountAmountAttribute() {
        $discount = (clone $this->saleItems)->map(function($item){
            return $item->refunded_total_discount_amount;
        })->sum();

        return $discount;
    }

    function getRefundedTaxAmountAttribute() {
        $refundedItems = $this->salesItemsRefunded();
        $tax = (clone $refundedItems)->map(function($item) use ($refundedItems) {
            return SaleHelper::singleTaxAmount($item, clone $refundedItems, $this->discount_type, $this->discount_value, $this->tax_percentage, $this->max_discount_amount);
        })->sum();

        return $tax;
    }

    function getRefundedGrandTotalAmountAttribute() {
        $refundedItems = $this->salesItemsRefunded();
        $grandTotal = (clone $refundedItems)->map(function($item) use ($refundedItems) {
            return SaleHelper::singleGrandTotal($item, clone $refundedItems, $this->discount_type, $this->discount_value, $this->tax_percentage, $this->max_discount_amount);
        })->sum();

        return $grandTotal;
    }

    function getPaymentStatusAttribute() {
        if($this->due_amount <= 0){
            return 'paid';
        }elseif($this->paid_amount > 0 && $this->due_amount > 0){
            return 'partial';
        }else{
            return 'unpaid';
        }
    }
}
