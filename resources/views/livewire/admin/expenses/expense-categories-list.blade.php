<div class="col-sm-12">
    <x-admin.filter-card :title="__('general.pages.expense-categories.filters')" icon="fa-filter" collapse-id="adminExpenseCategoryFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button type="button" class="btn btn-default btn-sm" wire:click="$toggle('collapseFilters')" data-toggle="collapse" data-target="#adminExpenseCategoryFilterCollapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.expense-categories.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.expense-categories.search') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.expense-categories.search_placeholder') }}" wire:model.live="filters.name">
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.expense-categories.status') }}</label>
                <select class="form-control" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.expense-categories.all') }}</option>
                    <option value="1">{{ __('general.pages.expense-categories.active') }}</option>
                    <option value="0">{{ __('general.pages.expense-categories.inactive') }}</option>
                </select>
            </div>
            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.expense-categories.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.titles.expense-categories')" icon="fa-tags">
        <x-slot:actions>
            @adminCan('expense_categories.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.expense-categories.export') }}
                </button>
            @endadminCan
            @adminCan('expense_categories.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editExpenseCategoryModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.expense-categories.new_expense_category') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.expense-categories.id') }}</th>
                <th>{{ __('general.pages.expense-categories.ar_name') }}</th>
                <th>{{ __('general.pages.expense-categories.name') }}</th>
                <th>{{ __('general.pages.expense-categories.status_label') }}</th>
                <th class="text-nowrap">{{ __('general.pages.expense-categories.actions') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($expenseCategories as $expenseCategory)
            <tr>
                <td>{{ $expenseCategory->id }}</td>
                <td>{{ $expenseCategory->ar_name }}</td>
                <td>{{ $expenseCategory->name }}</td>
                <td>
                    <span class="badge badge-{{ $expenseCategory->active ? 'success' : 'danger' }}">{{ $expenseCategory->active ? __('general.pages.expense-categories.active') : __('general.pages.expense-categories.inactive') }}</span>
                </td>
                <td class="text-nowrap">
                    @if($expenseCategory->default != 1)
                        @adminCan('expense_categories.update')
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#editExpenseCategoryModal" wire:click="setCurrent({{ $expenseCategory->id }})" data-original-title="{{ __('general.pages.expense-categories.edit') }}">
                                <i class="fa fa-pencil text-primary m-r-10"></i>
                            </a>
                        @endadminCan
                        @adminCan('expense_categories.delete')
                            <a href="javascript:void(0)" data-original-title="{{ __('general.pages.expense-categories.delete') }}" wire:click="deleteAlert({{ $expenseCategory->id }})">
                                <i class="fa fa-close text-danger m-r-10"></i>
                            </a>
                        @endadminCan
                    @else
                        <i class="fa fa-ban text-muted"></i>
                    @endif
                </td>
            </tr>
            @foreach ($expenseCategory->children as $child)
                <tr>
                    <td>----{{ $child->id }}</td>
                    <td>{{ $child->ar_name }}</td>
                    <td>{{ $child->name }}</td>
                    <td>
                        <span class="badge badge-{{ $child->active ? 'success' : 'danger' }}">{{ $child->active ? __('general.pages.expense-categories.active') : __('general.pages.expense-categories.inactive') }}</span>
                    </td>
                    <td class="text-nowrap">
                        @if($child->default != 1)
                            @adminCan('expense_categories.update')
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#editExpenseCategoryModal" wire:click="setCurrent({{ $child->id }})" data-original-title="{{ __('general.pages.expense-categories.edit') }}">
                                    <i class="fa fa-pencil text-primary m-r-10"></i>
                                </a>
                            @endadminCan
                            @adminCan('expense_categories.delete')
                                <a href="javascript:void(0)" data-original-title="{{ __('general.pages.expense-categories.delete') }}" wire:click="deleteAlert({{ $child->id }})">
                                    <i class="fa fa-close text-danger m-r-10"></i>
                                </a>
                            @endadminCan
                        @else
                            <i class="fa fa-ban text-muted"></i>
                        @endif
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse

        @if($expenseCategories->hasPages())
            <x-slot:footer>
                {{ $expenseCategories->links() }}
            </x-slot:footer>
        @endif
    </x-admin.table-card>

    {{-- add edit modal for discounts page --}}
    <div class="modal fade" id="editExpenseCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editExpenseCategoryModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExpenseCategoryModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Expense Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-6">
                        <label for="expenseCategoryName">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="expenseCategoryName" placeholder="Enter expense category name">
                    </div>
                    <div class="form-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" id="expenseCategoryActive" wire:model="data.active">
                            <span class="checkmark"></span> Is Active
                        </label>
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
