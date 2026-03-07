<div class="col-12">
    <x-hud.filter-card :title="__('general.pages.stock-taking.filters')" icon="fa-filter" collapse-id="hudStockTakingFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hudStockTakingFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.stock-taking.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.stock-taking.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.stock-taking.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Branch -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.stock-taking.branch') }}</label>
                        <select class="form-select select2"
                                name="filters.branch_id">
                            <option value="">{{ __('general.pages.stock-taking.all_branches_option') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Date -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.stock-taking.date') }}</label>
                        <input type="date" class="form-control" wire:model.live="filters.date">
                    </div>

                    <!-- Filter by Created By -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.stock-taking.created_by') }}</label>
                        <select class="form-select select2"
                                name="filters.created_by">
                            <option value="">{{ __('general.pages.stock-taking.all_users') }}</option>
                            @foreach($admins as $user)
                                <option value="{{ $user->id }}" {{ ($filters['created_by']??'') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.stock-taking.reset') }}
                        </button>
                    </div>

                </div>
            </div>
        </div>

        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.titles.stock-adjustments')" icon="fa-balance-scale" :render-table="false">
        <x-slot:actions>
            @adminCan('stock_adjustments.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.stock-taking.export') }}
                </button>
            @endadminCan
            @adminCan('stock_adjustments.create')
                <a class="btn btn-primary" href="{{ route('admin.stocks.adjustments.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.stock-taking.new_stock_adjustment') }}
                </a>
            @endadminCan
        </x-slot:actions>

        @include('admin.partials.tableHandler',[
            'rows' => $stockAdjustments,
            'columns' => $columns,
            'headers' => $headers
        ])
    </x-hud.table-card>
</div>

@push('scripts')
@include('layouts.hud.partials.select2-script')
@endpush
