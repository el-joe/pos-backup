<div class="container-fluid">
    <div class="row">
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
            <x-hud.table-card :title="__('general.pages.reports.sales.return.title')" icon="fa-undo" :render-table="false">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover mb-0 align-middle">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>{{ __('general.pages.reports.sales.return.invoice') }}</th>
                                    <th>{{ __('general.pages.reports.sales.return.customer') }}</th>
                                    <th>{{ __('general.pages.reports.sales.return.return_count') }}</th>
                                    <th>{{ __('general.pages.reports.sales.return.return_amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_count = 0;
                                    $total_amount = 0;
                                @endphp
                                @forelse($report as $row)
                                    @php
                                        $total_count += $row->return_count;
                                        $total_amount += $row->return_amount;
                                    @endphp
                                    <tr>
                                        <td>{{ $row->invoice_number }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->return_count }}</td>
                                        <td>{{ currencyFormat($row->return_amount, true) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            {{ __('general.pages.reports.sales.return.no_data') }}
                                        </td>
                                    </tr>
                                @endforelse
                                @if(count($report))
                                    <tr class="bg-success text-dark fw-bold">
                                        <td>{{ __('general.pages.reports.common.total') }}</td>
                                        <td></td>
                                        <td>{{ $total_count }}</td>
                                        <td>{{ currencyFormat($total_amount, true) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                </div>
            </x-hud.table-card>
        </div>
    </div>
</div>
@push('scripts')
    @include('layouts.hud.partials.daterange-picker-script')
@endpush
