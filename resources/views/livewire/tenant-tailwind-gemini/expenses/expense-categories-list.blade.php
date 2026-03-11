<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.expense-categories.filters')" icon="fa-filter" collapse-id="hudExpenseCategoryFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#hudExpenseCategoryFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.expense-categories.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.expense-categories.search') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.expense-categories.search_placeholder') }}" wire:model.blur="filters.name">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.expense-categories.status') }}</label>
                <select class="form-select select2" name="filters.active">
                    <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.expense-categories.all') }}</option>
                    <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.expense-categories.active') }}</option>
                    <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.expense-categories.inactive') }}</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.expense-categories.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.expense-categories')" icon="fa-tags">
        <x-slot:actions>
            @adminCan('expense_categories.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.expense-categories.export') }}
                </button>
            @endadminCan
            @adminCan('expense_categories.create')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editExpenseCategoryModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.expense-categories.new_expense_category') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.expense-categories.id') }}</th>
                <th>{{ __('general.pages.expense-categories.ar_name') }}</th>
                <th>{{ __('general.pages.expense-categories.name') }}</th>
                <th>{{ __('general.pages.expense-categories.status_label') }}</th>
                <th class="text-nowrap text-center">{{ __('general.pages.expense-categories.actions') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($expenseCategories as $expenseCategory)
            <tr>
                <td>{{ $expenseCategory->id }}</td>
                <td class="h5">{{ $expenseCategory->ar_name }}</td>
                <td class="h5">{{ $expenseCategory->name }}</td>
                <td>
                    <span class="badge bg-{{ $expenseCategory->active ? 'success' : 'danger' }}">
                        {{ $expenseCategory->active ? __('general.pages.expense-categories.active') : __('general.pages.expense-categories.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap text-center">
                    @if($expenseCategory->default != 1)
                        @adminCan('expense_categories.update')
                            <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editExpenseCategoryModal" wire:click="setCurrent({{ $expenseCategory->id }})" title="{{ __('general.pages.expense-categories.edit') }}">
                                <i class="fa fa-edit"></i>
                            </button>
                        @endadminCan
                        @adminCan('expense_categories.delete')
                            <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $expenseCategory->id }})" title="{{ __('general.pages.expense-categories.delete') }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        @endadminCan
                    @else
                        <i class="fas fa-ban"></i>
                    @endif
                </td>
            </tr>
            @foreach ($expenseCategory->children as $child)
                <tr>
                    <td>----{{ $child->id }}</td>
                    <td>{{ $child->ar_name }}</td>
                    <td>{{ $child->name }}</td>
                    <td>
                        <span class="badge bg-{{ $child->active ? 'success' : 'danger' }}">
                            {{ $child->active ? __('general.pages.expense-categories.active') : __('general.pages.expense-categories.inactive') }}
                        </span>
                    </td>
                    <td class="text-nowrap text-center">
                        @if($child->default != 1)
                            @adminCan('expense_categories.update')
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editExpenseCategoryModal" wire:click="setCurrent({{ $child->id }})" title="{{ __('general.pages.expense-categories.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endadminCan
                            @adminCan('expense_categories.delete')
                                <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $child->id }})" title="{{ __('general.pages.expense-categories.delete') }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @endadminCan
                        @else
                            <i class="fas fa-ban"></i>
                        @endif
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse
    </x-tenant-tailwind-gemini.table-card>

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
                    <div class="col-md-12">
                        <label for="expenseCategoryArName" class="form-label">{{ __('general.pages.expense-categories.ar_name') }}</label>
                        <input type="text" class="form-control" wire:model="data.ar_name" id="expenseCategoryArName" placeholder="{{ __('general.pages.expense-categories.enter_expense_category_ar_name') }}">
                    </div>
                    {{-- select parent category --}}
                    <div class="col-md-12">
                        <label for="expenseCategoryParent" class="form-label">{{ __('general.pages.expense-categories.parent_category') }}</label>
                        <select class="form-select select2" id="expenseCategoryParent" wire:model="data.parent_id">
                            <option value="">{{ __('general.pages.expense-categories.select_parent_category') }}</option>
                            @foreach($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}">{{ $parentCategory->{app()->getLocale() === 'ar' ? 'ar_name' : 'name'} ?? $parentCategory->name ?? '---' }}</option>
                            @endforeach
                        </select>
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
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
