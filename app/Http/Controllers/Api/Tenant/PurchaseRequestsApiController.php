<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Resources\Tenant\PurchaseRequestResource;
use App\Models\Tenant\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseRequestsApiController extends ApiController
{
    protected function permission(): string
    {
        return 'purchase-requests.list';
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
}
