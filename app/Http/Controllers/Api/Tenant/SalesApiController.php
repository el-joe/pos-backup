<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Resources\Tenant\SaleResource;
use App\Models\Tenant\Sale;
use App\Services\SellService;
use Illuminate\Http\Request;

class SalesApiController extends ApiController
{
    protected function permission(): string
    {
        return 'sales.list,sales.show,pos.create';
    }

    public function index(Request $request)
    {
        $sales = Sale::with(['customer', 'branch'])
            ->filter([
                'customer_id' => $request->query('customer_id'),
                'from_date' => $request->query('from_date'),
                'to_date' => $request->query('to_date'),
                // Sale::scopeFilter() already supports due_filter=paid|unpaid via a SQL-computed due amount.
                'due_filter' => in_array($request->query('status'), ['paid', 'unpaid'], true) ? $request->query('status') : null,
            ])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($sales, SaleResource::class);
    }

    public function show(int $id)
    {
        $sale = Sale::with(['customer', 'branch', 'saleItems.product'])->find($id);
        if (!$sale) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new SaleResource($sale));
    }

    public function store(Request $request, SellService $sellService)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'invoice_number' => 'nullable|string',
            'order_date' => 'nullable|date',
            'tax_id' => 'nullable|exists:taxes,id',
            'tax_percentage' => 'nullable|numeric',
            'discount_id' => 'nullable|exists:discounts,id',
            'discount_type' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'is_deferred' => 'nullable|boolean',
            'due_date' => 'nullable|date',
            'payment_note' => 'nullable|string',
            'payments' => 'nullable|array',
            'payments.*.account_id' => 'required_with:payments|exists:accounts,id',
            'payments.*.amount' => 'required_with:payments|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.unit_id' => 'required|exists:units,id',
            'products.*.quantity' => 'required|numeric|min:0.001',
            'products.*.unit_cost' => 'nullable|numeric',
            'products.*.sell_price' => 'required|numeric',
            'products.*.taxable' => 'nullable|boolean',
        ]);

        if (empty($validated['invoice_number'])) {
            $validated['invoice_number'] = Sale::generateInvoiceNumber();
        }
        $validated['order_date'] = $validated['order_date'] ?? now();
        $validated['paid_amount'] = array_sum(array_column($validated['payments'] ?? [], 'amount'));
        $validated['payment_amount'] = $validated['paid_amount'];

        $sale = $sellService->save(null, $validated);

        return $this->success(new SaleResource($sale->load(['customer', 'branch', 'saleItems.product'])), 201);
    }
}
