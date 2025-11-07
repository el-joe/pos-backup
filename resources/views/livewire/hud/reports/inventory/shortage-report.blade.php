<div class="col-12">
    <div class="card shadow-sm border-0 bg-dark text-light mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="mb-0">
                <i class="fa fa-warning me-2"></i> Inventory Losses
            </h4>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 table-dark align-middle">
                    <thead class="table-primary text-dark">
                        <tr>
                            <th>Product</th>
                            <th>Branch</th>
                            <th>Shortage Quantity</th>
                            <th>Shortage Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_qty = 0;
                            $total_value = 0;
                        @endphp
                        @forelse($report as $row)
                            @php
                                $total_qty += $row->shortage_qty;
                                $total_value += $row->shortage_value;
                            @endphp
                            <tr>
                                <td>{{ $row->product_name }}</td>
                                <td>{{ $row->branch_name }}</td>
                                <td>{{ $row->shortage_qty }}</td>
                                <td>{{ number_format($row->shortage_value, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No inventory shortage data found.</td>
                            </tr>
                        @endforelse
                        @if(count($report))
                            <tr class="bg-success bg-opacity-25 fw-semibold">
                                <td>Total</td>
                                <td></td>
                                <td>{{ $total_qty }}</td>
                                <td>{{ number_format($total_value, 2) }}</td>
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
