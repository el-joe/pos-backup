<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.stock-transfers.filters')" icon="fa-filter" collapse-id="hudStockTransferFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hudStockTransferFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.stock-transfers.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.stock-transfers.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.stock-transfers.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.stock-transfers.ref_no') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.stock-transfers.search_placeholder') }}"
                            wire:model.blur="filters.ref_no">
                    </div>

                    <!-- Filter by Stock From Branch -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.stock-transfers.stock_from_branch') }}</label>
                        <select class="form-select select2" name="filters.from_branch_id">
                            <option value="all">{{ __('general.pages.stock-transfers.all') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($filters['from_branch_id']??'all') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Stock To Branch -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.stock-transfers.stock_to_branch') }}</label>
                        <select class="form-select select2" name="filters.to_branch_id">
                            <option value="all">{{ __('general.pages.stock-transfers.all') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($filters['to_branch_id']??'all') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- filter by transfer date --}}
                    <div class="col-md-4">
               <label class="form-label">{{ __('general.pages.stock-transfers.transfer_date') }}</label>
                        <input type="date" class="form-control"
                               wire:model.live="filters.transfer_date">
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.stock-transfers.reset') }}
                        </button>
                    </div>

                </div>
            </div>
        </div>

        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.stock-transfers.stock_transfers')" icon="fa-exchange" :render-table="false">
        <x-slot:actions>
            @adminCan('stock_transfers.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.stock-transfers.export') }}
                </button>
            @endadminCan
            @adminCan('stock_transfers.create')
                <a class="btn btn-primary" href="{{ route('admin.stocks.transfers.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.stock-transfers.new_stock_transfer') }}
                </a>
            @endadminCan
        </x-slot:actions>

        @include('admin.partials.tableHandler',[
            'rows' => $stockTransfers,
            'columns' => $columns,
            'headers' => $headers
        ])
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
