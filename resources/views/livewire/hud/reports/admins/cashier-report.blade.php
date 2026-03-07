<div class="container-fluid py-3">
    <x-hud.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-sm-6">
                <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
            </div>
        </div>
    </x-hud.filter-card>

    <div class="col-12">
        <x-hud.table-card :title="__('general.pages.reports.admins.cashier_report.title')" icon="fa-user" :render-table="false">
                <div class="table-responsive">
                    <table class="table table-dark table-bordered table-hover table-striped mb-0 align-middle">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.admins.cashier_report.cashier') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.admins.cashier_report.total_sales') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.admins.cashier_report.total_refunds') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.admins.cashier_report.total_discounts') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.admins.cashier_report.net_sales') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sumSales = 0;
                                $sumRefunds = 0;
                                $sumDiscounts = 0;
                                $sumNet = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $sumSales += $row->total_sales;
                                    $sumRefunds += $row->total_refunds;
                                    $sumDiscounts += $row->total_discounts;
                                    $sumNet += $row->net_sales;
                                @endphp
                                <tr>
                                    <td>{{ $row->cashier }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_sales, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_refunds, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_discounts, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->net_sales, true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">{{ __('general.pages.reports.admins.cashier_report.no_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if(count($report))
                        <tfoot class="bg-success bg-opacity-25 fw-bold">
                            <tr>
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td class="text-end">{{ currencyFormat($sumSales, true) }}</td>
                                <td class="text-end">{{ currencyFormat($sumRefunds, true) }}</td>
                                <td class="text-end">{{ currencyFormat($sumDiscounts, true) }}</td>
                                <td class="text-end">{{ currencyFormat($sumNet, true) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
        </x-hud.table-card>
    </div>
</div>
@push('scripts')
    @include('layouts.hud.partials.daterange-picker-script')
@endpush
