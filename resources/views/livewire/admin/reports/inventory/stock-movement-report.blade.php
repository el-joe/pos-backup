
<div class="white-box">
    <h3 class="box-title">Stock Movement Report</h3>

    <div class="card section-card">
        <div class="card-body">
            <h4 class="section-title"><i class="fa fa-exchange"></i> Item Inflow / Outflow / Adjustments</h4>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Product</th>
                        <th>Inflow (Purchases)</th>
                        <th>Outflow (Sales)</th>
                        <th>Adjustments</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_inflow = 0;
                        $total_outflow = 0;
                        $total_adjustment = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_inflow += $row['inflow'];
                        $total_outflow += $row['outflow'];
                        $total_adjustment += $row['adjustment'];
                    @endphp
                    <tr>
                        <td>{{ $row['product_name'] }}</td>
                        <td>{{ $row['inflow'] }}</td>
                        <td>{{ $row['outflow'] }}</td>
                        <td>{{ $row['adjustment'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No stock movement found.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td>{{ $total_inflow }}</td>
                        <td>{{ $total_outflow }}</td>
                        <td>{{ $total_adjustment }}</td>
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
