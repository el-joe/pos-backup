<div class="container-fluid">
    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex align-items-center">
                <i class="fa fa-filter me-2"></i>
                <strong>{{ __('general.pages.reports.common.filter_options') }}</strong>
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

    <!-- Purchases Per Period -->
    <div class="col-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa fa-shopping-cart me-2"></i>
                <h5 class="mb-0">{{ __('general.pages.reports.purchases.summary.title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.pages.reports.purchases.summary.date') }}</th>
                                <th>{{ __('general.pages.reports.purchases.summary.purchase_count') }}</th>
                                <th>{{ __('general.pages.reports.purchases.summary.total_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_count = 0;
                                $total_value = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_count += $row['purchase_count'];
                                    $total_value += $row['total_value'];
                                @endphp
                                <tr>
                                    <td>{{ dateTimeFormat($row['purchase_date'], true, false) }}</td>
                                    <td>{{ $row['purchase_count'] }}</td>
                                    <td>{{ currencyFormat($row['total_value'], true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">{{ __('general.pages.reports.purchases.summary.no_data') }}</td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold table-success">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td>{{ $total_count }}</td>
                                    <td>{{ currencyFormat($total_value, true) }}</td>
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
