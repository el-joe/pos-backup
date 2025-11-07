<div class="container-fluid py-3">
    <!-- Filter Options Card -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-secondary text-white d-flex align-items-center">
                <i class="fa fa-filter me-2"></i>
                <strong>Filter Options</strong>
            </div>
            <div class="card-body">
                <form class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label for="from_date" class="form-label mb-1">From</label>
                        <input type="date" id="from_date" wire:model.lazy="from_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label for="to_date" class="form-label mb-1">To</label>
                        <input type="date" id="to_date" wire:model.lazy="to_date" class="form-control form-control-sm">
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

    <!-- Revenue Breakdown Report -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa fa-signal me-2"></i>
                <h5 class="mb-0">Revenue Breakdown by Branch</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-bordered table-hover table-striped mb-0 align-middle">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th>Branch</th>
                                <th class="text-end">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sum_revenue = 0; @endphp
                            @forelse($report as $row)
                                @php $sum_revenue += $row->total_revenue; @endphp
                                <tr>
                                    <td>{{ $row->branch_name }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-success">{{ number_format($row->total_revenue, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No revenue data found for the selected period.</td>
                                </tr>
                            @endforelse

                            @if(count($report))
                            <tr class="bg-success bg-opacity-25 fw-bold">
                                <td>Total</td>
                                <td class="text-end">{{ number_format($sum_revenue, 2) }}</td>
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
