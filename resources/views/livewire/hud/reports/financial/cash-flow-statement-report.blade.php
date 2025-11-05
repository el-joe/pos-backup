<div class="col-12">
    <!-- Filter Options Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fa fa-filter me-2"></i> Filter Options
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" class="form-control form-control-sm" wire:model.lazy="from_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">To Date</label>
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

    <!-- Cash Flow Statement Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fa fa-usd me-2"></i> Cash Flow Statement
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Account</th>
                            <th>Inflow</th>
                            <th>Outflow</th>
                            <th>Net Cash Flow</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['cash_flows'] ?? [] as $type => $flow)
                        <tr>
                            <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                            <td>{{ number_format($flow['inflow'], 2) }}</td>
                            <td>{{ number_format($flow['outflow'], 2) }}</td>
                            <td>{{ number_format($flow['net'], 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="fw-bold bg-primary-subtle">
                            <td>Total</td>
                            <td>{{ number_format($report['total_inflow'] ?? 0, 2) }}</td>
                            <td>{{ number_format($report['total_outflow'] ?? 0, 2) }}</td>
                            <td>{{ number_format($report['net_cash_flow'] ?? 0, 2) }}</td>
                        </tr>
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
