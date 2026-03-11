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
    public $dailyTrendLabels = [];
    public $dailySalesData = [];
    public $dailyPurchasesData = [];
    public $dailyExpensesData = [];
    public $monthlyTrendLabels = [];
    public $monthlySalesData = [];
    public $monthlyPurchasesData = [];
    public $monthlyExpensesData = [];
    public $operatingBreakdownLabels = [];
    public $operatingBreakdownData = [];
    public $collectionsSnapshotLabels = [];
    public $collectionsSnapshotData = [];

    protected function salesFilters(array $overrides = []): array
    {
        return array_merge([
            'branch_id' => $this->filter['branch_id'] ?? null,
            'customer_id' => $this->filter['customer_id'] ?? null,
            'from_date' => $this->filter['from_date'] ?? $this->filter['date_from'] ?? null,
            'to_date' => $this->filter['to_date'] ?? $this->filter['date_to'] ?? null,
        ], $overrides);
    }

    protected function purchaseFilters(array $overrides = []): array
    {
        return array_merge([
            'branch_id' => $this->filter['branch_id'] ?? null,
            'supplier_id' => $this->filter['supplier_id'] ?? null,
            'date_from' => $this->filter['date_from'] ?? $this->filter['from_date'] ?? null,
            'date_to' => $this->filter['date_to'] ?? $this->filter['to_date'] ?? null,
        ], $overrides);
    }

    protected function expenseFilters(array $overrides = []): array
    {
        return array_merge([
            'branch_id' => $this->filter['branch_id'] ?? null,
            'expense_category_id' => $this->filter['expense_category_id'] ?? null,
            'date_from' => $this->filter['date_from'] ?? $this->filter['from_date'] ?? null,
            'date_to' => $this->filter['date_to'] ?? $this->filter['to_date'] ?? null,
        ], $overrides);
    }

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
        $sales = Sale::filter($this->salesFilters())->get();
        $purchases = Purchase::filter($this->purchaseFilters())->get();
        $expensesAmount = (float) Expense::filter($this->expenseFilters())->sum('amount');
        $totalSales = $sales->sum(callback: fn($q)=>$q->grand_total_amount);
        $totalSalesRefunded = $this->getRefundTotal(Sale::class);
        $totalPurchaseRefunded = $this->getRefundTotal(Purchase::class);
        $totalPurchases = (float) $purchases->sum('total_amount');
        $salesCount = $sales->count();
        $purchaseCount = $purchases->count();
        $netSales = $totalSales - $totalSalesRefunded;
        $netPurchases = $totalPurchases - $totalPurchaseRefunded;
        $dueAmount = (float) $sales->sum('due_amount');
        $purchaseDue = (float) $purchases->sum('due_amount');
        $operatingProfit = $netSales - $netPurchases - $expensesAmount;

        $this->data['totalSales'] = $totalSales;
        $this->data['netSales'] = $netSales;
        $this->data['dueAmount'] = $dueAmount;
        $this->data['totalSalesReturn'] = $totalSalesRefunded;
        $this->data['totalPurchases'] = $totalPurchases;
        $this->data['purchaseDue'] = $purchaseDue;
        $this->data['totalPurchaseReturn'] = $totalPurchaseRefunded;
        $this->data['totalExpense'] = $expensesAmount;
        $this->data['salesCount'] = $salesCount;
        $this->data['purchaseCount'] = $purchaseCount;
        $this->data['averageSaleValue'] = $salesCount > 0 ? $totalSales / $salesCount : 0;
        $this->data['averagePurchaseValue'] = $purchaseCount > 0 ? $totalPurchases / $purchaseCount : 0;
        $this->data['salesCollectionRate'] = $totalSales > 0 ? (($totalSales - $dueAmount) / $totalSales) * 100 : 0;
        $this->data['purchasePaymentRate'] = $totalPurchases > 0 ? (($totalPurchases - $purchaseDue) / $totalPurchases) * 100 : 0;
        $this->data['operatingProfit'] = $operatingProfit;

        $this->operatingBreakdownLabels = [
            __('general.pages.statistics.net_sales'),
            __('general.pages.statistics.total_purchases'),
            __('general.pages.statistics.total_expense'),
            __('general.pages.statistics.operating_profit'),
        ];

        $this->operatingBreakdownData = [
            round($netSales, 2),
            round($netPurchases, 2),
            round($expensesAmount, 2),
            round($operatingProfit, 2),
        ];

        $this->collectionsSnapshotLabels = [
            __('general.pages.statistics.sales_collected'),
            __('general.pages.statistics.due_amount'),
            __('general.pages.statistics.purchases_paid'),
            __('general.pages.statistics.purchase_due'),
        ];

        $this->collectionsSnapshotData = [
            round(max($totalSales - $dueAmount, 0), 2),
            round($dueAmount, 2),
            round(max($totalPurchases - $purchaseDue, 0), 2),
            round($purchaseDue, 2),
        ];


        $this->getDailyChartData();
        $this->getMonthlyChartData();
    }

    function getDailyChartData(){
        $from = now()->subDays(29)->startOfDay();
        $to = now()->endOfDay();

        $sales = Sale::filter($this->salesFilters([
            'from_date' => $from->toDateString(),
            'to_date' => $to->toDateString(),
        ]))->get();

        $purchases = Purchase::filter($this->purchaseFilters([
            'date_from' => $from->toDateString(),
            'date_to' => $to->toDateString(),
        ]))->get();

        $expenses = Expense::filter($this->expenseFilters([
            'date_from' => $from->toDateString(),
            'date_to' => $to->toDateString(),
        ]))->get();

        $period = collect(CarbonPeriod::create($from, $to));

        $this->dailyTrendLabels = $period->map(fn(Carbon $date) => $date->format('d M'))->toArray();

        $this->dailySalesData = $period->map(function(Carbon $date) use ($sales) {
            return round((float) $sales->whereBetween('order_date', [
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay(),
            ])->sum('grand_total_amount'), 2);
        })->toArray();

        $this->dailyPurchasesData = $period->map(function(Carbon $date) use ($purchases) {
            return round((float) $purchases->whereBetween('order_date', [
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay(),
            ])->sum('total_amount'), 2);
        })->toArray();

        $this->dailyExpensesData = $period->map(function(Carbon $date) use ($expenses) {
            return round((float) $expenses->whereBetween('expense_date', [
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay(),
            ])->sum('amount'), 2);
        })->toArray();
    }

    function getMonthlyChartData(){
        $monthsFrom = now()->subMonths(11)->startOfMonth();
        $monthsTo = now()->endOfMonth();

        $sales = Sale::filter($this->salesFilters([
            'from_date' => $monthsFrom->toDateString(),
            'to_date' => $monthsTo->toDateString(),
        ]))->get();

        $purchases = Purchase::filter($this->purchaseFilters([
            'date_from' => $monthsFrom->toDateString(),
            'date_to' => $monthsTo->toDateString(),
        ]))->get();

        $expenses = Expense::filter($this->expenseFilters([
            'date_from' => $monthsFrom->toDateString(),
            'date_to' => $monthsTo->toDateString(),
        ]))->get();

        $period = collect(CarbonPeriod::create($monthsFrom, '1 month', $monthsTo));

        $this->monthlyTrendLabels = $period->map(fn(Carbon $date) => $date->format('M Y'))->toArray();

        $this->monthlySalesData = $period->map(function(Carbon $date) use ($sales) {
            return round((float) $sales->whereBetween('order_date', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth()
            ])->sum('grand_total_amount'), 2);
        })->toArray();

        $this->monthlyPurchasesData = $period->map(function(Carbon $date) use ($purchases) {
            return round((float) $purchases->whereBetween('order_date', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth()
            ])->sum('total_amount'), 2);
        })->toArray();

        $this->monthlyExpensesData = $period->map(function(Carbon $date) use ($expenses) {
            return round((float) $expenses->whereBetween('expense_date', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth()
            ])->sum('amount'), 2);
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
