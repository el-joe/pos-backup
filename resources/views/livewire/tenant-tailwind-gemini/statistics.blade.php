@if(adminCan('statistics.show'))
    @php
        $cards = [
            ['label' => __('general.pages.statistics.total_sales'), 'value' => currencyFormat($data['totalSales'], true), 'icon' => 'fa fa-cash-register', 'accent' => 'emerald'],
            ['label' => __('general.pages.statistics.net_sales'), 'value' => currencyFormat($data['netSales'], true), 'icon' => 'fa fa-chart-line', 'accent' => 'blue'],
            ['label' => __('general.pages.statistics.due_amount'), 'value' => currencyFormat($data['dueAmount'], true), 'icon' => 'fa fa-hand-holding-usd', 'accent' => 'amber'],
            ['label' => __('general.pages.statistics.total_sales_return'), 'value' => currencyFormat($data['totalSalesReturn'], true), 'icon' => 'fa fa-undo-alt', 'accent' => 'rose'],
            ['label' => __('general.pages.statistics.total_purchases'), 'value' => currencyFormat($data['totalPurchases'], true), 'icon' => 'fa fa-shopping-cart', 'accent' => 'indigo'],
            ['label' => __('general.pages.statistics.purchase_due'), 'value' => currencyFormat($data['purchaseDue'], true), 'icon' => 'fa fa-file-invoice-dollar', 'accent' => 'violet'],
            ['label' => __('general.pages.statistics.total_purchase_return'), 'value' => currencyFormat($data['totalPurchaseReturn'], true), 'icon' => 'fa fa-reply-all', 'accent' => 'pink'],
            ['label' => __('general.pages.statistics.total_expense'), 'value' => currencyFormat($data['totalExpense'], true), 'icon' => 'fa fa-receipt', 'accent' => 'slate'],
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach($cards as $card)
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
                            <h3 class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">{{ $card['value'] }}</h3>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300">
                            <i class="{{ $card['icon'] }} text-xl"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.statistics.sales_overview_last_30_days')">
                <div class="p-5">
                    <canvas id="lineChart"></canvas>
                </div>
            </x-tenant-tailwind-gemini.table-card>

            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.statistics.sales_overview_last_12_months')">
                <div class="p-5">
                    <canvas id="lineChart2"></canvas>
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

        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: @json($saleOrdersPerDaylabels),
                datasets: [{
                    label: '{{ __('general.pages.statistics.total_sales') }}',
                    data: @json($saleOrdersPerDayData),
                    borderColor: geminiChartColor,
                    backgroundColor: 'rgba(37, 99, 235, 0.15)',
                    pointBackgroundColor: geminiChartColor,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: geminiChartOptions()
        });

        new Chart(document.getElementById('lineChart2'), {
            type: 'line',
            data: {
                labels: @json($saleOrdersPerMonthlabels),
                datasets: [{
                    label: '{{ __('general.pages.statistics.total_sales') }}',
                    data: @json($saleOrdersPerMonthData),
                    borderColor: geminiChartColor,
                    backgroundColor: 'rgba(37, 99, 235, 0.15)',
                    pointBackgroundColor: geminiChartColor,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: geminiChartOptions()
        });
    </script>
@endpush
