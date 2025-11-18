<div class="container-fluid">
    <div class="row">
        <!-- Filter Options Card -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 bg-dark text-light">
                <div class="card-header d-flex align-items-center">
                    <i class="fa fa-filter me-2"></i>
                    <strong>{{ __('general.pages.reports.common.filter_options') }}</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-light">{{ __('general.pages.reports.common.from_date') }}</label>
                            <input type="date" class="form-control form-control-sm bg-dark text-light border-secondary" wire:model.lazy="from_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-light">{{ __('general.pages.reports.common.to_date') }}</label>
                            <input type="date" class="form-control form-control-sm bg-dark text-light border-secondary" wire:model.lazy="to_date">
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

        <!-- Sales Profit Card -->
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-dark text-light">
                <div class="card-header d-flex align-items-center">
                    <i class="fa fa-chart-line me-2"></i>
                    <h5 class="mb-0">{{ __('general.pages.reports.sales.profit.title') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover mb-0 align-middle">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>{{ __('general.pages.reports.sales.product.product') }}</th>
                                    <th>{{ __('general.pages.reports.sales.profit.sales_revenue') }}</th>
                                    <th>{{ __('general.pages.reports.sales.profit.cogs') }}</th>
                                    <th>{{ __('general.pages.reports.sales.profit.gross_profit') }}</th>
                                    <th>{{ __('general.pages.reports.sales.profit.margin_percentage') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_revenue = 0;
                                    $total_cogs = 0;
                                    $total_profit = 0;
                                @endphp
                                @forelse($report as $row)
                                    @php
                                        $total_revenue += $row->sales_revenue;
                                        $total_cogs += $row->cogs;
                                        $total_profit += $row->gross_profit;
                                    @endphp
                                    <tr>
                                        <td>{{ $row->product_name }}</td>
                                        <td>{{ number_format($row->sales_revenue, 2) }}</td>
                                        <td>{{ number_format($row->cogs, 2) }}</td>
                                        <td>{{ number_format($row->gross_profit, 2) }}</td>
                                        <td>{{ number_format($row->margin, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            {{ __('general.pages.reports.sales.profit.no_data') }}
                                        </td>
                                    </tr>
                                @endforelse
                                @if(count($report))
                                    <tr class="bg-success text-dark fw-bold">
                                        <td>{{ __('general.pages.reports.common.total') }}</td>
                                        <td>{{ number_format($total_revenue, 2) }}</td>
                                        <td>{{ number_format($total_cogs, 2) }}</td>
                                        <td>{{ number_format($total_profit, 2) }}</td>
                                        <td>{{ $total_revenue > 0 ? number_format($total_profit / $total_revenue * 100, 2) : '0.00' }}</td>
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
</div>
