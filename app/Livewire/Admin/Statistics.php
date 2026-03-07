<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Expense;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Refund;
use App\Models\Tenant\Sale;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class Statistics extends Component
{
    public $data = [];
    public $filter = [];
    public $saleOrdersPerDaylabels = [];
    public $saleOrdersPerDayData = [];
    public $saleOrdersPerMonthlabels = [];
    public $saleOrdersPerMonthData = [];

    protected function getRefundTotal(string $orderType): float
    {
        $query = Refund::query()
            ->with(['items.refundable', 'order'])
            ->where('order_type', $orderType)
            ->when($this->filter['branch_id'] ?? null, fn($q, $v) => $q->where('branch_id', $v))
            ->when($this->filter['from_date'] ?? null, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($this->filter['to_date'] ?? null, fn($q, $v) => $q->whereDate('created_at', '<=', $v));

        if ($orderType === Sale::class && ($this->filter['customer_id'] ?? null)) {
            $customerId = $this->filter['customer_id'];
            $query->whereHasMorph('order', [Sale::class], fn($q) => $q->where('customer_id', $customerId));
        }

        if ($orderType === Purchase::class && ($this->filter['supplier_id'] ?? null)) {
            $supplierId = $this->filter['supplier_id'];
            $query->whereHasMorph('order', [Purchase::class], fn($q) => $q->where('supplier_id', $supplierId));
        }

        return (float) $query->get()->sum('total');
    }

    function getData()
    {

        $sales = Sale::filter($this->filter)->get();
        $purchases = Purchase::filter($this->filter)->get();
        $expensesAmount = (float)Expense::filter($this->filter)->sum('amount');
        $totalSales = $sales->sum(callback: fn($q)=>$q->grand_total_amount);
        $totalSalesRefunded = $this->getRefundTotal(Sale::class);
        $totalPurchaseRefunded = $this->getRefundTotal(Purchase::class);

        $this->data['totalSales'] = $totalSales;
        $this->data['netSales'] = $totalSales - $totalSalesRefunded;
        $this->data['dueAmount'] = $sales->sum('due_amount');
        $this->data['totalSalesReturn'] = $totalSalesRefunded;
        $this->data['totalPurchases'] = $purchases->sum('total_amount');
        $this->data['purchaseDue'] = $purchases->sum('due_amount');
        $this->data['totalPurchaseReturn'] = $totalPurchaseRefunded;
        $this->data['totalExpense'] = $expensesAmount;


        $this->getMonthChartData();
        $this->getYearChartData();
    }

    function getMonthChartData(){
        $from = now()->subDays(29)->startOfDay();
        $to = now()->endOfDay();

        $sales = Sale::whereBetween('order_date', [
            $from,
            $to
        ])->get();


        $this->saleOrdersPerDaylabels = collect(CarbonPeriod::create($from,$to))->map(fn($date) => $date->format('d M'))->toArray();

        $this->saleOrdersPerDayData = collect($this->saleOrdersPerDaylabels)->map(function($label) use ($sales) {
            $date = Carbon::createFromFormat('d M', $label)->setYear(now()->year);
            $total = $sales->whereBetween('order_date', [
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay()
            ])->sum('grand_total_amount');
            return $total;
        })->toArray();
    }

    function getYearChartData(){
        $monthsFrom = now()->subMonths(11)->startOfMonth();
        $monthsTo = now()->endOfMonth();

        $monthSales = Sale::whereBetween('order_date', [
            $monthsFrom,
            $monthsTo
        ])->get();

        $this->saleOrdersPerMonthlabels = collect(CarbonPeriod::create($monthsFrom,$monthsTo))->map(fn($date) => $date->format('M Y'))->unique()->values()->toArray();

        $this->saleOrdersPerMonthData = collect($this->saleOrdersPerMonthlabels)->map(function($label) use ($monthSales) {
            $date = Carbon::createFromFormat('M Y', $label)->setDay(1);
            $total = $monthSales->whereBetween('order_date', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth()
            ])->sum('grand_total_amount');
            return $total;
        })->toArray();

    }

    function mount() {
        $this->getData();
    }

    public function render()
    {
        return layoutView('statistics',get_defined_vars())
            ->title(__('general.titles.statistics'));
    }
}
