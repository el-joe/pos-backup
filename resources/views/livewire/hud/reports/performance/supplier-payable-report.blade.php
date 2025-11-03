
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
            <h4 class="panel-title"><i class="glyphicon glyphicon-briefcase"></i> Supplier Payable Report</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                    <thead>
                        <tr class="active">
                            <th>Supplier</th>
                            <th class="text-right">Total Debit</th>
                            <th class="text-right">Total Credit</th>
                            <th class="text-right">Payable Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sum_balance = 0; $total_debit = 0; $total_credit = 0; @endphp
                        @forelse($report as $row)
                            @php $sum_balance += $row->balance;$total_debit += $row->total_debit;$total_credit += $row->total_credit; @endphp
                            <tr>
                                <td>{{ $row->supplier_name }}</td>
                                <td class="text-right">{{ number_format($row->total_debit, 2) }}</td>
                                <td class="text-right">{{ number_format($row->total_credit, 2) }}</td>
                                <td class="text-right">
                                    <span class="label label-{{ $row->balance > 0 ? 'danger' : 'success' }}">
                                        {{ number_format($row->balance, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No payables found for the selected period.</td>
                            </tr>
                        @endforelse
                        @if(count($report))
                        <tr style="background:#f1f8e9; font-weight:600;">
                            <td>Total</td>
                            <td class="text-right">{{ number_format($total_debit, 2) }}</td>
                            <td class="text-right">{{ number_format($total_credit, 2) }}</td>
                            <td class="text-right">{{ number_format($sum_balance, 2) }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
