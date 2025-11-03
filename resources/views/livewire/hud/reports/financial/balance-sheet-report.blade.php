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
            <h4 class="panel-title"><i class="glyphicon glyphicon-scale"></i> Balance Sheet</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <tbody>
                    {{-- ================= Assets ================= --}}
                    <tr style="background:#e3f2fd;">
                        <th colspan="2">Assets</th>
                    </tr>
                    @foreach($report['assets'] as $label => $amount)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ number_format($amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr style="background:#bbdefb; font-weight:600;">
                        <td>Total Assets</td>
                        <td>{{ number_format($report['total_assets'] ?? 0, 2) }}</td>
                    </tr>

                    {{-- ================= Liabilities ================= --}}
                    <tr style="background:#f3e5f5;">
                        <th colspan="2">Liabilities</th>
                    </tr>
                    @foreach($report['liabilities'] as $label => $amount)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ number_format($amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr style="background:#e1bee7; font-weight:600;">
                        <td>Total Liabilities</td>
                        <td>{{ number_format($report['total_liabilities'] ?? 0, 2) }}</td>
                    </tr>

                    {{-- ================= Equity ================= --}}
                    <tr style="background:#e8f5e8;">
                        <th colspan="2">Equity</th>
                    </tr>
                    @foreach($report['equity'] as $label => $amount)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ number_format($amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr style="background:#c8e6c9; font-weight:600;">
                        <td>Total Equity</td>
                        <td>{{ number_format($report['total_equity'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
