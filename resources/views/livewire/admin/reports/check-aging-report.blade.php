<div class="container-fluid">
    <x-admin.filter-card title="Filter Options" icon="fa-filter">
        <form wire:submit.prevent="applyFilter" class="row">
            <div class="col-md-3 form-group">
                <label for="direction">Direction</label>
                <select id="direction" wire:model.live="direction" class="form-control input-sm">
                    <option value="received">Received (Under Collection)</option>
                    <option value="issued">Issued</option>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="branch_id">Branch</label>
                <select id="branch_id" wire:model.live="branch_id" class="form-control input-sm">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group" style="margin-top:25px;">
                <button type="button" wire:click="resetFilters" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-refresh"></i> Reset</button>
            </div>
        </form>
    </x-admin.filter-card>

    <div class="row" style="margin-bottom: 15px;">
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

    <x-admin.table-card title="Check Aging Report" icon="fa-clock-o" :render-table="false">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" style="margin-bottom:0;">
                <thead class="active">
                    <tr>
                        <th>Bucket</th>
                        <th style="width: 150px">Count</th>
                        <th style="width: 200px">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
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
                    @foreach($buckets as $key => $items)
                        <tr class="{{ $key === 'overdue' && $items->count() ? 'danger' : '' }}">
                            <td>{{ $labels[$key] }}</td>
                            <td>{{ $items->count() }}</td>
                            <td>{{ currencyFormat($bucketTotals[$key], true) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-admin.table-card>

    @foreach($buckets as $key => $items)
        @if($items->count())
            <x-admin.table-card title="{{ $labels[$key] }}" icon="fa-file-text-o" :render-table="false">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" style="margin-bottom:0;">
                        <thead class="active">
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
            </x-admin.table-card>
        @endif
    @endforeach
</div>
