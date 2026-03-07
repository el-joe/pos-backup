
<div class="container-fluid">
    <x-admin.filter-card title="Filter Options" icon="fa-filter">
        <div class="row">
            <div class="col-sm-4">
                <label class="control-label" for="from_date">From</label>
                <input type="date" id="from_date" wire:model.lazy="from_date" class="form-control input-sm">
            </div>
            <div class="col-sm-4">
                <label class="control-label" for="to_date">To</label>
                <input type="date" id="to_date" wire:model.lazy="to_date" class="form-control input-sm">
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card title="Supplier Payable Report" icon="fa-briefcase" :render-table="false">
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
    </x-admin.table-card>
</div>
