<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Branch;
use App\Models\Tenant\CashRegister;
use App\Models\Tenant\Expense;
use App\Models\Tenant\OrderPayment;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Refund;
use App\Models\Tenant\Sale;
use App\Models\Tenant\SaleItem;
use App\Models\Tenant\Stock;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
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

    public $branches = [];
    public $topProducts = [];
    public $topCustomers = [];
    public $topSuppliers = [];
    public $lowStockProducts = [];
    public $paymentMethodBreakdown = [];
    public $cashRegisterSummary = [];
    public $profitSummary = [];

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

        $this->topProducts = $this->getTopProducts();
        $this->topCustomers = $this->getTopCustomers();
        $this->topSuppliers = $this->getTopSuppliers();
        $this->lowStockProducts = $this->getLowStockProducts();
        $this->paymentMethodBreakdown = $this->getPaymentMethodBreakdown();
        $this->cashRegisterSummary = $this->getCashRegisterSummary();
        $this->profitSummary = $this->getProfitSummary();

        $this->data['totalRefunds'] = $totalSalesRefunded + $totalPurchaseRefunded;
        $this->data['grossProfit'] = $this->profitSummary['gross_profit'];
        $this->data['netProfit'] = $this->profitSummary['net_profit'];
        $this->data['profitMarginPct'] = $this->profitSummary['profit_margin_pct'];
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

    public function getTopProducts(int $limit = 10): array
    {
        $filters = $this->salesFilters();

        return SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->when($filters['branch_id'] ?? null, fn($q, $v) => $q->where('sales.branch_id', $v))
            ->when($filters['customer_id'] ?? null, fn($q, $v) => $q->where('sales.customer_id', $v))
            ->when($filters['from_date'] ?? null, fn($q, $v) => $q->whereDate('sales.order_date', '>=', $v))
            ->when($filters['to_date'] ?? null, fn($q, $v) => $q->whereDate('sales.order_date', '<=', $v))
            ->groupBy('products.id', 'products.name', 'brands.name')
            ->orderByDesc(DB::raw('SUM(sale_items.qty - COALESCE(sale_items.refunded_qty, 0))'))
            ->limit($limit)
            ->get([
                'products.name as product_name',
                'brands.name as brand',
                DB::raw('SUM(sale_items.qty - COALESCE(sale_items.refunded_qty, 0)) as total_qty'),
                DB::raw('SUM((sale_items.qty - COALESCE(sale_items.refunded_qty, 0)) * sale_items.sell_price) as total_revenue'),
            ])
            ->map(fn($row) => [
                'product_name' => $row->product_name,
                'brand' => $row->brand,
                'total_qty' => (float) $row->total_qty,
                'total_revenue' => (float) $row->total_revenue,
            ])
            ->toArray();
    }

    public function getTopCustomers(int $limit = 10): array
    {
        // grand_total_amount is a computed accessor (tax/discount aware), so
        // aggregate in PHP rather than in SQL.
        return Sale::filter($this->salesFilters())
            ->whereNotNull('customer_id')
            ->with('customer')
            ->get()
            ->groupBy('customer_id')
            ->map(function ($sales) {
                $customer = $sales->first()->customer;

                return [
                    'customer_name' => $customer->name ?? '-',
                    'total_purchases_count' => $sales->count(),
                    'total_amount' => (float) $sales->sum(fn($sale) => $sale->grand_total_amount),
                ];
            })
            ->sortByDesc('total_amount')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function getTopSuppliers(int $limit = 10): array
    {
        // total_amount is a computed accessor, so aggregate in PHP.
        return Purchase::filter($this->purchaseFilters())
            ->whereNotNull('supplier_id')
            ->with('supplier')
            ->get()
            ->groupBy('supplier_id')
            ->map(function ($purchases) {
                $supplier = $purchases->first()->supplier;

                return [
                    'supplier_name' => $supplier->name ?? '-',
                    'total_purchases_count' => $purchases->count(),
                    'total_amount' => (float) $purchases->sum(fn($purchase) => $purchase->total_amount),
                ];
            })
            ->sortByDesc('total_amount')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function getLowStockProducts(): array
    {
        return Stock::query()
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->leftJoin('branches', 'branches.id', '=', 'stocks.branch_id')
            ->when($this->filter['branch_id'] ?? null, fn($q, $v) => $q->where('stocks.branch_id', $v))
            ->whereColumn('stocks.qty', '<', DB::raw('COALESCE(products.alert_qty, 5)'))
            ->orderBy('stocks.qty')
            ->limit(20)
            ->get([
                'products.name as product_name',
                'stocks.qty as current_qty',
                DB::raw('COALESCE(products.alert_qty, 5) as alert_qty'),
                'branches.name as branch_name',
            ])
            ->map(fn($row) => [
                'product_name' => $row->product_name,
                'current_qty' => (float) $row->current_qty,
                'alert_qty' => (float) $row->alert_qty,
                'branch_name' => $row->branch_name,
            ])
            ->toArray();
    }

    public function getPaymentMethodBreakdown(): array
    {
        $filters = $this->salesFilters();

        $saleIds = Sale::filter($filters)->pluck('id');

        $rows = OrderPayment::query()
            ->join('accounts', 'accounts.id', '=', 'order_payments.account_id')
            ->join('payment_methods', 'payment_methods.id', '=', 'accounts.payment_method_id')
            ->where('order_payments.payable_type', Sale::class)
            ->whereIn('order_payments.payable_id', $saleIds)
            ->groupBy('payment_methods.id', 'payment_methods.name')
            ->get([
                'payment_methods.name as method_name',
                DB::raw('COUNT(order_payments.id) as count'),
                DB::raw('SUM(order_payments.amount) as total_amount'),
            ]);

        $grandTotal = (float) $rows->sum('total_amount');

        return $rows->map(fn($row) => [
            'method_name' => $row->method_name,
            'count' => (int) $row->count,
            'total_amount' => (float) $row->total_amount,
            'percentage' => $grandTotal > 0 ? round(((float) $row->total_amount / $grandTotal) * 100, 1) : 0,
        ])->toArray();
    }

    public function getCashRegisterSummary(): array
    {
        $register = CashRegister::filter([
            'branch_id' => $this->filter['branch_id'] ?? (admin()->branch_id ?? null),
            'not_closed' => true,
        ])->latest('opened_at')->first();

        if (!$register) {
            return [
                'is_open' => false,
                'opening_balance' => 0,
                'closing_balance' => 0,
                'total_sales' => 0,
                'total_expenses' => 0,
            ];
        }

        return [
            'is_open' => true,
            'opening_balance' => (float) $register->opening_balance,
            'closing_balance' => (float) $register->calculated_closing_balance,
            'total_sales' => (float) $register->total_sales,
            'total_expenses' => (float) $register->total_expenses,
        ];
    }

    public function getProfitSummary(): array
    {
        $filters = $this->salesFilters();

        $totalSales = (float) Sale::filter($filters)->get()->sum(fn($sale) => $sale->grand_total_amount);

        $totalCogs = (float) SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->when($filters['branch_id'] ?? null, fn($q, $v) => $q->where('sales.branch_id', $v))
            ->when($filters['customer_id'] ?? null, fn($q, $v) => $q->where('sales.customer_id', $v))
            ->when($filters['from_date'] ?? null, fn($q, $v) => $q->whereDate('sales.order_date', '>=', $v))
            ->when($filters['to_date'] ?? null, fn($q, $v) => $q->whereDate('sales.order_date', '<=', $v))
            ->sum(DB::raw('(sale_items.qty - COALESCE(sale_items.refunded_qty, 0)) * sale_items.unit_cost'));

        $totalExpenses = (float) Expense::filter($this->expenseFilters())->sum('amount');

        $grossProfit = $totalSales - $totalCogs;
        $netProfit = $grossProfit - $totalExpenses;
        $profitMarginPct = $totalSales > 0 ? round(($netProfit / $totalSales) * 100, 2) : 0;

        return [
            'gross_profit' => round($grossProfit, 2),
            'net_profit' => round($netProfit, 2),
            'profit_margin_pct' => $profitMarginPct,
        ];
    }

    public function applyFilters()
    {
        $this->getData();
    }

    public function resetFilters()
    {
        $this->filter = [
            'branch_id' => null,
            'from_date' => now()->startOfMonth()->toDateString(),
            'to_date' => now()->toDateString(),
        ];

        $this->getData();
    }

    function mount() {
        $this->filter['from_date'] = $this->filter['from_date'] ?? now()->startOfMonth()->toDateString();
        $this->filter['to_date'] = $this->filter['to_date'] ?? now()->toDateString();
        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->toArray();
        $this->getData();
    }

    public function render()
    {
        return layoutView('statistics',get_defined_vars())
            ->title(__('general.titles.statistics'));
    }
}
