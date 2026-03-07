<div class="container-fluid py-3">
    <x-hud.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-sm-6">
                <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.pages.reports.performance.revenue_breakdown.title')" icon="fa-signal" :render-table="false">
                <div class="table-responsive">
                    <table class="table table-dark table-bordered table-hover table-striped mb-0 align-middle">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.performance.revenue_breakdown.branch') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.revenue_breakdown.total_revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sum_revenue = 0; @endphp
                            @forelse($report as $row)
                                @php $sum_revenue += $row->total_revenue; @endphp
                                <tr>
                                    <td>{{ $row->branch_name }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-success">{{ currencyFormat($row->total_revenue, true) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">{{ __('general.pages.reports.performance.revenue_breakdown.no_data') }}</td>
                                </tr>
                            @endforelse

                            @if(count($report))
                            <tr class="bg-success bg-opacity-25 fw-bold">
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td class="text-end">{{ currencyFormat($sum_revenue, true) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
    </x-hud.table-card>
</div>
@push('scripts')
    @include('layouts.hud.partials.daterange-picker-script')
@endpush
