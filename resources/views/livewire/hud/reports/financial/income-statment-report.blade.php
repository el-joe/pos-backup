<div class="container-fluid">
    <div class="row">
        <!-- Filter Options -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient border-0" style="background:linear-gradient(135deg,#1e1e2f,#2a2a40);">
                    <strong><i class="fa fa-filter me-2"></i> {{ __('general.pages.reports.common.filter_options') }}</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('general.pages.reports.common.from_date') }}</label>
                            <input type="date" class="form-control border-secondary" wire:model.lazy="from_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('general.pages.reports.common.to_date') }}</label>
                            <input type="date" class="form-control border-secondary" wire:model.lazy="to_date">
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

        <!-- Income Statement -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-blue-indigo">
                    <h4 class="card-title mb-0"><i class="fa fa-line-chart me-2"></i> {{ __('general.pages.reports.financial.income_statement.title') }}</h4>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <tbody>
                                {{-- ================= Revenue ================= --}}
                                <tr class="bg-primary bg-opacity-25">
                                    <th colspan="2">{{ __('general.pages.reports.financial.income_statement.revenue') }}</th>
                                </tr>
                                <tr>
                                    <td>{{ __('general.pages.reports.financial.income_statement.sales') }}</td>
                                    <td>{{ currencyFormat($report['accounts']['sales']['credit'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('general.pages.reports.financial.income_statement.sales_discount') }}</td>
                                    <td>-{{ currencyFormat($report['accounts']['sales_discount']['debit'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('general.pages.reports.financial.income_statement.sales_return') }}</td>
                                    <td>-{{ currencyFormat($report['accounts']['sales_return']['debit'] ?? 0, true) }}</td>
                                </tr>
                                <tr class="fw-semibold bg-primary bg-opacity-10">
                                    <td>{{ __('general.pages.reports.financial.income_statement.total_revenue') }}</td>
                                    <td>{{ currencyFormat($report['revenue'] ?? 0, true) }}</td>
                                </tr>

                                {{-- ================= COGS ================= --}}
                                <tr class="bg-info bg-opacity-25">
                                    <th colspan="2">{{ __('general.pages.reports.financial.income_statement.cogs_section') }}</th>
                                </tr>
                                <tr>
                                    <td>{{ __('general.pages.reports.financial.income_statement.cogs') }}</td>
                                    <td>{{ currencyFormat($report['accounts']['cogs']['debit'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('general.pages.reports.financial.income_statement.inventory_shortage') }}</td>
                                    <td>{{ currencyFormat($report['accounts']['inventory_shortage']['debit'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('general.pages.reports.financial.income_statement.purchase_discount') }}</td>
                                    <td>-{{ currencyFormat($report['accounts']['purchase_discount']['credit'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('general.pages.reports.financial.income_statement.purchase_return') }}</td>
                                    <td>-{{ currencyFormat($report['accounts']['purchase_return']['credit'] ?? 0, true) }}</td>
                                </tr>
                                <tr class="fw-semibold bg-info bg-opacity-10">
                                    <td>{{ __('general.pages.reports.financial.income_statement.total_cogs') }}</td>
                                    <td>{{ currencyFormat($report['cogs'] ?? 0, true) }}</td>
                                </tr>
                                <tr class="bg-warning bg-opacity-25 fw-bold">
                                    <th>{{ __('general.pages.reports.financial.income_statement.gross_profit') }}</th>
                                    <th>{{ currencyFormat($report['gross_profit'] ?? 0, true) }}</th>
                                </tr>

                                {{-- ================= Expenses ================= --}}
                                <tr class="bg-purple bg-opacity-25">
                                    <th colspan="2">{{ __('general.pages.reports.financial.income_statement.expenses') }}</th>
                                </tr>
                                <tr>
                                    <td>{{ __('general.pages.reports.financial.income_statement.expense') }}</td>
                                    <td>{{ currencyFormat($report['accounts']['expense']['debit'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('general.pages.reports.financial.income_statement.vat_payable') }}</td>
                                    <td>{{ currencyFormat($report['accounts']['vat_payable']['credit'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('general.pages.reports.financial.income_statement.vat_receivable') }}</td>
                                    <td>-{{ currencyFormat($report['accounts']['vat_receivable']['debit'] ?? 0, true) }}</td>
                                </tr>
                                <tr class="fw-semibold bg-purple bg-opacity-10">
                                    <td>{{ __('general.pages.reports.financial.income_statement.total_expenses') }}</td>
                                    <td>{{ currencyFormat($report['expenses'] ?? 0, true) }}</td>
                                </tr>
                                <tr class="bg-success bg-opacity-25 fw-bold fs-5">
                                    <th>{{ __('general.pages.reports.financial.income_statement.net_profit') }}</th>
                                    <th>{{ currencyFormat($report['net_profit'] ?? 0, true) }}</th>
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
    </div>
</div>

@push('styles')
<style>
    /* .bg-purple {
        background-color: #6f42c1 !important;
    }
    .bg-dark-subtle {
        background-color: #2c2f36 !important;
    }
    .table-dark th, .table-dark td {
        border-color: #444;
    } */
</style>
@endpush
