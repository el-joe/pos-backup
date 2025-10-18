<div class="white-box">
    <h3 class="box-title">VAT Summary</h3>

    <div class="card section-card m-b-20">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">From</label>
                    <input type="date" class="form-control" wire:model="from_date">
                </div>
                <div class="col-sm-4">
                    <label class="control-label">To</label>
                    <input type="date" class="form-control" wire:model="to_date">
                </div>
                <div class="col-sm-4 d-flex align-items-end justify-content-end" style="padding-top: 25px">
                    <button wire:click="resetDates" class="btn btn-default">Reset</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Card -->
    <div class="card section-card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
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
