<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Expense Categories</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit expense category --}}
                <a class="btn btn-primary" data-toggle="modal" data-target="#editExpenseCategoryModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New Expense Category
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenseCategories as $expenseCategory)
                    <tr>
                        <td>{{ $expenseCategory->id }}</td>
                        <td>{{ $expenseCategory->name }}</td>
                        <td>
                            <span class="badge badge-{{ $expenseCategory->active ? 'success' : 'danger' }}">{{ $expenseCategory->active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="text-nowrap">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#editExpenseCategoryModal" wire:click="setCurrent({{ $expenseCategory->id }})" data-toggle="tooltip" data-original-title="Edit">
                                <i class="fa fa-pencil text-primary m-r-10"></i>
                            </a>
                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $expenseCategory->id }})">
                                <i class="fa fa-close text-danger m-r-10"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $expenseCategories->links() }}
            </div>
        </div>
    </div>

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
