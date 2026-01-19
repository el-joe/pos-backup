<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.depreciation_expenses.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#depreciationFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.depreciation_expenses.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="depreciationFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.branch') }}</label>
                        <select class="form-select select2" name="filters.branch_id">
                            <option value="">{{ __('general.layout.all_branches') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.fixed_asset') }}</label>
                        <select class="form-select select2" name="filters.model_id">
                            <option value="">{{ __('general.pages.depreciation_expenses.all_assets') }}</option>
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}" {{ ($filters['model_id']??'') == $asset->id ? 'selected' : '' }}>{{ $asset->code }} - {{ $asset->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.category') }}</label>
                        <select class="form-select select2" name="filters.expense_category_id">
                            <option value="">{{ __('general.pages.depreciation_expenses.all_categories') }}</option>
                            @foreach ($expenseCategories as $cat)
                                <option value="{{ $cat->id }}" {{ ($filters['expense_category_id']??'') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.date') }}</label>
                        <input type="date" class="form-control" wire:model="filters.date" />
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.depreciation_expenses.reset') }}
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
            <h3 class="card-title mb-0">{{ __('general.pages.depreciation_expenses.depreciation_expenses') }}</h3>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.depreciation_expenses.export') }}
                </button>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.depreciation-expenses.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.depreciation_expenses.new_depreciation_expense') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.depreciation_expenses.branch') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.fixed_asset') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.category') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.amount') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.date') }}</th>
                            <th class="text-nowrap text-center">{{ __('general.pages.depreciation_expenses.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td>{{ $expense->id }}</td>
                                <td>{{ $expense->branch?->name ?? '—' }}</td>
                                <td>{{ $expense->model?->code ?? '' }} {{ $expense->model?->name ?? $expense->model_id }}</td>
                                <td>{{ $expense->category?->name ?? '—' }}</td>
                                <td>{{ currencyFormat($expense->amount ?? 0, true) }}</td>
                                <td>{{ $expense->expense_date }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.depreciation-expenses.details', $expense->id) }}">
                                        {{ __('general.pages.depreciation_expenses.details') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('general.pages.depreciation_expenses.no_records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('layouts.hud.partials.select2-script')
@endpush
