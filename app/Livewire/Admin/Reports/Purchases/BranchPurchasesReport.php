<?php


namespace App\Livewire\Admin\Reports\Purchases;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class BranchPurchasesReport extends Component
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
        $this->report = $this->getBranchPurchasesReport();

        return layoutView('reports.purchases.branch-purchases-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }

    protected function getBranchPurchasesReport()
    {
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');

        // Aggregate purchased quantity and value per branch
        $query = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->join('branches', 'purchases.branch_id', '=', 'branches.id')
            ->select([
                'branches.name as branch_name',
                DB::raw('SUM(purchase_items.qty - purchase_items.refunded_qty) as total_qty'),
                DB::raw('SUM((purchase_items.purchase_price - (purchase_items.purchase_price * purchase_items.discount_percentage / 100)) * (purchase_items.qty - purchase_items.refunded_qty)) as total_value'),
            ])
            ->whereBetween('purchases.order_date', [$this->from_date, $toDate])
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('total_value');

        return $query->get();
    }
}
