<?php

namespace App\Livewire\Admin\Refunds;

use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Refund;
use App\Models\Tenant\Sale;
use Illuminate\Support\Collection;
use Livewire\Component;

class RefundDetails extends Component
{
    public int $id;

    public function mount(int $id): void
    {
        $this->id = $id;
    }

    private function refund(): Refund
    {
        return Refund::query()
            ->with([
                'items.product',
                'items.unit',
                'items.refundable',
                'order',
            ])
            ->findOrFail($this->id);
    }

    private function refundTransactionTypes(Refund $refund): array
    {
        return match ($refund->order_type) {
            Sale::class => [TransactionTypeEnum::SALE_INVOICE_REFUND->value, TransactionTypeEnum::SALE_PAYMENT_REFUND->value],
            Purchase::class => [TransactionTypeEnum::PURCHASE_INVOICE_REFUND->value, TransactionTypeEnum::PURCHASE_PAYMENT_REFUND->value],
            default => [],
        };
    }

    public function render()
    {
        $refund = $this->refund();

        $order = $refund->order;

        $transactions = collect();
        if ($order && method_exists($order, 'transactions')) {
            $transactionTypes = $this->refundTransactionTypes($refund);

            $transactions = $order->transactions()
                ->with(['branch', 'lines.account'])
                ->when(!empty($transactionTypes), fn ($q) => $q->whereIn('type', $transactionTypes))
                ->orderByDesc('id')
                ->get();
        }

        return layoutView('refunds.refund-details', [
            'refund' => $refund,
            'order' => $order,
            'transactions' => $transactions,
        ])->title(__('general.titles.refund_details'));
    }
}
