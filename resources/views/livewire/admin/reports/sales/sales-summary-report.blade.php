
<div class="white-box">
    <h3 class="box-title">Sales Summary Report</h3>

    <div class="row mb-4">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" class="form-control" wire:model.lazy="from_date">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" class="form-control" wire:model.lazy="to_date">
        </div>
        <div class="col-md-3">
            <label>Period</label>
            <select class="form-control" wire:model.lazy="period">
                <option value="day">Day</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
            </select>
        </div>
    </div>

    <div class="card section-card">
        <div class="card-body">
            <h4 class="section-title"><i class="fa fa-line-chart"></i> Sales Summary</h4>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
                                        {{-- 'period' => $row->period,
                'gross_sales' => $row->gross_sales ?? 0,
                'discount' => $row->discount ?? 0,
                'sales_return' => $row->sales_return ?? 0,
                'net_sales' => $row->net_sales ?? 0,
                'vat_payable' => $row->vat_payable ?? 0,
                'total_collected' => $row->total_collected ?? 0,
                'cogs' => $row->cogs ?? 0,
                'gross_profit' => $row->gross_profit ?? 0, --}}

                        <th>Period</th>
                        <th>Gross Sales</th>
                        <th>Discount</th>
                        <th>Sales Return</th>
                        <th>Net Sales</th>
                        <th>VAT Payable</th>
                        <th>Total Collected</th>
                        <th>COGS</th>
                        <th>Gross Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $row)
                    <tr>
                        <td>{{ $row['period'] }}</td>
                        <td>{{ number_format($row['gross_sales'], 2) }}</td>
                        <td>-{{ number_format($row['discount'], 2) }}</td>
                        <td>-{{ number_format($row['sales_return'], 2) }}</td>
                        <td><span class="label label-success">{{ number_format($row['net_sales'], 2) }}</span></td>
                        <td>{{ number_format($row['vat_payable'], 2) }}</td>
                        <td>{{ number_format($row['total_collected'], 2) }}</td>
                        <td>{{ number_format($row['cogs'], 2) }}</td>
                        <td>{{ number_format($row['gross_profit'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No sales data found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ number_format(collect($report)->sum('gross_sales'), 2) }}</td>
                        <td>-{{ number_format(collect($report)->sum('discount'), 2) }}</td>
                        <td>-{{ number_format(collect($report)->sum('sales_return'), 2) }}</td>
                        <td><span class="label label-success">{{ number_format(collect($report)->sum('net_sales'), 2) }}</span></td>
                        <td>{{ number_format(collect($report)->sum('vat_payable'), 2) }}</td>
                        <td>{{ number_format(collect($report)->sum('total_collected'), 2) }}</td>
                        <td>{{ number_format(collect($report)->sum('cogs'), 2) }}</td>
                        <td>{{ number_format(collect($report)->sum('gross_profit'), 2) }}</td>
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
.label-success {
    background: #43a047;
    font-size: 15px;
    padding: 6px 14px;
    border-radius: 12px;
}
</style>
@endpush
