<?php

namespace App\Services;

use App\Enums\PurchaseRequestStatusEnum;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\PurchaseRequest;
use App\Repositories\PurchaseRequestRepository;
use Illuminate\Support\Facades\DB;

class PurchaseRequestService
{
    public function __construct(
        private PurchaseRequestRepository $repo,
        private PurchaseService $purchaseService,
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

    public function save($id = null, array $data): PurchaseRequest
    {
        return DB::transaction(function () use ($id, $data) {
            $request = $id ? $this->repo->find($id) : new PurchaseRequest();
            if (!$request) {
                $request = new PurchaseRequest();
            }

            $request->fill([
                'created_by' => $data['created_by'] ?? (admin()->id ?? null),
                'supplier_id' => $data['supplier_id'] ?? null,
                'branch_id' => $data['branch_id'],
                'request_number' => $data['request_number'],
                'request_date' => $data['request_date'] ?? null,
                'status' => $data['status'] ?? PurchaseRequestStatusEnum::DRAFT->value,
                'tax_id' => $data['tax_id'] ?? null,
                'tax_percentage' => $data['tax_percentage'] ?? 0,
                'discount_type' => $data['discount_type'] ?? null,
                'discount_value' => $data['discount_value'] ?? 0,
                'note' => $data['note'] ?? null,
            ])->save();

            $request->items()->delete();
            foreach ($data['orderProducts'] as $item) {
                $request->items()->create([
                    'purchase_request_id' => $request->id,
                    'product_id' => $item['id'],
                    'unit_id' => $item['unit_id'],
                    'qty' => $item['qty'] ?? 1,
                    'purchase_price' => $item['purchase_price'] ?? 0,
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'tax_percentage' => $item['tax_percentage'] ?? 0,
                    'x_margin' => $item['x_margin'] ?? 0,
                    'sell_price' => $item['sell_price'] ?? 0,
                ]);
            }

            return $request->refresh();
        });
    }

    public function convertToPurchaseOrder(int $purchaseRequestId, array $override = []): Purchase
    {
        $request = $this->repo->find($purchaseRequestId, ['items']);
        if (!$request) {
            abort(404);
        }

        if (($request->status?->value ?? (string) $request->status) === PurchaseRequestStatusEnum::CONVERTED->value) {
            abort(400, 'Request already converted.');
        }

        $purchase = $this->purchaseService->save(null, [
            'supplier_id' => $override['supplier_id'] ?? $request->supplier_id,
            'branch_id' => $override['branch_id'] ?? $request->branch_id,
            'ref_no' => $override['ref_no'] ?? ('PO-' . now()->format('YmdHis')),
            'order_date' => $override['order_date'] ?? now(),
            'tax_id' => $override['tax_id'] ?? $request->tax_id,
            'tax_percentage' => $override['tax_percentage'] ?? $request->tax_percentage,
            'discount_type' => $override['discount_type'] ?? $request->discount_type,
            'discount_value' => $override['discount_value'] ?? $request->discount_value,
            'payment_status' => 'pending',
            'orderProducts' => $request->items->map(fn ($i) => [
                'id' => $i->product_id,
                'unit_id' => $i->unit_id,
                'qty' => $i->qty,
                'purchase_price' => $i->purchase_price,
                'discount_percentage' => $i->discount_percentage,
                'tax_percentage' => $i->tax_percentage,
                'x_margin' => $i->x_margin,
                'sell_price' => $i->sell_price,
            ])->toArray(),
            'expenses' => [],
        ]);

        $request->update(['status' => PurchaseRequestStatusEnum::CONVERTED->value]);

        return $purchase;
    }
}
