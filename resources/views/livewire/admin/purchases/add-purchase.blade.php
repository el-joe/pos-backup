<div>
    <div class="white-box">
        <h3 class="box-title m-b-0">Purchase Details</h3>
        <p class="text-muted m-b-30 font-13"></p>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="form-group col-sm-4">
                    <label for="branch_id">Branch</label>
                    <select id="branch_id" wire:model.change="data.branch_id" class="form-control">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- supplier then category --}}
                <div class="form-group col-sm-4">
                    <label for="supplier_id">Supplier</label>
                    <select id="supplier_id" wire:model.change="data.supplier_id" class="form-control">
                        <option value="">Select Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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
                    <label for="order_date">Order Date</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-barcode"></i></div>
                        <input type="date" class="form-control" id="order_date" placeholder="Order Date" wire:model="data.order_date">
                    </div>
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
                            placeholder="Search Product by name/code"
                            wire:model.live.debounce.1000ms="product_search"
                            x-data
                            @reset-search-input.window="$el.value=''"
                        >
                    </div>
                </div>
            </div>
        </div>
        {{-- products table --}}
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Discount (%)</th>
                                <th>Net Unit Cost</th>
                                <th>Total Net Cost</th>
                                <th>Tax (%)</th>
                                <th>Subtotal (Incl. Tax)</th>
                                <th>Extra Margin (%)</th>
                                <th>Selling Price</th>
                                <th>Grand Total (Incl. Tax & Profit)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderProducts??[] as $product)
                                <tr>
                                    <td>{{ $product['name'] }}</td>
                                    <td>
                                        <select name="unit_id" id="unit_id" wire:model.change="orderProducts.{{ $product['id'] }}.unit_id" class="form-control">
                                            <option value="">Select Unit</option>
                                            @foreach ($product['units'] as $unit)
                                                <option value="{{ $unit['id'] }}">{{ $unit['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model.blur="orderProducts.{{ $product['id'] }}.qty" min="1" placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model.blur="orderProducts.{{ $product['id'] }}.purchase_price" step="0.01" min="0" placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model.blur="orderProducts.{{ $product['id'] }}.discount_percentage" step="0.01" min="0" placeholder="0.00">
                                    </td>
                                    <td>
                                        {{ number_format($product['unit_cost_after_discount'], 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($product['unit_cost_after_discount'] * $product['qty'], 2) }}
                                    </td>
                                    <td>
                                        <select name="tax_percentage" id="tax_percentage" wire:model.change="orderProducts.{{ $product['id'] }}.tax_percentage" class="form-control">
                                            <option value="">Select Tax</option>
                                            @foreach ($taxes as $tax)
                                                <option value="{{ $tax->rate }}" {{ $product['tax_percentage'] == $tax->rate ? 'selected' : '' }}>{{ $tax->name }} - {{ $tax->rate }}%</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        {{ number_format($product['sub_total'] * $product['qty'], 2) }}
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model.blur="orderProducts.{{ $product['id'] }}.x_margin" step="0.01" min="0" placeholder="0.00">
                                    </td>
                                    <td>
                                        {{ number_format($product['sell_price'], 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($product['total'], 2) }}
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" wire:click="delete({{ $product['id'] }})">
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

    <div class="white-box">
        <h3 class="box-title m-b-0">Order Expenses</h3>
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
        <h3 class="box-title m-b-0">Order Adjustments</h3>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="form-group col-sm-4">
                    <label for="discount_type">Discount Type</label>
                    <select id="discount_type" wire:model.live="data.discount_type" class="form-control">
                        <option value="">Select Discount Type</option>
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
                @if($data['discount_type']??false)
                    <div class="form-group col-sm-4">
                        <label for="discount_value">Discount Value</label>
                        <div class="input-group">
                            @if ($data['discount_type'] === 'percentage')
                                <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                            @elseif ($data['discount_type'] === 'fixed')
                                <div class="input-group-addon"><i class="fa fa-dollar"></i></div>
                            @endif
                            <input type="number" class="form-control" id="discount_value" placeholder="Discount Value" wire:model.blur="data.discount_value" step="any" min="0">
                        </div>
                    </div>
                @endif

                <div class="form-group col-sm-4">
                    <label for="tax">Tax</label>
                    <select id="tax" wire:model.live="data.tax_id" class="form-control">
                        <option value="">Select Tax</option>
                        @foreach ($taxes as $tax)
                            <option value="{{ $tax->id }}">{{ $tax->name }} - {{ $tax->rate }}%</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Purchase Summary & Totals --}}
    <div class="white-box">
        <h3 class="box-title m-b-0">Purchase Summary</h3>
        <p class="text-muted m-b-30 font-13">Review your purchase totals and finalize the order</p>

        <div class="row">
            {{-- Left side - Calculation breakdown --}}
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Order Breakdown</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Items Count</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-cube"></i></div>
                                        <input type="text" class="form-control" value="{{ count($orderProducts ?? []) }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Total Quantity</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-plus"></i></div>
                                        <input type="text" class="form-control" value="{{ $totalQuantity ?? 0 }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Subtotal (Before Discount)</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calculator"></i></div>
                                        <input type="text" class="form-control" value="{{ number_format($orderSubTotal ?? 0, 2) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Discount Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-minus"></i></div>
                                        <input type="text" class="form-control" value="{{ number_format($orderDiscountAmount ?? 0, 2) }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">After Discount</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-minus-circle"></i></div>
                                        <input type="text" class="form-control" value="{{ number_format($orderTotalAfterDiscount ?? 0, 2) }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Tax Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                        <input type="text" class="form-control" value="{{ number_format($orderTaxAmount ?? 0, 2) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right side - Final totals and actions --}}
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">Final Totals</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label text-primary">Grand Total</label>
                            <div class="input-group">
                                <div class="input-group-addon bg-primary"><i class="fa fa-money text-white"></i></div>
                                <input type="text" class="form-control input-lg text-center font-weight-bold"
                                       value="{{ number_format($orderGrandTotal ?? 0, 2) }}" readonly
                                       style="font-size: 18px; font-weight: bold; color: #2c3e50;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Payment Status</label>
                            <select class="form-control" wire:model.live="data.payment_status">
                                <option value="pending">Pending</option>
                                <option value="partial_paid">Partial Payment</option>
                                <option value="full_paid">Fully Paid</option>
                            </select>
                        </div>

                        {{-- make select to choose payment account from selected Supplier when payment_status is partial or paid --}}
                        @if(in_array($data['payment_status']??false, ['partial_paid', 'full_paid']))
                            <div class="form-group">
                                <label class="control-label">Payment Account</label>
                                <select class="form-control" wire:model="data.payment_account">
                                    <option value="">Select Payment Account</option>
                                    @foreach($paymentAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->paymentMethod->name }} - {{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if(($data['payment_status'] ?? '') === 'partial_paid')
                            <div class="form-group">
                                <label class="control-label">Paid Amount</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                                    <input type="number" class="form-control" wire:model="data.payment_amount"
                                           step="0.01" min="0" max="{{ $grandTotal ?? 0 }}" placeholder="0.00">
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="control-label">Notes</label>
                            <textarea class="form-control" wire:model="data.payment_note" rows="3"
                                      placeholder="Add any additional notes..."></textarea>
                        </div>

                        <hr>

                        <div class="text-center">
                            <button type="button" class="btn btn-success btn-lg btn-block waves-effect waves-light"
                                    wire:click="savePurchase" {{ count($orderProducts ?? []) === 0 ? 'disabled' : '' }}>
                                <i class="fa fa-save"></i> Save Purchase Order
                            </button>
                            <button type="button" class="btn btn-default btn-block waves-effect waves-light m-t-10">
                                <i class="fa fa-times"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title">Quick Stats</h4>
                    </div>
                    <div class="panel-body p-10">
                        <div class="row">
                            <div class="col-xs-6 text-center">
                                <h4 class="text-info m-0">{{ count($orderProducts ?? []) }}</h4>
                                <small class="text-muted">Items</small>
                            </div>
                            <div class="col-xs-6 text-center">
                                <h4 class="text-success m-0">{{ number_format($grandTotal ?? 0, 0) }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
