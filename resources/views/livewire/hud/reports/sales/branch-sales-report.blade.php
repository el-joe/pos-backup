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

    <!-- Branch Sales -->
    <div class="col-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h5 class="mb-0"><i class="fa fa-home me-2"></i> Branch Sales</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Branch</th>
                                <th>Sale Count</th>
                                <th>Total Sales</th>
                                <th>Avg Ticket Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_sales = 0;
                                $total_count = 0;
                                $avg_ticket_size = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_sales_record = $row->sales->sum(fn($q) => $q->grand_total_amount);
                                    $total_count_record = $row->sales->count();
                                    $avg_ticket_size_record = $total_count_record ? ($total_sales_record / $total_count_record) : 0;

                                    $total_sales += $total_sales_record;
                                    $total_count += $total_count_record;
                                    $avg_ticket_size += $avg_ticket_size_record;
                                @endphp
                                <tr>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $total_count_record }}</td>
                                    <td>{{ number_format($total_sales_record, 2) }}</td>
                                    <td>{{ number_format($avg_ticket_size_record, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No branch sales found for selected period.</td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="table-success fw-semibold">
                                    <td>Total</td>
                                    <td>{{ $total_count }}</td>
                                    <td>{{ number_format($total_sales, 2) }}</td>
                                    <td>{{ number_format($avg_ticket_size, 2) }}</td>
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
