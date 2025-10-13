
<div class="white-box">
    <h3 class="box-title">Product Sales Report</h3>

    <div class="row mb-4">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" class="form-control" wire:model.lazy="from_date">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" class="form-control" wire:model.lazy="to_date">
        </div>
    </div>

    <div class="card section-card">
        <div class="card-body">
            <h4 class="section-title"><i class="fa fa-cube"></i> Product Sales</h4>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Product</th>
                        <th>Quantity Sold</th>
                        <th>Total Cost</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_qty = 0;
                        $total_revenue = 0;
                        $total_cost = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_qty += $row->quantity_sold;
                        $total_revenue += $row->total_revenue;
                        $total_cost += $row->total_cost;
                    @endphp
                    <tr>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ number_format($row->quantity_sold, 0) }}</td>
                        <td>{{ number_format($row->total_cost, 2) }}</td>
                        <td>{{ number_format($row->total_revenue, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No product sales found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ number_format($total_qty, 0) }}</td>
                        <td>{{ number_format($total_cost, 2) }}</td>
                        <td>{{ number_format($total_revenue, 2) }}</td>
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
