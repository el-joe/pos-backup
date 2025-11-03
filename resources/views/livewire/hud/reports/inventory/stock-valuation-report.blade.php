<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-copyright-mark"></i> Stock Valuation</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Branch</th>
                        <th>Product (Unit)</th>
                        <th>Stock Quantity</th>
                        <th>Unit Cost</th>
                        <th>Stock Value</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_qty = 0;
                        $total_value = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_qty += $row->stock_qty;
                        $total_value += $row->stock_value;
                    @endphp
                    <tr>
                        <td>{{ $row->branch_name ?? 'N/A' }}</td>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ $row->stock_qty }}</td>
                        <td>{{ number_format($row->unit_cost, 2) }}</td>
                        <td>{{ number_format($row->stock_value, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No stock found.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td colspan="2">Total</td>
                        <td>{{ $total_qty }}</td>
                        <td></td>
                        <td>{{ number_format($total_value, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
