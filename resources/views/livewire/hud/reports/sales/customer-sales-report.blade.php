<div class="container-fluid">
    <div class="row">
        <!-- Filter Options Card -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 bg-dark text-light">
                <div class="card-header d-flex align-items-center">
                    <i class="fa fa-filter me-2"></i>
                    <strong>Filter Options</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-light">From Date</label>
                            <input type="date" class="form-control form-control-sm bg-dark text-light border-secondary" wire:model.lazy="from_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-light">To Date</label>
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

        <!-- Customer Sales Card -->
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-dark text-light">
                <div class="card-header d-flex align-items-center">
                    <i class="fa fa-user me-2"></i>
                    <h5 class="mb-0">Customer Sales</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover mb-0 align-middle">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>Customer</th>
                                    <th>Sale Count</th>
                                    <th>Total Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_spent = 0;
                                    $total_count = 0;
                                @endphp
                                @forelse($report as $row)
                                    @php
                                        $total_spent += $row->total_spent;
                                        $total_count += $row->sale_count;
                                    @endphp
                                    <tr>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->sale_count }}</td>
                                        <td>{{ number_format($row->total_spent, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            No customer sales found for selected period.
                                        </td>
                                    </tr>
                                @endforelse
                                @if(count($report))
                                    <tr class="bg-success text-dark fw-bold">
                                        <td>Total</td>
                                        <td>{{ $total_count }}</td>
                                        <td>{{ number_format($total_spent, 2) }}</td>
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
</div>
