<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Enums\AuditLogActionEnum;
use App\Enums\UserTypeEnum;
use App\Http\Resources\Tenant\CustomerResource;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Sale;
use App\Models\Tenant\User;
use App\Services\CashRegisterService;
use App\Services\SellService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersApiController extends ApiController
{
    protected function permission(): string
    {
        return 'customers.list,customers.show,customers.create,customers.update';
    }

    public function index(Request $request)
    {
        $customers = User::withCount('sales')
            ->filter([
                'type' => UserTypeEnum::CUSTOMER->value,
                'search' => $request->query('search'),
                'active' => $request->query('active', 'all'),
            ])
            ->when($request->query('branch_id') ?? admin()?->branch_id, function ($q, $branchId) {
                $q->whereHas('sales', fn ($q) => $q->where('branch_id', $branchId));
            })
            ->when($request->query('balance_filter'), function ($q, $balanceFilter) {
                $dueSalesQuery = fn ($q) => $q->where('is_deferred', 0)->whereRaw('(grand_total_amount - paid_amount) > 0.01');

                $balanceFilter === 'with_balance'
                    ? $q->whereHas('sales', $dueSalesQuery)
                    : $q->whereDoesntHave('sales', $dueSalesQuery);
            })
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($customers, CustomerResource::class);
    }

    public function show(Request $request, int $id)
    {
        $customer = User::withCount('sales')->where('type', UserTypeEnum::CUSTOMER->value)->find($id);
        if (!$customer) {
            return $this->error('Not Found', 404);
        }

        if ($request->query('include') === 'sales') {
            $customer->setRelation('recentSales', Sale::where('customer_id', $customer->id)
                ->orderByDesc('order_date')
                ->orderByDesc('id')
                ->limit(10)
                ->get());
        }

        return $this->success(new CustomerResource($customer));
    }

    public function recordPayment(Request $request, int $id, SellService $sellService, CashRegisterService $cashRegisterService)
    {
        $customer = User::where('type', UserTypeEnum::CUSTOMER->value)->find($id);
        if (!$customer) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validate([
            'account_id' => 'required|integer|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:255',
        ]);

        $dueSales = Sale::where('customer_id', $id)
            ->where('is_deferred', 0)
            ->orderBy('order_date')
            ->orderBy('id')
            ->get()
            ->filter(fn (Sale $sale) => (float) $sale->due_amount > 0);

        $totalDue = (float) $dueSales->sum(fn (Sale $sale) => (float) $sale->due_amount);

        if ($totalDue <= 0) {
            return $this->error('Customer has no outstanding balance', 422);
        }

        if ($validated['amount'] > $totalDue) {
            return $this->error('Amount exceeds outstanding balance', 422);
        }

        $remaining = (float) $validated['amount'];
        $applied = 0.0;

        DB::transaction(function () use ($dueSales, $validated, $sellService, $cashRegisterService, $id, &$remaining, &$applied) {
            foreach ($dueSales as $sale) {
                if ($remaining <= 0) {
                    break;
                }

                $due = (float) $sale->due_amount;
                if ($due <= 0) {
                    continue;
                }

                $payAmount = min($due, $remaining);

                $sellService->addPayment($sale->id, [
                    'payment_note' => $validated['note'] ?? null,
                    'payment_amount' => $payAmount,
                    'branch_id' => $sale->branch_id,
                    'payment_account' => $validated['account_id'],
                    'payments' => [
                        [
                            'account_id' => $validated['account_id'],
                            'amount' => $payAmount,
                        ],
                    ],
                ]);

                AuditLog::log(AuditLogActionEnum::CREATE_SALE_ORDER_PAYMENT, ['id' => $sale->id, 'customer_id' => $id]);

                $remaining -= $payAmount;
                $applied += $payAmount;
            }

            $cashRegister = $cashRegisterService->getOpenedCashRegister();
            if ($cashRegister && $applied > 0) {
                $cashRegisterService->increment($cashRegister->id, 'total_sales', $applied);
            }
        });

        $customer = User::withCount('sales')->find($id);

        return $this->success(new CustomerResource($customer));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $validated['type'] = UserTypeEnum::CUSTOMER->value;
        $customer = User::create($validated);

        return $this->success(new CustomerResource($customer), 201);
    }

    public function update(Request $request, int $id)
    {
        $customer = User::where('type', UserTypeEnum::CUSTOMER->value)->find($id);
        if (!$customer) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $customer->update($validated);

        return $this->success(new CustomerResource($customer));
    }
}
