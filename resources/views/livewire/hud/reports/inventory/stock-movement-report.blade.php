<div class="container-fluid">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-dark text-light">
            <div class="card-header d-flex align-items-center">
                <i class="fa fa-exchange me-2 text-info"></i>
                <h5 class="mb-0">Item Inflow / Outflow / Adjustments</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-striped align-middle mb-0">
                        <thead class="table-light text-dark">
                            <tr>
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
                                    <td colspan="4" class="text-center text-muted">No stock movement found.</td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold bg-success bg-opacity-25">
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

            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>
</div>
