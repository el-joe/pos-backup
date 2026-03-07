<div class="container-fluid">
    <x-admin.filter-card title="Filter Options" icon="fa-filter">
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
    </x-admin.filter-card>

    <x-admin.table-card title="Purchase Returns" icon="fa-repeat" :render-table="false">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Purchase Ref</th>
                        <th>Returned Quantity</th>
                        <th>Returned Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_qty = 0;
                        $total_amount = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_qty += $row->returned_qty;
                        $total_amount += $row->returned_amount;
                    @endphp
                    <tr>
                        <td>{{ $row->purchase_ref }}</td>
                        <td>{{ $row->returned_qty }}</td>
                        <td>{{ number_format($row->returned_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No purchase returns found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ $total_qty }}</td>
                        <td>{{ number_format($total_amount, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-admin.table-card>
</div>
