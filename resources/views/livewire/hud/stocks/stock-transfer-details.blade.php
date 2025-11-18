<div>
<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-exchange-alt me-2"></i> {{ __('general.titles.stock-transfers') }} #{{ $id }}</h5>
        </div>
        <div class="card-body">

            <!-- Refund Modal -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'details' ? 'active' : '' }}" wire:click="$set('activeTab', 'details')" data-bs-toggle="tab" data-bs-target="#detailsTab" type="button" role="tab">
                        <i class="fa fa-info-circle me-1"></i> {{ __('general.pages.stock-transfers.details_tab') }}
                            <h5 class="modal-title mx-auto"><i class="fa fa-undo"></i> {{ __('general.pages.purchases.refund_item') }}</h5>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'products' ? 'active' : '' }}" wire:click="$set('activeTab', 'products')" data-bs-toggle="tab" data-bs-target="#productsTab" type="button" role="tab">
                        <i class="fa fa-cubes me-1"></i> {{ __('general.pages.stock-transfers.products_tab') }}
                    </button>
                </li>
                                    <p class="mb-1">{{ __('general.pages.purchases.about_to_refund') }}</p>
                                    <strong class="refund-product-name">{{ $currentItem?->product?->name }}</strong>
                        <i class="fa fa-money-bill-wave me-1"></i> {{ __('general.pages.stock-transfers.expenses_tab') }}
                    </button>
                </li>
                                <label for="refundQty"><strong>{{ __('general.pages.purchases.quantity_to_refund') }}</strong></label>

                                <small class="form-text text-muted">{{ __('general.pages.purchases.max_refundable') }} {{ $currentItem?->actual_qty ?? 1 }}</small>
            <div class="tab-content">

                <!-- Details Tab -->
                            <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('general.pages.stock-transfers.cancel') }}</button>
                    <h5 class="mb-3"><i class="fa fa-info-circle me-2"></i> {{ __('general.pages.stock-transfers.stock_transfer_details') }}</h5>
                            <button type="button" class="btn btn-danger" wire:click="refundPurchaseItem">
                                <i class="fa fa-check"></i> {{ __('general.pages.purchases.confirm_refund') }}
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-dark text-light">
                                <h6><i class="fa fa-building me-2"></i>{{ __('general.pages.stock-transfers.from_branch') }}</h6>
                                <p class="mb-0">{{ $stockTransfer->fromBranch?->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-dark text-light">
                                <h6><i class="fa fa-building me-2"></i>{{ __('general.pages.stock-transfers.to_branch') }}</h6>
                                <p class="mb-0">{{ $stockTransfer->toBranch?->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-dark text-light">
                                <h6><i class="fa fa-hashtag me-2"></i>{{ __('general.pages.stock-transfers.ref_no') }}</h6>
                                <p class="mb-0">{{ $stockTransfer->ref_no ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-dark text-light">
                                <h6><i class="fa fa-calendar me-2"></i>{{ __('general.pages.stock-transfers.transfer_date') }}</h6>
                                <p class="mb-0">{{ carbon($stockTransfer->transfer_date)->format('F j, Y') ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-dark text-light">
                                <h6><i class="fa fa-info-circle me-2"></i>{{ __('general.pages.stock-transfers.status') }}</h6>
                                <p class="mb-0">{{ $stockTransfer->status->label() ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade {{ $activeTab === 'products' ? 'show active' : '' }}" id="productsTab" role="tabpanel">
                    <h5 class="mb-3"><i class="fa fa-cubes me-2"></i> {{ __('general.pages.stock-transfers.transferred_products') }}</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('general.pages.stock-transfers.product') }}</th>
                                    <th>{{ __('general.pages.stock-transfers.unit') }}</th>
                                    <th>{{ __('general.pages.stock-transfers.qty') }}</th>
                                    <th>{{ __('general.pages.stock-transfers.unit_price') }}</th>
                                    <th>{{ __('general.pages.stock-transfers.selling_price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stockTransfer->items as $item)
                                <tr>
                                    <td><strong>{{ $item?->product?->name }}</strong></td>
                                    <td>{{ $item?->unit?->name ?? 'N/A' }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ number_format($item->unit_cost, 2) }}</td>
                                    <td>{{ number_format($item->sell_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Expenses Tab -->
                <div class="tab-pane fade {{ $activeTab === 'expenses' ? 'show active' : '' }}" id="expensesTab" role="tabpanel">
                    <h5 class="mb-3"><i class="fa fa-money-bill-wave me-2"></i> {{ __('general.pages.stock-transfers.stock_transfer_expenses') }}</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('general.pages.stock-transfers.description') }}</th>
                                    <th>{{ __('general.pages.stock-transfers.amount') }}</th>
                                    <th>{{ __('general.pages.stock-transfers.expense_date') }}</th>
                                    <th class="text-center">{{ __('general.pages.stock-transfers.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stockTransfer->expenses ?? [] as $expense)
                                <tr>
                                    <td>{{ $expense->description ?? $expense->note ?? 'N/A' }}</td>
                                    <td>{{ number_format($expense->amount ?? 0, 2) }}</td>
                                    <td>{{ $expense->expense_date ? carbon($expense->expense_date)->format('Y-m-d') : 'N/A' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-danger" wire:click="deleteExpenseConfirm({{ $expense->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> <!-- end tab-content -->
        </div>

        <!-- Card Arrow Decoration -->
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
