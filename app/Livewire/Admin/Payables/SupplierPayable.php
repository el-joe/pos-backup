<?php

namespace App\Livewire\Admin\Payables;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\User;
use App\Services\CashRegisterService;
use App\Services\PurchaseService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Collection;
use Livewire\Component;

class SupplierPayable extends Component
{
    use LivewireOperations;

    public int $id;

    public ?User $supplier = null;

    public array $payment = [
        'account_id' => null,
        'amount' => null,
        'note' => null,
    ];

    private PurchaseService $purchaseService;
    private CashRegisterService $cashRegisterService;

    public function boot(): void
    {
        $this->purchaseService = app(PurchaseService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
    }

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->loadSupplier();
    }

    private function loadSupplier(): void
    {
        $this->supplier = User::with(['accounts.paymentMethod'])->findOrFail($this->id);
    }

    private function duePurchases(): Collection
    {
        $purchases = Purchase::with(['purchaseItems', 'expenses', 'branch'])
            ->where('supplier_id', $this->id)
            ->where('is_deferred', 0)
            ->orderBy('order_date')
            ->orderBy('id')
            ->get();

        return $purchases->filter(fn (Purchase $purchase) => (float) $purchase->due_amount > 0);
    }

    public function savePayment(): void
    {
        $duePurchases = $this->duePurchases();
        $totalDue = (float) $duePurchases->sum(fn (Purchase $purchase) => (float) $purchase->due_amount);

        $this->validate([
            'payment.account_id' => 'required|exists:accounts,id',
            'payment.amount' => 'required|numeric|min:0.01|max:' . $totalDue,
            'payment.note' => 'nullable|string|max:255',
        ]);

        if ($totalDue <= 0) {
            $this->popup('info', __('general.pages.payables.no_due_orders'));
            return;
        }

        $cashRegister = $this->cashRegisterService->getOpenedCashRegister();
        if ($cashRegister) {
            $this->cashRegisterService->increment($cashRegister->id, 'total_purchases', $this->payment['amount']);
        }

        $remaining = (float) $this->payment['amount'];
        $applied = 0.0;

        foreach ($duePurchases as $purchase) {
            if ($remaining <= 0) {
                break;
            }

            $due = (float) $purchase->due_amount;
            if ($due <= 0) {
                continue;
            }

            $payAmount = min($due, $remaining);

            $this->purchaseService->addPayment($purchase->id, [
                'payment_note' => $this->payment['note'] ?? null,
                'payment_status' => 'partial_paid',
                'payment_amount' => $payAmount,
                'branch_id' => $purchase->branch_id,
                'payment_account' => $this->payment['account_id'],
            ]);

            AuditLog::log(AuditLogActionEnum::CREATE_PURCHASE_PAYMENT, ['id' => $purchase->id, 'supplier_id' => $this->id]);

            $remaining -= $payAmount;
            $applied += $payAmount;
        }

        $this->reset('payment');

        $this->popup('success', __('general.pages.payables.payment_applied', ['amount' => currencyFormat($applied, true)]));

        $this->loadSupplier();
    }

    public function render()
    {
        $supplier = $this->supplier;
        $purchases = $this->duePurchases();
        $totalDue = (float) $purchases->sum(fn (Purchase $purchase) => (float) $purchase->due_amount);

        return layoutView('payables.supplier-payable', get_defined_vars())
            ->title(__('general.titles.supplier_payable'));
    }
}
