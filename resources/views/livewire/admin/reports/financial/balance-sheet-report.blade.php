
<div class="white-box">
    <h3 class="box-title">Balance Sheet Report</h3>

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
            <h4 class="section-title"><i class="fa fa-balance-scale"></i> Balance Sheet</h4>

            <table class="table table-bordered table-hover">
                <tbody>
                    {{-- ================= Assets ================= --}}
                    <tr style="background:#e3f2fd;">
                        <th colspan="2">Assets</th>
                    </tr>
                    @foreach($report['assets'] as $label => $amount)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ number_format($amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr style="background:#bbdefb; font-weight:600;">
                        <td>Total Assets</td>
                        <td>{{ number_format($report['total_assets'] ?? 0, 2) }}</td>
                    </tr>

                    {{-- ================= Liabilities ================= --}}
                    <tr style="background:#f3e5f5;">
                        <th colspan="2">Liabilities</th>
                    </tr>
                    @foreach($report['liabilities'] as $label => $amount)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ number_format($amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr style="background:#e1bee7; font-weight:600;">
                        <td>Total Liabilities</td>
                        <td>{{ number_format($report['total_liabilities'] ?? 0, 2) }}</td>
                    </tr>

                    {{-- ================= Equity ================= --}}
                    <tr style="background:#e8f5e8;">
                        <th colspan="2">Equity</th>
                    </tr>
                    @foreach($report['equity'] as $label => $amount)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ number_format($amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr style="background:#c8e6c9; font-weight:600;">
                        <td>Total Equity</td>
                        <td>{{ number_format($report['total_equity'] ?? 0, 2) }}</td>
                    </tr>
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
