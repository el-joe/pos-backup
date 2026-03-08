<div>
    <div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fa fa-file-text me-2"></i> {{ __('general.pages.sales.sale_order_details') }} #{{ $order->id }}</h5>
        </div>

        <div class="card-body">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'details' ? 'active' : '' }}" wire:click="$set('activeTab', 'details')" data-bs-toggle="tab" type="button">
                        <i class="fa fa-info-circle me-1"></i> {{ __('general.pages.sales.details_tab') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'products' ? 'active' : '' }}" wire:click="$set('activeTab', 'products')" data-bs-toggle="tab" type="button">
                        <i class="fa fa-cubes me-1"></i> {{ __('general.pages.sales.products_tab') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'transactions' ? 'active' : '' }}" wire:click="$set('activeTab', 'transactions')" data-bs-toggle="tab" type="button">
                        <i class="fa fa-exchange me-1"></i> {{ __('general.pages.sales.transactions_tab') }}
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Details Tab -->
                <div class="tab-pane fade {{ $activeTab === 'details' ? 'show active' : '' }}">
                    <h5 class="fw-bold mb-3"><i class="fa fa-info-circle me-2"></i> {{ __('general.pages.sales.sale_details') }}</h5>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="p-3 bg-dark-subtle rounded">
                                <h6><i class="fa fa-user me-1"></i> {{ __('general.pages.sales.customer') }}</h6>
                                <p class="mb-0">{{ $order->customer->name ?? __('general.messages.n_a') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-dark-subtle rounded">
                                <h6><i class="fa fa-building me-1"></i> {{ __('general.pages.sales.branch') }}</h6>
                                <p class="mb-0">{{ $order->branch->name ?? __('general.messages.n_a') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-dark-subtle rounded">
                                <h6><i class="fa fa-hashtag me-1"></i> {{ __('general.pages.sales.invoice_number') }}</h6>
                                <p class="mb-0">#{{ $order->invoice_number }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-dark-subtle rounded">
                                <h6><i class="fa fa-calendar me-1"></i> {{ __('general.pages.sales.order_date') }}</h6>
                                <p class="mb-0">{{ dateTimeFormat($order->created_at, true, false) }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3"><i class="fa fa-list-alt me-2"></i> {{ __('general.pages.sales.sale_summary') }}</h5>
                    <div class="row g-3">
                        @php
                            $summaryCards = [
                                ['title'=>__('general.pages.sales.items_count'),'icon'=>'fa-cube','bg'=>'bg-primary-subtle','color'=>'text-primary','value'=>$itemsCount],
                                ['title'=>__('general.pages.sales.total_quantity'),'icon'=>'fa-plus','bg'=>'bg-info-subtle','color'=>'text-info','value'=>$totalQty],
                                ['title'=>__('general.pages.sales.subtotal'),'icon'=>'fa-calculator','bg'=>'bg-warning-subtle','color'=>'text-warning','value'=>currencyFormat($subTotal, true)],
                                ['title'=>__('general.pages.sales.discount'),'icon'=>'fa-tag','bg'=>'bg-danger-subtle','color'=>'text-danger','value'=>currencyFormat($totalDiscount, true)],
                                ['title'=>__('general.pages.sales.tax'),'icon'=>'fa-percent','bg'=>'bg-secondary-subtle','color'=>'text-secondary','value'=>currencyFormat($totalTax, true)],
                                ['title'=>__('general.pages.sales.grand_total'),'icon'=>'fa-money','bg'=>'bg-gradient','color'=>'text-white','value'=>currencyFormat($grandTotal, true), 'gradient'=>'linear-gradient(135deg, #2196f3, #00c6ff)'],
                                ['title'=>__('general.pages.sales.paid'),'icon'=>'fa-check-circle','bg'=>'bg-success-subtle','color'=>'text-success','value'=>currencyFormat($paid, true)],
                                ['title'=>__('general.pages.sales.due'),'icon'=>'fa-clock-o','bg'=>'bg-light-subtle','color'=>'text-light','value'=>currencyFormat(($grandTotal - $paid), true)],
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
                    <h5 class="fw-bold mb-3"><i class="fa fa-cubes me-2"></i> {{ __('general.pages.sales.sale_products') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('general.pages.sales.product') }}</th>
                                    <th>{{ __('general.pages.sales.quantity') }}</th>
                                    <th>{{ __('general.pages.sales.refunded') }}</th>
                                    <th>{{ __('general.pages.sales.unit_price') }}</th>
                                    <th>{{ __('general.pages.sales.total') }}</th>
                                    <th>{{ __('general.pages.sales.refund_status') }}</th>
                                    <th>{{ __('general.pages.sales.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->saleItems as $item)
                                    <tr>
                                        <td><strong>{{ $item->product?->name }} - {{ $item->unit?->name }}</strong></td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ $item->refunded_qty }}</td>
                                        <td>{{ currencyFormat($item->sell_price, true) }}</td>
                                        <td>{{ currencyFormat($item->total, true) }}</td>
                                        <td>
                                            @if($item->actual_qty <= 0)
                                                <span class="badge bg-success">{{ __('general.pages.sales.fully_refunded') }}</span>
                                            @elseif($item->actual_qty < $item->qty)
                                                <span class="badge bg-warning text-dark">{{ __('general.pages.sales.partially_refunded') }}</span>
                                            @else
                                                <span class="badge bg-primary">{{ __('general.pages.sales.not_refunded') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->actual_qty <= 0)
                                                <button class="btn btn-sm btn-secondary" disabled><i class="fa fa-undo"></i> {{ __('general.pages.sales.refund') }}</button>
                                            @else
                                                <a class="btn btn-sm btn-danger" href="{{ route('admin.refunds.create',['order_type'=>'sale','order_id'=>$order->id]) }}">
                                                    <i class="fa fa-undo"></i> {{ __('general.pages.sales.refund') }}
                                                </a>
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
                    <h5 class="fw-bold mb-3"><i class="fa fa-exchange me-2"></i> {{ __('general.pages.sales.order_transactions') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('general.pages.purchases.transaction_lines.id') }}</th>
                                    <th>{{ __('general.pages.purchases.transaction_lines.transaction_id') }}</th>
                                    <th>{{ __('general.pages.purchases.transaction_lines.type') }}</th>
                                    <th>{{ __('general.pages.purchases.transaction_lines.branch') }}</th>
                                    <th>{{ __('general.pages.purchases.transaction_lines.reference') }}</th>
                                    <th>{{ __('general.pages.purchases.transaction_lines.note') }}</th>
                                    <th>{{ __('general.pages.purchases.transaction_lines.date') }}</th>
                                    <th>{{ __('general.pages.purchases.transaction_lines.account') }}</th>
                                    <th>{{ __('general.pages.purchases.transaction_lines.debit') }}</th>
                                    <th>{{ __('general.pages.purchases.transaction_lines.credit') }}</th>
                                    <th>{{ __('general.pages.purchases.transaction_lines.created_at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totals = ['debit' => 0, 'credit' => 0]; @endphp
                                @foreach($transactionLines as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->transaction_id ?? __('general.messages.n_a') }}</td>
                                        <td>{{ $transaction->type ?? __('general.messages.n_a') }}</td>
                                        <td>{{ $transaction->branch ?? __('general.messages.n_a') }}</td>
                                        <td>{{ $transaction->reference ?? __('general.messages.n_a') }}</td>
                                        <td>{{ $transaction->note ?? __('general.messages.n_a') }}</td>
                                        <td>{{ $transaction->date ?? __('general.messages.n_a') }}</td>
                                        <td>{{ $transaction->account ?? __('general.messages.n_a') }}</td>
                                        <td>{{ $transaction->line_type == 'debit' ? $transaction->amount : currencyFormat('0', true) }}</td>
                                        <td>{{ $transaction->line_type == 'credit' ? $transaction->amount : currencyFormat('0', true) }}</td>
                                        <td>{{ $transaction->created_at ?? __('general.messages.n_a') }}</td>
                                    </tr>
                                    @php
                                        if ($transaction->line_type == 'debit') {
                                            $totals['debit'] += $transaction->amount_raw;
                                        } elseif ($transaction->line_type == 'credit') {
                                            $totals['credit'] += $transaction->amount_raw;
                                        }
                                    @endphp
                                @endforeach
                                <tr class="table-secondary fw-bold">
                                    <td colspan="8" class="text-{{ app()->getLocale() == 'ar' ? 'end' : 'start' }}">{{ __('general.pages.purchases.transaction_lines.total') }}</td>
                                    <td>{{ currencyFormat($totals['debit'], true) }}</td>
                                    <td>{{ currencyFormat($totals['credit'], true) }}</td>
                                    <td colspan="2"></td>
                                </tr>
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
                    <h5 class="modal-title mx-auto"><i class="fa fa-undo"></i> {{ __('general.pages.sales.refund_item') }}</h5>
                    <button type="button" class="close refund-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="refund-warning">
                        <i class="fa fa-exclamation-circle"></i>
                        <div>
                            <p class="mb-1">{{ __('general.pages.sales.about_to_refund') }}</p>
                            <strong class="refund-product-name">{{ $currentItem?->product?->name }} - {{ $currentItem?->unit?->name }}</strong>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="refundQty"><strong>{{ __('general.pages.sales.quantity_to_refund') }}</strong></label>
                        <input type="number" class="form-control refund-input" id="refundQty" min="1" max="{{ $currentItem?->actual_qty ?? 1 }}" wire:model="refundedQty">
                        <small class="form-text text-muted">{{ __('general.pages.sales.max_refundable') }} {{ $currentItem?->actual_qty ?? 1 }}</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('general.pages.sales.cancel') }}</button>
                    @if ($currentItem)
                    <button type="button" class="btn btn-danger" wire:click="refundSaleItem">
                        <i class="fa fa-check"></i> {{ __('general.pages.sales.confirm_refund') }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
