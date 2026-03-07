<div class="col-sm-12">
    <x-admin.filter-card
        :title="__('general.pages.categories.filters')"
        icon="fa-filter"
        collapse-id="adminCategoryFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button type="button"
                    class="btn btn-default btn-sm"
                    wire:click="$toggle('collapseFilters')"
                    data-toggle="collapse"
                    data-target="#adminCategoryFilterCollapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.categories.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.categories.search_by_name') }}</label>
                <input type="text"
                       class="form-control"
                       placeholder="{{ __('general.pages.categories.search') }} ..."
                       wire:model.live="filters.search">
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.categories.parent_category') }}</label>
                <select class="form-control" wire:model.live="filters.parent_id">
                    <option value="">{{ __('general.pages.categories.all') }}</option>
                    @foreach ($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.categories.status') }}</label>
                <select class="form-control" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.categories.all') }}</option>
                    <option value="1">{{ __('general.pages.categories.active') }}</option>
                    <option value="0">{{ __('general.pages.categories.inactive') }}</option>
                </select>
            </div>

            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.categories.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.pages.categories.categories')" icon="fa-folder-open">
        <x-slot:actions>
            @adminCan('categories.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.categories.export') }}
                </button>
            @endadminCan
            @adminCan('categories.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editCategoryModal" wire:click="$dispatch('category-set-current', null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.categories.new_category') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>#</th>
                <th>{{ __('general.pages.categories.name') }}</th>
                <th>{{ __('general.pages.categories.parent_category') }}</th>
                <th>{{ __('general.pages.categories.status') }}</th>
                <th class="text-nowrap">{{ __('general.pages.categories.action') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->parent ? $category->parent->name : __('general.pages.categories.n_a') }}</td>
                <td>
                    <span class="badge badge-{{ $category->active ? 'success' : 'danger' }}">
                        {{ $category->active ? __('general.pages.categories.active') : __('general.pages.categories.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @adminCan('categories.update')
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#editCategoryModal" wire:click="$dispatch('category-set-current', { id : '{{ $category->id }}' })" data-original-title="{{ __('general.pages.categories.edit') }}">
                            <i class="fa fa-pencil text-primary m-r-10"></i>
                        </a>
                    @endadminCan
                    @adminCan('categories.delete')
                        <a href="javascript:void(0)" data-original-title="{{ __('general.pages.categories.delete') }}" wire:click="deleteAlert({{ $category->id }})">
                            <i class="fa fa-close text-danger"></i>
                        </a>
                    @endadminCan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse

        @if($categories->hasPages())
            <x-slot:footer>
                {{ $categories->links() }}
            </x-slot:footer>
        @endif
    </x-admin.table-card>

    {{-- add edit modal for categories page --}}
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="categoryName">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="categoryName" placeholder="Enter category name">
                    </div>
                    <div class="form-group">
                        <label for="categoryParent">Parent Category</label>
                        <select class="form-control" wire:model="data.parent_id" id="categoryParent">
                            <option value="">N/A</option>
                            @foreach ($allCategories as $cat)
                                @if ($current?->id !== $cat->id) {{-- Prevent selecting itself as parent --}}
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" id="categoryActive" wire:model="data.active">
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
