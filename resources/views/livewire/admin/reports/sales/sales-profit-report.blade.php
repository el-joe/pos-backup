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
            <h4 class="panel-title"><i class="glyphicon glyphicon-stats"></i> Sales Profit</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Product</th>
                        <th>Sales Revenue</th>
                        <th>COGS</th>
                        <th>Gross Profit</th>
                        <th>Margin (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_revenue = 0;
                        $total_cogs = 0;
                        $total_profit = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_revenue += $row->sales_revenue;
                        $total_cogs += $row->cogs;
                        $total_profit += $row->gross_profit;
                    @endphp
                    <tr>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ number_format($row->sales_revenue, 2) }}</td>
                        <td>{{ number_format($row->cogs, 2) }}</td>
                        <td>{{ number_format($row->gross_profit, 2) }}</td>
                        <td>{{ number_format($row->margin, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No sales profit data found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ number_format($total_revenue, 2) }}</td>
                        <td>{{ number_format($total_cogs, 2) }}</td>
                        <td>{{ number_format($total_profit, 2) }}</td>
                        <td>{{ $total_revenue > 0 ? number_format($total_profit / $total_revenue * 100, 2) : '0.00' }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
