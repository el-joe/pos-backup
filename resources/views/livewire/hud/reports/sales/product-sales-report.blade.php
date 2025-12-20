<div class="container-fluid">
    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <strong><i class="fa fa-filter me-2"></i> {{ __('general.pages.reports.common.filter_options') }}</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                        <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
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

    <!-- Product Sales -->
    <div class="col-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h5 class="mb-0"><i class="fa fa-th-large me-2"></i> {{ __('general.pages.reports.sales.product.title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>{{ __('general.pages.reports.sales.product.product') }}</th>
                                <th>{{ __('general.pages.reports.sales.product.quantity_sold') }}</th>
                                <th>{{ __('general.pages.reports.sales.product.total_cost') }}</th>
                                <th>{{ __('general.pages.reports.sales.product.total_revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_qty = 0;
                                $total_revenue = 0;
                                $total_cost = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_qty += $row->quantity_sold;
                                    $total_revenue += $row->total_revenue;
                                    $total_cost += $row->total_cost;
                                @endphp
                                <tr>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ number_format($row->quantity_sold, 0) }}</td>
                                    <td>{{ currencyFormat($row->total_cost, true) }}</td>
                                    <td>{{ currencyFormat($row->total_revenue, true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">{{ __('general.pages.reports.sales.product.no_data') }}</td>
                                </tr>
                            @endforelse

                            @if(count($report))
                                <tr class="table-success fw-semibold">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td>{{ currencyFormat($total_qty, true) }}</td>
                                    <td>{{ currencyFormat($total_cost, true) }}</td>
                                    <td>{{ currencyFormat($total_revenue, true) }}</td>
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
@push('scripts')
    @include('layouts.hud.partials.daterange-picker-script')
@endpush
