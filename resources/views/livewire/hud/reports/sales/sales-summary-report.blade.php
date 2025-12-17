<div class="container-fluid">
    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <strong><i class="fa fa-filter me-2"></i> {{ __('general.pages.reports.common.filter_options') }}</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.reports.common.from_date') }}</label>
                        <input type="date" class="form-control form-control-sm" wire:model.lazy="from_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.reports.common.to_date') }}</label>
                        <input type="date" class="form-control form-control-sm" wire:model.lazy="to_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.reports.common.period') }}</label>
                        <select class="form-select form-select-sm" wire:model.lazy="period">
                            <option value="day">{{ __('general.pages.reports.common.day') }}</option>
                            <option value="week">{{ __('general.pages.reports.common.week') }}</option>
                            <option value="month">{{ __('general.pages.reports.common.month') }}</option>
                        </select>
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

    <!-- Sales Summary -->
    <div class="col-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h5 class="mb-0"><i class="fa fa-chart-bar me-2"></i> {{ __('general.pages.reports.sales.summary.title') }}</h5>
            </div>
            <div class="card-body p-0">
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
                                <td>{{ $row['period'] }}</td>
                                <td>{{ currency()->symbol }}{{ number_format($row['gross_sales'], 2) }}</td>
                                <td class="text-danger">-{{ currency()->symbol }}{{ number_format($row['discount'], 2) }}</td>
                                <td class="text-danger">-{{ currency()->symbol }}{{ number_format($row['sales_return'], 2) }}</td>
                                <td><span class="badge bg-success">{{ currency()->symbol }}{{ number_format($row['net_sales'], 2) }}</span></td>
                                <td>{{ currency()->symbol }}{{ number_format($row['vat_payable'], 2) }}</td>
                                <td>{{ currency()->symbol }}{{ number_format($row['total_collected'], 2) }}</td>
                                <td>{{ currency()->symbol }}{{ number_format($row['cogs'], 2) }}</td>
                                <td>{{ currency()->symbol }}{{ number_format($row['gross_profit'], 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">{{ __('general.pages.reports.sales.summary.no_data') }}</td>
                            </tr>
                            @endforelse

                            @if(count($report))
                            <tr class="table-success fw-semibold">
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td>{{ currency()->symbol }}{{ number_format(collect($report)->sum('gross_sales'), 2) }}</td>
                                <td class="text-danger">-{{ currency()->symbol }}{{ number_format(collect($report)->sum('discount'), 2) }}</td>
                                <td class="text-danger">-{{ currency()->symbol }}{{ number_format(collect($report)->sum('sales_return'), 2) }}</td>
                                <td><span class="badge bg-success">{{ currency()->symbol }}{{ number_format(collect($report)->sum('net_sales'), 2) }}</span></td>
                                <td>{{ currency()->symbol }}{{ number_format(collect($report)->sum('vat_payable'), 2) }}</td>
                                <td>{{ currency()->symbol }}{{ number_format(collect($report)->sum('total_collected'), 2) }}</td>
                                <td>{{ currency()->symbol }}{{ number_format(collect($report)->sum('cogs'), 2) }}</td>
                                <td>{{ currency()->symbol }}{{ number_format(collect($report)->sum('gross_profit'), 2) }}</td>
                            </tr>
                            @endif
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
</div>
