<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><i class="glyphicon glyphicon-filter"></i> Filter Options</strong>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">From</label>
                    <input type="date" class="form-control input-sm" wire:model="from_date">
                </div>
                <div class="col-sm-4">
                    <label class="control-label">To</label>
                    <input type="date" class="form-control input-sm" wire:model="to_date">
                </div>
                <div class="col-sm-4 d-flex align-items-end justify-content-end" style="padding-top: 25px">
                    <button wire:click="resetDates" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-refresh"></i> Reset</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Panel -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-list-alt"></i> VAT Summary</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Metric</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>VAT Payable (Sales)</td>
                        <td class="text-right">{{ number_format($report['vat_payable'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>VAT Receivable (Purchases)</td>
                        <td class="text-right">{{ number_format($report['vat_receivable'] ?? 0, 2) }}</td>
                    </tr>
                    <tr style="font-weight:600; background:#f1f8e9;">
                        <td>Net VAT (Payable - Receivable)</td>
                        <td class="text-right">{{ number_format($report['net'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
