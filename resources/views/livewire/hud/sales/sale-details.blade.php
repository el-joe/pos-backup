<div>
    <div class="white-box">
        <h3 class="box-title">Sale Order Details #{{ $order->id }}</h3>
        <!-- Nav tabs -->
        <ul class="nav customtab nav-tabs" role="tablist">
            <li role="presentation" class="{{ $activeTab === 'details' ? 'active' : '' }}"><a wire:click="$set('activeTab', 'details')" href="#home1" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Details</span></a></li>
            <li role="presentation" class="{{ $activeTab === 'products' ? 'active' : '' }}"><a wire:click="$set('activeTab', 'products')" href="#profile1" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Products</span></a></li>
            <li role="presentation" class="{{ $activeTab === 'transactions' ? 'active' : '' }}"><a wire:click="$set('activeTab', 'transactions')" href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-exchange-horizontal"></i></span> <span class="hidden-xs">Transactions</span></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade {{ $activeTab === 'details' ? 'in active' : '' }}" id="home1">
                <div class="sale-details-container">
                    <h3 class="section-title"><i class="fa fa-info-circle"></i> Sale Details</h3>

                    <!-- Basic Sale Info -->
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="detail-box">
                                <h4><i class="fa fa-user"></i> Customer</h4>
                                <p>{{ $order->customer->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="detail-box">
                                <h4><i class="fa fa-building"></i> Branch</h4>
                                <p>{{ $order->branch->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="detail-box">
                                <h4><i class="fa fa-hashtag"></i> Invoice No.</h4>
                                <p>#{{ $order->invoice_number }}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="detail-box">
                                <h4><i class="fa fa-calendar"></i> Order Date</h4>
                                <p>{{ $order->created_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <!-- Sale Summary Cards -->
                    <h3 class="section-title"><i class="fa fa-list-alt"></i> Sale Summary</h3>
                    <div class="row g-3">
                        <!-- Items Count -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #e3f2fd;">
                                <div class="card-icon" style="background: #2196f3;"><i class="fa fa-cube"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#2196f3;">Items Count</h5>
                                    <h2 style="color:#1565c0;">{{ $itemsCount }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Total Quantity -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #e0f7fa;">
                                <div class="card-icon" style="background: #00bcd4;"><i class="fa fa-plus"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#00bcd4;">Total Quantity</h5>
                                    <h2 style="color:#00838f;">{{ $totalQty }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Subtotal -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #fffde7;">
                                <div class="card-icon" style="background: #ffc107;"><i class="fa fa-calculator"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#ffc107;">Subtotal</h5>
                                    <h2 style="color:#ff6f00;">{{ $subTotal }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Discount Amount -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #ffebee;">
                                <div class="card-icon" style="background: #f44336;"><i class="fa fa-tag"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#f44336;">Discount @if($order->discount_type == 'rate') ({{ round($order->discount_value,1) }}%)  @endif</h5>
                                    <h2 style="color:#c62828;">{{ $totalDiscount }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Tax Amount -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #f3e5f5;">
                                <div class="card-icon" style="background: #9c27b0;"><i class="fa fa-percent"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#9c27b0;">Tax Amount @if($order->tax_percentage) ({{ round($order->tax_percentage,1) }}%)  @endif</h5>
                                    <h2 style="color:#7b1fa2;">{{ $totalTax }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Grand Total -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card grand-total-card" style="background: linear-gradient(135deg, #2196f3, #00c6ff); color: #fff;">
                                <div class="card-icon" style="background: #2196f3;"><i class="fa fa-money"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#fff;">Grand Total</h5>
                                    <h2 style="color:#fff;">{{ $grandTotal }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Paid Amount -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #e8f5e8;">
                                <div class="card-icon" style="background: #4caf50;"><i class="fa fa-check-circle"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#4caf50;">Paid Amount</h5>
                                    <h2 style="color:#2e7d32;">{{ $paid }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Due Amount -->
                        <div class="col-md-3 col-sm-6" style="margin-bottom:24px;">
                            <div class="summary-card modern-card" style="background: #fff3e0;">
                                <div class="card-icon" style="background: #ff9800;"><i class="fa fa-clock-o"></i></div>
                                <div class="card-content">
                                    <h5 style="color:#ff9800;">Due Amount</h5>
                                    <h2 style="color:#f57c00;">{{ number_format(($grandTotal - $paid), 2) }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade {{ $activeTab === 'products' ? 'in active' : '' }}" id="profile1">
                <div class="products-container">
                    <h3 class="section-title"><i class="fa fa-cubes"></i> Sale Products</h3>

                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped product-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Refunded Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th>Refund Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->saleItems as $item)
                                        <tr>
                                            <td><strong>{{ $item->product?->name }} - {{ $item->unit?->name }}</strong></td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{ $item->refunded_qty }}</td>
                                            <td>{{ number_format($item->sell_price, 2) }}</td>
                                            <td>{{ number_format($item->total, 2) }}</td>
                                            <td>
                                                @if($item->actual_qty <= 0)
                                                    <span class="badge badge-success">Fully Refunded</span>
                                                @elseif($item->actual_qty < $item->qty)
                                                    <span class="badge badge-warning">Partially Refunded</span>
                                                @else
                                                    <span class="badge badge-primary">Not Refunded</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->actual_qty <= 0)
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="fa fa-undo"></i> Refund
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-danger refund-btn" wire:click="setCurrentItem({{ $item->id }})" data-toggle="modal" data-target="#refundModal">
                                                        <i class="fa fa-undo"></i> Refund
                                                    </button>
                                                @endif
                                                </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade {{ $activeTab === 'transactions' ? 'in active' : '' }}" id="messages1">
                <h3 class="box-title m-b-0">Order Transactions</h3>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->transactions->whereIn('type',[
                                    App\Enums\TransactionTypeEnum::SALE_PAYMENT,
                                    App\Enums\TransactionTypeEnum::SALE_PAYMENT_REFUND
                                ]) as $transaction)
                                    <tr>
                                        <td>#{{ $transaction->id }}</td>
                                        <td>{{ $transaction->type->label() }}</td>
                                        <td>{{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ carbon($transaction->created_at)->format('d M Y, h:i A') }}</td>
                                        {{-- <td>
                                            <button class="btn btn-danger" wire:click="deleteTransactionConfirm({{ $transaction->id }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                            <strong class="refund-product-name">{{ $currentItem?->product?->name }} - {{ $currentItem?->unit?->name }}</strong>
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
                    <button type="button" class="btn btn-danger" wire:click="refundSaleItem">
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
    .sale-details-container {
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

    /* Refund Modal Content */
    .refund-modal-content {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    /* Header */
    .refund-modal-header {
        background: linear-gradient(135deg, #ff7e5f, #feb47b);
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
