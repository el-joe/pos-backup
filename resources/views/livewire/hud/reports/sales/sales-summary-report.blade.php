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
                <div class="col-md-3 form-group">
                    <label>Period</label>
                    <select class="form-control input-sm" wire:model.lazy="period">
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-stats"></i> Sales Summary</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                                        {{-- 'period' => $row->period,
                'gross_sales' => $row->gross_sales ?? 0,
                'discount' => $row->discount ?? 0,
                'sales_return' => $row->sales_return ?? 0,
                'net_sales' => $row->net_sales ?? 0,
                'vat_payable' => $row->vat_payable ?? 0,
                'total_collected' => $row->total_collected ?? 0,
                'cogs' => $row->cogs ?? 0,
                'gross_profit' => $row->gross_profit ?? 0, --}}

                        <th>Period</th>
                        <th>Gross Sales</th>
                        <th>Discount</th>
                        <th>Sales Return</th>
                        <th>Net Sales</th>
                        <th>VAT Payable</th>
                        <th>Total Collected</th>
                        <th>COGS</th>
                        <th>Gross Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $row)
                    <tr>
                        <td>{{ $row['period'] }}</td>
                        <td>{{ number_format($row['gross_sales'], 2) }}</td>
                        <td>-{{ number_format($row['discount'], 2) }}</td>
                        <td>-{{ number_format($row['sales_return'], 2) }}</td>
                        <td><span class="label label-success">{{ number_format($row['net_sales'], 2) }}</span></td>
                        <td>{{ number_format($row['vat_payable'], 2) }}</td>
                        <td>{{ number_format($row['total_collected'], 2) }}</td>
                        <td>{{ number_format($row['cogs'], 2) }}</td>
                        <td>{{ number_format($row['gross_profit'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No sales data found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ number_format(collect($report)->sum('gross_sales'), 2) }}</td>
                        <td>-{{ number_format(collect($report)->sum('discount'), 2) }}</td>
                        <td>-{{ number_format(collect($report)->sum('sales_return'), 2) }}</td>
                        <td><span class="label label-success">{{ number_format(collect($report)->sum('net_sales'), 2) }}</span></td>
                        <td>{{ number_format(collect($report)->sum('vat_payable'), 2) }}</td>
                        <td>{{ number_format(collect($report)->sum('total_collected'), 2) }}</td>
                        <td>{{ number_format(collect($report)->sum('cogs'), 2) }}</td>
                        <td>{{ number_format(collect($report)->sum('gross_profit'), 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
