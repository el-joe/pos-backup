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
            <h4 class="panel-title"><i class="glyphicon glyphicon-stats"></i> Income Statement</h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                <tbody>
                    {{-- ================= Revenue ================= --}}
                    <tr style="background:#e3f2fd;">
                        <th colspan="2">Revenue</th>
                    </tr>
                    <tr>
                        <td>Sales</td>
                        <td>{{ number_format($report['accounts']['sales']['credit'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Sales Discount</td>
                        <td>-{{ number_format($report['accounts']['sales_discount']['debit'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Sales Return</td>
                        <td>-{{ number_format($report['accounts']['sales_return']['debit'] ?? 0, 2) }}</td>
                    </tr>
                    <tr style="background:#bbdefb; font-weight:600;">
                        <td>Total Revenue</td>
                        <td>{{ number_format($report['revenue'] ?? 0, 2) }}</td>
                    </tr>

                    {{-- ================= COGS ================= --}}
                    <tr style="background:#e0f7fa;">
                        <th colspan="2">Cost of Goods Sold</th>
                    </tr>
                    <tr>
                        <td>COGS</td>
                        <td>{{ number_format($report['accounts']['cogs']['debit'] ?? 0, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        <td>Inventory</td>
                        <td>{{ number_format($report['accounts']['inventory']['debit'] ?? 0, 2) }}</td>
                    </tr> --}}
                    <tr>
                        <td>Inventory Shortage</td>
                        <td>{{ number_format($report['accounts']['inventory_shortage']['debit'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Purchase Discount</td>
                        <td>-{{ number_format($report['accounts']['purchase_discount']['credit'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Purchase Return</td>
                        <td>-{{ number_format($report['accounts']['purchase_return']['credit'] ?? 0, 2) }}</td>
                    </tr>
                    <tr style="background:#b2ebf2; font-weight:600;">
                        <td>Total COGS</td>
                        <td>{{ number_format($report['cogs'] ?? 0, 2) }}</td>
                    </tr>
                    <tr style="background:#fffde7;">
                        <th>Gross Profit</th>
                        <th>{{ number_format($report['gross_profit'] ?? 0, 2) }}</th>
                    </tr>

                    {{-- ================= Expenses ================= --}}
                    <tr style="background:#f3e5f5;">
                        <th colspan="2">Expenses</th>
                    </tr>
                    <tr>
                        <td>Expense</td>
                        <td>{{ number_format($report['accounts']['expense']['debit'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>VAT Payable</td>
                        <td>{{ number_format($report['accounts']['vat_payable']['credit'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>VAT Receivable</td>
                        <td>-{{ number_format($report['accounts']['vat_receivable']['debit'] ?? 0, 2) }}</td>
                    </tr>
                    <tr style="background:#e1bee7; font-weight:600;">
                        <td>Total Expenses</td>
                        <td>{{ number_format($report['expenses'] ?? 0, 2) }}</td>
                    </tr>
                    <tr style="background:#e8f5e8; font-size:18px;">
                        <th>Net Profit</th>
                        <th>{{ number_format($report['net_profit'] ?? 0, 2) }}</th>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
