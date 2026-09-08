<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Requests\Api\Tenant\ConvertRequestRequest;
use App\Http\Requests\Api\Tenant\EmptyBodyRequest;
use App\Http\Requests\Api\Tenant\StoreSaleRequestRequest;
use App\Http\Requests\Api\Tenant\UpdateSaleRequestRequest;
use App\Http\Resources\Tenant\SaleRequestResource;
use App\Http\Resources\Tenant\SaleResource;
use App\Models\Tenant\SaleRequest;
use App\Services\SaleRequestService;
use Illuminate\Http\Request;

class SaleRequestsApiController extends ApiController
{
    protected function permission(): string
    {
        return 'sale_requests.list,sale_requests.show,sale_requests.create,sale_requests.update,sale_requests.approve';
    }

    public function index(Request $request)
    {
        $requests = SaleRequest::with(['customer'])
            ->filter([
                'status' => $request->query('status'),
                'branch_id' => $request->query('branch_id'),
                'customer_id' => $request->query('customer_id'),
                'from_date' => $request->query('from_date'),
                'to_date' => $request->query('to_date'),
            ])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($requests, SaleRequestResource::class);
    }

    public function show(int $id)
    {
        $saleRequest = SaleRequest::with(['items', 'customer'])->find($id);
        if (!$saleRequest) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new SaleRequestResource($saleRequest));
    }

    public function store(StoreSaleRequestRequest $request, SaleRequestService $service)
    {
        $validated = $request->validated();
        if (empty($validated['quote_number'])) {
            $validated['quote_number'] = 'SQ-'.now()->format('YmdHis');
        }

        $saleRequest = $service->save(null, $validated);

        return $this->success(new SaleRequestResource($saleRequest->load(['items', 'customer'])), 201);
    }

    public function update(UpdateSaleRequestRequest $request, int $id, SaleRequestService $service)
    {
        $existing = SaleRequest::find($id);
        if (!$existing) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validated();
        $validated = array_merge([
            'customer_id' => $existing->customer_id,
            'branch_id' => $existing->branch_id,
            'quote_number' => $existing->quote_number,
            'request_date' => $existing->request_date,
            'valid_until' => $existing->valid_until,
            'status' => $existing->status?->value ?? $existing->status,
            'tax_id' => $existing->tax_id,
            'tax_percentage' => $existing->tax_percentage,
            'discount_id' => $existing->discount_id,
            'discount_type' => $existing->discount_type,
            'discount_value' => $existing->discount_value,
            'max_discount_amount' => $existing->max_discount_amount,
            'note' => $existing->note,
            'products' => $existing->items->map(fn ($i) => [
                'id' => $i->product_id,
                'unit_id' => $i->unit_id,
                'qty' => $i->qty,
                'taxable' => $i->taxable,
                'unit_cost' => $i->unit_cost,
                'sell_price' => $i->sell_price,
            ])->toArray(),
        ], $validated);

        $saleRequest = $service->save($id, $validated);

        return $this->success(new SaleRequestResource($saleRequest->load(['items', 'customer'])));
    }

    public function approve(EmptyBodyRequest $request, int $id, SaleRequestService $service)
    {
        $saleRequest = SaleRequest::find($id);
        if (!$saleRequest) {
            return $this->error('Not Found', 404);
        }

        $saleRequest = $service->approve($id);

        return $this->success(new SaleRequestResource($saleRequest));
    }

    public function reject(EmptyBodyRequest $request, int $id, SaleRequestService $service)
    {
        $saleRequest = SaleRequest::find($id);
        if (!$saleRequest) {
            return $this->error('Not Found', 404);
        }

        $saleRequest = $service->reject($id);

        return $this->success(new SaleRequestResource($saleRequest));
    }

    public function convertToSale(ConvertRequestRequest $request, int $id, SaleRequestService $service)
    {
        $saleRequest = SaleRequest::find($id);
        if (!$saleRequest) {
            return $this->error('Not Found', 404);
        }

        // abort()/abort(400,...) inside convertToSaleOrder() surface as proper HTTP
        // errors via the shared api/v1 exception handler in bootstrap/app.php.
        $sale = $service->convertToSaleOrder($id, $request->validated());

        return $this->success(new SaleResource($sale->load(['customer', 'branch', 'saleItems.product'])), 201);
    }
}
