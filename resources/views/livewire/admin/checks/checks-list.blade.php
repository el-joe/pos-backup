<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Checks</h4>
    </div>

    <div class="panel-body">
        <div class="row" style="margin-bottom:15px;">
            <div class="col-md-3">
                <label>Direction</label>
                <select class="form-control" wire:model.live="filters.direction">
                    <option value="">All</option>
                    <option value="received">Received</option>
                    <option value="issued">Issued</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <select class="form-control" wire:model.live="filters.status">
                    <option value="">All</option>
                    <option value="under_collection">Under Collection</option>
                    <option value="collected">Collected</option>
                    <option value="bounced">Bounced</option>
                    <option value="issued">Issued</option>
                    <option value="cleared">Cleared</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Branch</label>
                <select class="form-control" wire:model.live="filters.branch_id">
                    <option value="">All</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Search</label>
                <input type="text" class="form-control" wire:model.live="filters.search" placeholder="Check # or bank">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Direction</th>
                        <th>Status</th>
                        <th>Check #</th>
                        <th>Bank</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Customer / Supplier</th>
                        <th>Branch</th>
                        <th style="width:220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($checks as $check)
                        <tr>
                            <td>{{ $check->id }}</td>
                            <td>{{ ucfirst($check->direction) }}</td>
                            <td>{{ str_replace('_',' ', ucfirst($check->status)) }}</td>
                            <td>{{ $check->check_number ?? '-' }}</td>
                            <td>{{ $check->bank_name ?? '-' }}</td>
                            <td>{{ $check->due_date?->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ currencyFormat($check->amount, true) }}</td>
                            <td>
                                @if($check->direction === 'received')
                                    {{ $check->customer?->name ?? '-' }}
                                @else
                                    {{ $check->supplier?->name ?? '-' }}
                                @endif
                            </td>
                            <td>{{ $check->branch?->name ?? '-' }}</td>
                            <td>
                                @if($check->direction === 'received' && $check->status === 'under_collection')
                                    <button class="btn btn-success btn-sm" wire:click="collect({{ $check->id }})">Collect</button>
                                    <button class="btn btn-danger btn-sm" wire:click="bounce({{ $check->id }})">Bounce</button>
                                @elseif($check->direction === 'issued' && $check->status === 'issued')
                                    <button class="btn btn-primary btn-sm" wire:click="clearIssued({{ $check->id }})">Clear</button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No checks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $checks->links() }}
        </div>
    </div>
</div>
