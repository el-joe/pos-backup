<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Enums\AuditLogActionEnum;
use App\Enums\UserTypeEnum;
use App\Http\Resources\Tenant\CustomerResource;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\User;
use App\Services\CashRegisterService;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuppliersApiController extends ApiController
{
    protected function permission(): string
    {
        return 'suppliers.list,suppliers.show,suppliers.create,suppliers.update';
    }

    public function index(Request $request)
    {
        $suppliers = User::withCount('purchases')
            ->filter([
                'type' => UserTypeEnum::SUPPLIER->value,
                'search' => $request->query('search'),
                'active' => $request->query('active', 'all'),
            ])
            ->when($request->query('branch_id') ?? admin()?->branch_id, function ($q, $branchId) {
                $q->whereHas('purchases', fn ($q) => $q->where('branch_id', $branchId));
            })
            ->when($request->query('balance_filter'), function ($q, $balanceFilter) {
                $duePurchasesQuery = fn ($q) => $q->where('is_deferred', 0)->whereRaw('(total_amount - paid_amount) > 0.01');

                $balanceFilter === 'with_balance'
                    ? $q->whereHas('purchases', $duePurchasesQuery)
                    : $q->whereDoesntHave('purchases', $duePurchasesQuery);
            })
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($suppliers, CustomerResource::class);
    }

    public function show(Request $request, int $id)
    {
        $supplier = User::withCount('purchases')->where('type', UserTypeEnum::SUPPLIER->value)->find($id);
        if (!$supplier) {
            return $this->error('Not Found', 404);
        }

        if ($request->query('include') === 'purchases') {
            $supplier->setRelation('recentPurchases', Purchase::where('supplier_id', $supplier->id)
                ->orderByDesc('order_date')
                ->orderByDesc('id')
                ->limit(10)
                ->get());
        }

        return $this->success(new CustomerResource($supplier));
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

        $validated['type'] = UserTypeEnum::SUPPLIER->value;
        $supplier = User::create($validated);

        return $this->success(new CustomerResource($supplier), 201);
    }

    public function update(Request $request, int $id)
    {
        $supplier = User::where('type', UserTypeEnum::SUPPLIER->value)->find($id);
        if (!$supplier) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $supplier->update($validated);

        return $this->success(new CustomerResource($supplier));
    }

    public function recordPayment(Request $request, int $id, PurchaseService $purchaseService, CashRegisterService $cashRegisterService)
    {
        $supplier = User::where('type', UserTypeEnum::SUPPLIER->value)->find($id);
        if (!$supplier) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validate([
            'account_id' => 'required|integer|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:255',
        ]);

        $duePurchases = Purchase::where('supplier_id', $id)
            ->where('is_deferred', 0)
            ->orderBy('order_date')
            ->orderBy('id')
            ->get()
            ->filter(fn (Purchase $purchase) => (float) $purchase->due_amount > 0);

        $totalDue = (float) $duePurchases->sum(fn (Purchase $purchase) => (float) $purchase->due_amount);

        if ($totalDue <= 0) {
            return $this->error('Supplier has no outstanding balance', 422);
        }

        if ($validated['amount'] > $totalDue) {
            return $this->error('Amount exceeds outstanding balance', 422);
        }

        $remaining = (float) $validated['amount'];
        $applied = 0.0;

        DB::transaction(function () use ($duePurchases, $validated, $purchaseService, $cashRegisterService, $id, &$remaining, &$applied) {
            foreach ($duePurchases as $purchase) {
                if ($remaining <= 0) {
                    break;
                }

                $due = (float) $purchase->due_amount;
                if ($due <= 0) {
                    continue;
                }

                $payAmount = min($due, $remaining);

                $purchaseService->addPayment($purchase->id, [
                    'payment_note' => $validated['note'] ?? null,
                    'payment_status' => 'partial_paid',
                    'payment_amount' => $payAmount,
                    'branch_id' => $purchase->branch_id,
                    'payment_account' => $validated['account_id'],
                ]);

                AuditLog::log(AuditLogActionEnum::CREATE_PURCHASE_PAYMENT, ['id' => $purchase->id, 'supplier_id' => $id]);

                $remaining -= $payAmount;
                $applied += $payAmount;
            }

            $cashRegister = $cashRegisterService->getOpenedCashRegister();
            if ($cashRegister && $applied > 0) {
                $cashRegisterService->increment($cashRegister->id, 'total_purchases', $applied);
            }
        });

        $supplier = User::withCount('purchases')->find($id);

        return $this->success(new CustomerResource($supplier));
    }
}
