<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Expense;
use App\Models\Tenant\Purchase;
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

    function getData()
    {

        $sales = Sale::filter($this->filter)->get();
        $purchases = Purchase::filter($this->filter)->get();
        $expensesAmount = (float)Expense::filter($this->filter)->sum('amount');
        $totalSales = $sales->sum(callback: fn($q)=>$q->grand_total_amount);
        $totalSalesRefunded = $sales->sum(fn($q)=>$q->refunded_grand_total_amount);

        $this->data['totalSales'] = $totalSales + $totalSalesRefunded;
        $this->data['netSales'] = $sales->sum('net_total_amount');
        $this->data['dueAmount'] = $sales->sum('due_amount');
        $this->data['totalSalesReturn'] = $totalSalesRefunded;
        $this->data['totalPurchases'] = $purchases->sum('total_amount');
        $this->data['purchaseDue'] = $purchases->sum('due_amount');
        $this->data['totalPurchaseReturn'] = $purchases->sum(fn($q)=>$q->refunded_total_amount);
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
