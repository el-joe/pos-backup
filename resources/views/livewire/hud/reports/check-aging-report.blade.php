<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">{{ __('general.titles.check_aging_report') }}</div>
        <div class="card-body">
            <form wire:submit.prevent="applyFilter" class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Direction</label>
                    <select wire:model.live="direction" class="form-select form-select-sm">
                        <option value="received">Received (Under Collection)</option>
                        <option value="issued">Issued</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Branch</label>
                    <select wire:model.live="branch_id" class="form-select form-select-sm">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" wire:click="resetFilters" class="btn btn-outline-secondary btn-sm">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="alert alert-info">
                <strong>Aging total:</strong> {{ currencyFormat($grandTotal, true) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert {{ abs($grandTotal - $controlAccountBalance) < 0.01 ? 'alert-success' : 'alert-danger' }}">
                <strong>{{ $controlAccount->name }} balance:</strong> {{ currencyFormat($controlAccountBalance, true) }}
                @if(abs($grandTotal - $controlAccountBalance) >= 0.01)
                    &mdash; <strong>reconciliation mismatch</strong>
                @else
                    &mdash; reconciled
                @endif
            </div>
        </div>
    </div>

    @php
        $labels = [
            'overdue' => 'Overdue',
            'due_0_7' => 'Due in 0-7 days',
            'due_8_30' => 'Due in 8-30 days',
            'due_31_60' => 'Due in 31-60 days',
            'due_60_plus' => 'Due in 60+ days',
            'no_due_date' => 'No due date',
        ];
    @endphp

    <div class="card mb-3">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Bucket</th>
                        <th>Count</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($buckets as $key => $items)
                        <tr class="{{ $key === 'overdue' && $items->count() ? 'table-danger' : '' }}">
                            <td>{{ $labels[$key] }}</td>
                            <td>{{ $items->count() }}</td>
                            <td>{{ currencyFormat($bucketTotals[$key], true) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach($buckets as $key => $items)
        @if($items->count())
            <div class="card mb-3">
                <div class="card-header">{{ $labels[$key] }}</div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Check #</th>
                                <th>Bank</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Customer / Supplier</th>
                                <th>Branch</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $check)
                                <tr>
                                    <td>{{ $check->check_number }}</td>
                                    <td>{{ $check->bank_name }}</td>
                                    <td>{{ $check->due_date?->format('Y-m-d') }}</td>
                                    <td>{{ currencyFormat($check->amount, true) }}</td>
                                    <td>{{ $check->direction === 'received' ? ($check->customer?->name ?? '-') : ($check->supplier?->name ?? '-') }}</td>
                                    <td>{{ $check->branch?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
</div>
