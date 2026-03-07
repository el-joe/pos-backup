<div class="container-fluid">
    <x-admin.filter-card title="Filter Options" icon="fa-filter">
        <div class="row">
            <div class="col-sm-4">
                <label class="control-label">From</label>
                <input type="date" class="form-control input-sm" wire:model.live="from_date">
            </div>
            <div class="col-sm-4">
                <label class="control-label">To</label>
                <input type="date" class="form-control input-sm" wire:model.live="to_date">
            </div>
            <div class="col-sm-4" style="padding-top:25px">
                <button wire:click="resetDates" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-refresh"></i> Reset</button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card title="Cashier Report" icon="fa-user" :render-table="false">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
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
    </x-admin.table-card>
</div>
