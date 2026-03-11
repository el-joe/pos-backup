<div class="row">
    @if(adminCan('statistics.show'))
        @php
            $highlightCards = [
                ['label' => __('general.pages.statistics.total_sales'), 'value' => currencyFormat($data['totalSales'], true), 'icon' => 'fa fa-cash-register', 'accent' => 'success'],
                ['label' => __('general.pages.statistics.net_sales'), 'value' => currencyFormat($data['netSales'], true), 'icon' => 'fa fa-chart-line', 'accent' => 'info'],
                ['label' => __('general.pages.statistics.operating_profit'), 'value' => currencyFormat($data['operatingProfit'], true), 'icon' => 'fa fa-signal', 'accent' => $data['operatingProfit'] >= 0 ? 'success' : 'danger'],
                ['label' => __('general.pages.statistics.total_purchases'), 'value' => currencyFormat($data['totalPurchases'], true), 'icon' => 'fa fa-shopping-cart', 'accent' => 'primary'],
            ];

            $detailCards = [
                ['label' => __('general.pages.statistics.due_amount'), 'value' => currencyFormat($data['dueAmount'], true), 'icon' => 'fa fa-hand-holding-usd', 'accent' => 'warning'],
                ['label' => __('general.pages.statistics.purchase_due'), 'value' => currencyFormat($data['purchaseDue'], true), 'icon' => 'fa fa-file-invoice-dollar', 'accent' => 'secondary'],
                ['label' => __('general.pages.statistics.total_sales_return'), 'value' => currencyFormat($data['totalSalesReturn'], true), 'icon' => 'fa fa-undo-alt', 'accent' => 'danger'],
                ['label' => __('general.pages.statistics.total_purchase_return'), 'value' => currencyFormat($data['totalPurchaseReturn'], true), 'icon' => 'fa fa-reply-all', 'accent' => 'danger'],
                ['label' => __('general.pages.statistics.total_expense'), 'value' => currencyFormat($data['totalExpense'], true), 'icon' => 'fa fa-receipt', 'accent' => 'dark'],
                ['label' => __('general.pages.statistics.sales_count'), 'value' => number_format($data['salesCount']), 'icon' => 'fa fa-shopping-bag', 'accent' => 'primary'],
                ['label' => __('general.pages.statistics.average_sale_value'), 'value' => currencyFormat($data['averageSaleValue'], true), 'icon' => 'fa fa-balance-scale', 'accent' => 'info'],
                ['label' => __('general.pages.statistics.sales_collection_rate'), 'value' => number_format($data['salesCollectionRate'], 1) . '%', 'icon' => 'fa fa-percentage', 'accent' => 'success'],
            ];
        @endphp

        <div class="col-12 mb-3">
            <div class="card overflow-hidden">
                <div class="card-body text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #0ea5e9 100%);">
                    <div class="row align-items-center gy-4">
                        <div class="col-lg-8">
                            <div class="text-white-50 mb-2">{{ __('general.pages.statistics.sales_overview_last_12_months') }}</div>
                            <h2 class="mb-3">{{ currencyFormat($data['operatingProfit'], true) }}</h2>
                            <div class="d-flex flex-wrap gap-4">
                                <div>
                                    <div class="text-white-50 small">{{ __('general.pages.statistics.sales_count') }}</div>
                                    <div class="fs-4 fw-bold">{{ number_format($data['salesCount']) }}</div>
                                </div>
                                <div>
                                    <div class="text-white-50 small">{{ __('general.pages.statistics.average_sale_value') }}</div>
                                    <div class="fs-4 fw-bold">{{ currencyFormat($data['averageSaleValue'], true) }}</div>
                                </div>
                                <div>
                                    <div class="text-white-50 small">{{ __('general.pages.statistics.sales_collection_rate') }}</div>
                                    <div class="fs-4 fw-bold">{{ number_format($data['salesCollectionRate'], 1) }}%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="rounded-3 p-3" style="background: rgba(255,255,255,0.12);">
                                        <div class="text-white-50 small">{{ __('general.pages.statistics.sales_collected') }}</div>
                                        <div class="fs-5 fw-bold">{{ currencyFormat(max($data['totalSales'] - $data['dueAmount'], 0), true) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-3 p-3" style="background: rgba(255,255,255,0.12);">
                                        <div class="text-white-50 small">{{ __('general.pages.statistics.purchases_paid') }}</div>
                                        <div class="fs-5 fw-bold">{{ currencyFormat(max($data['totalPurchases'] - $data['purchaseDue'], 0), true) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-3 p-3" style="background: rgba(255,255,255,0.12);">
                                        <div class="text-white-50 small">{{ __('general.pages.statistics.average_purchase_value') }}</div>
                                        <div class="fs-5 fw-bold">{{ currencyFormat($data['averagePurchaseValue'], true) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-3 p-3" style="background: rgba(255,255,255,0.12);">
                                        <div class="text-white-50 small">{{ __('general.pages.statistics.purchase_payment_rate') }}</div>
                                        <div class="fs-5 fw-bold">{{ number_format($data['purchasePaymentRate'], 1) }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        @foreach($highlightCards as $card)
            <div class="col-xl-3 col-lg-6 mb-3">
                <div class="card text-decoration-none h-100">
                    <div class="card-body d-flex align-items-center m-5px bg-{{ $card['accent'] }} bg-opacity-10 text-{{ $card['accent'] }}">
                        <div class="flex-fill">
                            <div class="mb-1">{{ $card['label'] }}</div>
                            <h2 class="mb-0">{{ $card['value'] }}</h2>
                        </div>
                        <div class="opacity-50">
                            <i class="{{ $card['icon'] }} fa-3x"></i>
                        </div>
                    </div>
                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
        @endforeach

        @foreach($detailCards as $card)
            <div class="col-xl-3 col-lg-6 mb-3">
                <div class="card text-decoration-none h-100">
                    <div class="card-body d-flex align-items-center m-5px bg-{{ $card['accent'] }} bg-opacity-10 text-{{ $card['accent'] }}">
                        <div class="flex-fill">
                            <div class="mb-1">{{ $card['label'] }}</div>
                            <h4 class="mb-0">{{ $card['value'] }}</h4>
                        </div>
                        <div class="opacity-50">
                            <i class="{{ $card['icon'] }} fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-xl-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('general.pages.statistics.daily_financial_activity') }}</h4>
                </div>
                <div class="card-body" style="height: 360px;">
                    <canvas id="dailyFinancialChart"></canvas>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('general.pages.statistics.monthly_financial_activity') }}</h4>
                </div>
                <div class="card-body" style="height: 360px;">
                    <canvas id="monthlyFinancialChart"></canvas>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('general.pages.statistics.operating_result_breakdown') }}</h4>
                </div>
                <div class="card-body" style="height: 360px;">
                    <canvas id="operatingBreakdownChart"></canvas>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('general.pages.statistics.collections_vs_obligations') }}</h4>
                </div>
                <div class="card-body" style="height: 360px;">
                    <canvas id="collectionsSnapshotChart"></canvas>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="alert alert-danger">
                {{ __('general.messages.you_do_not_have_permission_to_access') }}
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script src="{{ asset('hud/assets/plugins/chart.js/dist/chart.umd.js') }}"></script>
    <script>
        window.statisticsCharts = window.statisticsCharts || {};

        const createOrReplaceChart = (key, elementId, config) => {
            if (window.statisticsCharts[key]) {
                window.statisticsCharts[key].destroy();
            }

            const element = document.getElementById(elementId);

            if (!element) {
                return;
            }

            window.statisticsCharts[key] = new Chart(element, config);
        };

        const commonScales = {
            x: {
                grid: {
                    color: 'rgba(148, 163, 184, 0.15)'
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(148, 163, 184, 0.15)'
                }
            }
        };

        createOrReplaceChart('dailyFinancialChart', 'dailyFinancialChart', {
            type: 'line',
            data: {
                labels: @json($dailyTrendLabels),
                datasets: [
                    {
                        label: '{{ __('general.pages.statistics.total_sales') }}',
                        data: @json($dailySalesData),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        pointBackgroundColor: '#10b981',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35
                    },
                    {
                        label: '{{ __('general.pages.statistics.total_purchases') }}',
                        data: @json($dailyPurchasesData),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.08)',
                        pointBackgroundColor: '#3b82f6',
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
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: commonScales
            }
        });

        createOrReplaceChart('monthlyFinancialChart', 'monthlyFinancialChart', {
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
                        backgroundColor: 'rgba(59, 130, 246, 0.78)',
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
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: commonScales
            }
        });

        createOrReplaceChart('operatingBreakdownChart', 'operatingBreakdownChart', {
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
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: commonScales
            }
        });

        createOrReplaceChart('collectionsSnapshotChart', 'collectionsSnapshotChart', {
            type: 'doughnut',
            data: {
                labels: @json($collectionsSnapshotLabels),
                datasets: [{
                    data: @json($collectionsSnapshotData),
                    backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .gap-4 {
            gap: 1.5rem;
        }
    </style>
@endpush
