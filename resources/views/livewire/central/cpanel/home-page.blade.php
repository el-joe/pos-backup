@php
    $defaultCurrencySymbol = \App\Models\Currency::query()->value('symbol') ?? '$';
@endphp

<div class="row">
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold">{{ __('general.titles.statistics') }}</div>
                        <div class="text-muted small">CPanel overview</div>
                    </div>
                    <a class="btn btn-outline-theme" href="{{ route('cpanel.dashboard') }}">
                        <i class="fa fa-sync"></i> Refresh
                    </a>
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

    {{-- Section 1: KPI Cards --}}
    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">{{ __('general.dashboard.total_tenants') }}</div>
                    <i class="fa fa-building text-inverse"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['tenants'] ?? 0) }}</h3>
                <div class="text-muted small">+{{ number_format($stats['new_tenants_this_month'] ?? 0) }} this month</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.customers.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">{{ __('general.dashboard.active_subscriptions') }}</div>
                    <i class="fa fa-check-circle text-success"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['subscriptions_paid'] ?? 0) }}</h3>
                <div class="text-muted small">{{ number_format($stats['subscriptions_expiring_soon'] ?? 0) }} expiring soon</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.subscriptions.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">{{ __('general.dashboard.mrr') }}</div>
                    <i class="fa fa-chart-line text-theme"></i>
                </div>
                <h3 class="mb-0">{{ $defaultCurrencySymbol }} {{ number_format((float) ($stats['mrr'] ?? 0), 2) }}</h3>
                <div class="text-muted small">Churn: {{ number_format((float) ($stats['churn_rate'] ?? 0), 2) }}%</div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">{{ __('general.dashboard.revenue_this_month') }}</div>
                    <i class="fa fa-coins text-warning"></i>
                </div>
                <h3 class="mb-0">{{ $defaultCurrencySymbol }} {{ number_format((float) ($stats['revenue_this_month'] ?? 0), 2) }}</h3>
                <div class="text-muted small">Last month: {{ $defaultCurrencySymbol }} {{ number_format((float) ($stats['revenue_last_month'] ?? 0), 2) }}</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.subscriptions.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">{{ __('general.dashboard.unread_contacts') }}</div>
                    <i class="fa fa-envelope text-primary"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['contacts_unread'] ?? 0) }}</h3>
                <div class="text-muted small">Not opened yet</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.contacts.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">{{ __('general.dashboard.pending_requests') }}</div>
                    <i class="fa fa-user-plus text-danger"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['pending_register_requests'] ?? 0) }}</h3>
                <div class="text-muted small">Waiting for approval</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.register-requests.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    {{-- Section 2: Traffic Analytics --}}
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">{{ __('general.dashboard.traffic_analytics') }}</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" wire:click="setTrafficPeriod('7')" class="btn btn-outline-theme {{ $trafficPeriod === '7' ? 'active' : '' }}">{{ __('general.dashboard.days_7') }}</button>
                    <button type="button" wire:click="setTrafficPeriod('30')" class="btn btn-outline-theme {{ $trafficPeriod === '30' ? 'active' : '' }}">{{ __('general.dashboard.days_30') }}</button>
                    <button type="button" wire:click="setTrafficPeriod('90')" class="btn btn-outline-theme {{ $trafficPeriod === '90' ? 'active' : '' }}">{{ __('general.dashboard.days_90') }}</button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('general.dashboard.total_views') }}</div>
                        <h4 class="mb-0">{{ number_format($traffic['total_views'] ?? 0) }}</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('general.dashboard.unique_sessions') }}</div>
                        <h4 class="mb-0">{{ number_format($traffic['unique_sessions'] ?? 0) }}</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">{{ __('general.dashboard.top_page') }}</div>
                        <h4 class="mb-0 text-truncate">{{ $traffic['top_pages'][0]['path'] ?? '-' }}</h4>
                    </div>
                </div>

                <div style="height: 280px;">
                    <canvas id="trafficChart" wire:ignore></canvas>
                </div>

                @if ($expiringSoon->isNotEmpty())
                    <div class="mt-4">
                        <div class="fw-bold small mb-2">{{ __('general.dashboard.expiring_subscriptions') }}</div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('general.dashboard.tenant') }}</th>
                                        <th>{{ __('general.dashboard.plan_name') }}</th>
                                        <th>{{ __('general.dashboard.ends_at') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expiringSoon as $subscription)
                                        <tr class="table-warning">
                                            <td>{{ $subscription->tenant?->name ?? '-' }}</td>
                                            <td>{{ $subscription->plan?->name ?? $subscription->plan?->name_en ?? '-' }}</td>
                                            <td>{{ optional($subscription->end_date)->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    {{-- Section 3: Top Pages / Subscriptions by Plan --}}
    <div class="col-xl-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header"><h5 class="mb-0">{{ __('general.dashboard.top_pages') }}</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.dashboard.path') }}</th>
                                <th class="text-end">{{ __('general.dashboard.views') }}</th>
                                <th class="text-end">{{ __('general.dashboard.percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topPages as $page)
                                <tr>
                                    <td class="text-truncate" style="max-width: 220px;">{{ $page['path'] }}</td>
                                    <td class="text-end">{{ number_format($page['views']) }}</td>
                                    <td class="text-end">{{ number_format($page['percentage'], 2) }}%</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">No data yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header"><h5 class="mb-0">{{ __('general.dashboard.subscriptions_by_plan') }}</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.dashboard.plan') }}</th>
                                <th class="text-end">{{ __('general.dashboard.count') }}</th>
                                <th class="text-end">{{ __('general.dashboard.revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($revenueByPlan as $plan)
                                <tr>
                                    <td>{{ $plan['plan_name'] }}</td>
                                    <td class="text-end">{{ number_format($plan['count']) }}</td>
                                    <td class="text-end">{{ $defaultCurrencySymbol }} {{ number_format($plan['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">No data yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    {{-- Section 4: Tenants by Country (existing map) --}}
    <div class="col-xl-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">{{ __('general.dashboard.tenants_by_country') }}</h5></div>
            <div class="card-body">
                <div class="ratio ratio-21x9 mb-3">
                    <div id="world-map" class="jvectormap-without-padding"></div>
                </div>
                <table class="w-100 small mb-0 text-truncate text-inverse text-opacity-60">
                    <thead>
                        <tr class="text-inverse text-opacity-75">
                            <th class="w-50">COUNTRY</th>
                            <th class="w-25 text-end">TENANTS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenantsByCountry as $row)
                            <tr>
                                <td>{{ strtoupper($row['country']) }}</td>
                                <td class="text-end">{{ number_format($row['total']) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted">No data yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Paid Subscriptions Amount (by Currency)</h5>
                <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.subscriptions.list') }}">Subscriptions</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Currency</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paidAmountsByCurrency as $row)
                                <tr>
                                    <td>{{ $row['code'] }}</td>
                                    <td class="text-end">
                                        {{ $row['symbol'] }} {{ number_format((float) ($row['total'] ?? 0), 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No paid subscriptions yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>
</div>

@push('styles')
    <link href="{{ asset('hud/assets/') }}/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('hud/assets/') }}/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
    <script src="{{ asset('hud/assets/') }}/plugins/jvectormap-content/world-mill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        (function () {
            const trafficLabels = @json(collect($traffic['views_by_day'] ?? [])->pluck('date'));
            const trafficData = @json(collect($traffic['views_by_day'] ?? [])->pluck('total'));
            const chartLabel = @json(__('general.dashboard.total_views'));

            let trafficChart = null;

            function renderTrafficChart() {
                const canvas = document.getElementById('trafficChart');
                if (!canvas || typeof Chart === 'undefined') {
                    return;
                }

                if (trafficChart) {
                    trafficChart.destroy();
                }

                trafficChart = new Chart(canvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: trafficLabels,
                        datasets: [{
                            label: chartLabel,
                            data: trafficData,
                            borderColor: '#6259ca',
                            backgroundColor: 'rgba(98, 89, 202, 0.15)',
                            fill: true,
                            tension: 0.3,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } },
                    },
                });
            }

            renderTrafficChart();

            document.addEventListener('livewire:navigated', renderTrafficChart);
            if (window.Livewire) {
                window.Livewire.hook('morph.updated', ({ el }) => {
                    if (el.querySelector && el.querySelector('#trafficChart')) {
                        renderTrafficChart();
                    }
                });
            }
        })();
    </script>
    <script src="{{ asset('hud/assets/') }}/plugins/apexcharts/dist/apexcharts.min.js"></script>
    <script src="{{ asset('hud/assets/') }}/js/demo/dashboard.demo.js"></script>
@endpush
