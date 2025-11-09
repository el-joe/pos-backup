<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.categories') }}</h5>
            <button class="btn btn-theme" data-bs-toggle="modal" data-bs-target="#editCategoryModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus me-1"></i> New Category
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Icon</th>
                            <th>Status</th>
                            <th class="text-nowrap text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->parent ? $category->parent->name : 'N/A' }}</td>
                                <td>
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}"></i> {{ $category->icon }}
                                    @else
                                        N/A
                                    @endif
                                <td>
                                    <span class="badge bg-{{ $category->active ? 'success' : 'danger' }}">
                                        {{ $category->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <button class="btn btn-sm btn-outline-primary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal"
                                        wire:click="setCurrent({{ $category->id }})"
                                        title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="deleteAlert({{ $category->id }})"
                                        title="Delete">
                                        <i class="fa fa-times"></i>
                                    </button>
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
                        {{ $current?->id ? 'Edit' : 'New' }} Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="categoryName" placeholder="Enter category name">
                        </div>

                        <div class="mb-3">
                            <label for="categoryParent" class="form-label">Parent Category</label>
                            <select class="form-select" wire:model="data.parent_id" id="categoryParent">
                                <option value="">N/A</option>
                                @foreach ($allCategories as $cat)
                                    @if ($current?->id !== $cat->id)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3" wire:ignore>
                            <label for="categoryIcon" class="form-label">Icon</label>
                            <select class="selectpicker form-control" name="data.icon" id="categoryIcon" data-live-search="true" title="Select Icon">
                                @foreach ($bootstrapIcons as $icon)
                                    <option value="{{ $icon }}" data-content="<i class='{{ $icon }}'></i> {{ $icon }}">
                                        {{ $icon }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="categoryActive" wire:model="data.active">
                            <label class="form-check-label" for="categoryActive">Is Active</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
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
