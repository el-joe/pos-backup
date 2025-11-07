<div class="container-fluid">
    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex align-items-center">
                <i class="fa fa-filter me-2"></i>
                <strong>Filter Options</strong>
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

    <!-- Purchases Per Period -->
    <div class="col-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa fa-shopping-cart me-2"></i>
                <h5 class="mb-0">Purchases Per Period</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Purchase Count</th>
                                <th>Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_count = 0;
                                $total_value = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_count += $row['purchase_count'];
                                    $total_value += $row['total_value'];
                                @endphp
                                <tr>
                                    <td>{{ $row['purchase_date'] }}</td>
                                    <td>{{ $row['purchase_count'] }}</td>
                                    <td>{{ number_format($row['total_value'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No purchases found for selected period.</td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold table-success">
                                    <td>Total</td>
                                    <td>{{ $total_count }}</td>
                                    <td>{{ number_format($total_value, 2) }}</td>
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
