<div class="container-fluid py-3">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-sm-6">
                <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.performance.sales_threshold.title')" icon="fa-bell" :render-table="false">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                        <thead >
                            <tr>
                                <th>{{ __('general.pages.reports.performance.sales_threshold.customer') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.sales_threshold.sales_threshold') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.sales_threshold.total_sales') }}</th>
                                <th class="text-center">{{ __('general.pages.reports.performance.sales_threshold.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sum_sales = 0; @endphp
                            @forelse($report as $row)
                                @php $sum_sales += $row->total_sales; @endphp
                                <tr>
                                    <td>{{ $row->customer_name }}</td>
                                    <td class="text-end">{{ currencyFormat($row->sales_threshold, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_sales, true) }}</td>
                                    <td class="text-center">
                                        @php
                                            $badgeClass = $row->status == 'Reached' ? 'bg-success' : 'bg-danger';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $row->status == 'Reached' ? __('general.pages.reports.performance.sales_threshold.reached') : __('general.pages.reports.performance.sales_threshold.not_reached') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">{{ __('general.pages.reports.performance.sales_threshold.no_data') }}</td>
                                </tr>
                            @endforelse

                            @if(count($report))
                            <tr class="bg-emerald-50 font-bold text-slate-900">
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td></td>
                                <td class="text-end">{{ currencyFormat($sum_sales, true) }}</td>
                                <td></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
