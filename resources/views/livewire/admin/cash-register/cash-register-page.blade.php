<div class="row" x-data="{
        expected: {{ (float) ($aggregates['calculated_closing_balance'] ?? 0) }},
        counted: {{ (float) ($closing_balance_input ?? 0) }},
        get difference() { return (parseFloat(this.counted || 0) - parseFloat(this.expected || 0)).toFixed(2); }
    }">
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
                        <td>Expected Closing Balance</td>
                        <td class="text-right">{{ number_format($aggregates['calculated_closing_balance'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            @if($currentRegister)
                <h4 class="box-title">Recent Sessions</h4>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Opened</th>
                            <th>Closed</th>
                            <th class="text-right">Closing Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSessions as $session)
                            <tr>
                                <td>{{ $session->opened_at?->format('Y-m-d H:i') }}</td>
                                <td>{{ $session->closed_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td class="text-right">{{ number_format($session->closing_balance, 2) }}</td>
                                <td>
                                    @if($session->status === 'open')
                                        <span class="badge badge-success"><i class="fa fa-check-circle"></i> Open</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fa fa-lock"></i> Closed</span>
                                    @endif
                                    @if($session->discrepancy && abs($session->discrepancy) > 0.009)
                                        <span class="badge badge-warning"><i class="fa fa-exclamation-triangle"></i> Discrepancy</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="white-box">
            <h4 class="box-title">Open / Close Register</h4>

            @if($currentRegister)
                <p>
                    <strong>Open since:</strong> {{ $currentRegister->opened_at }}
                    @if($currentRegister->currency_code)
                        <span class="badge badge-info">{{ $currentRegister->currency_code }}</span>
                    @endif
                </p>
                <p><strong>Opening balance:</strong> {{ number_format($currentRegister->opening_balance, 2) }}</p>

                <hr>
                <h5>Cash Deposit</h5>
                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" class="form-control" dir="ltr" style="text-align:right" wire:model="deposit_amount_input">
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
                    <input type="number" class="form-control" dir="ltr" style="text-align:right" wire:model="withdrawal_amount_input">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" wire:model="withdrawal_notes"></textarea>
                </div>
                <button wire:click="withdrawCash" class="btn btn-warning">Record Withdrawal</button>

                <hr>
                <div class="form-group">
                    <label>Expected Closing Balance</label>
                    <input type="text" class="form-control" dir="ltr" style="text-align:right" readonly value="{{ number_format($aggregates['calculated_closing_balance'] ?? 0, 2) }}">
                </div>
                <div class="form-group">
                    <label>Counted Cash</label>
                    <input type="number" class="form-control" dir="ltr" style="text-align:right" wire:model.live="closing_balance_input" x-model="counted">
                </div>
                <div class="form-group">
                    <label>Difference</label>
                    <input type="text" class="form-control" dir="ltr" style="text-align:right" readonly x-bind:value="difference">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" wire:model="closing_notes"></textarea>
                </div>

                @if($requiresOverride)
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        The counted cash differs from the expected balance (difference: {{ number_format($discrepancyPreview, 2) }}) by more than the allowed threshold. Provide a reason to override and close anyway.
                    </div>
                    <div class="form-group">
                        <label>Override Reason</label>
                        <textarea class="form-control" wire:model="override_reason"></textarea>
                        @error('override_reason') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <button wire:click="closeRegister" class="btn btn-danger">Confirm Override & Close</button>
                @else
                    <button wire:click="confirmCloseRegister" class="btn btn-danger">Close Register</button>
                @endif
            @else
                <div class="form-group">
                    <label>Opening Balance</label>
                    <input type="number" class="form-control" dir="ltr" style="text-align:right" wire:model="opening_balance_input">
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
