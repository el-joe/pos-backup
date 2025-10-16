
<div class="white-box">
    <h3 class="box-title">COGS Report</h3>

    <div class="card section-card">
        <div class="card-body">
            <h4 class="section-title"><i class="fa fa-money"></i> Cost of Goods Sold</h4>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Branch</th>
                        <th>COGS Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_cogs = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_cogs += $row->cogs_amount;
                    @endphp
                    <tr>
                        <td>{{ $row->branch_name }}</td>
                        <td>{{ number_format($row->cogs_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center">No COGS data found.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ number_format($total_cogs, 2) }}</td>
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
