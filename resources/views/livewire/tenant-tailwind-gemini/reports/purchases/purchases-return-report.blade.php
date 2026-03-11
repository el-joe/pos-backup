<div class="container-fluid">
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
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.purchases.return.title')" icon="fa-repeat" :render-table="false">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                        <thead >
                            <tr>
                                <th>{{ __('general.pages.reports.purchases.return.purchase_ref') }}</th>
                                <th>{{ __('general.pages.reports.purchases.return.returned_quantity') }}</th>
                                <th>{{ __('general.pages.reports.purchases.return.returned_amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_qty = 0;
                                $total_amount = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_qty += $row->returned_qty;
                                    $total_amount += $row->returned_amount;
                                @endphp
                                <tr>
                                    <td>{{ $row->purchase_ref }}</td>
                                    <td>{{ $row->returned_qty }}</td>
                                    <td>{{ currencyFormat($row->returned_amount, true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        {{ __('general.pages.reports.purchases.return.no_data') }}
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="bg-emerald-50 font-semibold text-slate-900">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td>{{ $total_qty }}</td>
                                    <td>{{ currencyFormat($total_amount, true) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
            </div>
        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
