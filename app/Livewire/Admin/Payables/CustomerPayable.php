<?php

namespace App\Livewire\Admin\Payables;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Models\Tenant\Sale;
use App\Models\Tenant\User;
use App\Services\AccountService;
use App\Services\CashRegisterService;
use App\Services\SellService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Collection;
use Livewire\Component;

class CustomerPayable extends Component
{
    use LivewireOperations;

    public int $id;

    public ?User $customer = null;

    public array $payment = [
        'account_id' => null,
        'amount' => null,
        'note' => null,
    ];

    private SellService $sellService;
    private CashRegisterService $cashRegisterService;
    private AccountService $accountService;

    public function boot(): void
    {
        $this->sellService = app(SellService::class);
        $this->cashRegisterService = app(CashRegisterService::class);
        $this->accountService = app(AccountService::class);
    }

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->loadCustomer();
    }

    private function loadCustomer(): void
    {
        $this->customer = User::with(['accounts.paymentMethod'])->findOrFail($this->id);
    }

    private function dueSales(): Collection
    {
        $sales = Sale::with(['saleItems', 'branch'])
            ->where('customer_id', $this->id)
            ->where('is_deferred', 0)
            ->orderBy('order_date')
            ->orderBy('id')
            ->get();

        return $sales->filter(fn (Sale $sale) => (float) $sale->due_amount > 0);
    }

    public function savePayment(): void
    {
        $dueSales = $this->dueSales();
        $totalDue = (float) $dueSales->sum(fn (Sale $sale) => (float) $sale->due_amount);

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
            $this->cashRegisterService->increment($cashRegister->id, 'total_sales', $this->payment['amount']);
        }

        $remaining = (float) $this->payment['amount'];
        $applied = 0.0;

        foreach ($dueSales as $sale) {
            if ($remaining <= 0) {
                break;
            }

            $due = (float) $sale->due_amount;
            if ($due <= 0) {
                continue;
            }

            $payAmount = min($due, $remaining);

            $this->sellService->addPayment($sale->id, [
                'payment_note' => $this->payment['note'] ?? null,
                'payment_amount' => $payAmount,
                'branch_id' => $sale->branch_id,
                'payment_account' => $this->payment['account_id'],
                'payments' => [
                    [
                        'account_id' => $this->payment['account_id'],
                        'amount' => $payAmount,
                    ],
                ],
            ]);

            AuditLog::log(AuditLogActionEnum::CREATE_SALE_ORDER_PAYMENT, ['id' => $sale->id, 'customer_id' => $this->id]);

            $remaining -= $payAmount;
            $applied += $payAmount;
        }

        $this->reset('payment');

        if($applied > 0 && $this->customer){
            $customerForNotify = $this->customer;
            superAdmins()->each(function(\App\Models\Tenant\Admin $admin) use ($customerForNotify, $applied){
                $admin->notifyCustomerPaymentReceived($customerForNotify, $applied);
            });
        }

        $this->popup('success', __('general.pages.payables.payment_applied', ['amount' => currencyFormat($applied, true)]));

        $this->loadCustomer();
    }

    public function render()
    {
        $customer = $this->customer;
        $sales = $this->dueSales();
        $totalDue = (float) $sales->sum(fn (Sale $sale) => (float) $sale->due_amount);
        $branchIds = $sales->pluck('branch_id')->filter()->unique()->values()->all();
        $paymentAccounts = $this->accountService->getPaymentAccountsForBranchIds($branchIds);

        return layoutView('payables.customer-payable', get_defined_vars())
            ->title(__('general.titles.customer_payable'));
    }
}
