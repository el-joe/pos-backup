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
        <x-hud.table-card :title="__('general.pages.reports.purchases.product.title')" icon="fa-th-large" :render-table="false">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.pages.reports.purchases.product.product') }}</th>
                                <th>{{ __('general.pages.reports.purchases.product.total_quantity') }}</th>
                                <th>{{ __('general.pages.reports.purchases.product.total_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_qty = 0;
                                $total_value = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_qty += $row->total_qty;
                                    $total_value += $row->total_value;
                                @endphp
                                <tr>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ $row->total_qty }}</td>
                                    <td>{{ currencyFormat($row->total_value, true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        {{ __('general.pages.reports.purchases.product.no_data') }}
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold table-success">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td>{{ $total_qty }}</td>
                                    <td>{{ currencyFormat($total_value, true) }}</td>
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
