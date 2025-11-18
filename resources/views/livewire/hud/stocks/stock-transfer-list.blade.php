<div class="col-12">
    <div class="card shadow-sm mb-3">
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
                        <select class="form-select" wire:model.live="filters.from_branch_id">
                            <option value="all">{{ __('general.pages.stock-transfers.all') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Stock To Branch -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.stock-transfers.stock_to_branch') }}</label>
                        <select class="form-select" wire:model.live="filters.to_branch_id">
                            <option value="all">{{ __('general.pages.stock-transfers.all') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
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

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">{{ __('general.pages.stock-transfers.stock_transfers') }}</h3>
            <div class="d-flex align-items-center gap-2">
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.stock-transfers.export') }}
                </button>
                <a class="btn btn-primary" href="{{ route('admin.stocks.transfers.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.stock-transfers.new_stock_transfer') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.tableHandler',[
                'rows' => $stockTransfers,
                'columns' => $columns,
                'headers' => $headers
            ])
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* .card {
        border-radius: 16px;
        border: 1.5px solid #e3e6ed;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 16px 24px;
    }
    .card-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
    } */
</style>
@endpush
