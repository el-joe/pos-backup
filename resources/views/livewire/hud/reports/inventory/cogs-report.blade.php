<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-shopping-cart"></i> Cost of Goods Sold</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Branch</th>
                        <th>COGS Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_cogs = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_cogs += $row->cogs_amount;
                    @endphp
                    <tr>
                        <td>{{ $row->branch_name }}</td>
                        <td>{{ number_format($row->cogs_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center">No COGS data found.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ number_format($total_cogs, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
