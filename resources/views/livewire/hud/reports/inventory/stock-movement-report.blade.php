<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-transfer"></i> Item Inflow / Outflow / Adjustments</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Product</th>
                        <th>Inflow (Purchases)</th>
                        <th>Outflow (Sales)</th>
                        <th>Adjustments</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_inflow = 0;
                        $total_outflow = 0;
                        $total_adjustment = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_inflow += $row['inflow'];
                        $total_outflow += $row['outflow'];
                        $total_adjustment += $row['adjustment'];
                    @endphp
                    <tr>
                        <td>{{ $row['product_name'] }}</td>
                        <td>{{ $row['inflow'] }}</td>
                        <td>{{ $row['outflow'] }}</td>
                        <td>{{ $row['adjustment'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No stock movement found.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ $total_inflow }}</td>
                        <td>{{ $total_outflow }}</td>
                        <td>{{ $total_adjustment }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
