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
            <h4 class="panel-title"><i class="glyphicon glyphicon-home"></i> Branch Sales</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Branch</th>
                        <th>Sale Count</th>
                        <th>Total Sales</th>
                        <th>Avg Ticket Size</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_sales = 0;
                        $total_count = 0;
                        $avg_ticket_size = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_sales_record = $row->sales->sum(fn($q)=>$q->grand_total_amount);
                        $total_count_record = $row->sales->count();
                        $avg_ticket_size_record = $total_count_record ? ($total_sales_record / $total_count_record) : 0;

                        $total_sales += $total_sales_record;
                        $total_count += $total_count_record;
                        $avg_ticket_size += $avg_ticket_size_record;
                    @endphp
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $total_count_record }}</td>
                        <td>{{ number_format($total_sales_record, 2) }}</td>
                        <td>{{ number_format($avg_ticket_size_record, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No branch sales found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ $total_count }}</td>
                        <td>{{ number_format($total_sales, 2) }}</td>
                        <td>{{ number_format($avg_ticket_size, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
