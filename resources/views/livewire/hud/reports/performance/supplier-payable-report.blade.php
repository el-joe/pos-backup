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
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">{{ __('general.pages.reports.common.from') }}</label>
                        <input type="date" id="from_date" wire:model.lazy="from_date" class="form-control form-control-sm bg-dark text-light border-secondary">
                    </div>
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">{{ __('general.pages.reports.common.to') }}</label>
                        <input type="date" id="to_date" wire:model.lazy="to_date" class="form-control form-control-sm bg-dark text-light border-secondary">
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

    <!-- Supplier Payable Report Card -->
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-dark text-light">
            <div class="card-header d-flex align-items-center">
                <i class="fa fa-briefcase me-2"></i>
                <h5 class="card-title mb-0">{{ __('general.pages.reports.performance.supplier_payable.title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover align-middle mb-0">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.common.supplier') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.customer_outstanding.total_debit') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.customer_outstanding.total_credit') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.supplier_payable.payable_balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sum_balance = 0;
                                $total_debit = 0;
                                $total_credit = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $sum_balance += $row->balance;
                                    $total_debit += $row->total_debit;
                                    $total_credit += $row->total_credit;
                                @endphp
                                <tr>
                                    <td>{{ $row->supplier_name }}</td>
                                    <td class="text-end">{{ number_format($row->total_debit, 2) }}</td>
                                    <td class="text-end">{{ number_format($row->total_credit, 2) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $row->balance > 0 ? 'danger' : 'success' }}">
                                            {{ number_format($row->balance, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">{{ __('general.pages.reports.performance.supplier_payable.no_data') }}</td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold table-success text-dark">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td class="text-end">{{ number_format($total_debit, 2) }}</td>
                                    <td class="text-end">{{ number_format($total_credit, 2) }}</td>
                                    <td class="text-end">{{ number_format($sum_balance, 2) }}</td>
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
