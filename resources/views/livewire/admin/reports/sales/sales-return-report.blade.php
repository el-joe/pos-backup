
<div class="white-box">
    <h3 class="box-title">Sales Return Report</h3>

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
            <h4 class="section-title"><i class="fa fa-undo"></i> Sales Returns</h4>

            <table class="table table-bordered table-hover">
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
