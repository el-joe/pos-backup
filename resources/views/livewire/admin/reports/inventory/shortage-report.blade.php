<div class="container-fluid">
    <x-admin.table-card title="Inventory Losses" icon="fa-warning" :render-table="false">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Product</th>
                        <th>Branch</th>
                        <th>Shortage Quantity</th>
                        <th>Shortage Value</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_qty = 0;
                        $total_value = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_qty += $row->shortage_qty;
                        $total_value += $row->shortage_value;
                    @endphp
                    <tr>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ $row->branch_name }}</td>
                        <td>{{ $row->shortage_qty }}</td>
                        <td>{{ number_format($row->shortage_value, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No inventory shortage data found.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td></td>
                        <td>{{ $total_qty }}</td>
                        <td>{{ number_format($total_value, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-admin.table-card>
</div>
