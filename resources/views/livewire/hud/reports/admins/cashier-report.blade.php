<div class="container-fluid py-3">
    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-secondary text-white d-flex align-items-center">
                <i class="fa fa-filter me-2"></i>
                <strong>{{ __('general.pages.reports.common.filter_options') }}</strong>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-end">
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

    <!-- Cashier Report -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa fa-user me-2"></i>
                <h5 class="mb-0">{{ __('general.pages.reports.admins.cashier_report.title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-bordered table-hover table-striped mb-0 align-middle">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.admins.cashier_report.cashier') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.admins.cashier_report.total_sales') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.admins.cashier_report.total_refunds') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.admins.cashier_report.total_discounts') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.admins.cashier_report.net_sales') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sumSales = 0;
                                $sumRefunds = 0;
                                $sumDiscounts = 0;
                                $sumNet = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $sumSales += $row->total_sales;
                                    $sumRefunds += $row->total_refunds;
                                    $sumDiscounts += $row->total_discounts;
                                    $sumNet += $row->net_sales;
                                @endphp
                                <tr>
                                    <td>{{ $row->cashier }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_sales, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_refunds, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_discounts, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->net_sales, true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">{{ __('general.pages.reports.admins.cashier_report.no_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if(count($report))
                        <tfoot class="bg-success bg-opacity-25 fw-bold">
                            <tr>
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td class="text-end">{{ currencyFormat($sumSales, true) }}</td>
                                <td class="text-end">{{ currencyFormat($sumRefunds, true) }}</td>
                                <td class="text-end">{{ currencyFormat($sumDiscounts, true) }}</td>
                                <td class="text-end">{{ currencyFormat($sumNet, true) }}</td>
                            </tr>
                        </tfoot>
                        @endif
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
