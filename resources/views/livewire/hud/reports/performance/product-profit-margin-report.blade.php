<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><i class="glyphicon glyphicon-filter"></i> Filter Options</strong>
        </div>
        <div class="panel-body">
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="from_date" class="col-sm-1 control-label">From</label>
                    <div class="col-sm-2">
                        <input type="date" id="from_date" wire:model.lazy="from_date" class="form-control input-sm">
                    </div>
                    <label for="to_date" class="col-sm-1 control-label">To</label>
                    <div class="col-sm-2">
                        <input type="date" id="to_date" wire:model.lazy="to_date" class="form-control input-sm">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-stats"></i> Product Profit Margin Report</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                    <thead>
                        <tr class="active">
                            <th>Product</th>
                            <th class="text-right">Total Sales</th>
                            <th class="text-right">Total COGS</th>
                            <th class="text-right">Profit</th>
                            <th class="text-right">Profit Margin (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sum_sales = 0;
                            $sum_cogs = 0;
                            $sum_profit = 0;
                        @endphp
                        @forelse($report as $row)
                            @php
                                $sum_sales += $row->total_sales;
                                $sum_cogs += $row->total_cogs;
                                $sum_profit += $row->profit;
                            @endphp
                            <tr>
                                <td>{{ $row->product_name }}</td>
                                <td class="text-right">{{ number_format($row->total_sales, 2) }}</td>
                                <td class="text-right">{{ number_format($row->total_cogs, 2) }}</td>
                                <td class="text-right">{{ number_format($row->profit, 2) }}</td>
                                <td class="text-right"><span class="label label-success">{{ number_format($row->profit_margin_percent, 2) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No data found for the selected period.</td>
                            </tr>
                        @endforelse
                        @if(count($report))
                        <tr style="background:#f1f8e9; font-weight:600;">
                            <td>Total</td>
                            <td class="text-right">{{ number_format($sum_sales, 2) }}</td>
                            <td class="text-right">{{ number_format($sum_cogs, 2) }}</td>
                            <td class="text-right">{{ number_format($sum_profit, 2) }}</td>
                            <td></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
