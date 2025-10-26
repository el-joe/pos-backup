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
            <h4 class="panel-title"><i class="glyphicon glyphicon-th-large"></i> Product Sales</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
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
                        <td colspan="4" class="text-center">No product sales found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
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
    </div>
</div>
