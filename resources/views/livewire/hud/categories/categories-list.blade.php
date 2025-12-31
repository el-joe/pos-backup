<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.categories.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.categories.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.categories.search_by_name') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.categories.search') }} ..."
                            wire:model.blur="filters.search">
                    </div>

                    {{-- Parent Categories --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.categories.parent_category') }}</label>
                        <select class="form-select select2" name="filters.parent_id">
                            <option value="all">{{ __('general.pages.categories.all') }}</option>
                            @foreach ($allCategories as $cat)
                                <option value="{{ $cat->id }}" {{ ($filters['parent_id']??0) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.categories.status') }}</label>
                        <select class="form-select select2" name="filters.active">
                            <option value="all" {{ ($filters['active']??'all') == 'all' ? 'selected' : '' }}>{{ __('general.pages.categories.all') }}</option>
                            <option value="1" {{ ($filters['active']??'all') == '1' ? 'selected' : '' }}>{{ __('general.pages.categories.active') }}</option>
                            <option value="0" {{ ($filters['active']??'all') == '0' ? 'selected' : '' }}>{{ __('general.pages.categories.inactive') }}</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.categories.reset') }}
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
            <h5 class="mb-0">{{ __('general.pages.categories.categories') }}</h5>

            <div class="d-flex align-items-center gap-2">
                @adminCan('categories.export')
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.categories.export') }}
                </button>
                @endadminCan
                @adminCan('categories.create')
                <button class="btn btn-theme" data-bs-toggle="modal" data-bs-target="#editCategoryModal" wire:click="$dispatch('category-set-current', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.categories.new_category') }}
                </button>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.categories.name') }}</th>
                            <th>{{ __('general.pages.categories.parent_category') }}</th>
                            <th>{{ __('general.pages.categories.icon') }}</th>
                            <th>{{ __('general.pages.categories.status') }}</th>
                            <th class="text-nowrap text-end">{{ __('general.pages.categories.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
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
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $categories->links() }}
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
</div>

@push('scripts')
    @livewire('admin.categories.category-modal')
    @include('layouts.hud.partials.select2-script')
@endpush
