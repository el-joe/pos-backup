<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.expenses.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.expenses.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Branch -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.expenses.branch') }}</label>
                        <select class="form-select select2"
                                name="filters.branch_id">
                            <option value="">{{ __('general.layout.all_branches') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Category -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.expenses.category') }}</label>
                        <select class="form-select select2"
                                name="filters.expense_category_id">
                            <option value="">{{ __('general.pages.expenses.all_categories') }}</option>
                            @foreach($expenseCategories as $category)
                                <option value="{{ $category->id }}" {{ ($filters['expense_category_id']??'') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">{{ __('general.pages.expenses.type') }}</label>
                        <select class="form-select select2"
                                wire:model="filters.type">
                            <option value="">{{ __('general.pages.expenses.types.all_types') }}</option>
                            <option value="normal">{{ __('general.pages.expenses.types.normal') }}</option>
                            <option value="prepaid">{{ __('general.pages.expenses.types.prepaid') }}</option>
                            <option value="accrued">{{ __('general.pages.expenses.types.accrued') }}</option>
                        </select>
                    </div>
                    <!-- Filter by Date -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.expenses.date') }}</label>
                        <input type="date" class="form-control"
                               wire:model="filters.date" />
                    </div>
                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.expenses.reset') }}
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
            <h3 class="card-title mb-0">{{ __('general.titles.expenses') }}</h3>
            <div class="d-flex align-items-center gap-2">
                @adminCan('expenses.export')
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.expenses.export') }}
                </button>
                @endadminCan
                @adminCan('expenses.create')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.expenses.new_expense') }}
                </button>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.tableHandler',[
                'rows'=>$expenses,
                'columns'=>$columns,
                'headers'=>$headers
            ])
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Add/Edit Expense Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExpenseModalLabel">{{ $current?->id ? __('general.pages.expenses.edit_expense') : __('general.pages.expenses.new_expense') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="expenseBranch" class="form-label">{{ __('general.pages.expenses.branch') }}</label>
                            @if(admin()->branch_id == null)
                            <select id="expenseBranch" name="data.branch_id" class="form-select select2">
                                <option value="">{{ __('general.pages.expenses.select_branch') }}</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ ($data['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @else
                            <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="expenseCategory" class="form-label">{{ __('general.pages.expenses.category') }}</label>
                            <select id="expenseCategory" name="data.expense_category_id" class="form-select select2">
                                <option value="">{{ __('general.pages.expenses.select_category') }}</option>
                                @foreach($expenseCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ ($data['expense_category_id']??'') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="expenseType" class="form-label">{{ __('general.pages.expenses.type') }}</label>
                            <select id="expenseType" name="data.type" class="form-select select2">
                                <option value="">{{ __('general.pages.expenses.types.all_types') }}</option>
                                @foreach(\App\Enums\Tenant\ExpenseTypeEnum::cases() as $type)
                                    <option value="{{ $type->value }}" {{ ($data['type']??'') == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="expenseAmount" class="form-label">{{ __('general.pages.expenses.amount') }}</label>
                            <input type="number" step="any" class="form-control" wire:model="data.amount" id="expenseAmount" placeholder="{{ __('general.pages.expenses.enter_amount') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="taxPercentage" class="form-label">{{ __('general.pages.expenses.tax_percentage') }}</label>
                            <input type="number" step="any" class="form-control" wire:model="data.tax_percentage" id="taxPercentage" placeholder="{{ __('general.pages.expenses.enter_tax_percentage') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="expenseDate" class="form-label">{{ __('general.pages.expenses.date') }}</label>
                            <input type="date" class="form-control" wire:model="data.expense_date" id="expenseDate">
                        </div>

                        <div class="col-12">
                            <label for="expenseNote" class="form-label">{{ __('general.pages.expenses.note') }}</label>
                            <textarea class="form-control" wire:model="data.note" id="expenseNote" rows="3" placeholder="{{ __('general.pages.expenses.enter_note') }}"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.expenses.close') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.expenses.save') }}</button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
@include('layouts.hud.partials.select2-script')
@endpush
