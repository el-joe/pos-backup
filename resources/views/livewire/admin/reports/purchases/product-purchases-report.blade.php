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

    <x-admin.table-card title="Product Purchases" icon="fa-th-large" :render-table="false">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Product</th>
                        <th>Total Quantity</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_qty = 0;
                        $total_value = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_qty += $row->total_qty;
                        $total_value += $row->total_value;
                    @endphp
                    <tr>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ $row->total_qty }}</td>
                        <td>{{ number_format($row->total_value, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No product purchases found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ $total_qty }}</td>
                        <td>{{ number_format($total_value, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-admin.table-card>
</div>
