<div class="col-sm-12">
    <x-admin.filter-card :title="__('general.pages.transactions.filters')" icon="fa-filter" collapse-id="adminTransactionsFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button type="button" class="btn btn-default btn-sm" wire:click="$toggle('collapseFilters')" data-toggle="collapse" data-target="#adminTransactionsFilterCollapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.transactions.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.transactions.transaction_id') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.transactions.search_placeholder') }}" wire:model.live="filters.transaction_id">
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.transactions.transaction_type') }}</label>
                <select class="form-control" wire:model.live="filters.transaction_type">
                    <option value="">{{ __('general.pages.transactions.all') }}</option>
                    @foreach (App\Enums\TransactionTypeEnum::cases() as $case)
                        <option value="{{ $case->value }}">{{ $case->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.transactions.branch') }}</label>
                <select class="form-control" wire:model.live="filters.branch_id">
                    <option value="">{{ __('general.pages.transactions.all') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.transactions.date') }}</label>
                <input type="date" class="form-control" wire:model.live="filters.date">
            </div>
            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.transactions.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.pages.transactions.transactions_list')" icon="fa-exchange" :render-table="false">
        @include('admin.partials.tableHandler',[
            'rows' => $transactionLines,
            'columns' => $columns,
            'headers' => $headers,
        ])
    </x-admin.table-card>
</div>
@push('styles')
@endpush
