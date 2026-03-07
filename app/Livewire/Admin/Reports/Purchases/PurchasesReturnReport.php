<?php


namespace App\Livewire\Admin\Reports\Purchases;

use App\Models\Tenant\Purchase;
use App\Models\Tenant\Refund;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class PurchasesReturnReport extends Component
{
    public $from_date;
    public $to_date;
    public $report = [];

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    public function render()
    {
        $this->report = $this->getPurchasesReturnReport();

        return layoutView('reports.purchases.purchases-return-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }

    protected function getPurchasesReturnReport()
    {
        $fromDate = carbon($this->from_date)->startOfDay()->format('Y-m-d H:i:s');
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');

        $refunds = Refund::with(['order', 'items.refundable'])
            ->where('order_type', Purchase::class)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        return $refunds
            ->groupBy('order_id')
            ->map(function ($group) {
                $first = $group->first();
                $order = $first?->order;

                return (object) [
                    'purchase_ref' => $order?->ref_no ?? 'N/A',
                    'returned_qty' => $group->sum(fn($refund) => $refund->items->sum('qty')),
                    'returned_amount' => $group->sum('total'),
                ];
            })
            ->sortByDesc('returned_amount')
            ->values();
    }
}
