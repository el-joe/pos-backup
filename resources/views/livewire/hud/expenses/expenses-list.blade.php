<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">{{ __('general.titles.expenses') }}</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus"></i> New Expense
            </button>
        </div>

        <div class="card-body">
            @include('admin.partials.tableHandler',[
                'rows'=>$expenses,
                'columns'=>$columns,
                'headers'=>$headers
            ])
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Add/Edit Expense Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExpenseModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="expenseBranch" class="form-label">Branch</label>
                            @if(admin()->branch_id == null)
                            <select id="expenseBranch" wire:model="data.branch_id" class="form-select">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @else
                            <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="expenseCategory" class="form-label">Category</label>
                            <select id="expenseCategory" wire:model="data.expense_category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach($expenseCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="expenseAmount" class="form-label">Amount</label>
                            <input type="number" step="any" class="form-control" wire:model="data.amount" id="expenseAmount" placeholder="Enter amount">
                        </div>

                        <div class="col-md-6">
                            <label for="taxPercentage" class="form-label">Tax Percentage (%)</label>
                            <input type="number" step="any" class="form-control" wire:model="data.tax_percentage" id="taxPercentage" placeholder="Enter tax percentage">
                        </div>

                        <div class="col-md-6">
                            <label for="expenseDate" class="form-label">Date</label>
                            <input type="date" class="form-control" wire:model="data.expense_date" id="expenseDate">
                        </div>

                        <div class="col-12">
                            <label for="expenseNote" class="form-label">Note</label>
                            <textarea class="form-control" wire:model="data.note" id="expenseNote" rows="3" placeholder="Enter note"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('styles')
@endpush
