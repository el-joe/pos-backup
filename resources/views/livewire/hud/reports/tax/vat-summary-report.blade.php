<div class="col-12">
    <!-- Filter Options Card -->
    <div class="card shadow-sm border-0 bg-dark text-light mb-4">
        <div class="card-header">
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

    <!-- VAT Summary Card -->
    <div class="card shadow-sm border-0 bg-dark text-light mb-4">
        <div class="card-header d-flex align-items-center">
            <h4 class="mb-0">
                <i class="fa fa-list-alt me-2"></i> {{ __('general.pages.reports.tax.vat_summary.title') }}
            </h4>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 table-dark align-middle">
                    <thead class="table-primary text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.common.metric') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.common.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ __('general.pages.reports.tax.vat_summary.vat_payable_sales') }}</td>
                            <td class="text-end">{{ currencyFormat($report['vat_payable'] ?? 0, true) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('general.pages.reports.tax.vat_summary.vat_receivable_purchases') }}</td>
                            <td class="text-end">{{ currencyFormat($report['vat_receivable'] ?? 0, true) }}</td>
                        </tr>
                        <tr class="bg-success bg-opacity-25 fw-semibold">
                            <td>{{ __('general.pages.reports.tax.vat_summary.net_vat') }}</td>
                            <td class="text-end">{{ currencyFormat($report['net'] ?? 0, true) }}</td>
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
