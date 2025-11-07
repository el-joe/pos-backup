<div class="col-12">

    <!-- Stock Transfer Details -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Stock Transfer Details</h3>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="from_branch_id" class="form-label">From Branch</label>
                    <select id="from_branch_id" wire:model.change="data.from_branch_id" class="form-select">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="to_branch_id" class="form-label">To Branch</label>
                    <select id="to_branch_id" wire:model.change="data.to_branch_id" class="form-select">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="ref_no" class="form-label">Ref No.</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-product-hunt"></i></span>
                        <input type="text" class="form-control" id="ref_no" placeholder="Ref No." wire:model="data.ref_no">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="transfer_date" class="form-label">Transfer Date</label>
                    <input type="date" class="form-control" id="transfer_date" wire:model="data.transfer_date">
                </div>

                <div class="col-md-4">
                    <label for="expense_paid_branch_id" class="form-label">Who will pay for the expense?</label>
                    <select id="expense_paid_branch_id" wire:model.change="data.expense_paid_branch_id" class="form-select">
                        <option value="">Select Branch</option>
                        @foreach ($selectedBranches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" wire:model.change="data.status" class="form-select">
                        <option value="">Select Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
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

    <!-- Order Products -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Order Products</h3>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input
                            type="text"
                            class="form-control"
                            id="product_search"
                            placeholder="Search Product by name/code/sku"
                            onkeydown="productSearchEvent(event)"
                        >
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Selling Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($items ?? []) as $index => $product)
                            <tr>
                                <td class="fw-semibold">{{ $product['name'] }}</td>
                                <td>
                                    <select wire:model.change="items.{{ $index }}.unit_id" class="form-select">
                                        <option value="">Select Unit</option>
                                        @foreach ($product['units'] as $unit)
                                            <option value="{{ $unit['id'] }}" {{ $items[$index]['unit_id'] == $unit['id'] ? 'selected' : '' }}>
                                                {{ $unit['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model.blur="items.{{ $index }}.qty" step="any" min="1" max="{{ $product['max_stock'] ?? 0 }}" placeholder="0.00">
                                    <small class="text-muted">Max: {{ $product['max_stock'] }}</small>
                                </td>
                                <td>{{ $product['unit_cost'] }}</td>
                                <td>{{ $product['sell_price'] }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" wire:click="delete({{ $index }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Expenses -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Expenses</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Expense Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['expenses'] ?? [] as $index => $expense)
                        <tr>
                            <td><input type="text" class="form-control" wire:model="data.expenses.{{ $index }}.description" placeholder="Description"></td>
                            <td><input type="number" class="form-control" wire:model.blur="data.expenses.{{ $index }}.amount" step="any" min="0" placeholder="0.00"></td>
                            <td><input type="date" class="form-control" wire:model="data.expenses.{{ $index }}.expense_date"></td>
                            <td>
                                <button class="btn btn-danger btn-sm" wire:click="removeExpense({{ $index }})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button class="btn btn-primary mt-2" wire:click="addExpense">
                <i class="fa fa-plus"></i> Add New Expense
            </button>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <div class="row justify-content-center g-3">
                <div class="col-md-3">
                    <button type="button" class="btn btn-success w-100 btn-lg"
                        wire:click="save" {{ count($items ?? []) === 0 ? 'disabled' : '' }}>
                        <i class="fa fa-save"></i> Do Transfer
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-secondary w-100 btn-lg">
                        <i class="fa fa-times"></i> Cancel
                    </button>
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

@push('styles')
<style>
    .order-products-table th, .order-products-table td {
        white-space: nowrap;
    }
    .order-products-table thead th {
        background: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    .order-products-table tbody tr:hover {
        background: #f1f3f9;
        transition: background 0.2s;
    }
</style>
@endpush

@push('scripts')
<script>
    window.addEventListener('reset-search-input', () => {
        const input = document.getElementById('product_search');
        if (input) input.value = '';
    });

    function productSearchEvent(event) {
        if (event.key === "Enter") {
            @this.set('product_search', event.target.value);
            clearTimeout(window.productSearchTimeout);
        } else {
            clearTimeout(window.productSearchTimeout);
            window.productSearchTimeout = setTimeout(() => {
                @this.set('product_search', event.target.value);
            }, 2000);
        }
    }
</script>
@endpush
