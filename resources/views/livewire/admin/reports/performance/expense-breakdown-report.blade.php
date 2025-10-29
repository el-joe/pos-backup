
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
            <h4 class="panel-title"><i class="glyphicon glyphicon-list-alt"></i> Expense Breakdown Report</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                    <thead>
                        <tr class="active">
                            <th>Category</th>
                            <th class="text-right">Total Debit</th>
                            <th class="text-right">Total Credit</th>
                            <th class="text-right">Net</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sum_debit = 0; $sum_credit = 0; $sum_net = 0; @endphp
                        @forelse($report as $row)
                            @php
                                $sum_debit += $row->total_debit;
                                $sum_credit += $row->total_credit;
                                $net = $row->total_debit - $row->total_credit;
                                $sum_net += $net;
                            @endphp
                            <tr>
                                <td>{{ $row->category_name }}</td>
                                <td class="text-right">{{ number_format($row->total_debit, 2) }}</td>
                                <td class="text-right">{{ number_format($row->total_credit, 2) }}</td>
                                <td class="text-right">
                                    <span class="label label-{{ $net > 0 ? 'danger' : ($net < 0 ? 'success' : 'default') }}">
                                        {{ number_format($net, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No expense data found for the selected period.</td>
                            </tr>
                        @endforelse
                        @if(count($report))
                        <tr style="background:#f1f8e9; font-weight:600;">
                            <td>Total</td>
                            <td class="text-right">{{ number_format($sum_debit, 2) }}</td>
                            <td class="text-right">{{ number_format($sum_credit, 2) }}</td>
                            <td class="text-right">{{ number_format($sum_net, 2) }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
