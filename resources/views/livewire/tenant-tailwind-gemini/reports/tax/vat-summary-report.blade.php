<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.tax.vat_summary.title')" icon="fa-list-alt" :render-table="false" class="mb-4">
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
    </x-tenant-tailwind-gemini.table-card>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
