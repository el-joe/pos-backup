<div class="col-sm-12">
    <x-admin.filter-card title="Checks" icon="fa-filter" collapse-id="adminChecksFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button type="button" class="btn btn-default btn-sm" wire:click="$toggle('collapseFilters')" data-toggle="collapse" data-target="#adminChecksFilterCollapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> Show / Hide
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-3 form-group">
                <label>Direction</label>
                <select class="form-control" wire:model.live="filters.direction">
                    <option value="">All</option>
                    <option value="received">Received</option>
                    <option value="issued">Issued</option>
                </select>
            </div>
            <div class="col-md-3 form-group">
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
            <div class="col-md-3 form-group">
                <label>Branch</label>
                <select class="form-control" wire:model.live="filters.branch_id">
                    <option value="">All</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label>Search</label>
                <input type="text" class="form-control" wire:model.live="filters.search" placeholder="Check # or bank">
            </div>
            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> Reset
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card title="Checks" icon="fa-money-check-alt">
        <x-slot:head>
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
        </x-slot:head>

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
                <td colspan="10" class="text-center text-muted">No checks found.</td>
            </tr>
        @endforelse

        @if($checks->hasPages())
            <x-slot:footer>
                {{ $checks->links() }}
            </x-slot:footer>
        @endif
    </x-admin.table-card>
</div>
