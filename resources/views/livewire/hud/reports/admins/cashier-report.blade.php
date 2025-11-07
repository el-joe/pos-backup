<div class="container-fluid py-3">
    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-secondary text-white d-flex align-items-center">
                <i class="fa fa-filter me-2"></i>
                <strong>Filter Options</strong>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label mb-1">From</label>
                        <input type="date" class="form-control form-control-sm" wire:model.live="from_date">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-1">To</label>
                        <input type="date" class="form-control form-control-sm" wire:model.live="to_date">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button wire:click="resetDates" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fa fa-refresh me-1"></i> Reset
                        </button>
                    </div>
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

    <!-- Cashier Report -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa fa-user me-2"></i>
                <h5 class="mb-0">Cashier Report</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-bordered table-hover table-striped mb-0 align-middle">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th>Cashier</th>
                                <th class="text-end">Total Sales</th>
                                <th class="text-end">Total Refunds</th>
                                <th class="text-end">Total Discounts</th>
                                <th class="text-end">Net Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sumSales = 0;
                                $sumRefunds = 0;
                                $sumDiscounts = 0;
                                $sumNet = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $sumSales += $row->total_sales;
                                    $sumRefunds += $row->total_refunds;
                                    $sumDiscounts += $row->total_discounts;
                                    $sumNet += $row->net_sales;
                                @endphp
                                <tr>
                                    <td>{{ $row->cashier }}</td>
                                    <td class="text-end">{{ number_format($row->total_sales, 2) }}</td>
                                    <td class="text-end">{{ number_format($row->total_refunds, 2) }}</td>
                                    <td class="text-end">{{ number_format($row->total_discounts, 2) }}</td>
                                    <td class="text-end">{{ number_format($row->net_sales, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        No cashier data available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if(count($report))
                        <tfoot class="bg-success bg-opacity-25 fw-bold">
                            <tr>
                                <td>Total</td>
                                <td class="text-end">{{ number_format($sumSales, 2) }}</td>
                                <td class="text-end">{{ number_format($sumRefunds, 2) }}</td>
                                <td class="text-end">{{ number_format($sumDiscounts, 2) }}</td>
                                <td class="text-end">{{ number_format($sumNet, 2) }}</td>
                            </tr>
                        </tfoot>
                        @endif
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
