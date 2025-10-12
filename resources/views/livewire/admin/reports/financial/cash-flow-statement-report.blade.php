
<div class="white-box">
    <h3 class="box-title">Cash Flow Statement Report</h3>

    <div class="row mb-4">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" class="form-control" wire:model.lazy="from_date">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" class="form-control" wire:model.lazy="to_date">
        </div>
    </div>

    <div class="card section-card">
        <div class="card-body">
            <h4 class="section-title"><i class="fa fa-money"></i> Cash Flow Statement</h4>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
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
                    <tr style="background:#bbdefb; font-weight:600;">
                        <td>Total</td>
                        <td>{{ number_format($report['total_inflow'] ?? 0, 2) }}</td>
                        <td>{{ number_format($report['total_outflow'] ?? 0, 2) }}</td>
                        <td>{{ number_format($report['net_cash_flow'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
.white-box {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 30px 28px;
    margin-top: 20px;
}
.section-title {
    font-size: 22px;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 2px solid #f1f1f1;
    padding-bottom: 10px;
}
.section-card {
    border-radius: 16px;
    margin-top: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    background: #fff;
}
</style>
@endpush
