<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">{{ __('general.pages.checks.checks') }}</h4>
    </div>

    <div class="panel-body">
        <div class="row" style="margin-bottom:15px;">
            <div class="col-md-3">
                <label>{{ __('general.pages.checks.direction') }}</label>
                <select class="form-control" wire:model.live="filters.direction">
                    <option value="">{{ __('general.pages.checks.all') }}</option>
                    <option value="received">{{ __('general.pages.checks.received') }}</option>
                    <option value="issued">{{ __('general.pages.checks.issued') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>{{ __('general.pages.checks.status') }}</label>
                <select class="form-control" wire:model.live="filters.status">
                    <option value="">{{ __('general.pages.checks.all') }}</option>
                    <option value="under_collection">{{ __('general.pages.checks.under_collection') }}</option>
                    <option value="collected">{{ __('general.pages.checks.collected') }}</option>
                    <option value="bounced">{{ __('general.pages.checks.bounced') }}</option>
                    <option value="issued">{{ __('general.pages.checks.issued') }}</option>
                    <option value="cleared">{{ __('general.pages.checks.cleared') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>{{ __('general.pages.checks.branch') }}</label>
                <select class="form-control" wire:model.live="filters.branch_id">
                    <option value="">{{ __('general.pages.checks.all') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>{{ __('general.pages.checks.search') }}</label>
                <input type="text" class="form-control" wire:model.live="filters.search" placeholder="{{ __('general.pages.checks.search_placeholder') }}">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('general.pages.checks.direction') }}</th>
                        <th>{{ __('general.pages.checks.status') }}</th>
                        <th>{{ __('general.pages.checks.check_number') }}</th>
                        <th>{{ __('general.pages.checks.bank') }}</th>
                        <th>{{ __('general.pages.checks.due_date') }}</th>
                        <th>{{ __('general.pages.checks.amount') }}</th>
                        <th>{{ __('general.pages.checks.customer_supplier') }}</th>
                        <th>{{ __('general.pages.checks.branch') }}</th>
                        <th style="width:220px;">{{ __('general.pages.checks.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($checks as $check)
                        <tr>
                            <td>{{ $check->id }}</td>
                            <td>{{ __('general.pages.checks.'.$check->direction) }}</td>
                            <td>{{ __('general.pages.checks.'.$check->status) }}</td>
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
                                    <button class="btn btn-success btn-sm" wire:click="collect({{ $check->id }})">{{ __('general.pages.checks.collect') }}</button>
                                    <button class="btn btn-danger btn-sm" wire:click="bounce({{ $check->id }})">{{ __('general.pages.checks.bounce') }}</button>
                                @elseif($check->direction === 'issued' && $check->status === 'issued')
                                    <button class="btn btn-primary btn-sm" wire:click="clearIssued({{ $check->id }})">{{ __('general.pages.checks.clear') }}</button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">{{ __('general.pages.checks.no_checks_found') }}</td>
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
