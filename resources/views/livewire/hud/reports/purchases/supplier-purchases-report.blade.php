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
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.reports.common.from_date') }}</label>
                        <input type="date" class="form-control form-control-sm" wire:model.lazy="from_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.reports.common.to_date') }}</label>
                        <input type="date" class="form-control form-control-sm" wire:model.lazy="to_date">
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

    <!-- Supplier Purchases -->
    <div class="col-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa fa-road me-2"></i>
                <h5 class="mb-0">{{ __('general.pages.reports.purchases.supplier.title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.pages.reports.purchases.supplier.supplier') }}</th>
                                <th>{{ __('general.pages.reports.purchases.supplier.total_quantity') }}</th>
                                <th>{{ __('general.pages.reports.purchases.supplier.total_value') }}</th>
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
                                    <td>{{ $row->supplier_name }}</td>
                                    <td>{{ $row->total_qty }}</td>
                                    <td>{{ currency()->symbol }}{{ number_format($row->total_value, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        {{ __('general.pages.reports.purchases.supplier.no_data') }}
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold table-success">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td>{{ $total_qty }}</td>
                                    <td>{{ currency()->symbol }}{{ number_format($total_value, 2) }}</td>
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
