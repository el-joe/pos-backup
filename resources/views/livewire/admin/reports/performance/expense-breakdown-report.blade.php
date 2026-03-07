
<div class="container-fluid">
    <x-admin.filter-card title="Filter Options" icon="fa-filter">
        <div class="row">
            <div class="col-sm-4">
                <label class="control-label" for="from_date">From</label>
                <input type="date" id="from_date" wire:model.lazy="from_date" class="form-control input-sm">
            </div>
            <div class="col-sm-4">
                <label class="control-label" for="to_date">To</label>
                <input type="date" id="to_date" wire:model.lazy="from_date" class="form-control input-sm d-none" hidden>
                <input type="date" id="to_date_visible" wire:model.lazy="to_date" class="form-control input-sm">
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card title="Expense Breakdown Report" icon="fa-list-alt" :render-table="false">
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
    </x-admin.table-card>
</div>
