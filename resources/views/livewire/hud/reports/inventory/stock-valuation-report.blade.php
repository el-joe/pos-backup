<div class="container-fluid">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-dark text-light">
            <div class="card-header d-flex align-items-center">
                <i class="fa fa-copyright me-2 text-warning"></i>
                <h5 class="mb-0">{{ __('general.pages.reports.inventory.stock_valuation.title') }}</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-striped align-middle mb-0">
                        <thead class="table-light text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.inventory.stock_valuation.branch') }}</th>
                                <th>{{ __('general.pages.reports.inventory.stock_valuation.product_unit') }}</th>
                                <th>{{ __('general.pages.reports.inventory.stock_valuation.stock_quantity') }}</th>
                                <th>{{ __('general.pages.reports.inventory.stock_valuation.unit_cost') }}</th>
                                <th>{{ __('general.pages.reports.inventory.stock_valuation.stock_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_qty = 0;
                                $total_value = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_qty += $row->stock_qty;
                                    $total_value += $row->stock_value;
                                @endphp
                                <tr>
                                    <td>{{ $row->branch_name ?? __('general.messages.n_a') }}</td>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ $row->stock_qty }}</td>
                                    <td>{{ currencyFormat($row->unit_cost, true) }}</td>
                                    <td>{{ currencyFormat($row->stock_value, true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">{{ __('general.pages.reports.inventory.stock_valuation.no_data') }}</td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold bg-success bg-opacity-25">
                                    <td colspan="2">{{ __('general.pages.reports.common.total') }}</td>
                                    <td>{{ $total_qty }}</td>
                                    <td></td>
                                    <td>{{ currencyFormat($total_value, true) }}</td>
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
