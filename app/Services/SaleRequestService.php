<?php

namespace App\Services;

use App\Enums\SaleRequestStatusEnum;
use App\Models\Tenant\Sale;
use App\Models\Tenant\SaleRequest;
use App\Repositories\SaleRequestRepository;
use Illuminate\Support\Facades\DB;

class SaleRequestService
{
    public function __construct(
        private SaleRequestRepository $repo,
        private SellService $sellService,
    ) {}

    public function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    public function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    public function first($id = null, $relations = [])
    {
        return $this->repo->first($relations, ['id' => $id]);
    }

    public function save($id = null, array $data): SaleRequest
    {
        return DB::transaction(function () use ($id, $data) {
            $request = $id ? $this->repo->find($id) : new SaleRequest();

            if (!$request) {
                $request = new SaleRequest();
            }

            $request->fill([
                'created_by' => $data['created_by'] ?? (admin()->id ?? null),
                'customer_id' => $data['customer_id'],
                'branch_id' => $data['branch_id'],
                'quote_number' => $data['quote_number'],
                'request_date' => $data['request_date'] ?? null,
                'valid_until' => $data['valid_until'] ?? null,
                'status' => $data['status'] ?? SaleRequestStatusEnum::DRAFT->value,
                'tax_id' => $data['tax_id'] ?? null,
                'tax_percentage' => $data['tax_percentage'] ?? 0,
                'discount_id' => $data['discount_id'] ?? null,
                'discount_type' => $data['discount_type'] ?? null,
                'discount_value' => $data['discount_value'] ?? 0,
                'max_discount_amount' => $data['max_discount_amount'] ?? 0,
                'note' => $data['note'] ?? null,
            ])->save();

            $request->items()->delete();
            foreach ($data['products'] as $item) {
                $request->items()->create([
                    'sale_request_id' => $request->id,
                    'product_id' => $item['id'],
                    'unit_id' => $item['unit_id'],
                    'qty' => $item['qty'] ?? $item['quantity'] ?? 1,
                    'taxable' => $item['taxable'] ?? 1,
                    'unit_cost' => $item['unit_cost'] ?? 0,
                    'sell_price' => $item['sell_price'] ?? 0,
                ]);
            }

            return $request->refresh();
        });
    }

    public function convertToSaleOrder(int $saleRequestId, array $override = []): Sale
    {
        $request = $this->repo->find($saleRequestId, ['items']);
        if (!$request) {
            abort(404);
        }

        if (($request->status?->value ?? (string) $request->status) === SaleRequestStatusEnum::CONVERTED->value) {
            abort(400, 'Request already converted.');
        }

        $sale = $this->sellService->save(null, [
            'customer_id' => $override['customer_id'] ?? $request->customer_id,
            'branch_id' => $override['branch_id'] ?? $request->branch_id,
            'invoice_number' => $override['invoice_number'] ?? Sale::generateInvoiceNumber(),
            'order_date' => $override['order_date'] ?? now(),
            'due_date' => $override['due_date'] ?? null,
            'tax_id' => $override['tax_id'] ?? $request->tax_id,
            'tax_percentage' => $override['tax_percentage'] ?? $request->tax_percentage,
            'discount_id' => $override['discount_id'] ?? $request->discount_id,
            'discount_type' => $override['discount_type'] ?? $request->discount_type,
            'discount_value' => $override['discount_value'] ?? $request->discount_value,
            'payments' => [],
            'products' => $request->items->map(fn ($i) => [
                'id' => $i->product_id,
                'unit_id' => $i->unit_id,
                'qty' => $i->qty,
                'taxable' => $i->taxable,
                'unit_cost' => $i->unit_cost,
                'sell_price' => $i->sell_price,
            ])->toArray(),
        ]);

        $request->update(['status' => SaleRequestStatusEnum::CONVERTED->value]);

        return $sale;
    }
}
