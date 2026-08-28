@if(adminCan('statistics.show'))
@php
$highlightCards = [
['label' => __('general.pages.statistics.total_sales'), 'value' => currencyFormat($data['totalSales'], true), 'icon' => 'fa fa-cash-register', 'accent' => 'text-emerald-600'],
['label' => __('general.pages.statistics.net_sales'), 'value' => currencyFormat($data['netSales'], true), 'icon' => 'fa fa-chart-line', 'accent' => 'text-blue-600'],
['label' => __('general.pages.statistics.operating_profit'), 'value' => currencyFormat($data['operatingProfit'], true), 'icon' => 'fa fa-signal', 'accent' => $data['operatingProfit'] >= 0 ? 'text-emerald-600' : 'text-rose-600'],
['label' => __('general.pages.statistics.total_purchases'), 'value' => currencyFormat($data['totalPurchases'], true), 'icon' => 'fa fa-shopping-cart', 'accent' => 'text-indigo-600'],
];

$kpiCards = [
['label' => __('statistics.total_sales'), 'value' => currencyFormat($data['totalSales'], true), 'icon' => 'fa fa-cash-register', 'bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400'],
['label' => __('statistics.total_purchases'), 'value' => currencyFormat($data['totalPurchases'], true), 'icon' => 'fa fa-shopping-cart', 'bg' => 'bg-blue-50 dark:bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400'],
['label' => __('statistics.total_expenses'), 'value' => currencyFormat($data['totalExpense'], true), 'icon' => 'fa fa-receipt', 'bg' => 'bg-rose-50 dark:bg-rose-500/10', 'text' => 'text-rose-600 dark:text-rose-400'],
['label' => __('statistics.net_profit'), 'value' => currencyFormat($data['netProfit'], true), 'icon' => 'fa fa-chart-line', 'bg' => 'bg-purple-50 dark:bg-purple-500/10', 'text' => 'text-purple-600 dark:text-purple-400'],
['label' => __('statistics.profit_margin'), 'value' => number_format($data['profitMarginPct'], 1) . '%', 'icon' => 'fa fa-percentage', 'bg' => 'bg-teal-50 dark:bg-teal-500/10', 'text' => 'text-teal-600 dark:text-teal-400'],
['label' => __('statistics.total_refunds'), 'value' => currencyFormat($data['totalRefunds'], true), 'icon' => 'fa fa-undo-alt', 'bg' => 'bg-orange-50 dark:bg-orange-500/10', 'text' => 'text-orange-600 dark:text-orange-400'],
];

$detailCards = [
['label' => __('general.pages.statistics.due_amount'), 'value' => currencyFormat($data['dueAmount'], true), 'icon' => 'fa fa-hand-holding-usd'],
['label' => __('general.pages.statistics.purchase_due'), 'value' => currencyFormat($data['purchaseDue'], true), 'icon' => 'fa fa-file-invoice-dollar'],
['label' => __('general.pages.statistics.total_sales_return'), 'value' => currencyFormat($data['totalSalesReturn'], true), 'icon' => 'fa fa-undo-alt'],
['label' => __('general.pages.statistics.total_purchase_return'), 'value' => currencyFormat($data['totalPurchaseReturn'], true), 'icon' => 'fa fa-reply-all'],
['label' => __('general.pages.statistics.total_expense'), 'value' => currencyFormat($data['totalExpense'], true), 'icon' => 'fa fa-receipt'],
['label' => __('general.pages.statistics.sales_count'), 'value' => number_format($data['salesCount']), 'icon' => 'fa fa-shopping-bag'],
['label' => __('general.pages.statistics.average_sale_value'), 'value' => currencyFormat($data['averageSaleValue'], true), 'icon' => 'fa fa-balance-scale'],
['label' => __('general.pages.statistics.sales_collection_rate'), 'value' => number_format($data['salesCollectionRate'], 1) . '%', 'icon' => 'fa fa-percentage'],
];
@endphp

