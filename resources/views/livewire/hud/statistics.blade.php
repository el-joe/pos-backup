<div class="row">
    @if(adminCan('statistics.show'))
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('statistics.filter_branch') }}</label>
                            <select class="form-select" wire:model="filter.branch_id">
                                <option value="">{{ __('statistics.all_branches') }}</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('statistics.from_date') }}</label>
                            <input type="date" class="form-control" wire:model="filter.from_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('statistics.to_date') }}</label>
                            <input type="date" class="form-control" wire:model="filter.to_date">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="button" class="btn btn-primary flex-fill" wire:click="applyFilters">{{ __('statistics.apply') }}</button>
                            <button type="button" class="btn btn-outline-secondary flex-fill" wire:click="resetFilters">{{ __('statistics.reset') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $highlightCards = [
                ['label' => __('statistics.total_sales'), 'value' => currencyFormat($data['totalSales'], true), 'icon' => 'fa fa-cash-register', 'accent' => 'success'],
                ['label' => __('statistics.total_purchases'), 'value' => currencyFormat($data['totalPurchases'], true), 'icon' => 'fa fa-shopping-cart', 'accent' => 'primary'],
                ['label' => __('statistics.total_expenses'), 'value' => currencyFormat($data['totalExpense'], true), 'icon' => 'fa fa-receipt', 'accent' => 'danger'],
                ['label' => __('statistics.net_profit'), 'value' => currencyFormat($data['netProfit'], true), 'icon' => 'fa fa-chart-line', 'accent' => $data['netProfit'] >= 0 ? 'purple' : 'danger', 'style' => $data['netProfit'] >= 0 ? 'background-color: rgba(147,51,234,.1); color:#9333ea;' : null],
                ['label' => __('statistics.profit_margin'), 'value' => number_format($data['profitMarginPct'], 1) . '%', 'icon' => 'fa fa-percentage', 'accent' => 'teal', 'style' => 'background-color: rgba(20,184,166,.1); color:#14b8a6;'],
                ['label' => __('statistics.total_refunds'), 'value' => currencyFormat($data['totalRefunds'], true), 'icon' => 'fa fa-undo-alt', 'accent' => 'orange', 'style' => 'background-color: rgba(249,115,22,.1); color:#f97316;'],
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
            <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                <div class="card text-decoration-none h-100">
                    <div class="card-body d-flex align-items-center m-5px {{ !empty($card['style']) ? '' : 'bg-'.$card['accent'].' bg-opacity-10 text-'.$card['accent'] }}"
                        @if(!empty($card['style'])) style="{{ $card['style'] }}" @endif>
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

        <div class="col-xl-8 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('statistics.sales_vs_purchases_vs_expenses') }}</h4>
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

        <div class="col-xl-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('statistics.payment_methods') }}</h4>
                </div>
                <div class="card-body" style="height: 360px;">
                    @if(count($paymentMethodBreakdown))
                        <canvas id="paymentMethodChart"></canvas>
                    @else
                        <div class="text-muted text-center py-5">{{ __('statistics.no_payment_data') }}</div>
                    @endif
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        @if($cashRegisterSummary['is_open'])
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('statistics.cash_register_summary') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-md-3">
                            <div class="text-muted small">{{ __('statistics.opening_balance') }}</div>
                            <div class="fs-4 fw-bold">{{ currencyFormat($cashRegisterSummary['opening_balance'], true) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">{{ __('statistics.closing_balance') }}</div>
                            <div class="fs-4 fw-bold">{{ currencyFormat($cashRegisterSummary['closing_balance'], true) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">{{ __('statistics.todays_sales') }}</div>
                            <div class="fs-4 fw-bold text-success">{{ currencyFormat($cashRegisterSummary['total_sales'], true) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">{{ __('statistics.todays_expenses') }}</div>
                            <div class="fs-4 fw-bold text-danger">{{ currencyFormat($cashRegisterSummary['total_expenses'], true) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-xl-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('statistics.top_products') }}</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('statistics.product') }}</th>
                                    <th class="text-end">{{ __('statistics.qty_sold') }}</th>
                                    <th class="text-end">{{ __('statistics.revenue') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $row)
                                    <tr>
                                        <td>{{ $row['product_name'] }}</td>
                                        <td class="text-end">{{ number_format($row['total_qty'], 2) }}</td>
                                        <td class="text-end">{{ currencyFormat($row['total_revenue'], true) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">{{ __('statistics.no_data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
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

        <div class="col-xl-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('statistics.top_customers') }}</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('statistics.customer') }}</th>
                                    <th class="text-end">{{ __('statistics.orders') }}</th>
                                    <th class="text-end">{{ __('statistics.total_amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers as $row)
                                    <tr>
                                        <td>{{ $row['customer_name'] }}</td>
                                        <td class="text-end">{{ number_format($row['total_purchases_count']) }}</td>
                                        <td class="text-end">{{ currencyFormat($row['total_amount'], true) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">{{ __('statistics.no_data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
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

        <div class="col-xl-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('statistics.top_suppliers') }}</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('statistics.supplier') }}</th>
                                    <th class="text-end">{{ __('statistics.orders') }}</th>
                                    <th class="text-end">{{ __('statistics.total_amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topSuppliers as $row)
                                    <tr>
                                        <td>{{ $row['supplier_name'] }}</td>
                                        <td class="text-end">{{ number_format($row['total_purchases_count']) }}</td>
                                        <td class="text-end">{{ currencyFormat($row['total_amount'], true) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">{{ __('statistics.no_data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
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

        @if(count($lowStockProducts))
        <div class="col-12 mb-3">
            <div class="card border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <h4 class="card-title mb-0 text-warning"><i class="fa fa-exclamation-triangle me-2"></i>{{ __('statistics.low_stock_alert') }}</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('statistics.product') }}</th>
                                    <th>{{ __('statistics.branch') }}</th>
                                    <th class="text-end">{{ __('statistics.current_qty') }}</th>
                                    <th class="text-end">{{ __('statistics.alert_qty') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $row)
                                    <tr>
                                        <td>{{ $row['product_name'] }}</td>
                                        <td>{{ $row['branch_name'] }}</td>
                                        <td class="text-end text-danger fw-bold">{{ number_format($row['current_qty'], 2) }}</td>
                                        <td class="text-end">{{ number_format($row['alert_qty'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-12 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('statistics.monthly_sales_vs_purchases') }}</h4>
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
                labels: @json($dailyLabelsJs),
                datasets: [
                    {
                        label: '{{ __('general.pages.statistics.total_sales') }}',
                        data: @json($dailySalesJs),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        pointBackgroundColor: '#10b981',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35
                    },
                    {
                        label: '{{ __('general.pages.statistics.total_purchases') }}',
                        data: @json($dailyPurchasesJs),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.08)',
                        pointBackgroundColor: '#3b82f6',
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
                labels: @json($monthlyLabelsJs),
                datasets: [
                    {
                        label: '{{ __('general.pages.statistics.total_sales') }}',
                        data: @json($monthlySalesJs),
                        backgroundColor: 'rgba(16, 185, 129, 0.78)',
                        borderRadius: 8
                    },
                    {
                        label: '{{ __('general.pages.statistics.total_purchases') }}',
                        data: @json($monthlyPurchasesJs),
                        backgroundColor: 'rgba(59, 130, 246, 0.78)',
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

        createOrReplaceChart('paymentMethodChart', 'paymentMethodChart', {
            type: 'doughnut',
            data: {
                labels: @json(collect($paymentMethodBreakdown)->pluck('method_name')),
                datasets: [{
                    data: @json(collect($paymentMethodBreakdown)->pluck('total_amount')),
                    backgroundColor: ['#10b981', '#3b82f6', '#f97316', '#a855f7', '#14b8a6', '#ef4444'],
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
