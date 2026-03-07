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

    <x-admin.table-card title="Purchases Per Period" icon="fa-shopping-cart" :render-table="false">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Date</th>
                        <th>Purchase Count</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_count = 0;
                        $total_value = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_count += $row['purchase_count'];
                        $total_value += $row['total_value'];
                    @endphp
                    <tr>
                        <td>{{ $row['purchase_date'] }}</td>
                        <td>{{ $row['purchase_count'] }}</td>
                        <td>{{ number_format($row['total_value'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No purchases found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ $total_count }}</td>
                        <td>{{ number_format($total_value, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-admin.table-card>
</div>
