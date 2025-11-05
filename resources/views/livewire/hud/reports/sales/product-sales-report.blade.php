<div class="container-fluid">
    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <strong><i class="fa fa-filter me-2"></i> Filter Options</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control form-control-sm" wire:model.lazy="from_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
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

    <!-- Product Sales -->
    <div class="col-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h5 class="mb-0"><i class="fa fa-th-large me-2"></i> Product Sales</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Product</th>
                                <th>Quantity Sold</th>
                                <th>Total Cost</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_qty = 0;
                                $total_revenue = 0;
                                $total_cost = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_qty += $row->quantity_sold;
                                    $total_revenue += $row->total_revenue;
                                    $total_cost += $row->total_cost;
                                @endphp
                                <tr>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ number_format($row->quantity_sold, 0) }}</td>
                                    <td>{{ number_format($row->total_cost, 2) }}</td>
                                    <td>{{ number_format($row->total_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No product sales found for selected period.</td>
                                </tr>
                            @endforelse

                            @if(count($report))
                                <tr class="table-success fw-semibold">
                                    <td>Total</td>
                                    <td>{{ number_format($total_qty, 0) }}</td>
                                    <td>{{ number_format($total_cost, 2) }}</td>
                                    <td>{{ number_format($total_revenue, 2) }}</td>
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
