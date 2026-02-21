<div class="row">
    <div class="col-md-8">
        <div class="white-box">
            <h3 class="box-title">Cash Register Summary</h3>
            <p class="text-muted">Aggregated totals across registers</p>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>Field</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Opening Balance</td>
                        <td class="text-right">{{ number_format($aggregates['opening_balance'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Sales</td>
                        <td class="text-right">{{ number_format($aggregates['total_sales'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Sale Refunds</td>
                        <td class="text-right">{{ number_format($aggregates['total_sale_refunds'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Purchases</td>
                        <td class="text-right">{{ number_format($aggregates['total_purchases'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Purchase Refunds</td>
                        <td class="text-right">{{ number_format($aggregates['total_purchase_refunds'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Expenses</td>
                        <td class="text-right">{{ number_format($aggregates['total_expenses'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Expense Refunds</td>
                        <td class="text-right">{{ number_format($aggregates['total_expense_refunds'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Deposits</td>
                        <td class="text-right">{{ number_format($aggregates['total_deposits'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Withdrawals</td>
                        <td class="text-right">{{ number_format($aggregates['total_withdrawals'] ?? 0, 2) }}</td>
                    </tr>
                    <tr style="font-weight:600; background:#f1f8e9;">
                        <td>Closing Balance</td>
                        <td class="text-right">{{ number_format($aggregates['closing_balance'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-4">
        <div class="white-box">
            <h4 class="box-title">Open / Close Register</h4>

            @if($currentRegister)
                <p><strong>Open since:</strong> {{ $currentRegister->opened_at }}</p>
                <p><strong>Opening balance:</strong> {{ number_format($currentRegister->opening_balance, 2) }}</p>

                <hr>
                <h5>Cash Deposit</h5>
                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" class="form-control" wire:model="deposit_amount_input">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" wire:model="deposit_notes"></textarea>
                </div>
                <button wire:click="depositCash" class="btn btn-primary">Record Deposit</button>

                <hr>
                <h5>Cash Withdrawal</h5>
                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" class="form-control" wire:model="withdrawal_amount_input">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" wire:model="withdrawal_notes"></textarea>
                </div>
                <button wire:click="withdrawCash" class="btn btn-warning">Record Withdrawal</button>

                <hr>
                <div class="form-group">
                    <label>Closing Balance</label>
                    <input type="number" class="form-control" wire:model="closing_balance_input">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" wire:model="closing_notes"></textarea>
                </div>
                <button wire:click="closeRegister" class="btn btn-danger">Close Register</button>
            @else
                <div class="form-group">
                    <label>Opening Balance</label>
                    <input type="number" class="form-control" wire:model="opening_balance_input">
                </div>
                @if(admin()->branch_id === null)
                    <div class="form-group">
                        <label>Select Branch</label>
                        <select class="form-control" wire:model="branchId">
                            <option value="">-- Select Branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <button wire:click="openRegister" class="btn btn-success">Open Register</button>
            @endif

        </div>
    </div>
</div>
