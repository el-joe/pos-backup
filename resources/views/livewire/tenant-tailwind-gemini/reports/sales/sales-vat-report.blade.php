<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                        <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                    </div>
                </div>
            </x-tenant-tailwind-gemini.filter-card>
        </div>

        <div class="col-12">
            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.sales.vat.title')" icon="fa-file-invoice-dollar" :render-table="false">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover mb-0 align-middle">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>{{ __('general.pages.reports.sales.vat.invoice') }}</th>
                                    <th>{{ __('general.pages.reports.sales.vat.customer') }}</th>
                                    <th>{{ __('general.pages.reports.sales.vat.sale_date') }}</th>
                                    <th>{{ __('general.pages.reports.sales.vat.vat_payable') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_vat = 0;
                                @endphp
                                @forelse($report as $row)
                                    @php
                                        $total_vat += $row->vat_payable;
                                    @endphp
                                    <tr>
                                        <td>{{ $row->invoice_number }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ dateTimeFormat($row->sale_date, true, false) }}</td>
                                        <td>{{ currencyFormat($row->vat_payable, true) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            {{ __('general.pages.reports.sales.vat.no_data') }}
                                        </td>
                                    </tr>
                                @endforelse
                                @if(count($report))
                                    <tr class="bg-success text-dark fw-bold">
                                        <td>{{ __('general.pages.reports.common.total') }}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ currencyFormat($total_vat, true) }}</td>
                                    </tr>
                                @endif
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
