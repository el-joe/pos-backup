<div class="col-12">
    <x-hud.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.pages.reports.tax.withholding_tax.title')" icon="fa-list-alt" :render-table="false" class="mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped mb-0 table-dark align-middle">
                    <thead class="table-primary text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.tax.withholding_tax.id') }}</th>
                                <th>{{ __('general.pages.reports.tax.withholding_tax.account') }}</th>
                                <th>{{ __('general.pages.reports.tax.withholding_tax.type') }}</th>
                                <th>{{ __('general.pages.reports.tax.withholding_tax.supplier_customer') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.tax.withholding_tax.withholding_amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @forelse($report as $row)
                            @php $total += $row->withholding_amount; @endphp
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->account_name }}</td>
                                <td>{{ $row->party_type }}</td>
                                <td>{{ $row->party_name ?? __('general.messages.n_a') }}</td>
                                <td class="text-end">{{ currencyFormat($row->withholding_amount, true) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('general.pages.reports.tax.withholding_tax.no_data') }}</td>
                            </tr>
                        @endforelse

                        @if(count($report))
                            <tr class="bg-success bg-opacity-25 fw-semibold">
                                <td colspan="3">{{ __('general.pages.reports.common.total') }}</td>
                                <td colspan="2" class="text-end">{{ currencyFormat($total, true) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
        </div>
    </x-hud.table-card>
</div>
@push('scripts')
    @include('layouts.hud.partials.daterange-picker-script')
@endpush
