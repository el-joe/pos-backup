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
                        <select class="form-select" wire:model.live="filters.parent_id">
                            <option value="all">{{ __('general.pages.categories.all') }}</option>
                            @foreach ($allCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.categories.status') }}</label>
                        <select class="form-select" wire:model.live="filters.active">
                            <option value="all">{{ __('general.pages.categories.all') }}</option>
                            <option value="1">{{ __('general.pages.categories.active') }}</option>
                            <option value="0">{{ __('general.pages.categories.inactive') }}</option>
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
                <button class="btn btn-theme" data-bs-toggle="modal" data-bs-target="#editCategoryModal" wire:click="setCurrent(null)">
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
                                        wire:click="setCurrent({{ $category->id }})"
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

    <!-- Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">
                        {{ $current?->id ? __('general.pages.categories.edit_category') : __('general.pages.categories.new_category') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">{{ __('general.pages.categories.name') }}</label>
                            <input type="text" class="form-control" wire:model="data.name" id="categoryName" placeholder="{{ __('general.pages.categories.name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="categoryParent" class="form-label">{{ __('general.pages.categories.parent_category') }}</label>
                            <select class="form-select" wire:model="data.parent_id" id="categoryParent">
                                <option value="">{{ __('general.pages.categories.n_a') }}</option>
                                @foreach ($allCategories as $cat)
                                    @if ($current?->id !== $cat->id)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3" wire:ignore>
                            <label for="categoryIcon" class="form-label">{{ __('general.pages.categories.icon') }}</label>
                            <select class="selectpicker form-control" name="data.icon" id="categoryIcon" data-live-search="true" title="{{ __('general.pages.categories.select_icon') }}">
                                @foreach ($bootstrapIcons as $icon)
                                    <option value="{{ $icon }}" data-content="<i class='{{ $icon }}'></i> {{ $icon }}">
                                        {{ $icon }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="categoryActive" wire:model="data.active">
                            <label class="form-check-label" for="categoryActive">{{ __('general.pages.categories.is_active') }}</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.categories.close') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.categories.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script>
        $('.selectpicker').selectpicker({});

        $('.selectpicker').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            @this.set($(this).attr('name'), $(this).val());
        });

        // add livewire event
        Livewire.on('changeSelect', (data) => {

            $('.selectpicker').selectpicker('val',data[0]);
        });
    </script>
@endpush
