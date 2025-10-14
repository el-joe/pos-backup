
<div class="white-box">
    <h3 class="box-title">Sales Profit Report</h3>

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
            <h4 class="section-title"><i class="fa fa-bar-chart"></i> Sales Profit</h4>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Product</th>
                        <th>Sales Revenue</th>
                        <th>COGS</th>
                        <th>Gross Profit</th>
                        <th>Margin (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_revenue = 0;
                        $total_cogs = 0;
                        $total_profit = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_revenue += $row->sales_revenue;
                        $total_cogs += $row->cogs;
                        $total_profit += $row->gross_profit;
                    @endphp
                    <tr>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ number_format($row->sales_revenue, 2) }}</td>
                        <td>{{ number_format($row->cogs, 2) }}</td>
                        <td>{{ number_format($row->gross_profit, 2) }}</td>
                        <td>{{ number_format($row->margin, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No sales profit data found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ number_format($total_revenue, 2) }}</td>
                        <td>{{ number_format($total_cogs, 2) }}</td>
                        <td>{{ number_format($total_profit, 2) }}</td>
                        <td>{{ $total_revenue > 0 ? number_format($total_profit / $total_revenue * 100, 2) : '0.00' }}</td>
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
