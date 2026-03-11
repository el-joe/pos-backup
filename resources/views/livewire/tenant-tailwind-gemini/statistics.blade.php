@if(adminCan('statistics.show'))
    @php
        $highlightCards = [
            ['label' => __('general.pages.statistics.total_sales'), 'value' => currencyFormat($data['totalSales'], true), 'icon' => 'fa fa-cash-register', 'accent' => 'text-emerald-600'],
            ['label' => __('general.pages.statistics.net_sales'), 'value' => currencyFormat($data['netSales'], true), 'icon' => 'fa fa-chart-line', 'accent' => 'text-blue-600'],
            ['label' => __('general.pages.statistics.operating_profit'), 'value' => currencyFormat($data['operatingProfit'], true), 'icon' => 'fa fa-signal', 'accent' => $data['operatingProfit'] >= 0 ? 'text-emerald-600' : 'text-rose-600'],
            ['label' => __('general.pages.statistics.total_purchases'), 'value' => currencyFormat($data['totalPurchases'], true), 'icon' => 'fa fa-shopping-cart', 'accent' => 'text-indigo-600'],
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
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
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
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
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

        <div class="grid gap-6 xl:grid-cols-2">
            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.statistics.daily_financial_activity')">
                <div class="h-80 p-5">
                    <canvas id="dailyFinancialChart"></canvas>
                </div>
            </x-tenant-tailwind-gemini.table-card>

            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.statistics.monthly_financial_activity')">
                <div class="h-80 p-5">
                    <canvas id="monthlyFinancialChart"></canvas>
                </div>
            </x-tenant-tailwind-gemini.table-card>
        </div>

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
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
                        grid: { color: geminiGridColor },
                        ticks: { color: geminiTickColor }
                    },
                    y: {
                        grid: { color: geminiGridColor },
                        ticks: { color: geminiTickColor }
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
                labels: @json($dailyTrendLabels),
                datasets: [
                    {
                        label: '{{ __('general.pages.statistics.total_sales') }}',
                        data: @json($dailySalesData),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.15)',
                        pointBackgroundColor: '#10b981',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35
                    },
                    {
                        label: '{{ __('general.pages.statistics.total_purchases') }}',
                        data: @json($dailyPurchasesData),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        pointBackgroundColor: '#2563eb',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.35
                    },
                    {
                        label: '{{ __('general.pages.statistics.total_expense') }}',
                        data: @json($dailyExpensesData),
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

        replaceGeminiChart('monthlyFinancialChart', 'monthlyFinancialChart', {
            type: 'bar',
            data: {
                labels: @json($monthlyTrendLabels),
                datasets: [
                    {
                        label: '{{ __('general.pages.statistics.total_sales') }}',
                        data: @json($monthlySalesData),
                        backgroundColor: 'rgba(16, 185, 129, 0.78)',
                        borderRadius: 8
                    },
                    {
                        label: '{{ __('general.pages.statistics.total_purchases') }}',
                        data: @json($monthlyPurchasesData),
                        backgroundColor: 'rgba(37, 99, 235, 0.78)',
                        borderRadius: 8
                    },
                    {
                        label: '{{ __('general.pages.statistics.total_expense') }}',
                        data: @json($monthlyExpensesData),
                        backgroundColor: 'rgba(249, 115, 22, 0.78)',
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
                    label: '{{ __('general.pages.statistics.operating_result_breakdown') }}',
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
