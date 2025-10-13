
<div class="white-box">
    <h3 class="box-title">Branch Sales Report</h3>

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
            <h4 class="section-title"><i class="fa fa-building"></i> Branch Sales</h4>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Branch</th>
                        <th>Sale Count</th>
                        <th>Total Sales</th>
                        <th>Avg Ticket Size</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_sales = 0;
                        $total_count = 0;
                        $avg_ticket_size = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_sales_record = $row->sales->sum(fn($q)=>$q->grand_total_amount);
                        $total_count_record = $row->sales->count();
                        $avg_ticket_size_record = $total_count_record ? ($total_sales_record / $total_count_record) : 0;

                        $total_sales += $total_sales_record;
                        $total_count += $total_count_record;
                        $avg_ticket_size += $avg_ticket_size_record;
                    @endphp
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $total_count_record }}</td>
                        <td>{{ number_format($total_sales_record, 2) }}</td>
                        <td>{{ number_format($avg_ticket_size_record, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No branch sales found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ $total_count }}</td>
                        <td>{{ number_format($total_sales, 2) }}</td>
                        <td>{{ number_format($avg_ticket_size, 2) }}</td>
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
