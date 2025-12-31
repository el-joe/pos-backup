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

    <!-- Balance Sheet Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fa fa-balance-scale me-2"></i> {{ __('general.pages.reports.financial.balance_sheet.title') }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                    <tbody>
                        {{-- ================= Assets ================= --}}
                        <tr class="bg-light">
                            <th colspan="2" class="text-uppercase text-primary">{{ __('general.pages.reports.financial.balance_sheet.assets') }}</th>
                        </tr>
                        @foreach($report['assets'] as $label => $amount)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>{{ currencyFormat($amount, true) }}</td>
                        </tr>
                        @endforeach
                        <tr class="fw-bold bg-primary-subtle">
                            <td>{{ __('general.pages.reports.financial.balance_sheet.total_assets') }}</td>
                            <td>{{ currencyFormat($report['total_assets'] ?? 0, true) }}</td>
                        </tr>

                        {{-- ================= Liabilities ================= --}}
                        <tr class="bg-light">
                            <th colspan="2" class="text-uppercase text-danger">{{ __('general.pages.reports.financial.balance_sheet.liabilities') }}</th>
                        </tr>
                        @foreach($report['liabilities'] as $label => $amount)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>{{ currencyFormat($amount, true) }}</td>
                        </tr>
                        @endforeach
                        <tr class="fw-bold bg-danger-subtle">
                            <td>{{ __('general.pages.reports.financial.balance_sheet.total_liabilities') }}</td>
                            <td>{{ currencyFormat($report['total_liabilities'] ?? 0, true) }}</td>
                        </tr>

                        {{-- ================= Equity ================= --}}
                        <tr class="bg-light">
                            <th colspan="2" class="text-uppercase text-success">{{ __('general.pages.reports.financial.balance_sheet.equity') }}</th>
                        </tr>
                        @foreach($report['equity'] as $label => $amount)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>{{ currencyFormat($amount, true) }}</td>
                        </tr>
                        @endforeach
                        <tr class="fw-bold bg-success-subtle">
                            <td>{{ __('general.pages.reports.financial.balance_sheet.total_equity') }}</td>
                            <td>{{ currencyFormat($report['total_equity'] ?? 0, true) }}</td>
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
