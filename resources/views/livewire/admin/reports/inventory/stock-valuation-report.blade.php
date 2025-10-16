
<div class="white-box">
    <h3 class="box-title">Stock Valuation Report</h3>

    <div class="card section-card">
        <div class="card-body">
            <h4 class="section-title"><i class="fa fa-cubes"></i> Current Stock Value</h4>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Branch</th>
                        <th>Product (Unit)</th>
                        <th>Stock Quantity</th>
                        <th>Unit Cost</th>
                        <th>Stock Value</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_qty = 0;
                        $total_value = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_qty += $row->stock_qty;
                        $total_value += $row->stock_value;
                    @endphp
                    <tr>
                        <td>{{ $row->branch_name ?? 'N/A' }}</td>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ $row->stock_qty }}</td>
                        <td>{{ number_format($row->unit_cost, 2) }}</td>
                        <td>{{ number_format($row->stock_value, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No stock found.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td colspan="2">Total</td>
                        <td>{{ $total_qty }}</td>
                        <td></td>
                        <td>{{ number_format($total_value, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
.white-box {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 30px 28px;
    margin-top: 20px;
}
.section-title {
    font-size: 22px;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 2px solid #f1f1f1;
    padding-bottom: 10px;
}
.section-card {
    border-radius: 16px;
    margin-top: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    background: #fff;
}
</style>
@endpush
