<div>
    <div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fa fa-file-text me-2"></i> Sale Order Details #{{ $order->id }}</h5>
        </div>

        <div class="card-body">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'details' ? 'active' : '' }}" wire:click="$set('activeTab', 'details')" data-bs-toggle="tab" type="button">
                        <i class="fa fa-info-circle me-1"></i> Details
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'products' ? 'active' : '' }}" wire:click="$set('activeTab', 'products')" data-bs-toggle="tab" type="button">
                        <i class="fa fa-cubes me-1"></i> Products
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'transactions' ? 'active' : '' }}" wire:click="$set('activeTab', 'transactions')" data-bs-toggle="tab" type="button">
                        <i class="fa fa-exchange me-1"></i> Transactions
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Details Tab -->
                <div class="tab-pane fade {{ $activeTab === 'details' ? 'show active' : '' }}">
                    <h5 class="fw-bold mb-3"><i class="fa fa-info-circle me-2"></i> Sale Details</h5>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="p-3 bg-dark-subtle rounded">
                                <h6><i class="fa fa-user me-1"></i> Customer</h6>
                                <p class="mb-0">{{ $order->customer->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-dark-subtle rounded">
                                <h6><i class="fa fa-building me-1"></i> Branch</h6>
                                <p class="mb-0">{{ $order->branch->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-dark-subtle rounded">
                                <h6><i class="fa fa-hashtag me-1"></i> Invoice No.</h6>
                                <p class="mb-0">#{{ $order->invoice_number }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-dark-subtle rounded">
                                <h6><i class="fa fa-calendar me-1"></i> Order Date</h6>
                                <p class="mb-0">{{ $order->created_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3"><i class="fa fa-list-alt me-2"></i> Sale Summary</h5>
                    <div class="row g-3">
                        @php
                            $summaryCards = [
                                ['title'=>'Items Count','icon'=>'fa-cube','bg'=>'bg-primary-subtle','color'=>'text-primary','value'=>$itemsCount],
                                ['title'=>'Total Quantity','icon'=>'fa-plus','bg'=>'bg-info-subtle','color'=>'text-info','value'=>$totalQty],
                                ['title'=>'Subtotal','icon'=>'fa-calculator','bg'=>'bg-warning-subtle','color'=>'text-warning','value'=>$subTotal],
                                ['title'=>'Discount','icon'=>'fa-tag','bg'=>'bg-danger-subtle','color'=>'text-danger','value'=>$totalDiscount],
                                ['title'=>'Tax','icon'=>'fa-percent','bg'=>'bg-secondary-subtle','color'=>'text-secondary','value'=>$totalTax],
                                ['title'=>'Grand Total','icon'=>'fa-money','bg'=>'bg-gradient','color'=>'text-white','value'=>$grandTotal, 'gradient'=>'linear-gradient(135deg, #2196f3, #00c6ff)'],
                                ['title'=>'Paid','icon'=>'fa-check-circle','bg'=>'bg-success-subtle','color'=>'text-success','value'=>$paid],
                                ['title'=>'Due','icon'=>'fa-clock-o','bg'=>'bg-light-subtle','color'=>'text-light','value'=>number_format(($grandTotal - $paid), 2)],
                            ];
                        @endphp

                        @foreach($summaryCards as $card)
                            <div class="col-md-3 col-sm-6">
                                <div class="card border-0 shadow-sm h-100 {{ $card['bg'] ?? '' }}">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="rounded-circle p-3 bg-opacity-25 {{ $card['color'] }} me-3">
                                            <i class="fa {{ $card['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 {{ $card['color'] }}">{{ $card['title'] }}</h6>
                                            <h4 class="mb-0 fw-bold {{ $card['color'] }}">{{ $card['value'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade {{ $activeTab === 'products' ? 'show active' : '' }}">
                    <h5 class="fw-bold mb-3"><i class="fa fa-cubes me-2"></i> Sale Products</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Refunded</th>
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
                                                <span class="badge bg-success">Fully Refunded</span>
                                            @elseif($item->actual_qty < $item->qty)
                                                <span class="badge bg-warning text-dark">Partially Refunded</span>
                                            @else
                                                <span class="badge bg-primary">Not Refunded</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->actual_qty <= 0)
                                                <button class="btn btn-sm btn-secondary" disabled><i class="fa fa-undo"></i> Refund</button>
                                            @else
                                                <button class="btn btn-sm btn-danger" wire:click="setCurrentItem({{ $item->id }})" data-bs-toggle="modal" data-bs-target="#refundModal">
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

                <!-- Transactions Tab -->
                <div class="tab-pane fade {{ $activeTab === 'transactions' ? 'show active' : '' }}">
                    <h5 class="fw-bold mb-3"><i class="fa fa-exchange me-2"></i> Order Transactions</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
