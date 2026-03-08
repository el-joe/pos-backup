<div class="container-fluid">
    <div class="col-12 mb-4">
        <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                    <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('general.pages.reports.common.period') }}</label>
                    <select class="form-select select2 form-select-sm" name="period">
                        <option value="day" {{ $period == 'day' ? 'selected' : '' }}>{{ __('general.pages.reports.common.day') }}</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>{{ __('general.pages.reports.common.week') }}</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>{{ __('general.pages.reports.common.month') }}</option>
                    </select>
                </div>
            </div>
        </x-tenant-tailwind-gemini.filter-card>
    </div>

    <div class="col-12">
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.sales.summary.title')" icon="fa-chart-bar" :render-table="false">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>{{ __('general.pages.reports.sales.summary.period') }}</th>
                                <th>{{ __('general.pages.reports.sales.summary.gross_sales') }}</th>
                                <th>{{ __('general.pages.reports.sales.summary.discount') }}</th>
                                <th>{{ __('general.pages.reports.sales.summary.sales_return') }}</th>
                                <th>{{ __('general.pages.reports.sales.summary.net_sales') }}</th>
                                <th>{{ __('general.pages.reports.sales.summary.vat_payable') }}</th>
                                <th>{{ __('general.pages.reports.sales.summary.total_collected') }}</th>
                                <th>{{ __('general.pages.reports.sales.summary.cogs') }}</th>
                                <th>{{ __('general.pages.reports.sales.summary.gross_profit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report as $row)
                            <tr>
                                <td>{{ dateTimeFormat($row['period'], true, false) }}</td>
                                <td>{{ currencyFormat($row['gross_sales'], true) }}</td>
                                <td class="text-danger">-{{ currencyFormat($row['discount'], true) }}</td>
                                <td class="text-danger">-{{ currencyFormat($row['sales_return'], true) }}</td>
                                <td><span class="badge bg-success">{{ currencyFormat($row['net_sales'], true) }}</span></td>
                                <td>{{ currencyFormat($row['vat_payable'], true) }}</td>
                                <td>{{ currencyFormat($row['total_collected'], true) }}</td>
                                <td>{{ currencyFormat($row['cogs'], true) }}</td>
                                <td>{{ currencyFormat($row['gross_profit'], true) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">{{ __('general.pages.reports.sales.summary.no_data') }}</td>
                            </tr>
                            @endforelse

                            @if(count($report))
                            <tr class="table-success fw-semibold">
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td>{{ currencyFormat(collect($report)->sum('gross_sales'), true) }}</td>
                                <td class="text-danger">-{{ currencyFormat(collect($report)->sum('discount'), true) }}</td>
                                <td class="text-danger">-{{ currencyFormat(collect($report)->sum('sales_return'), true) }}</td>
                                <td><span class="badge bg-success">{{ currencyFormat(collect($report)->sum('net_sales'), true) }}</span></td>
                                <td>{{ currencyFormat(collect($report)->sum('vat_payable'), true) }}</td>
                                <td>{{ currencyFormat(collect($report)->sum('total_collected'), true) }}</td>
                                <td>{{ currencyFormat(collect($report)->sum('cogs'), true) }}</td>
                                <td>{{ currencyFormat(collect($report)->sum('gross_profit'), true) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
            </div>
        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
