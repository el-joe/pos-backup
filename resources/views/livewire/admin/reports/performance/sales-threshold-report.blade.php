
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

    <x-admin.table-card title="Sales Threshold (Discount Trigger) Report" icon="fa-bell" :render-table="false">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                    <thead>
                        <tr class="active">
                            <th>Customer</th>
                            <th class="text-right">Sales Threshold</th>
                            <th class="text-right">Total Sales</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sum_sales = 0; @endphp
                        @forelse($report as $row)
                            @php $sum_sales += $row->total_sales; @endphp
                            <tr>
                                <td>{{ $row->customer_name }}</td>
                                <td class="text-right">{{ number_format($row->sales_threshold, 2) }}</td>
                                <td class="text-right">{{ number_format($row->total_sales, 2) }}</td>
                                <td class="text-center">
                                    <span class="label label-{{ $row->status == 'Reached' ? 'success' : 'danger' }}">{{ $row->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No sales threshold data found for the selected period.</td>
                            </tr>
                        @endforelse
                        @if(count($report))
                        <tr style="background:#f1f8e9; font-weight:600;">
                            <td>Total</td>
                            <td></td>
                            <td class="text-right">{{ number_format($sum_sales, 2) }}</td>
                            <td></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
    </x-admin.table-card>
</div>
