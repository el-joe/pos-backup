<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Expense;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Refund;
use App\Models\Tenant\Sale;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Cache;
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

    protected function cacheKey(string $suffix): string
    {
        return 'admin_statistics_' . ($this->filter['branch_id'] ?? 'all') . '_' . $suffix . '_' . md5(serialize($this->filter));
    }

    function getData()
    {
        $result = Cache::remember($this->cacheKey('data'), 60, function () {
            $sales = Sale::with('saleItems')->filter($this->salesFilters())->get();
            $purchases = Purchase::with(['purchaseItems', 'expenses'])->filter($this->purchaseFilters())->get();
            $expensesAmount = (float) Expense::filter($this->expenseFilters())->sum('amount');
            $totalSales = $sales->sum(fn($q) => $q->grand_total_amount);
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

            $data = [
                'totalSales' => $totalSales,
                'netSales' => $netSales,
                'dueAmount' => $dueAmount,
                'totalSalesReturn' => $totalSalesRefunded,
                'totalPurchases' => $totalPurchases,
                'purchaseDue' => $purchaseDue,
                'totalPurchaseReturn' => $totalPurchaseRefunded,
                'totalExpense' => $expensesAmount,
                'salesCount' => $salesCount,
                'purchaseCount' => $purchaseCount,
                'averageSaleValue' => $salesCount > 0 ? $totalSales / $salesCount : 0,
                'averagePurchaseValue' => $purchaseCount > 0 ? $totalPurchases / $purchaseCount : 0,
                'salesCollectionRate' => $totalSales > 0 ? (($totalSales - $dueAmount) / $totalSales) * 100 : 0,
                'purchasePaymentRate' => $totalPurchases > 0 ? (($totalPurchases - $purchaseDue) / $totalPurchases) * 100 : 0,
                'operatingProfit' => $operatingProfit,
            ];

            $operatingBreakdownData = [
                round($netSales, 2),
                round($netPurchases, 2),
                round($expensesAmount, 2),
                round($operatingProfit, 2),
            ];

            $collectionsSnapshotData = [
                round(max($totalSales - $dueAmount, 0), 2),
                round($dueAmount, 2),
                round(max($totalPurchases - $purchaseDue, 0), 2),
                round($purchaseDue, 2),
            ];

            return compact('data', 'operatingBreakdownData', 'collectionsSnapshotData');
        });

        $this->data = $result['data'];
        $this->operatingBreakdownData = $result['operatingBreakdownData'];
        $this->collectionsSnapshotData = $result['collectionsSnapshotData'];

        $this->operatingBreakdownLabels = [
            __('general.pages.statistics.net_sales'),
            __('general.pages.statistics.total_purchases'),
            __('general.pages.statistics.total_expense'),
            __('general.pages.statistics.operating_profit'),
        ];

        $this->collectionsSnapshotLabels = [
            __('general.pages.statistics.sales_collected'),
            __('general.pages.statistics.due_amount'),
            __('general.pages.statistics.purchases_paid'),
            __('general.pages.statistics.purchase_due'),
        ];

        $this->getDailyChartData();
        $this->getMonthlyChartData();
    }

    function getDailyChartData(){
        $result = Cache::remember($this->cacheKey('daily'), 60, function () {
            $from = now()->subDays(29)->startOfDay();
            $to = now()->endOfDay();

            $sales = Sale::with('saleItems')->filter($this->salesFilters([
                'from_date' => $from->toDateString(),
                'to_date' => $to->toDateString(),
            ]))->get();

            $purchases = Purchase::with(['purchaseItems', 'expenses'])->filter($this->purchaseFilters([
                'date_from' => $from->toDateString(),
                'date_to' => $to->toDateString(),
            ]))->get();

            $expenses = Expense::filter($this->expenseFilters([
                'date_from' => $from->toDateString(),
                'date_to' => $to->toDateString(),
            ]))->get();

            $period = collect(CarbonPeriod::create($from, $to));

            $labels = $period->map(fn(Carbon $date) => $date->format('d M'))->toArray();

            $salesData = $period->map(function(Carbon $date) use ($sales) {
                return round((float) $sales->whereBetween('order_date', [
                    $date->copy()->startOfDay(),
                    $date->copy()->endOfDay(),
                ])->sum('grand_total_amount'), 2);
            })->toArray();

            $purchasesData = $period->map(function(Carbon $date) use ($purchases) {
                return round((float) $purchases->whereBetween('order_date', [
                    $date->copy()->startOfDay(),
                    $date->copy()->endOfDay(),
                ])->sum('total_amount'), 2);
            })->toArray();

            $expensesData = $period->map(function(Carbon $date) use ($expenses) {
                return round((float) $expenses->whereBetween('expense_date', [
                    $date->copy()->startOfDay(),
                    $date->copy()->endOfDay(),
                ])->sum('amount'), 2);
            })->toArray();

            return compact('labels', 'salesData', 'purchasesData', 'expensesData');
        });

        $this->dailyTrendLabels = $result['labels'];
        $this->dailySalesData = $result['salesData'];
        $this->dailyPurchasesData = $result['purchasesData'];
        $this->dailyExpensesData = $result['expensesData'];
    }

    function getMonthlyChartData(){
        $result = Cache::remember($this->cacheKey('monthly'), 60, function () {
            $monthsFrom = now()->subMonths(11)->startOfMonth();
            $monthsTo = now()->endOfMonth();

            $sales = Sale::with('saleItems')->filter($this->salesFilters([
                'from_date' => $monthsFrom->toDateString(),
                'to_date' => $monthsTo->toDateString(),
            ]))->get();

            $purchases = Purchase::with(['purchaseItems', 'expenses'])->filter($this->purchaseFilters([
                'date_from' => $monthsFrom->toDateString(),
                'date_to' => $monthsTo->toDateString(),
            ]))->get();

            $expenses = Expense::filter($this->expenseFilters([
                'date_from' => $monthsFrom->toDateString(),
                'date_to' => $monthsTo->toDateString(),
            ]))->get();

            $period = collect(CarbonPeriod::create($monthsFrom, '1 month', $monthsTo));

            $labels = $period->map(fn(Carbon $date) => $date->format('M Y'))->toArray();

            $salesData = $period->map(function(Carbon $date) use ($sales) {
                return round((float) $sales->whereBetween('order_date', [
                    $date->copy()->startOfMonth(),
                    $date->copy()->endOfMonth()
                ])->sum('grand_total_amount'), 2);
            })->toArray();

            $purchasesData = $period->map(function(Carbon $date) use ($purchases) {
                return round((float) $purchases->whereBetween('order_date', [
                    $date->copy()->startOfMonth(),
                    $date->copy()->endOfMonth()
                ])->sum('total_amount'), 2);
            })->toArray();

            $expensesData = $period->map(function(Carbon $date) use ($expenses) {
                return round((float) $expenses->whereBetween('expense_date', [
                    $date->copy()->startOfMonth(),
                    $date->copy()->endOfMonth()
                ])->sum('amount'), 2);
            })->toArray();

            return compact('labels', 'salesData', 'purchasesData', 'expensesData');
        });

        $this->monthlyTrendLabels = $result['labels'];
        $this->monthlySalesData = $result['salesData'];
        $this->monthlyPurchasesData = $result['purchasesData'];
        $this->monthlyExpensesData = $result['expensesData'];
    }

    function mount() {
        if (admin()->branch_id) {
            $this->filter['branch_id'] = admin()->branch_id;
        }

        $this->getData();
    }

    public function render()
    {
        return layoutView('statistics',get_defined_vars())
            ->title(__('general.titles.statistics'));
    }
}
