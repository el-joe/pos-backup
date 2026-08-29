<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Resources\Tenant\RefundResource;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Refund;
use App\Models\Tenant\RefundItem;
use App\Models\Tenant\Sale;
use App\Services\PurchaseService;
use App\Services\SellService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundsApiController extends ApiController
{
    protected function permission(): string
    {
        return 'refunds.list,refunds.show,refunds.create';
    }

    public function index(Request $request)
    {
        $query = Refund::with(['items']);

        if ($orderType = $request->query('order_type')) {
            $orderType = match ($orderType) {
                'sale' => Sale::class,
                'purchase' => Purchase::class,
                default => null,
            };
            if ($orderType) {
                $query->where('order_type', $orderType);
            }
        }

        if ($branchId = $request->query('branch_id')) {
            $query->where('branch_id', $branchId);
        }

        if ($fromDate = $request->query('from_date')) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate = $request->query('to_date')) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $refunds = $query->orderByDesc('id')->paginate($this->perPage($request));

        return $this->paginated($refunds, RefundResource::class);
    }

    public function show(int $id)
    {
        $refund = Refund::with(['items'])->find($id);
        if (!$refund) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new RefundResource($refund));
    }

    public function store(Request $request, SellService $sellService, PurchaseService $purchaseService)
    {
        $orderType = $request->input('order_type');
        $orderTable = $orderType === 'purchase' ? 'purchases' : 'sales';

        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'order_type' => 'required|in:sale,purchase',
            'order_id' => "required|integer|exists:{$orderTable},id",
            'reason' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.order_item_id' => 'required|integer',
            'items.*.qty' => 'required|numeric|min:0.001',
        ]);

        $orderModelClass = $orderType === 'purchase' ? Purchase::class : Sale::class;
        $order = $orderModelClass::query()->find($validated['order_id']);
        if (!$order) {
            return $this->error('Order not found', 422);
        }

        $itemRelation = $orderType === 'sale' ? 'saleItems' : 'purchaseItems';
        $orderItemIds = $order->{$itemRelation}()->pluck('id')->toArray();

        $refundItems = collect($validated['items'])
            ->filter(fn ($item) => in_array($item['order_item_id'], $orderItemIds) && $item['qty'] > 0)
            ->keyBy('order_item_id');

        if ($refundItems->isEmpty()) {
            return $this->error('No valid refund items provided', 422);
        }

        foreach ($refundItems as $itemId => $item) {
            $orderItem = $order->{$itemRelation}()->where('id', $itemId)->first();
            if (!$orderItem || ($orderItem->actual_qty ?? 0) == 0) {
                return $this->error('Invalid refund quantity for the selected item', 422);
            }
        }

        DB::beginTransaction();
        try {
            $refund = Refund::create([
                'branch_id' => $validated['branch_id'],
                'order_type' => $orderModelClass,
                'order_id' => $validated['order_id'],
                'reason' => $validated['reason'] ?? null,
            ]);

            foreach ($refundItems as $itemId => $item) {
                $orderItem = $order->{$itemRelation}()->where('id', $itemId)->first();
                if (!$orderItem) {
                    throw new \Exception('Invalid order item selected for refund.');
                }
                RefundItem::create([
                    'refund_id' => $refund->id,
                    'product_id' => $orderItem->product_id,
                    'unit_id' => $orderItem->unit_id,
                    'qty' => $item['qty'],
                    'refundable_type' => get_class($orderItem),
                    'refundable_id' => $orderItem->id,
                ]);
            }

            if ($orderType === 'sale') {
                foreach ($refundItems as $itemId => $item) {
                    $sellService->refundSaleItem($itemId, $item['qty']);
                }
            } else {
                foreach ($refundItems as $itemId => $item) {
                    $purchaseService->refundPurchaseItem($itemId, $item['qty']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 422);
        }

        return $this->success(new RefundResource($refund->load('items')), 201);
    }
}
