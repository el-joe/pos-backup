<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Cash Register Summary</h5>
                <small class="text-muted">Aggregated totals across registers</small>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Field</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="fw-semibold">
                            <td>Opening Balance</td>
                            <td class="text-end">{{ number_format($aggregates['opening_balance'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Sales</td>
                            <td class="text-end">{{ number_format($aggregates['total_sales'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Sale Refunds</td>
                            <td class="text-end">{{ number_format($aggregates['total_sale_refunds'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Purchases</td>
                            <td class="text-end">{{ number_format($aggregates['total_purchases'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Purchase Refunds</td>
                            <td class="text-end">{{ number_format($aggregates['total_purchase_refunds'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Expenses</td>
                            <td class="text-end">{{ number_format($aggregates['total_expenses'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Expense Refunds</td>
                            <td class="text-end">{{ number_format($aggregates['total_expense_refunds'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Deposits</td>
                            <td class="text-end">{{ number_format($aggregates['total_deposits'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Withdrawals</td>
                            <td class="text-end">{{ number_format($aggregates['total_withdrawals'] ?? 0, 2) }}</td>
                        </tr>
                        <tr class="fw-semibold table-light">
                            <td>Closing Balance</td>
                            <td class="text-end">{{ number_format($aggregates['closing_balance'] ?? 0, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Open / Close Register</h5>
            </div>
            <div class="card-body">
                @if($currentRegister)
                <p><strong>Open since:</strong> {{ $currentRegister->opened_at }}</p>
                <p><strong>Opening balance:</strong> {{ number_format($currentRegister->opening_balance, 2) }}</p>

                <div class="mb-3">
                    <label class="form-label">Closing Balance</label>
                    <input type="number" class="form-control" wire:model="closing_balance_input">
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" wire:model="closing_notes" rows="3"></textarea>
                </div>

                <button wire:click="closeRegister" class="btn btn-danger w-100">Close Register</button>
                @else
                <div class="mb-3">
                    <label class="form-label">Opening Balance</label>
                    <input type="number" class="form-control" wire:model="opening_balance_input">
                </div>

                @if(admin()->branch_id === null)
                <div class="mb-3">
                    <label class="form-label">Select Branch</label>
                    <select class="form-select" wire:model="branchId">
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <button wire:click="openRegister" class="btn btn-success w-100">Open Register</button>
                @endif
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>

        </div>
    </div>
</div>
