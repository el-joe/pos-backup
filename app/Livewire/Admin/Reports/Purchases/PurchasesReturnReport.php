<?php


namespace App\Livewire\Admin\Reports\Purchases;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
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
        return view('livewire.admin.reports.purchases.purchases-return-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }

    protected function getPurchasesReturnReport()
    {
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');
        // Aggregate returned quantity and amount per purchase
        $query = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->select([
                'purchases.ref_no as purchase_ref',
                DB::raw('SUM(purchase_items.refunded_qty) as returned_qty'),
                DB::raw('SUM((purchase_items.purchase_price - (purchase_items.purchase_price * purchase_items.discount_percentage / 100)) * purchase_items.refunded_qty) as returned_amount'),
            ])
            ->where('purchase_items.refunded_qty', '>', 0)
            ->whereBetween('purchases.order_date', [$this->from_date, $toDate])
            ->groupBy('purchases.id', 'purchases.ref_no')
            ->orderByDesc('returned_amount');

        return $query->get();
    }
}
