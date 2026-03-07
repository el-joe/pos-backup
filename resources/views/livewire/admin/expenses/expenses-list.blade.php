<div class="col-sm-12">
    <x-admin.filter-card :title="__('general.pages.expenses.filters')" icon="fa-filter" collapse-id="adminExpenseFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button type="button" class="btn btn-default btn-sm" wire:click="$toggle('collapseFilters')" data-toggle="collapse" data-target="#adminExpenseFilterCollapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.expenses.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.expenses.branch') }}</label>
                <select class="form-control" wire:model.live="filters.branch_id">
                    <option value="">{{ __('general.layout.all_branches') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.expenses.category') }}</label>
                <select class="form-control" wire:model.live="filters.expense_category_id">
                    <option value="">{{ __('general.pages.expenses.all_categories') }}</option>
                    @foreach($expenseCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.expenses.type') }}</label>
                <select class="form-control" wire:model.live="filters.type">
                    <option value="">{{ __('general.pages.expenses.types.all_types') }}</option>
                    <option value="normal">{{ __('general.pages.expenses.types.normal') }}</option>
                    <option value="prepaid">{{ __('general.pages.expenses.types.prepaid') }}</option>
                    <option value="accrued">{{ __('general.pages.expenses.types.accrued') }}</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.expenses.date') }}</label>
                <input type="date" class="form-control" wire:model.live="filters.date" />
            </div>
            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.expenses.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.titles.expenses')" icon="fa-money" :render-table="false">
        <x-slot:actions>
            @adminCan('expenses.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.expenses.export') }}
                </button>
            @endadminCan
            @adminCan('expenses.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addExpenseModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.expenses.new_expense') }}
                </a>
            @endadminCan
        </x-slot:actions>

        @include('admin.partials.tableHandler', [
            'rows' => $expenses,
            'columns' => $columns,
            'headers' => $headers,
        ])
    </x-admin.table-card>

    <div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="editExpenseCategoryModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExpenseCategoryModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Expense</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-6">
                        <label for="expenseCategoryName">Branch</label>
                        <select name="branch" id="expenseCategoryName" wire:model="data.branch_id" class="form-control">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6">
                        <label for="expenseCategoryName">Category</label>
                        <select name="category" id="expenseCategoryName" wire:model="data.expense_category_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($expenseCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="expenseAmount">Amount</label>
                        <input type="number" step="any" class="form-control" wire:model="data.amount" id="expenseAmount" placeholder="Enter amount">
                    </div>

                    {{-- tax percentage --}}
                    <div class="form-group">
                        <label for="taxPercentage">Tax Percentage (%)</label>
                        <input type="number" step="any" class="form-control" wire:model="data.tax_percentage" id="taxPercentage" placeholder="Enter tax percentage">
                    </div>

                    {{-- expense date --}}
                    <div class="form-group">
                        <label for="expenseDate">Date</label>
                        <input type="date" class="form-control" wire:model="data.expense_date" id="expenseDate">
                    </div>

                    {{-- note --}}
                    <div class="form-group">
                        <label for="expenseNote">Note</label>
                        <textarea class="form-control" wire:model="data.note" id="expenseNote" placeholder="Enter note"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
@endpush
