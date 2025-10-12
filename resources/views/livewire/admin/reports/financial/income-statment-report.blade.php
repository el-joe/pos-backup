<div class="white-box">
    <h3 class="box-title">Income Statement Report</h3>

    <div class="row mb-4">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" class="form-control" wire:model.lazy="from_date">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" class="form-control" wire:model.lazy="to_date">
        </div>
    </div>

    <div class="card section-card">
        <div class="card-body">
            <h4 class="section-title"><i class="fa fa-bar-chart"></i> Income Statement</h4>

            <table class="table table-bordered table-hover">
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

@push('styles')
<style>
.white-box {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 30px 28px;
    margin-top: 20px;
}
.section-title {
    font-size: 22px;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 2px solid #f1f1f1;
    padding-bottom: 10px;
}
.section-card {
    border-radius: 16px;
    margin-top: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    background: #fff;
}
</style>
@endpush
