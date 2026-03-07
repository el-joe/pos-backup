
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

    <x-admin.table-card title="Revenue Breakdown by Branch" icon="fa-signal" :render-table="false">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                    <thead>
                        <tr class="active">
                            <th>Branch</th>
                            <th class="text-right">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sum_revenue = 0; @endphp
                        @forelse($report as $row)
                            @php $sum_revenue += $row->total_revenue; @endphp
                            <tr>
                                <td>{{ $row->branch_name }}</td>
                                <td class="text-right">
                                    <span class="label label-success">{{ number_format($row->total_revenue, 2) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No revenue data found for the selected period.</td>
                            </tr>
                        @endforelse
                        @if(count($report))
                        <tr style="background:#f1f8e9; font-weight:600;">
                            <td>Total</td>
                            <td class="text-right">{{ number_format($sum_revenue, 2) }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
    </x-admin.table-card>
</div>
