<div class="container-fluid">
    <x-admin.filter-card title="Filter Options" icon="fa-filter">
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
    </x-admin.filter-card>

    <x-admin.table-card title="VAT Summary" icon="fa-list-alt" :render-table="false">
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
    </x-admin.table-card>
</div>
