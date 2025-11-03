<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><i class="glyphicon glyphicon-filter"></i> Filter Options</strong>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>From Date</label>
                    <input type="date" class="form-control input-sm" wire:model.lazy="from_date">
                </div>
                <div class="col-md-3 form-group">
                    <label>To Date</label>
                    <input type="date" class="form-control input-sm" wire:model.lazy="to_date">
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-usd"></i> Cash Flow Statement</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
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
</div>
