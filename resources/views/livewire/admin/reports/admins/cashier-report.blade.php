<div class="white-box">
    <h3 class="box-title">Cashier Report</h3>

    <div class="card section-card">
        <div class="card-body">
            <div class="card m-b-20">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="control-label">From</label>
                            <input type="date" class="form-control" wire:model.live="from_date">
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">To</label>
                            <input type="date" class="form-control" wire:model.live="to_date">
                        </div>
                        <div class="col-sm-4 d-flex align-items-end justify-content-end" style="padding-top:25px">
                            <button wire:click="resetDates" class="btn btn-default">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Cashier</th>
                        <th class="text-right">Total Sales</th>
                        <th class="text-right">Total Refunds</th>
                        <th class="text-right">Total Discounts</th>
                        <th class="text-right">Net Sales</th>
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
                            <td class="text-right">{{ number_format($row->total_sales, 2) }}</td>
                            <td class="text-right">{{ number_format($row->total_refunds, 2) }}</td>
                            <td class="text-right">{{ number_format($row->total_discounts, 2) }}</td>
                            <td class="text-right">{{ number_format($row->net_sales, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No cashier data available.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($report))
                <tfoot>
                    <tr style="font-weight:600; background:#f1f8e9;">
                        <td>Total</td>
                        <td class="text-right">{{ number_format($sumSales, 2) }}</td>
                        <td class="text-right">{{ number_format($sumRefunds, 2) }}</td>
                        <td class="text-right">{{ number_format($sumDiscounts, 2) }}</td>
                        <td class="text-right">{{ number_format($sumNet, 2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
