<div class="col-sm-12">
    <x-admin.filter-card :title="__('general.pages.stock-transfers.filters')" icon="fa-filter" collapse-id="adminStockTransferFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button type="button" class="btn btn-default btn-sm" wire:click="$toggle('collapseFilters')" data-toggle="collapse" data-target="#adminStockTransferFilterCollapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.stock-transfers.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.stock-transfers.ref_no') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.stock-transfers.search_placeholder') }}" wire:model.live="filters.ref_no">
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.stock-transfers.stock_from_branch') }}</label>
                <select class="form-control" wire:model.live="filters.from_branch_id">
                    <option value="">{{ __('general.pages.stock-transfers.all') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.stock-transfers.stock_to_branch') }}</label>
                <select class="form-control" wire:model.live="filters.to_branch_id">
                    <option value="">{{ __('general.pages.stock-transfers.all') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.stock-transfers.transfer_date') }}</label>
                <input type="date" class="form-control" wire:model.live="filters.transfer_date">
            </div>
            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.stock-transfers.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.pages.stock-transfers.stock_transfers')" icon="fa-exchange" :render-table="false">
        <x-slot:actions>
            @adminCan('stock_transfers.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.stock-transfers.export') }}
                </button>
            @endadminCan
            @adminCan('stock_transfers.create')
                <a class="btn btn-primary btn-sm" href="{{ route('admin.stocks.transfers.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.stock-transfers.new_stock_transfer') }}
                </a>
            @endadminCan
        </x-slot:actions>

        @include('admin.partials.tableHandler',[
            'rows' => $stockTransfers,
            'columns' => $columns,
            'headers' => $headers,
        ])
    </x-admin.table-card>
</div>
@push('styles')
@endpush
