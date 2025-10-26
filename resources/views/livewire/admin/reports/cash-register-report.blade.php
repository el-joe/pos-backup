<div class="white-box">
    <div class="card mb-3">
        <div class="card-body">
            <form wire:submit.prevent="applyFilter" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" id="from_date" wire:model.defer="from_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" id="to_date" wire:model.defer="to_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select id="branch_id" wire:model.defer="branch_id" class="form-control">
                        <option value="">All Branches</option>
                        {{-- Optionally populate with branches if available --}}
                        @if(function_exists('branches'))
                            @foreach(branches() as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="admin_id" class="form-label">Admin/Cashier</label>
                    <select id="admin_id" wire:model.defer="admin_id" class="form-control">
                        <option value="">All Admins</option>
                        {{-- Optionally populate with admins if available --}}
                        @if(function_exists('admins'))
                            @foreach(admins() as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <button type="button" wire:click="resetFilters" class="btn btn-secondary">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Cash Register Summary</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Opened At</th>
                            <th>Closed At</th>
                            <th>Branch</th>
                            <th>Admin</th>
                            <th>Opening Balance</th>
                            <th>Total Sales</th>
                            <th>Total Sale Refunds</th>
                            <th>Total Purchases</th>
                            <th>Total Purchase Refunds</th>
                            <th>Total Expenses</th>
                            <th>Total Expense Refunds</th>
                            <th>Total Deposits</th>
                            <th>Total Withdrawals</th>
                            <th>Closing Balance</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registers as $register)
                            <tr>
                                <td>{{ $register->opened_at }}</td>
                                <td>{{ $register->closed_at ?? '-' }}</td>
                                <td>{{ $register->branch?->name ?? $register->branch_id }}</td>
                                <td>{{ $register->admin->name ?? $register->admin_id }}</td>
                                <td>{{ number_format($register->opening_balance, 2) }}</td>
                                <td>{{ number_format($register->total_sales, 2) }}</td>
                                <td>{{ number_format($register->total_sale_refunds, 2) }}</td>
                                <td>{{ number_format($register->total_purchases, 2) }}</td>
                                <td>{{ number_format($register->total_purchase_refunds, 2) }}</td>
                                <td>{{ number_format($register->total_expenses, 2) }}</td>
                                <td>{{ number_format($register->total_expense_refunds, 2) }}</td>
                                <td>{{ number_format($register->total_deposits, 2) }}</td>
                                <td>{{ number_format($register->total_withdrawals, 2) }}</td>
                                <td>{{ number_format($register->closing_balance, 2) }}</td>
                                <td><span class="badge badge-{{ $register->status == 'open' ? 'success' : 'danger' }}">{{ ucfirst($register->status) }}</span></td>
                                <td>{{ $register->notes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center">No cash register records found for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
