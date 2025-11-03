<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><i class="glyphicon glyphicon-filter"></i> Filter Options</strong>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>From Date</label>
                    <input type="date" class="form-control input-sm" wire:model.lazy="from_date">
                </div>
                <div class="col-md-3 form-group">
                    <label>To Date</label>
                    <input type="date" class="form-control input-sm" wire:model.lazy="to_date">
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-file"></i> VAT Payable Transactions</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Sale Date</th>
                        <th>VAT Payable</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_vat = 0;
                    @endphp
                    @forelse($report as $row)
                    @php
                        $total_vat += $row->vat_payable;
                    @endphp
                    <tr>
                        <td>{{ $row->invoice_number }}</td>
                        <td>{{ $row->customer_name }}</td>
                        <td>{{ $row->sale_date }}</td>
                        <td>{{ number_format($row->vat_payable, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No VAT payable transactions found for selected period.</td>
                    </tr>
                    @endforelse
                    @if(count($report))
                    <tr style="background:#f1f8e9; font-weight:600;">
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($total_vat, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
