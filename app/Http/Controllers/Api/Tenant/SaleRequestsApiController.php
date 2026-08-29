<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Resources\Tenant\SaleRequestResource;
use App\Models\Tenant\SaleRequest;
use Illuminate\Http\Request;

class SaleRequestsApiController extends ApiController
{
    protected function permission(): string
    {
        return 'sale-requests.list';
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
}
