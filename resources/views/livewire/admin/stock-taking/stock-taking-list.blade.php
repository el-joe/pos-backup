<div class="col-sm-12">
    <x-admin.filter-card :title="__('general.pages.stock-taking.filters')" icon="fa-filter" collapse-id="adminStockTakingFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button type="button" class="btn btn-default btn-sm" wire:click="$toggle('collapseFilters')" data-toggle="collapse" data-target="#adminStockTakingFilterCollapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.stock-taking.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.stock-taking.branch') }}</label>
                <select class="form-control" wire:model.live="filters.branch_id">
                    <option value="">{{ __('general.pages.stock-taking.all_branches_option') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.stock-taking.date') }}</label>
                <input type="date" class="form-control" wire:model.live="filters.date">
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.stock-taking.created_by') }}</label>
                <select class="form-control" wire:model.live="filters.created_by">
                    <option value="">{{ __('general.pages.stock-taking.all_users') }}</option>
                    @foreach($admins as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.stock-taking.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.titles.stock-adjustments')" icon="fa-balance-scale" :render-table="false">
        <x-slot:actions>
            @adminCan('stock_adjustments.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.stock-taking.export') }}
                </button>
            @endadminCan
            @adminCan('stock_adjustments.create')
                <a class="btn btn-primary btn-sm" href="{{ route('admin.stocks.adjustments.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.stock-taking.new_stock_adjustment') }}
                </a>
            @endadminCan
        </x-slot:actions>

        @include('admin.partials.tableHandler',[
            'rows' => $stockAdjustments,
            'columns' => $columns,
            'headers' => $headers,
        ])
    </x-admin.table-card>
</div>
@push('styles')
@endpush
