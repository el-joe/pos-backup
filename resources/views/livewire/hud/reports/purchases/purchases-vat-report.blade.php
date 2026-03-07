<div class="container-fluid">
    <div class="col-12 mb-4">
        <x-hud.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                    <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                </div>
            </div>
        </x-hud.filter-card>
    </div>

    <div class="col-12">
        <x-hud.table-card :title="__('general.pages.reports.purchases.vat.title')" icon="fa-file-invoice-dollar" :render-table="false">
            <div class="table-responsive">
                <table class="table table-dark table-hover table-striped align-middle mb-0">
                        <thead class="table-light text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.purchases.vat.date') }}</th>
                                <th>{{ __('general.pages.reports.purchases.vat.vat_amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_vat = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_vat += $row->vat_amount;
                                @endphp
                                <tr>
                                    <td>{{ dateTimeFormat($row->vat_date, true, false) }}</td>
                                    <td>{{ currencyFormat($row->vat_amount, true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">{{ __('general.pages.reports.purchases.vat.no_data') }}</td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold bg-success bg-opacity-25">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td>{{ currencyFormat($total_vat, true) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
            </div>
        </x-hud.table-card>
    </div>
</div>
@push('scripts')
    @include('layouts.hud.partials.daterange-picker-script')
@endpush
