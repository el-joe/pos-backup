<?php

namespace App\Livewire\Admin\Reports\Sales;

use App\Models\Tenant\Refund;
use App\Models\Tenant\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SalesReturnReport extends Component
{
    public $from_date;
    public $to_date;
    public $report = [];

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->loadReport();
    }

    public function updatedFromDate() { $this->loadReport(); }
    public function updatedToDate() { $this->loadReport(); }

    public function loadReport()
    {
        $fromDate = carbon($this->from_date)->startOfDay()->format('Y-m-d H:i:s');
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');

        $refunds = Refund::with(['order', 'items.refundable'])
            ->where('order_type', Sale::class)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        $this->report = $refunds
            ->groupBy('order_id')
            ->map(function ($group) {
                $first = $group->first();
                $order = $first?->order;

                return (object) [
                    'sale_id' => $first?->order_id,
                    'invoice_number' => $order?->invoice_number ?? 'N/A',
                    'customer_name' => $order?->customer?->name ?? 'N/A',
                    'return_count' => $group->sum(fn($refund) => $refund->items->sum('qty')),
                    'return_amount' => $group->sum('total'),
                ];
            })
            ->sortByDesc('return_amount')
            ->values();
    }

    public function render()
    {
        return layoutView('reports.sales.sales-return-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }
}
