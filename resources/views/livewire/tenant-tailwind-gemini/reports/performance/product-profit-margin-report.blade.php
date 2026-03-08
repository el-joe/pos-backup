<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-sm-6">
                <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.performance.product_profit_margin.title')" icon="fa-line-chart" :render-table="false" class="mb-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 table-dark align-middle">
                    <thead class="table-primary text-dark">
                        <tr>
                            <th>{{ __('general.pages.reports.performance.product_profit_margin.product') }}</th>
                            <th class="text-end">{{ __('general.pages.reports.performance.product_profit_margin.total_sales') }}</th>
                            <th class="text-end">{{ __('general.pages.reports.performance.product_profit_margin.total_cogs') }}</th>
                            <th class="text-end">{{ __('general.pages.reports.performance.product_profit_margin.profit') }}</th>
                            <th class="text-end">{{ __('general.pages.reports.performance.product_profit_margin.profit_margin_percentage') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sum_sales = 0;
                            $sum_cogs = 0;
                            $sum_profit = 0;
                        @endphp
                        @forelse($report as $row)
                            @php
                                $sum_sales += $row->total_sales;
                                $sum_cogs += $row->total_cogs;
                                $sum_profit += $row->profit;
                            @endphp
                            <tr>
                                <td>{{ $row->product_name }}</td>
                                <td class="text-end">{{ currencyFormat($row->total_sales, true) }}</td>
                                <td class="text-end">{{ currencyFormat($row->total_cogs, true) }}</td>
                                <td class="text-end">{{ currencyFormat($row->profit, true) }}</td>
                                <td class="text-end">
                                    <span class="badge bg-success bg-opacity-75">{{ number_format($row->profit_margin_percent, 2) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('general.pages.reports.performance.product_profit_margin.no_data') }}</td>
                            </tr>
                        @endforelse

                        @if(count($report))
                            <tr class="bg-success bg-opacity-25 fw-semibold">
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td class="text-end">{{ currencyFormat($sum_sales, true) }}</td>
                                <td class="text-end">{{ currencyFormat($sum_cogs, true) }}</td>
                                <td class="text-end">{{ currencyFormat($sum_profit, true) }}</td>
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
