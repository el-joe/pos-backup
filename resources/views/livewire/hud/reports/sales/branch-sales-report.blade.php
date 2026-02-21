<div class="container-fluid">
    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <strong><i class="fa fa-filter me-2"></i> {{ __('general.pages.reports.common.filter_options') }}</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                        <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                    </div>
                </div>
            </div>

            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>

    <!-- Branch Sales -->
    <div class="col-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h5 class="mb-0"><i class="fa fa-home me-2"></i> {{ __('general.pages.reports.sales.branch.title') }}</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>{{ __('general.pages.reports.sales.branch.branch') }}</th>
                                <th>{{ __('general.pages.reports.sales.branch.sale_count') }}</th>
                                <th>{{ __('general.pages.reports.sales.branch.total_sales') }}</th>
                                <th>{{ __('general.pages.reports.sales.branch.avg_ticket_size') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_sales = 0;
                                $total_count = 0;
                                $avg_ticket_size = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_sales_record = $row->sales->sum(fn($q) => $q->grand_total_amount);
                                    $total_count_record = $row->sales->count();
                                    $avg_ticket_size_record = $total_count_record ? ($total_sales_record / $total_count_record) : 0;

                                    $total_sales += $total_sales_record;
                                    $total_count += $total_count_record;
                                    $avg_ticket_size += $avg_ticket_size_record;
                                @endphp
                                <tr>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $total_count_record }}</td>
                                    <td>{{ currencyFormat($total_sales_record, true) }}</td>
                                    <td>{{ currencyFormat($avg_ticket_size_record, true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">{{ __('general.pages.reports.sales.branch.no_data') }}</td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="table-success fw-semibold">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td>{{ $total_count }}</td>
                                    <td>{{ currencyFormat($total_sales, true) }}</td>
                                    <td>{{ currencyFormat($avg_ticket_size, true) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    @include('layouts.hud.partials.daterange-picker-script')
@endpush
