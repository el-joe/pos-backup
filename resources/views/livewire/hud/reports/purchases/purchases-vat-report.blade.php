<div class="container-fluid">
    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0 bg-dark text-light">
            <div class="card-header d-flex align-items-center">
                <i class="fa fa-filter me-2 text-info"></i>
                <strong>{{ __('general.pages.reports.common.filter_options') }}</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.reports.common.from_date') }}</label>
                        <input type="date" class="form-control form-control-sm bg-dark text-light border-secondary" wire:model.lazy="from_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.reports.common.to_date') }}</label>
                        <input type="date" class="form-control form-control-sm bg-dark text-light border-secondary" wire:model.lazy="to_date">
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

    <!-- VAT Receivable Transactions -->
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-dark text-light">
            <div class="card-header d-flex align-items-center">
                <i class="fa fa-file-invoice-dollar me-2 text-success"></i>
                <h5 class="mb-0">{{ __('general.pages.reports.purchases.vat.title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-striped align-middle mb-0">
                        <thead class="table-light text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.purchases.vat.date') }}</th>
                                <th>{{ __('general.pages.reports.purchases.vat.vat_amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_vat = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_vat += $row->vat_amount;
                                @endphp
                                <tr>
                                    <td>{{ $row->vat_date }}</td>
                                    <td>{{ currency()->symbol }}{{ number_format($row->vat_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">{{ __('general.pages.reports.purchases.vat.no_data') }}</td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold bg-success bg-opacity-25">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td>{{ currency()->symbol }}{{ number_format($total_vat, 2) }}</td>
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
