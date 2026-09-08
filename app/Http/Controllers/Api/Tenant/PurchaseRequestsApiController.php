<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Requests\Api\Tenant\ConvertRequestRequest;
use App\Http\Requests\Api\Tenant\EmptyBodyRequest;
use App\Http\Requests\Api\Tenant\StorePurchaseRequestRequest;
use App\Http\Requests\Api\Tenant\UpdatePurchaseRequestRequest;
use App\Http\Resources\Tenant\PurchaseRequestResource;
use App\Http\Resources\Tenant\PurchaseResource;
use App\Models\Tenant\PurchaseRequest;
use App\Services\PurchaseRequestService;
use Illuminate\Http\Request;

class PurchaseRequestsApiController extends ApiController
{
    protected function permission(): string
    {
        return 'purchase_requests.list,purchase_requests.show,purchase_requests.create,purchase_requests.update,purchase_requests.approve';
    }

    public function index(Request $request)
    {
        $requests = PurchaseRequest::with(['supplier'])
            ->filter([
                'status' => $request->query('status'),
                'branch_id' => $request->query('branch_id'),
                'supplier_id' => $request->query('supplier_id'),
                'date_from' => $request->query('from_date'),
                'date_to' => $request->query('to_date'),
            ])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($requests, PurchaseRequestResource::class);
    }

    public function show(int $id)
    {
        $purchaseRequest = PurchaseRequest::with(['items', 'supplier'])->find($id);
        if (!$purchaseRequest) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new PurchaseRequestResource($purchaseRequest));
    }

    public function store(StorePurchaseRequestRequest $request, PurchaseRequestService $service)
    {
        $validated = $request->validated();
        if (empty($validated['request_number'])) {
            $validated['request_number'] = 'PR-'.now()->format('YmdHis');
        }

        $purchaseRequest = $service->save(null, $validated);

        return $this->success(new PurchaseRequestResource($purchaseRequest->load(['items', 'supplier'])), 201);
    }

    public function update(UpdatePurchaseRequestRequest $request, int $id, PurchaseRequestService $service)
    {
        $existing = PurchaseRequest::find($id);
        if (!$existing) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validated();
        $validated = array_merge([
            'supplier_id' => $existing->supplier_id,
            'branch_id' => $existing->branch_id,
            'request_number' => $existing->request_number,
            'request_date' => $existing->request_date,
            'status' => $existing->status?->value ?? $existing->status,
            'tax_id' => $existing->tax_id,
            'tax_percentage' => $existing->tax_percentage,
            'discount_type' => $existing->discount_type,
            'discount_value' => $existing->discount_value,
            'note' => $existing->note,
            'orderProducts' => $existing->items->map(fn ($i) => [
                'id' => $i->product_id,
                'unit_id' => $i->unit_id,
                'qty' => $i->qty,
                'purchase_price' => $i->purchase_price,
                'discount_percentage' => $i->discount_percentage,
                'tax_percentage' => $i->tax_percentage,
                'x_margin' => $i->x_margin,
                'sell_price' => $i->sell_price,
            ])->toArray(),
        ], $validated);

        $purchaseRequest = $service->save($id, $validated);

        return $this->success(new PurchaseRequestResource($purchaseRequest->load(['items', 'supplier'])));
    }

    public function approve(EmptyBodyRequest $request, int $id, PurchaseRequestService $service)
    {
        $purchaseRequest = PurchaseRequest::find($id);
        if (!$purchaseRequest) {
            return $this->error('Not Found', 404);
        }

        $purchaseRequest = $service->approve($id);

        return $this->success(new PurchaseRequestResource($purchaseRequest));
    }

    public function reject(EmptyBodyRequest $request, int $id, PurchaseRequestService $service)
    {
        $purchaseRequest = PurchaseRequest::find($id);
        if (!$purchaseRequest) {
            return $this->error('Not Found', 404);
        }

        $purchaseRequest = $service->reject($id);

        return $this->success(new PurchaseRequestResource($purchaseRequest));
    }

    public function convertToPurchase(ConvertRequestRequest $request, int $id, PurchaseRequestService $service)
    {
        $purchaseRequest = PurchaseRequest::find($id);
        if (!$purchaseRequest) {
            return $this->error('Not Found', 404);
        }

        // abort()/abort(400,...) inside convertToPurchaseOrder() surface as proper HTTP
        // errors via the shared api/v1 exception handler in bootstrap/app.php.
        $purchase = $service->convertToPurchaseOrder($id, $request->validated());

        return $this->success(new PurchaseResource($purchase->load(['supplier', 'branch', 'purchaseItems.product'])), 201);
    }
}
