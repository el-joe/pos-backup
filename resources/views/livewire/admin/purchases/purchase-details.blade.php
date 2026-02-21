<div>
    <div class="white-box">
        <h3 class="box-title">Purchase Order Details #{{ $id }}</h3>
        <!-- Nav tabs -->
        <ul class="nav customtab nav-tabs" role="tablist">
            <li role="presentation" class="{{ $activeTab === 'details' ? 'active' : '' }}"><a wire:click="$set('activeTab', 'details')" href="#home1" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Details</span></a></li>
            <li role="presentation" class="{{ $activeTab === 'products' ? 'active' : '' }}"><a wire:click="$set('activeTab', 'products')" href="#profile1" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Products</span></a></li>
            <li role="presentation" class="{{ $activeTab === 'expenses' ? 'active' : '' }}"><a wire:click="$set('activeTab', 'expenses')" href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-exchange-horizontal"></i></span> <span class="hidden-xs">Expenses</span></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade {{ $activeTab === 'details' ? 'in active' : '' }}" id="home1">
                <div class="purchase-details-container">
                    <h3 class="section-title"><i class="fa fa-info-circle"></i> Purchase Details</h3>

                    <!-- Basic Purchase Info -->
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="detail-box">
                                <h4><i class="fa fa-building"></i> Branch</h4>
                                <p>{{ $purchase->branch?->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="detail-box">
                                <h4><i class="fa fa-truck"></i> Supplier</h4>
                                <p>{{ $purchase->supplier?->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="detail-box">
                                <h4><i class="fa fa-hashtag"></i> Ref NO.</h4>
                                <p>{{ $purchase->ref_no ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="detail-box">
                                <h4><i class="fa fa-calendar"></i> Order Date</h4>
                                <p>{{ carbon($purchase->order_date)->format('F j, Y') ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <!-- Purchase Summary Cards -->
                    <h3 class="section-title"><i class="fa fa-list-alt"></i> Purchase Summary</h3>
                    <div class="row g-3">
                        <!-- Items Count -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #e3f2fd;">
                                <div class="card-icon" style="background: #2196f3;"><i class="fa fa-cube"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#2196f3;">Items Count</h5>
                                    <h2 style="color:#1565c0;">{{ $purchase->purchaseItems->count() }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Total Quantity -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #e0f7fa;">
                                <div class="card-icon" style="background: #00bcd4;"><i class="fa fa-plus"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#00bcd4;">Total Quantity</h5>
                                    <h2 style="color:#00838f;">{{ $actualQty }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Expenses Total -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #ffebee;">
                                <div class="card-icon" style="background: #e53935;">
                                    <i class="fa fa-credit-card"></i>
                                </div>
                                <div class="card-content">
                                    <h5 style="color:#e53935;">Expenses Total</h5>
                                    <h2 style="color:#b71c1c;">{{ number_format($purchase->expenses->sum('amount') ?? 0, 2) }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Subtotal -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #fffde7;">
                                <div class="card-icon" style="background: #ffc107;"><i class="fa fa-calculator"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#ffc107;">Subtotal</h5>
                                    <h2 style="color:#ff6f00;">{{ number_format($orderSubTotal ?? 0, 2) }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <!-- After Discount -->
                        <div class="col-md-4 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #eceff1;">
                                <div class="card-icon" style="background: #607d8b;"><i class="fa fa-minus-circle"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#607d8b;">After Discount</h5>
                                    <h2 style="color:#263238;">{{ number_format($orderTotalAfterDiscount ?? 0, 2) }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Tax Amount -->
                        <div class="col-md-4 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #e8f5e9;">
                                <div class="card-icon" style="background: #43a047;"><i class="fa fa-percent"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#43a047;">Tax Amount</h5>
                                    <h2 style="color:#1b5e20;">{{ number_format($orderTaxAmount ?? 0, 2) }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Grand Total -->
                        <div class="col-md-4 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card grand-total-card" style="background: linear-gradient(135deg, #2196f3, #00c6ff); color: #fff;">
                                <div class="card-icon" style="background: #2196f3;"><i class="fa fa-money"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#fff;">Grand Total</h5>
                                    <h2 style="color:#fff;">{{ number_format($orderGrandTotal ?? 0, 2) }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade {{ $activeTab === 'products' ? 'in active' : '' }}" id="profile1">
                <div class="products-container">
                    <h3 class="section-title"><i class="fa fa-cubes"></i> Order Products</h3>

                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped product-table">
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
                                        <th>Grand Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase->purchaseItems as $item)
                                    <tr>
                                        <td><strong>{{ $item?->product?->name }}</strong></td>
                                        <td>{{ $item?->unit?->name ?? 'N/A' }}</td>
                                        <td>{{ $item->actual_qty }}</td>
                                        <td>{{ number_format($item->purchase_price, 2) }}</td>
                                        <td>{{ number_format($item->discount_percentage, 2) }}%</td>
                                        <td>{{ number_format($item->unit_cost_after_discount, 2) }}</td>
                                        <td>{{ number_format($item->total_after_discount, 2) }}</td>
                                        <td>{{ $item->tax_percentage ? number_format($item->tax_percentage, 2) : 'N/A' }}%</td>
                                        <td>{{ number_format($item->unit_amount_after_tax, 2) }}</td>
                                        <td>{{ number_format($item->x_margin, 2) }}%</td>
                                        <td>{{ number_format($item->total_after_x_margin, 2) }}</td>
                                        <td>{{ number_format($item->total_after_x_margin * $item->actual_qty, 2) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger refund-btn" wire:click="setCurrentItem({{ $item->id }})" data-toggle="modal" data-target="#refundModal">
                                                <i class="fa fa-undo"></i> Refund
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

            <div role="tabpanel" class="tab-pane fade {{ $activeTab === 'expenses' ? 'in active' : '' }}" id="messages1">
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
                                @foreach ($purchase->expenses ?? [] as $index => $expense)
                                <tr>
                                    <td>
                                        {{ $expense->description ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ number_format($expense->amount ?? 0, 2) }}
                                    </td>
                                    <td>
                                        {{ $expense->expense_date ? carbon($expense->expense_date)->format('Y-m-d') : 'N/A' }}
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" wire:click="deleteExpenseConfirm({{ $expense->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <button class="btn btn-primary" wire:click="addExpense">
                            <i class="fa fa-plus"></i> Add New Expense
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Refund Modal -->
    <div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-labelledby="refundModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content refund-modal-content">
                <div class="modal-header refund-modal-header">
                    <h5 class="modal-title mx-auto"><i class="fa fa-undo"></i> Refund Item</h5>
                    <button type="button" class="close refund-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="refund-warning">
                        <i class="fa fa-exclamation-circle"></i>
                        <div>
                            <p class="mb-1">You are about to refund the product:</p>
                            <strong class="refund-product-name">{{ $currentItem?->product?->name }}</strong>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="refundQty"><strong>Quantity to Refund</strong></label>
                        <input type="number" class="form-control refund-input" id="refundQty" min="1" max="{{ $currentItem?->actual_qty ?? 1 }}" wire:model="refundedQty">
                        <small class="form-text text-muted">Max refundable quantity: {{ $currentItem?->actual_qty ?? 1 }}</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    @if ($currentItem)
                    <button type="button" class="btn btn-danger" wire:click="refundPurchaseItem">
                        <i class="fa fa-check"></i> Confirm Refund
                    </button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@push('styles')
<style>
    .purchase-details-container {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 25px;
        margin-top: 20px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 22px;
        margin-bottom: 20px;
        font-weight: 600;
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 10px;
    }

    .section-divider {
        margin: 30px 0;
        border-top: 2px dashed #ddd;
    }

    .detail-box {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        background: #fafafa;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .detail-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .detail-box h4 {
        font-size: 16px;
        margin-bottom: 8px;
        color: #333;
    }

    .detail-box p {
        font-size: 15px;
        font-weight: 600;
        color: #007bff;
    }

    /* Summary cards */
    .summary-card.modern-card {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-card.modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    .card-icon {
        flex-shrink: 0;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-size: 24px;
        margin-right: 15px;
    }

    .card-icon.bg-primary {
        background: #007bff;
    }

    .card-icon.bg-info {
        background: #17a2b8;
    }

    .card-icon.bg-success {
        background: #28a745;
    }

    .card-icon.bg-warning {
        background: #ffc107;
    }

    .card-icon.bg-danger {
        background: #dc3545;
    }

    .card-icon.bg-secondary {
        background: #6c757d;
    }

    .card-icon.bg-gradient {
        background: linear-gradient(135deg, #007bff, #00c6ff);
    }

    .card-content h5 {
        margin: 0;
        font-size: 14px;
        color: #6c757d;
        text-transform: uppercase;
    }

    .card-content h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
    }

    /* Grand total special card */
    .grand-total-card {
        background: linear-gradient(135deg, #007bff, #00c6ff);
        color: #fff;
    }

    .grand-total-card .card-content h5,
    .grand-total-card .card-content h2 {
        color: #fff;
    }

    /* ---------- Refund Modal Styles ---------- */
    /* Refund Modal Content */
    .refund-modal-content {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    /* Header */
    .refund-modal-header {
        background: linear-gradient(135deg, #ff7e5f, #feb47b);
        /* Orange gradient */
        color: #fff;
        padding: 18px 20px;
        position: relative;
        text-align: center;
        font-size: 18px;
        font-weight: 600;
    }

    /* Close button top-right */
    .refund-close {
        position: absolute;
        top: 12px;
        right: 16px;
        background: none;
        border: none;
        font-size: 24px;
        color: #fff;
        cursor: pointer;
    }

    /* Warning content */
    .refund-warning {
        background: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 12px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 15px;
        margin-bottom: 20px;
    }

    .refund-warning i {
        color: #856404;
        font-size: 24px;
    }

    .refund-product-name {
        font-size: 16px;
        color: #d9534f;
    }

    /* Quantity input */
    .refund-input {
        border: 2px solid #ff7e5f;
        border-radius: 8px;
        padding: 10px;
        font-size: 16px;
    }

    .refund-input:focus {
        outline: none;
        box-shadow: 0 0 6px #ff7e5f;
    }

    /* Footer buttons */
    .modal-footer .btn-light {
        background: #f1f1f1;
        border-radius: 6px;
    }

    .modal-footer .btn-danger {
        border-radius: 6px;
        background: #ff4e50;
        border: none;
    }

    .modal-footer .btn-danger:hover {
        background: #ff2c2c;
    }

</style>
@endpush
