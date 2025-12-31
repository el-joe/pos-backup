<div class="col-12">
    <!-- Filter Options Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fa fa-filter me-2"></i> {{ __('general.pages.reports.common.filter_options') }}
            </h5>
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

    <!-- Cash Flow Statement Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fa fa-usd me-2"></i> {{ __('general.pages.reports.financial.cash_flow.title') }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                    <thead class="table-primary text-center">
                            <tr>
                                <th>{{ __('general.pages.reports.financial.cash_flow.account') }}</th>
                                <th>{{ __('general.pages.reports.financial.cash_flow.inflow') }}</th>
                                <th>{{ __('general.pages.reports.financial.cash_flow.outflow') }}</th>
                                <th>{{ __('general.pages.reports.financial.cash_flow.net_cash_flow') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['cash_flows'] ?? [] as $type => $flow)
                        <tr>
                            <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                            <td>{{ currencyFormat($flow['inflow'], true) }}</td>
                            <td>{{ currencyFormat($flow['outflow'], true) }}</td>
                            <td>{{ currencyFormat($flow['net'], true) }}</td>
                        </tr>
                        @endforeach
                        <tr class="fw-bold bg-primary-subtle">
                            <td>{{ __('general.pages.reports.common.total') }}</td>
                            <td>{{ currencyFormat($report['total_inflow'] ?? 0, true) }}</td>
                            <td>{{ currencyFormat($report['total_outflow'] ?? 0, true) }}</td>
                            <td>{{ currencyFormat($report['net_cash_flow'] ?? 0, true) }}</td>
                        </tr>
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
@push('scripts')
    @include('layouts.hud.partials.daterange-picker-script')
@endpush
