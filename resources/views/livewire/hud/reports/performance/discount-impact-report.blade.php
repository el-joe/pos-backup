
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
            <h4 class="panel-title"><i class="glyphicon glyphicon-tags"></i> Discount Impact Report</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                    <thead>
                        <tr class="active">
                            <th>Branch</th>
                            <th class="text-right">Total Revenue</th>
                            <th class="text-right">Total Discount</th>
                            <th class="text-right">Discount %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sum_revenue = 0; $sum_discount = 0; @endphp
                        @forelse($report as $row)
                            @php
                                $sum_revenue += $row->total_revenue;
                                $sum_discount += $row->total_discount;
                            @endphp
                            <tr>
                                <td>{{ $row->branch_name }}</td>
                                <td class="text-right">{{ number_format($row->total_revenue, 2) }}</td>
                                <td class="text-right">{{ number_format($row->total_discount, 2) }}</td>
                                <td class="text-right">
                                    <span class="label label-{{ $row->discount_percentage > 10 ? 'danger' : ($row->discount_percentage > 0 ? 'warning' : 'success') }}">
                                        {{ number_format($row->discount_percentage, 2) }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No discount data found for the selected period.</td>
                            </tr>
                        @endforelse
                        @if(count($report))
                        <tr style="background:#f1f8e9; font-weight:600;">
                            <td>Total</td>
                            <td class="text-right">{{ number_format($sum_revenue, 2) }}</td>
                            <td class="text-right">{{ number_format($sum_discount, 2) }}</td>
                            <td></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
