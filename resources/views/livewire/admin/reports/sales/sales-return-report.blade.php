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

    <x-admin.table-card title="Sales Returns" icon="fa-undo" :render-table="false">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Return Count</th>
                        <th>Return Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_count = 0;
                        $total_amount = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_count += $row->return_count;
                        $total_amount += $row->return_amount;
                    @endphp
                    <tr>
                        <td>{{ $row->invoice_number }}</td>
                        <td>{{ $row->customer_name }}</td>
                        <td>{{ $row->return_count }}</td>
                        <td>{{ number_format($row->return_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No sales returns found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td></td>
                        <td>{{ $total_count }}</td>
                        <td>{{ number_format($total_amount, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-admin.table-card>
</div>
