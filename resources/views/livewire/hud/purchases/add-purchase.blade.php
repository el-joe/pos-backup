<div>
   <div class="col-12">
    <div class="card shadow-sm  mb-4">
        <div class="card-header">
            <h5 class="mb-0">Purchase Details</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="branch_id" class="form-label">Branch</label>
                    @if(admin()->branch_id == null)
                    <select id="branch_id" wire:model.change="data.branch_id" class="form-select">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @else
                    <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                    @endif
                </div>

                <div class="col-md-4">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select id="supplier_id" wire:model.change="data.supplier_id" class="form-select">
                        <option value="">Select Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="ref_no" class="form-label">Ref No.</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-product-hunt"></i></span>
                        <input type="text" id="ref_no" class="form-control" placeholder="Ref No." wire:model="data.ref_no">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="order_date" class="form-label">Order Date</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                        <input type="date" id="order_date" class="form-control" placeholder="Order Date" wire:model="data.order_date">
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
</div>

    {{-- new white-box for order products --}}
    <div class="col-12">
        <div class="card shadow-sm  mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Products</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input
                                type="text"
                                id="product_search"
                                class="form-control"
                                placeholder="Search Product by name/code"
                                wire:model.live.debounce.1000ms="product_search"
                                x-data
                                @reset-search-input.window="$el.value=''"
                            >
                        </div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="table-responsive">
                    <div class="responsive-table-wrapper">
                        <table class="table table-bordered align-middle order-products-table" style="min-width:1200px;">
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
                                    <th>Selling Price/Unit</th>
                                    <th>Grand Total (Incl. Tax & Profit)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderProducts ?? [] as $index => $product)
                                    <tr>
                                        <td class="fw-semibold">{{ $product['name'] }}</td>
                                        <td>
                                            <select name="unit_id"
                                                id="unit_id"
                                                wire:model.change="orderProducts.{{ $index }}.unit_id"
                                                class="form-select">
                                                <option value="">Select Unit</option>
                                                @foreach ($product['units'] as $unit)
                                                    <option value="{{ $unit['id'] }}">{{ $unit['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control"
                                                wire:model.blur="orderProducts.{{ $index }}.qty"
                                                min="1" placeholder="0.00">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control"
                                                wire:model.blur="orderProducts.{{ $index }}.purchase_price"
                                                step="0.01" min="0" placeholder="0.00">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control"
                                                wire:model.blur="orderProducts.{{ $index }}.discount_percentage"
                                                step="0.01" min="0" placeholder="0.00">
                                        </td>
                                        <td class="text-muted">{{ number_format($product['unit_cost_after_discount'], 2) }}</td>
                                        <td class="text-muted">{{ number_format($product['unit_cost_after_discount'] * $product['qty'], 2) }}</td>
                                        <td>
                                            <select name="tax_percentage"
                                                id="tax_percentage"
                                                wire:model.change="orderProducts.{{ $index }}.tax_percentage"
                                                class="form-select">
                                                <option value="">Select Tax</option>
                                                @foreach ($taxes as $tax)
                                                    <option value="{{ $tax->rate }}" {{ $product['tax_percentage'] == $tax->rate ? 'selected' : '' }}>
                                                        {{ $tax->name }} - {{ $tax->rate }}%
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-muted">{{ number_format($product['sub_total'] * $product['qty'], 2) }}</td>
                                        <td>
                                            <input type="number" class="form-control"
                                                wire:model.blur="orderProducts.{{ $index }}.x_margin"
                                                step="0.01" min="0" placeholder="0.00">
                                        </td>
                                        <td class="fw-semibold">{{ number_format($product['sell_price'], 2) }}</td>
                                        <td class="fw-semibold">{{ number_format($product['total'], 2) }}</td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-danger rounded-2"
                                                wire:click="delete({{ $index }})">
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

            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>

<div class="col-12">
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Order Expenses</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Expense Date</th>
                            <th class="text-center">Action</th>
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
                                <td class="text-center">
                                    <button class="btn btn-danger btn-sm" wire:click="removeExpense({{ $index }})" title="Remove">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary" wire:click="addExpense">
                    <i class="fa fa-plus"></i> Add New Expense
                </button>
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

<div class="col-12">
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Order Adjustments</h5>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="discount_type" class="form-label">Discount Type</label>
                    <select id="discount_type" wire:model.live="data.discount_type" class="form-select">
                        <option value="">Select Discount Type</option>
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>

                @if($data['discount_type'] ?? false)
                    <div class="col-md-4">
                        <label for="discount_value" class="form-label">Discount Value</label>
                        <div class="input-group">
                            @if ($data['discount_type'] === 'percentage')
                                <span class="input-group-text"><i class="fa fa-percent"></i></span>
                            @elseif ($data['discount_type'] === 'fixed')
                                <span class="input-group-text"><i class="fa fa-dollar"></i></span>
                            @endif
                            <input type="number" class="form-control" id="discount_value" placeholder="Discount Value"
                                wire:model.blur="data.discount_value" step="any" min="0">
                        </div>
                    </div>
                @endif

                <div class="col-md-4">
                    <label for="tax" class="form-label">Tax</label>
                    <select id="tax" wire:model.live="data.tax_id" class="form-select">
                        <option value="">Select Tax</option>
                        @foreach ($taxes as $tax)
                            <option value="{{ $tax->id }}">{{ $tax->name }} - {{ $tax->rate }}%</option>
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
</div>

    {{-- Purchase Summary & Totals --}}
<div class="col-12">
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Purchase Summary</h5>
            <p class="text-muted small mb-0">Review your purchase totals and finalize the order</p>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Left side - Calculation breakdown --}}
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">Order Breakdown</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Items Count</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-cube"></i></span>
                                        <input type="text" class="form-control" value="{{ count($orderProducts ?? []) }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Total Quantity</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-plus"></i></span>
                                        <input type="text" class="form-control" value="{{ $totalQuantity ?? 0 }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Subtotal (Before Discount)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-calculator"></i></span>
                                        <input type="text" class="form-control" value="{{ number_format($orderSubTotal ?? 0, 2) }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <label class="form-label">Discount Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-minus"></i></span>
                                        <input type="text" class="form-control" value="{{ number_format($orderDiscountAmount ?? 0, 2) }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">After Discount</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-minus-circle"></i></span>
                                        <input type="text" class="form-control" value="{{ number_format($orderTotalAfterDiscount ?? 0, 2) }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Tax Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-percent"></i></span>
                                        <input type="text" class="form-control" value="{{ number_format($orderTaxAmount ?? 0, 2) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right side - Final totals and actions --}}
                <div class="col-lg-4">
                    <div class="card border-primary shadow-sm mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="card-title mb-0">Final Totals</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-primary fw-bold">Grand Total</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white"><i class="fa fa-money"></i></span>
                                    <input type="text"
                                           class="form-control text-center fw-bold"
                                           value="{{ number_format($orderGrandTotal ?? 0, 2) }}"
                                           readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Status</label>
                                <select class="form-select" wire:model.live="data.payment_status">
                                    <option value="">Choose One...</option>
                                    <option value="pending">Pending</option>
                                    <option value="partial_paid">Partial Payment</option>
                                    <option value="full_paid">Fully Paid</option>
                                </select>
                            </div>

                            @if(in_array($data['payment_status'] ?? false, ['partial_paid', 'full_paid']))
                                <div class="mb-3">
                                    <label class="form-label">Payment Account</label>
                                    <select class="form-select" wire:model="data.payment_account">
                                        <option value="">Select Payment Account</option>
                                        @foreach($paymentAccounts as $account)
                                            <option value="{{ $account->id }}">
                                                {{ $account->paymentMethod->name }} - {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if(($data['payment_status'] ?? '') === 'partial_paid')
                                <div class="mb-3">
                                    <label class="form-label">Paid Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                        <input type="number" class="form-control" wire:model="data.payment_amount"
                                               step="0.01" min="0" max="{{ $grandTotal ?? 0 }}" placeholder="0.00">
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" wire:model="data.payment_note" rows="3"
                                          placeholder="Add any additional notes..."></textarea>
                            </div>

                            <hr>

                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success btn-lg"
                                        wire:click="savePurchase"
                                        {{ count($orderProducts ?? []) === 0 ? 'disabled' : '' }}>
                                    <i class="fa fa-save"></i> Save Purchase Order
                                </button>
                                <button type="button" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="card border-info shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h6 class="card-title mb-0">Quick Stats</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-6">
                                    <h4 class="text-info mb-0">{{ count($orderProducts ?? []) }}</h4>
                                    <small class="text-muted">Items</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success mb-0">{{ number_format($grandTotal ?? 0, 0) }}</h4>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> {{-- /Right Side --}}
            </div> {{-- /Row --}}
        </div> {{-- /Card Body --}}

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>
</div>

@push('scripts')
    <script>
        window.addEventListener('reset-search-input', event => {
            const input = document.getElementById('product_search');
            if (input) {
                input.value = '';
            }
        });
    </script>

@endpush
