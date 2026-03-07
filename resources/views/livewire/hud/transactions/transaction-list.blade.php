<div class="col-12">
    <x-hud.filter-card :title="__('general.pages.transactions.filters')" icon="fa-filter" collapse-id="hudTransactionsFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hudTransactionsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.transactions.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">

                    <!-- Filter by Transaction_id -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.transactions.transaction_id') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.transactions.search_placeholder') }}"
                            wire:model.blur="filters.transaction_id">
                    </div>

                    <!-- Filter by Transaction Type -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.transactions.transaction_type') }}</label>
                        <select class="form-select select2" name="filters.transaction_type">
                            <option value="all">{{ __('general.pages.transactions.all') }}</option>
                            @foreach (App\Enums\TransactionTypeEnum::cases() as $case)
                                <option value="{{ $case->value }}" {{ ($filters['transaction_type']??'') == $case->value ? 'selected' : '' }}>{{ $case->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Branch -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.transactions.branch') }}</label>
                        <select class="form-select select2" name="filters.branch_id">
                            <option value="all">{{ __('general.pages.transactions.all') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Date -->
                    <div class="col-md-4">
               <label class="form-label">{{ __('general.pages.transactions.date') }}</label>
                        <input type="date" class="form-control"
                               wire:model.live="filters.date">
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.transactions.reset') }}
                        </button>
                    </div>

        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.pages.transactions.transactions_list')" icon="fa-exchange" :render-table="false">
        <x-slot:actions>
            @adminCan('transactions.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.transactions.export') }}
                </button>
            @endadminCan
        </x-slot:actions>

        @include('admin.partials.tableHandler',[
            'rows'=> $transactionLines,
            'columns' => $columns,
            'headers' => $headers,
        ])
    </x-hud.table-card>
</div>

@push('scripts')
    @include('layouts.hud.partials.select2-script')
@endpush
