<div class="col-12">
    <!-- Filter Options Card -->
    <div class="card shadow-sm border-0 bg-dark text-light mb-4">
        <div class="card-header">
            <strong><i class="fa fa-filter me-2"></i> Filter Options</strong>
        </div>
        <div class="card-body">
            <form class="row g-3 align-items-center">
                <div class="col-md-2">
                    <label for="from_date" class="form-label">From</label>
                    <input type="date" id="from_date" wire:model.lazy="from_date" class="form-control form-control-sm bg-secondary text-light border-0">
                </div>
                <div class="col-md-2">
                    <label for="to_date" class="form-label">To</label>
                    <input type="date" id="to_date" wire:model.lazy="to_date" class="form-control form-control-sm bg-secondary text-light border-0">
                </div>
            </form>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Product Profit Margin Report Card -->
    <div class="card shadow-sm border-0 bg-dark text-light mb-4">
        <div class="card-header d-flex align-items-center">
            <h4 class="mb-0">
                <i class="fa fa-line-chart me-2"></i> Product Profit Margin Report
            </h4>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 table-dark align-middle">
                    <thead class="table-primary text-dark">
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Total Sales</th>
                            <th class="text-end">Total COGS</th>
                            <th class="text-end">Profit</th>
                            <th class="text-end">Profit Margin (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sum_sales = 0;
                            $sum_cogs = 0;
                            $sum_profit = 0;
                        @endphp
                        @forelse($report as $row)
                            @php
                                $sum_sales += $row->total_sales;
                                $sum_cogs += $row->total_cogs;
                                $sum_profit += $row->profit;
                            @endphp
                            <tr>
                                <td>{{ $row->product_name }}</td>
                                <td class="text-end">{{ number_format($row->total_sales, 2) }}</td>
                                <td class="text-end">{{ number_format($row->total_cogs, 2) }}</td>
                                <td class="text-end">{{ number_format($row->profit, 2) }}</td>
                                <td class="text-end">
                                    <span class="badge bg-success bg-opacity-75">{{ number_format($row->profit_margin_percent, 2) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No data found for the selected period.</td>
                            </tr>
                        @endforelse

                        @if(count($report))
                            <tr class="bg-success bg-opacity-25 fw-semibold">
                                <td>Total</td>
                                <td class="text-end">{{ number_format($sum_sales, 2) }}</td>
                                <td class="text-end">{{ number_format($sum_cogs, 2) }}</td>
                                <td class="text-end">{{ number_format($sum_profit, 2) }}</td>
                                <td></td>
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
