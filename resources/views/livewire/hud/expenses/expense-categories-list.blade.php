<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.expense-categories.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.expense-categories.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.expense-categories.search') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.expense-categories.search_placeholder') }}"
                            wire:model.blur="filters.name">
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.expense-categories.status') }}</label>
                        <select class="form-select select2" name="filters.active">
                            <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.expense-categories.all') }}</option>
                            <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.expense-categories.active') }}</option>
                            <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.expense-categories.inactive') }}</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.expense-categories.reset') }}
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
            <h3 class="card-title mb-0">{{ __('general.titles.expense-categories') }}</h3>
            <div class="d-flex align-items-center gap-2">
                @adminCan('expense_categories.export')
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.expense-categories.export') }}
                </button>
                @endadminCan
                @adminCan('expense_categories.create')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editExpenseCategoryModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.expense-categories.new_expense_category') }}
                </button>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.expense-categories.id') }}</th>
                            <th>{{ __('general.pages.expense-categories.name') }}</th>
                            <th>{{ __('general.pages.expense-categories.status_label') }}</th>
                            <th class="text-nowrap text-center">{{ __('general.pages.expense-categories.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenseCategories as $expenseCategory)
                        <tr>
                            <td>{{ $expenseCategory->id }}</td>
                            <td>{{ $expenseCategory->name }}</td>
                            <td>
                                <span class="badge bg-{{ $expenseCategory->active ? 'success' : 'danger' }}">
                                    {{ $expenseCategory->active ? __('general.pages.expense-categories.active') : __('general.pages.expense-categories.inactive') }}
                                </span>
                            </td>
                            <td class="text-nowrap text-center">
                                @adminCan('expense_categories.update')
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editExpenseCategoryModal"
                                    wire:click="setCurrent({{ $expenseCategory->id }})" title="{{ __('general.pages.expense-categories.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                @endadminCan
                                @adminCan('expense_categories.delete')
                                <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $expenseCategory->id }})" title="{{ __('general.pages.expense-categories.delete') }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endadminCan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    {{ $expenseCategories->links() }}
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

    <!-- Edit Expense Category Modal -->
    <div class="modal fade" id="editExpenseCategoryModal" tabindex="-1" aria-labelledby="editExpenseCategoryModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $current?->id ? __('general.pages.expense-categories.edit_expense_category') : __('general.pages.expense-categories.new_expense_category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <label for="expenseCategoryName" class="form-label">{{ __('general.pages.expense-categories.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name" id="expenseCategoryName" placeholder="{{ __('general.pages.expense-categories.enter_expense_category_name') }}">
                    </div>
                    <div class="col-12">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="expenseCategoryActive" wire:model="data.active">
                            <label class="form-check-label" for="expenseCategoryActive">{{ __('general.pages.expense-categories.is_active') }}</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.expense-categories.close') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.expense-categories.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('layouts.hud.partials.select2-script')
@endpush
