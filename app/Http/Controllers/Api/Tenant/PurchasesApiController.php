<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Resources\Tenant\PurchaseResource;
use App\Models\Tenant\Purchase;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchasesApiController extends ApiController
{
    protected function permission(): string
    {
        return 'purchases.list,purchases.show,purchases.create';
    }

    public function index(Request $request)
    {
        $purchases = Purchase::with(['supplier', 'branch'])
            ->filter([
                'supplier_id' => $request->query('supplier_id'),
                'status' => $request->query('status'),
                'date_from' => $request->query('from_date'),
                'date_to' => $request->query('to_date'),
            ])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($purchases, PurchaseResource::class);
    }

    public function show(int $id)
    {
        $purchase = Purchase::with(['supplier', 'branch', 'purchaseItems.product'])->find($id);
        if (!$purchase) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new PurchaseResource($purchase));
    }

    public function store(Request $request, PurchaseService $purchaseService)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'ref_no' => 'nullable|string',
            'order_date' => 'nullable|date',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable|numeric',
            'tax_id' => 'nullable|exists:taxes,id',
            'tax_rate' => 'nullable|numeric',
            'payment_status' => 'nullable|in:pending,partial_paid,full_paid',
            'is_deferred' => 'nullable|boolean',
            'orderProducts' => 'required|array|min:1',
            'orderProducts.*.id' => 'required|exists:products,id',
            'orderProducts.*.unit_id' => 'required|exists:units,id',
            'orderProducts.*.qty' => 'required|numeric|min:0.001',
            'orderProducts.*.purchase_price' => 'required|numeric',
            'orderProducts.*.discount_percentage' => 'nullable|numeric',
            'orderProducts.*.tax_percentage' => 'nullable|numeric',
            'orderProducts.*.x_margin' => 'nullable|numeric',
            'orderProducts.*.sell_price' => 'required|numeric',
        ]);

        $purchase = $purchaseService->save(null, $validated);

        return $this->success(new PurchaseResource($purchase->load(['supplier', 'branch', 'purchaseItems.product'])), 201);
    }
}
