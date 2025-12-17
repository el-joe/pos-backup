<div class="col-12">
    <!-- Filter Options Card -->
    <div class="card shadow-sm border-0 bg-dark text-light mb-4">
        <div class="card-header">
            <strong><i class="fa fa-filter me-2"></i> {{ __('general.pages.reports.common.filter_options') }}</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('general.pages.reports.common.from') }}</label>
                    <input type="date" class="form-control form-control-sm bg-secondary text-light border-0" wire:model.defer="from_date">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('general.pages.reports.common.to') }}</label>
                    <input type="date" class="form-control form-control-sm bg-secondary text-light border-0" wire:model.defer="to_date">
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-end">
                    <button wire:click="resetDates" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-refresh me-1"></i> {{ __('general.pages.reports.common.reset') }}
                    </button>
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

    <!-- Withholding Tax Card -->
    <div class="card shadow-sm border-0 bg-dark text-light mb-4">
        <div class="card-header d-flex align-items-center">
            <h4 class="mb-0">
                <i class="fa fa-list-alt me-2"></i> {{ __('general.pages.reports.tax.withholding_tax.title') }}
            </h4>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 table-dark align-middle">
                    <thead class="table-primary text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.tax.withholding_tax.id') }}</th>
                                <th>{{ __('general.pages.reports.tax.withholding_tax.account') }}</th>
                                <th>{{ __('general.pages.reports.tax.withholding_tax.type') }}</th>
                                <th>{{ __('general.pages.reports.tax.withholding_tax.supplier_customer') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.tax.withholding_tax.withholding_amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @forelse($report as $row)
                            @php $total += $row->withholding_amount; @endphp
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->account_name }}</td>
                                <td>{{ $row->party_type }}</td>
                                <td>{{ $row->party_name ?? 'N/A' }}</td>
                                <td class="text-end">{{ currency()->symbol }}{{ number_format($row->withholding_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('general.pages.reports.tax.withholding_tax.no_data') }}</td>
                            </tr>
                        @endforelse

                        @if(count($report))
                            <tr class="bg-success bg-opacity-25 fw-semibold">
                                <td colspan="3">{{ __('general.pages.reports.common.total') }}</td>
                                <td colspan="2" class="text-end">{{ currency()->symbol }}{{ number_format($total, 2) }}</td>
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
