<div class="container-fluid">
    <!-- Filter Options Card -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0 bg-dark text-light">
            <div class="card-header d-flex align-items-center">
                <i class="fa fa-filter me-2"></i>
                <strong>{{ __('general.pages.reports.common.filter_options') }}</strong>
            </div>
            <div class="card-body">
                <form class="row g-3">
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

    <!-- Expense Breakdown Report Card -->
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-dark text-light">
            <div class="card-header d-flex align-items-center">
                <i class="fa fa-list-alt me-2"></i>
                <h5 class="card-title mb-0">{{ __('general.pages.reports.performance.expense_breakdown.title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover align-middle mb-0">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.common.category') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.customer_outstanding.total_debit') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.customer_outstanding.total_credit') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.common.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sum_debit = 0;
                                $sum_credit = 0;
                                $sum_net = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $sum_debit += $row->total_debit;
                                    $sum_credit += $row->total_credit;
                                    $net = $row->total_debit - $row->total_credit;
                                    $sum_net += $net;
                                @endphp
                                <tr>
                                    <td>{{ $row->category_name }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_debit, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_credit, true) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $net > 0 ? 'danger' : ($net < 0 ? 'success' : 'secondary') }}">
                                            {{ currencyFormat($net, true) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        {{ __('general.pages.reports.performance.expense_breakdown.no_data') }}
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold table-success text-dark">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td class="text-end">{{ currencyFormat($sum_debit, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($sum_credit, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($sum_net, true) }}</td>
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
