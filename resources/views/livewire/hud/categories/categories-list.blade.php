<div class="col-12">
    <x-hud.filter-card
        :title="__('general.pages.categories.filters')"
        icon="fa-filter"
        collapse-id="hudCategoryFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hudCategoryFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.categories.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.categories.search_by_name') }}</label>
                <input type="text" class="form-control"
                    placeholder="{{ __('general.pages.categories.search') }} ..."
                    wire:model.blur="filters.search">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.categories.parent_category') }}</label>
                <select class="form-select select2" name="filters.parent_id">
                    <option value="all">{{ __('general.pages.categories.all') }}</option>
                    @foreach ($allCategories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['parent_id']??0) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.categories.status') }}</label>
                <select class="form-select select2" name="filters.active">
                    <option value="all" {{ ($filters['active']??'all') == 'all' ? 'selected' : '' }}>{{ __('general.pages.categories.all') }}</option>
                    <option value="1" {{ ($filters['active']??'all') == '1' ? 'selected' : '' }}>{{ __('general.pages.categories.active') }}</option>
                    <option value="0" {{ ($filters['active']??'all') == '0' ? 'selected' : '' }}>{{ __('general.pages.categories.inactive') }}</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.categories.reset') }}
                </button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.pages.categories.categories')" icon="fa-folder-open">
        <x-slot:actions>
            @adminCan('categories.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.categories.export') }}
                </button>
            @endadminCan
            @adminCan('categories.create')
                <button class="btn btn-theme" data-bs-toggle="modal" data-bs-target="#editCategoryModal" wire:click="$dispatch('category-set-current', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.categories.new_category') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>#</th>
                <th>{{ __('general.pages.categories.name') }}</th>
                <th>{{ __('general.pages.categories.parent_category') }}</th>
                <th>{{ __('general.pages.categories.icon') }}</th>
                <th>{{ __('general.pages.categories.status') }}</th>
                <th class="text-nowrap text-end">{{ __('general.pages.categories.action') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->parent ? $category->parent->name : __('general.pages.categories.n_a') }}</td>
                <td>
                    @if($category->icon)
                        <i class="{{ $category->icon }}"></i> {{ $category->icon }}
                    @else
                        {{ __('general.pages.categories.n_a') }}
                    @endif
                </td>
                <td>
                    <span class="badge bg-{{ $category->active ? 'success' : 'danger' }}">
                        {{ $category->active ? __('general.pages.categories.active') : __('general.pages.categories.inactive') }}
                    </span>
                </td>
                <td class="text-end text-nowrap">
                    @adminCan('categories.update')
                        <button class="btn btn-sm btn-outline-primary me-1"
                            data-bs-toggle="modal"
                            data-bs-target="#editCategoryModal"
                            wire:click="$dispatch('category-set-current', {id : '{{ $category->id }}' })"
                            title="{{ __('general.pages.categories.edit') }}">
                            <i class="fa fa-pencil"></i>
                        </button>
                    @endadminCan
                    @adminCan('categories.delete')
                        <button class="btn btn-sm btn-outline-danger"
                            wire:click="deleteAlert({{ $category->id }})"
                            title="{{ __('general.pages.categories.delete') }}">
                            <i class="fa fa-times"></i>
                        </button>
                    @endadminCan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse

        @if($categories->hasPages())
            <x-slot:footer>
                {{ $categories->links('pagination::default5') }}
            </x-slot:footer>
        @endif
    </x-hud.table-card>
</div>

@push('scripts')
    @livewire('admin.categories.category-modal')
    @include('layouts.hud.partials.select2-script')
@endpush
