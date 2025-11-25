<div class="col-12">
    <div class="card shadow-sm border-0 bg-dark text-light mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="mb-0">
                <i class="fa fa-shopping-cart me-2"></i> {{ __('general.pages.reports.inventory.cogs.title') }}
            </h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 table-dark align-middle">
                    <thead class="table-primary text-dark">
                        <tr>
                            <th>{{ __('general.pages.reports.inventory.cogs.branch') }}</th>
                            <th>{{ __('general.pages.reports.inventory.cogs.cogs_amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_cogs = 0;
                        @endphp
                        @forelse($report as $row)
                            @php
                                $total_cogs += $row->cogs_amount;
                            @endphp
                            <tr>
                                <td>{{ $row->branch_name }}</td>
                                <td>{{ number_format($row->cogs_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">{{ __('general.pages.reports.inventory.cogs.no_data') }}</td>
                            </tr>
                        @endforelse
                        @if(count($report))
                            <tr class="bg-success bg-opacity-25 fw-semibold">
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td>{{ number_format($total_cogs, 2) }}</td>
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
