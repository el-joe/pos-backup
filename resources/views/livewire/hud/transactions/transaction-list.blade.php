<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filters</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> Show / Hide
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Transaction_id -->
                    <div class="col-md-4">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" class="form-control"
                            placeholder="Search ..."
                            wire:model.blur="filters.transaction_id">
                    </div>

                    <!-- Filter by Transaction Type -->
                    <div class="col-md-4">
                        <label class="form-label">Transaction Type</label>
                        <select class="form-select" wire:model.live="filters.transaction_type">
                            <option value="all">All</option>
                            @foreach (App\Enums\TransactionTypeEnum::cases() as $case)
                                <option value="{{ $case->value }}">{{ $case->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Branch -->
                    <div class="col-md-4">
                        <label class="form-label">Branch</label>
                        <select class="form-select" wire:model.live="filters.branch_id">
                            <option value="all">All</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Date -->
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control"
                               wire:model.live="filters.date">
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> Reset
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

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Transactions List</h3>

            <div class="d-flex align-items-center gap-2">
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> Export
                </button>
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.tableHandler',[
                'rows'=> $transactionLines,
                'columns' => $columns,
                'headers' => $headers,
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
@endpush
