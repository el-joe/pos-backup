<div class="container-fluid py-3">
    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-secondary text-white d-flex align-items-center">
                <i class="fa fa-filter me-2"></i>
                <strong>{{ __('general.pages.reports.common.filter_options') }}</strong>
            </div>
            <div class="card-body">
                <form class="row g-3 align-items-center">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                        <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                    </div>
                </form>
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>

    <!-- Discount Impact Report -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa fa-tags me-2"></i>
                <h5 class="mb-0">{{ __('general.pages.reports.performance.discount_impact.title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-bordered table-hover table-striped mb-0 align-middle">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.performance.discount_impact.branch') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.discount_impact.total_revenue') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.discount_impact.total_discount') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.discount_impact.discount_percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sum_revenue = 0; $sum_discount = 0; @endphp
                            @forelse($report as $row)
                                @php
                                    $sum_revenue += $row->total_revenue;
                                    $sum_discount += $row->total_discount;
                                @endphp
                                <tr>
                                    <td>{{ $row->branch_name }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_revenue, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_discount, true) }}</td>
                                    <td class="text-end">
                                        @php
                                            $badgeClass = $row->discount_percentage > 10
                                                ? 'bg-danger'
                                                : ($row->discount_percentage > 0 ? 'bg-warning text-dark' : 'bg-success');
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ number_format($row->discount_percentage, 2) }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">{{ __('general.pages.reports.performance.discount_impact.no_data') }}</td>
                                </tr>
                            @endforelse

                            @if(count($report))
                            <tr class="bg-success bg-opacity-25 fw-bold">
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td class="text-end">{{ currencyFormat($sum_revenue, true) }}</td>
                                <td class="text-end">{{ currencyFormat($sum_discount, true) }}</td>
                                <td></td>
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
