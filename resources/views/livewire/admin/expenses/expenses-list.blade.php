<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Expenses</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit expense category --}}
                <a class="btn btn-primary" data-toggle="modal" data-target="#addExpenseModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New Expense
                </a>
            </div>
        </div>

        <x-table-component :rows="$expenses" :columns="$columns" :headers="$headers" />
    </div>

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
