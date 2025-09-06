<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Categories</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit category --}}
                <a  class="btn btn-primary" data-toggle="modal" data-target="#editCategoryModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New Category
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Parent Category</th>
                        <th>Status</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->parent ? $category->parent->name : 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $category->active ? 'success' : 'danger' }}">
                                    {{ $category->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#editCategoryModal" wire:click="setCurrent({{ $category->id }})" data-toggle="tooltip" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary m-r-10"></i>
                                </a>
                                <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $category->id }})">
                                    <i class="fa fa-close text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

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
