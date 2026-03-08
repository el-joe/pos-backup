<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.financial.cash_flow.title')" icon="fa-usd" :render-table="false">
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
    </x-tenant-tailwind-gemini.table-card>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