<div class="space-y-6">
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:!bg-slate-900">
        <div class="grid gap-4 items-end sm:grid-cols-2 xl:grid-cols-5">
            <div>
                <label class="text-sm text-slate-500 dark:text-slate-400">{{ __('statistics.filter_branch') }}</label>
                <select wire:model="filter.branch_id" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                    <option value="">{{ __('statistics.all_branches') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm text-slate-500 dark:text-slate-400">{{ __('statistics.from_date') }}</label>
                <input type="date" wire:model="filter.from_date" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="text-sm text-slate-500 dark:text-slate-400">{{ __('statistics.to_date') }}</label>
                <input type="date" wire:model="filter.to_date" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div class="flex gap-2 xl:col-span-2">
                <button type="button" wire:click="applyFilters" class="flex-1 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">{{ __('statistics.apply') }}</button>
                <button type="button" wire:click="resetFilters" class="flex-1 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">{{ __('statistics.reset') }}</button>
            </div>
        </div>
    </section>

    <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-6">
        @foreach($kpiCards as $card)
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:!bg-slate-900">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl {{ $card['bg'] }} {{ $card['text'] }}">
                <i class="{{ $card['icon'] }}"></i>
            </div>
            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
            <h3 class="mt-1 text-xl font-bold text-slate-900 dark:text-white">{{ $card['value'] }}</h3>
        </div>
        @endforeach
    </div>

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.28),_transparent_35%),linear-gradient(135deg,#0f172a,#1d4ed8,#0284c7)] p-6 text-white shadow-xl dark:border-slate-800">
        <div class="grid gap-6 xl:grid-cols-[1.5fr_1fr] xl:items-center">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-white/60">{{ __('general.pages.statistics.sales_overview_last_12_months') }}</p>
                <h2 class="mt-3 text-4xl font-semibold">{{ currencyFormat($data['operatingProfit'], true) }}</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-xs text-white/60">{{ __('general.pages.statistics.sales_count') }}</p>
                        <p class="mt-2 text-2xl font-semibold">{{ number_format($data['salesCount']) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-xs text-white/60">{{ __('general.pages.statistics.average_sale_value') }}</p>
                        <p class="mt-2 text-2xl font-semibold">{{ currencyFormat($data['averageSaleValue'], true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-xs text-white/60">{{ __('general.pages.statistics.sales_collection_rate') }}</p>
                        <p class="mt-2 text-2xl font-semibold">{{ number_format($data['salesCollectionRate'], 1) }}%</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <p class="text-xs text-white/60">{{ __('general.pages.statistics.sales_collected') }}</p>
                    <p class="mt-2 text-2xl font-semibold">{{ currencyFormat(max($data['totalSales'] - $data['dueAmount'], 0), true) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <p class="text-xs text-white/60">{{ __('general.pages.statistics.purchases_paid') }}</p>
                    <p class="mt-2 text-2xl font-semibold">{{ currencyFormat(max($data['totalPurchases'] - $data['purchaseDue'], 0), true) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <p class="text-xs text-white/60">{{ __('general.pages.statistics.average_purchase_value') }}</p>
                    <p class="mt-2 text-2xl font-semibold">{{ currencyFormat($data['averagePurchaseValue'], true) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <p class="text-xs text-white/60">{{ __('general.pages.statistics.purchase_payment_rate') }}</p>
                    <p class="mt-2 text-2xl font-semibold">{{ number_format($data['purchasePaymentRate'], 1) }}%</p>
                </div>
            </div>
        </div>
    </section>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach($highlightCards as $card)
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:!bg-slate-900">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
                    <h3 class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">{{ $card['value'] }}</h3>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 {{ $card['accent'] }} dark:bg-slate-800">
                    <i class="{{ $card['icon'] }} text-xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach($detailCards as $card)
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:!bg-slate-900">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
                    <h3 class="mt-3 text-xl font-bold text-slate-900 dark:text-white">{{ $card['value'] }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                    <i class="{{ $card['icon'] }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <x-tenant-tailwind-gemini.table-card :title="__('statistics.sales_vs_purchases_vs_expenses')">
                <div class="h-80 p-5">
                    <canvas id="dailyFinancialChart"></canvas>
                </div>
            </x-tenant-tailwind-gemini.table-card>
        </div>

        <x-tenant-tailwind-gemini.table-card :title="__('statistics.payment_methods')">
            <div class="h-80 p-5">
                @if(count($paymentMethodBreakdown))
                    <canvas id="paymentMethodChart"></canvas>
                @else
                    <p class="flex h-full items-center justify-center text-sm text-slate-400">{{ __('statistics.no_payment_data') }}</p>
                @endif
            </div>
        </x-tenant-tailwind-gemini.table-card>
    </div>

    @if($cashRegisterSummary['is_open'])
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:!bg-slate-900">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('statistics.cash_register_summary') }}</h3>
        <div class="mt-4 grid gap-4 sm:grid-cols-4">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('statistics.opening_balance') }}</p>
                <p class="mt-1 text-xl font-bold text-slate-900 dark:text-white">{{ currencyFormat($cashRegisterSummary['opening_balance'], true) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('statistics.closing_balance') }}</p>
                <p class="mt-1 text-xl font-bold text-slate-900 dark:text-white">{{ currencyFormat($cashRegisterSummary['closing_balance'], true) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('statistics.todays_sales') }}</p>
                <p class="mt-1 text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ currencyFormat($cashRegisterSummary['total_sales'], true) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('statistics.todays_expenses') }}</p>
                <p class="mt-1 text-xl font-bold text-rose-600 dark:text-rose-400">{{ currencyFormat($cashRegisterSummary['total_expenses'], true) }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-3">
        <x-tenant-tailwind-gemini.table-card :title="__('statistics.top_products')">
            <div class="overflow-x-auto p-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 dark:text-slate-400">
                            <th class="pb-2">{{ __('statistics.product') }}</th>
                            <th class="pb-2 text-right">{{ __('statistics.qty_sold') }}</th>
                            <th class="pb-2 text-right">{{ __('statistics.revenue') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($topProducts as $row)
                        <tr>
                            <td class="py-2 text-slate-900 dark:text-white">{{ $row['product_name'] }}</td>
                            <td class="py-2 text-right text-slate-600 dark:text-slate-300">{{ number_format($row['total_qty'], 2) }}</td>
                            <td class="py-2 text-right text-slate-600 dark:text-slate-300">{{ currencyFormat($row['total_revenue'], true) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="py-4 text-center text-slate-400">{{ __('statistics.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-tenant-tailwind-gemini.table-card>

        <x-tenant-tailwind-gemini.table-card :title="__('statistics.top_customers')">
            <div class="overflow-x-auto p-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 dark:text-slate-400">
                            <th class="pb-2">{{ __('statistics.customer') }}</th>
                            <th class="pb-2 text-right">{{ __('statistics.orders') }}</th>
                            <th class="pb-2 text-right">{{ __('statistics.total_amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($topCustomers as $row)
                        <tr>
                            <td class="py-2 text-slate-900 dark:text-white">{{ $row['customer_name'] }}</td>
                            <td class="py-2 text-right text-slate-600 dark:text-slate-300">{{ number_format($row['total_purchases_count']) }}</td>
                            <td class="py-2 text-right text-slate-600 dark:text-slate-300">{{ currencyFormat($row['total_amount'], true) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="py-4 text-center text-slate-400">{{ __('statistics.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-tenant-tailwind-gemini.table-card>

        <x-tenant-tailwind-gemini.table-card :title="__('statistics.top_suppliers')">
            <div class="overflow-x-auto p-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 dark:text-slate-400">
                            <th class="pb-2">{{ __('statistics.supplier') }}</th>
                            <th class="pb-2 text-right">{{ __('statistics.orders') }}</th>
                            <th class="pb-2 text-right">{{ __('statistics.total_amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($topSuppliers as $row)
                        <tr>
                            <td class="py-2 text-slate-900 dark:text-white">{{ $row['supplier_name'] }}</td>
                            <td class="py-2 text-right text-slate-600 dark:text-slate-300">{{ number_format($row['total_purchases_count']) }}</td>
                            <td class="py-2 text-right text-slate-600 dark:text-slate-300">{{ currencyFormat($row['total_amount'], true) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="py-4 text-center text-slate-400">{{ __('statistics.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-tenant-tailwind-gemini.table-card>
    </div>

    @if(count($lowStockProducts))
    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 shadow-sm dark:border-amber-500/20 dark:bg-amber-500/10">
        <h3 class="flex items-center gap-2 text-lg font-semibold text-amber-700 dark:text-amber-300">
            <i class="fa fa-exclamation-triangle"></i> {{ __('statistics.low_stock_alert') }}
        </h3>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-amber-700/70 dark:text-amber-300/70">
                        <th class="pb-2">{{ __('statistics.product') }}</th>
                        <th class="pb-2">{{ __('statistics.branch') }}</th>
                        <th class="pb-2 text-right">{{ __('statistics.current_qty') }}</th>
                        <th class="pb-2 text-right">{{ __('statistics.alert_qty') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100 dark:divide-amber-500/10">
                    @foreach($lowStockProducts as $row)
                    <tr>
                        <td class="py-2 text-slate-900 dark:text-white">{{ $row['product_name'] }}</td>
                        <td class="py-2 text-slate-600 dark:text-slate-300">{{ $row['branch_name'] }}</td>
                        <td class="py-2 text-right font-semibold text-rose-600 dark:text-rose-400">{{ number_format($row['current_qty'], 2) }}</td>
                        <td class="py-2 text-right text-slate-600 dark:text-slate-300">{{ number_format($row['alert_qty'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <x-tenant-tailwind-gemini.table-card :title="__('statistics.monthly_sales_vs_purchases')">
        <div class="h-80 p-5">
            <canvas id="monthlyFinancialChart"></canvas>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <div class="grid gap-6 xl:grid-cols-2">
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.statistics.operating_result_breakdown')">
            <div class="h-80 p-5">
                <canvas id="operatingBreakdownChart"></canvas>
            </div>
        </x-tenant-tailwind-gemini.table-card>

        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.statistics.collections_vs_obligations')">
            <div class="h-80 p-5">
                <canvas id="collectionsSnapshotChart"></canvas>
            </div>
        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>
@else
<div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-600 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300">
    {{ __('general.messages.you_do_not_have_permission_to_access') }}
</div>
@endif

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    @php
        $isRtl = app()->getLocale() === 'ar';
        $dailyLabelsJs = $isRtl ? array_reverse($dailyTrendLabels) : $dailyTrendLabels;
        $dailySalesJs = $isRtl ? array_reverse($dailySalesData) : $dailySalesData;
        $dailyPurchasesJs = $isRtl ? array_reverse($dailyPurchasesData) : $dailyPurchasesData;
        $dailyExpensesJs = $isRtl ? array_reverse($dailyExpensesData) : $dailyExpensesData;
        $monthlyLabelsJs = $isRtl ? array_reverse($monthlyTrendLabels) : $monthlyTrendLabels;
        $monthlySalesJs = $isRtl ? array_reverse($monthlySalesData) : $monthlySalesData;
        $monthlyPurchasesJs = $isRtl ? array_reverse($monthlyPurchasesData) : $monthlyPurchasesData;
    @endphp
    const geminiChartColor = '#2563eb';
    const geminiGridColor = 'rgba(148, 163, 184, 0.18)';
    const geminiTickColor = '#94a3b8';
    window.statisticsCharts = window.statisticsCharts || {};

    function replaceGeminiChart(key, elementId, config) {
        if (window.statisticsCharts[key]) {
            window.statisticsCharts[key].destroy();
        }

        const element = document.getElementById(elementId);

        if (!element) {
            return;
        }

        window.statisticsCharts[key] = new Chart(element, config);
    }

    function geminiChartOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        color: geminiGridColor
                    },
                    ticks: {
                        color: geminiTickColor
                    }
                },
                y: {
                    grid: {
                        color: geminiGridColor
                    },
                    ticks: {
                        color: geminiTickColor
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: geminiTickColor
                    }
                }
            }
        };
    }

    replaceGeminiChart('dailyFinancialChart', 'dailyFinancialChart', {
        type: 'line',
        data: {
            labels: @json($dailyLabelsJs),
            datasets: [{
                    label: '{{ __('general.pages.statistics.total_sales') }}',
                    data: @json($dailySalesJs),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.15)',
                    pointBackgroundColor: '#10b981',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35
                },
                {
                    label: '{{ __('general.pages.statistics.total_purchases') }}',
                    data: @json($dailyPurchasesJs),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    pointBackgroundColor: '#2563eb',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.35
                },
                {
                    label: '{{ __('general.pages.statistics.total_expense') }}',
                    data: @json($dailyExpensesJs),
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.08)',
                    pointBackgroundColor: '#f97316',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.35
                }
            ]
        },
        options: geminiChartOptions()
    });

    replaceGeminiChart('paymentMethodChart', 'paymentMethodChart', {
        type: 'doughnut',
        data: {
            labels: @json(collect($paymentMethodBreakdown)->pluck('method_name')),
            datasets: [{
                data: @json(collect($paymentMethodBreakdown)->pluck('total_amount')),
                backgroundColor: ['#10b981', '#2563eb', '#f97316', '#a855f7', '#14b8a6', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: geminiTickColor
                    }
                }
            }
        }
    });

    replaceGeminiChart('monthlyFinancialChart', 'monthlyFinancialChart', {
        type: 'bar',
        data: {
            labels: @json($monthlyLabelsJs),
            datasets: [{
                    label: '{{ __('general.pages.statistics.total_sales') }}',
                    data: @json($monthlySalesJs),
                    backgroundColor: 'rgba(16, 185, 129, 0.78)',
                    borderRadius: 8
                },
                {
                    label: '{{ __('general.pages.statistics.total_purchases') }}',
                    data: @json($monthlyPurchasesJs),
                    backgroundColor: 'rgba(37, 99, 235, 0.78)',
                    borderRadius: 8
                }
            ]
        },
        options: geminiChartOptions()
    });

    replaceGeminiChart('operatingBreakdownChart', 'operatingBreakdownChart', {
        type: 'bar',
        data: {
            labels: @json($operatingBreakdownLabels),
            datasets: [{
                label: '{{ __('
                general.pages.statistics.operating_result_breakdown ') }}',
                data: @json($operatingBreakdownData),
                backgroundColor: ['#22c55e', '#2563eb', '#f97316', '#0f172a'],
                borderRadius: 10
            }]
        },
        options: {
            ...geminiChartOptions(),
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    replaceGeminiChart('collectionsSnapshotChart', 'collectionsSnapshotChart', {
        type: 'doughnut',
        data: {
            labels: @json($collectionsSnapshotLabels),
            datasets: [{
                data: @json($collectionsSnapshotData),
                backgroundColor: ['#10b981', '#f59e0b', '#2563eb', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: geminiTickColor
                    }
                }
            }
        }
    });
</script>
@endpush