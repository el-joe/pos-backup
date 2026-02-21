<div>
    <div class="white-box">
        <h3 class="box-title m-b-0">Stock Transfer Details</h3>
        <p class="text-muted m-b-30 font-13"></p>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="form-group col-sm-4">
                    <label for="from_branch_id">From Branch</label>
                    <select id="from_branch_id" wire:model.change="data.from_branch_id" class="form-control">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="to_branch_id">To Branch</label>
                    <select id="to_branch_id" wire:model.change="data.to_branch_id" class="form-control">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-4">
                    <label for="ref_no">Ref NO.</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-product-hunt"></i></div>
                        <input type="text" class="form-control" id="ref_no" placeholder="Ref NO." wire:model="data.ref_no">
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label for="transfer_date">Transfer Date</label>
                    <input type="date" class="form-control" id="transfer_date" placeholder="Transfer Date" wire:model="data.transfer_date">
                </div>
                <div class="form-group col-sm-4">
                    <label for="status">Who will pay for the expense?</label>
                    <select id="status" wire:model.change="data.expense_paid_branch_id" class="form-control">
                        <option value="">Select Branch</option>
                        @foreach ($selectedBranches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="status">Status</label>
                    <select id="status" wire:model.change="data.status" class="form-control">
                        <option value="">Select Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>
    {{-- new white-box for order products --}}

    <div class="white-box">
        <h3 class="box-title m-b-0">Order Products</h3>
        <p class="text-muted m-b-30 font-13"></p>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="form-group col-sm-4">
                    {{-- <label for="product_search">Search Product</label> --}}
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                        <input
                            type="text" class="form-control" id="product_search"
                            placeholder="Search Product by name/code/sku"
                            onkeydown="productSearchEvent(event)"
                        >
                    </div>
                </div>
            </div>
        </div>
        {{-- products table --}}
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <div class="responsive-table-wrapper" style="overflow-x:auto;">
                        <table class="table table-bordered order-products-table" style="min-width:1200px;">
                        <thead>
                            <tr>
                                <th style="min-width:140px;">Product</th>
                                <th style="min-width:100px;">Unit</th>
                                <th style="min-width:80px;">Qty</th>
                                <th style="min-width:110px;">Unit Price</th>
                                <th style="min-width:120px;">Selling Price</th>
                                <th style="min-width:90px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (($items??[]) as $index=>$product)
                                <tr>
                                    <td style="vertical-align:middle; font-weight:500; color:#444;">{{ $product['name'] }}</td>
                                    <td>
                                        <select name="unit_id" id="unit_id" wire:model.change="items.{{ $index }}.unit_id" class="form-control" style="min-width:90px;">
                                            <option value="">Select Unit</option>
                                            @foreach ($product['units'] as $unit)
                                                <option value="{{ $unit['id'] }}" {{ $items[$index]['unit_id'] == $unit['id'] ? 'selected' : '' }}>{{ $unit['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model.blur="items.{{ $index }}.qty" step="any" min="1" max="{{ $product['max_stock'] ?? 0 }}" placeholder="0.00" style="min-width:70px;">
                                        <span class="text-muted">Max: {{ $product['max_stock'] }}</span>
                                    </td>
                                    <td>
                                        {{ $product['unit_cost'] }}
                                    </td>
                                    <td>
                                        {{ $product['sell_price'] }}
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" wire:click="delete({{ $index }})" style="border-radius:6px; padding:6px 12px;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="white-box">
        <h3 class="box-title m-b-0">Expenses</h3>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <table class="table table-bordered">
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
                                <td>
                                    <input type="text" class="form-control" wire:model="data.expenses.{{ $index }}.description" placeholder="Description">
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model.blur="data.expenses.{{ $index }}.amount" step="any" min="0" placeholder="0.00">
                                </td>
                                <td>
                                    <input type="date" class="form-control" wire:model="data.expenses.{{ $index }}.expense_date">
                                </td>
                                <td>
                                    <button class="btn btn-danger" wire:click="removeExpense({{ $index }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-primary" wire:click="addExpense">
                    <i class="fa fa-plus"></i> Add New Expense
                </button>
            </div>
        </div>
    </div>
    <div class="white-box">
        <div class="row text-center" style="margin-top: 20px;">
            <div class="col-xs-12 col-sm-6 col-md-3 col-md-offset-3">
                <button type="button"
                    class="btn btn-success btn-lg btn-block waves-effect waves-light"
                    wire:click="save" {{ count($items ?? []) === 0 ? 'disabled' : '' }}>
                    <i class="fa fa-save"></i> Do Transfer
                </button>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <button type="button"
                    class="btn btn-default btn-lg btn-block waves-effect waves-light">
                    <i class="fa fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .order-products-table th, .order-products-table td {
            white-space: nowrap;
            vertical-align: middle;
        }
        .responsive-table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .order-products-table {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
            margin-bottom: 0;
        }
        .order-products-table thead th {
            background: #f7f7f7;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #e1e1e1;
        }
        .order-products-table tbody tr:hover {
            background: #f0f8ff;
            transition: background 0.2s;
        }
        @media (max-width: 900px) {
            .order-products-table {
                min-width: 900px;
            }
        }
        @media (max-width: 600px) {
            .order-products-table {
                min-width: 600px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        window.addEventListener('reset-search-input', event => {
            const input = document.getElementById('product_search');
            if (input) {
                input.value = '';
            }
        });
    </script>
    <script>
        function productSearchEvent(event) {
            // i want set livewire key after 2 seconds of user stop typing
            if (event.key === "Enter") {
                // if user press enter key then set livewire key immediately
                @this.set('product_search', event.target.value);
                clearTimeout(window.productSearchTimeout);
            } else {
                // if user type other key then set livewire key after 2 seconds
                clearTimeout(window.productSearchTimeout);
                window.productSearchTimeout = setTimeout(() => {
                    @this.set('product_search', event.target.value);
                }, 2000);
            }
        }
    </script>

@endpush
