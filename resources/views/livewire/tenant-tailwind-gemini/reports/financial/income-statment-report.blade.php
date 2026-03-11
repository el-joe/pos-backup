<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter">
                <div class="row g-3">
                    <div class="col-6" wire:ignore>
                        <label class="form-label">{{ __('general.pages.reports.common.date_range') }}</label>
                        <div class="input-group">
                            <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                        </div>
                    </div>
                </div>
            </x-tenant-tailwind-gemini.filter-card>
        </div>

        <div class="col-12">
            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.financial.income_statement.title')" icon="fa-line-chart" :render-table="false">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <tbody>
                                {{-- ================= Revenue ================= --}}
                                <tr class="bg-primary bg-opacity-25">
                                    <th colspan="2">{{ __('general.pages.reports.financial.income_statement.revenue') }}</th>
                                </tr>
                                <tr>
                                    <td>{{ \App\Enums\AccountTypeEnum::SALES->translatedLabel() }}</td>
                                    <td>{{ currencyFormat($report['gross_sales'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ \App\Enums\AccountTypeEnum::SALES_DISCOUNT->translatedLabel() }}</td>
                                    <td>-{{ currencyFormat($report['sales_discount_total'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ \App\Enums\AccountTypeEnum::SALES_RETURN->translatedLabel() }}</td>
                                    <td>-{{ currencyFormat($report['sales_return_total'] ?? 0, true) }}</td>
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
                                    <td>{{ \App\Enums\AccountTypeEnum::COGS->translatedLabel() }}</td>
                                    <td>{{ currencyFormat($report['cogs_total'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ \App\Enums\AccountTypeEnum::INVENTORY_SHORTAGE->translatedLabel() }}</td>
                                    <td>{{ currencyFormat($report['inventory_shortage_total'] ?? 0, true) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ \App\Enums\AccountTypeEnum::PURCHASE_DISCOUNT->translatedLabel() }}</td>
                                    <td>-{{ currencyFormat($report['purchase_discount_total'] ?? 0, true) }}</td>
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
                                @foreach(($report['expenses_breakdown'] ?? []) as $type => $amount)
                                    <tr>
                                        <td>{{ \App\Enums\AccountTypeEnum::tryFrom($type)?->translatedLabel() ?? $type }}</td>
                                        <td>{{ currencyFormat($amount, true) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="fw-semibold bg-purple bg-opacity-10">
                                    <td>{{ __('general.pages.reports.financial.income_statement.total_expenses') }}</td>
                                    <td>{{ currencyFormat($report['expenses'] ?? 0, true) }}</td>
                                </tr>
                                <tr class="bg-emerald-50 text-lg font-bold text-slate-900">
                                    <th>{{ __('general.pages.reports.financial.income_statement.net_profit') }}</th>
                                    <th>{{ currencyFormat($report['net_profit'] ?? 0, true) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </x-tenant-tailwind-gemini.table-card>
        </div>
    </div>
</div>

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
