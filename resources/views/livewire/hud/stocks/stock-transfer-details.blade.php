<div>

    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fa fa-exchange-alt me-2"></i>
                    {{ __('general.titles.stock-transfers') }} #{{ $id }}
                </h5>
            </div>

            <div class="card-body">

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'details' ? 'active' : '' }}"
                                wire:click="$set('activeTab', 'details')"
                                data-bs-toggle="tab"
                                data-bs-target="#detailsTab"
                                type="button" role="tab">
                            <i class="fa fa-info-circle me-1"></i>
                            {{ __('general.pages.stock-transfers.details_tab') }}
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'products' ? 'active' : '' }}"
                                wire:click="$set('activeTab', 'products')"
                                data-bs-toggle="tab"
                                data-bs-target="#productsTab"
                                type="button" role="tab">
                            <i class="fa fa-cubes me-1"></i>
                            {{ __('general.pages.stock-transfers.products_tab') }}
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'expenses' ? 'active' : '' }}"
                                wire:click="$set('activeTab', 'expenses')"
                                data-bs-toggle="tab"
                                data-bs-target="#expensesTab"
                                type="button" role="tab">
                            <i class="fa fa-money-bill-wave me-1"></i>
                            {{ __('general.pages.stock-transfers.expenses_tab') }}
                        </button>
                    </li>
                </ul>

                <div class="tab-content">

                    <!-- DETAILS TAB -->
                    <div class="tab-pane fade {{ $activeTab === 'details' ? 'show active' : '' }}" id="detailsTab" role="tabpanel">
                        <h5 class="mb-3">
                            <i class="fa fa-info-circle me-2"></i>
                            {{ __('general.pages.stock-transfers.stock_transfer_details') }}
                        </h5>

                        <div class="row g-3">

                            <div class="col-md-3">
                                <div class="border rounded p-3 bg-dark text-light">
                                    <h6><i class="fa fa-building me-2"></i>{{ __('general.pages.stock-transfers.from_branch') }}</h6>
                                    <p class="mb-0">{{ $stockTransfer->fromBranch?->name ?? __('general.messages.n_a') }}</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="border rounded p-3 bg-dark text-light">
                                    <h6><i class="fa fa-building me-2"></i>{{ __('general.pages.stock-transfers.to_branch') }}</h6>
                                    <p class="mb-0">{{ $stockTransfer->toBranch?->name ?? __('general.messages.n_a') }}</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="border rounded p-3 bg-dark text-light">
                                    <h6><i class="fa fa-hashtag me-2"></i>{{ __('general.pages.stock-transfers.ref_no') }}</h6>
                                    <p class="mb-0">{{ $stockTransfer->ref_no ?? __('general.messages.n_a') }}</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="border rounded p-3 bg-dark text-light">
                                    <h6><i class="fa fa-calendar me-2"></i>{{ __('general.pages.stock-transfers.transfer_date') }}</h6>
                                    <p class="mb-0">{{ dateTimeFormat($stockTransfer->transfer_date, true, false) ?? __('general.messages.n_a') }}</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="border rounded p-3 bg-dark text-light">
                                    <h6><i class="fa fa-info-circle me-2"></i>{{ __('general.pages.stock-transfers.status') }}</h6>
                                    <p class="mb-0">{{ $stockTransfer->status->label() ?? __('general.messages.n_a') }}</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- PRODUCTS TAB -->
                    <div class="tab-pane fade {{ $activeTab === 'products' ? 'show active' : '' }}" id="productsTab" role="tabpanel">
                        <h5 class="mb-3">
                            <i class="fa fa-cubes me-2"></i>
                            {{ __('general.pages.stock-transfers.transferred_products') }}
                        </h5>

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
                                        <td>{{ $item?->unit?->name ?? __('general.messages.n_a') }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ currencyFormat($item->unit_cost, true) }}</td>
                                        <td>{{ currencyFormat($item->sell_price, true) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- EXPENSES TAB -->
                    <div class="tab-pane fade {{ $activeTab === 'expenses' ? 'show active' : '' }}" id="expensesTab" role="tabpanel">
                        <h5 class="mb-3">
                            <i class="fa fa-money-bill-wave me-2"></i>
                            {{ __('general.pages.stock-transfers.stock_transfer_expenses') }}
                        </h5>

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
                                        <td>{{ $expense->description ?? $expense->note ?? __('general.messages.n_a') }}</td>
                                        <td>{{ currencyFormat($expense->amount ?? 0, true) }}</td>
                                        <td>{{ $expense->expense_date ? carbon($expense->expense_date)->format('Y-m-d') : __('general.messages.n_a') }}</td>
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

                </div> <!-- End tab-content -->

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


    <!-- MODAL (Inside same root div!) -->
    <div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content refund-modal-content">

                <div class="modal-header refund-modal-header">
                    <h5 class="modal-title mx-auto"><i class="fa fa-undo"></i> {{ __('general.pages.stock-transfers.refund_item') }}</h5>
                    <button type="button" class="close refund-close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="refund-warning">
                        <i class="fa fa-exclamation-circle"></i>
                        <div>
                            <p class="mb-1">{{ __('general.pages.stock-transfers.refund_warning') }}</p>
                            <strong class="refund-product-name">{{ $currentItem?->product?->name }}</strong>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label><strong>{{ __('general.pages.stock-transfers.quantity_to_refund') }}</strong></label>
                        <input type="number" min="1" max="{{ $currentItem?->actual_qty ?? 1 }}" wire:model="refundedQty" class="form-control refund-input">
                        <small class="form-text text-muted">{{ __('general.pages.stock-transfers.max', ['qty' => $currentItem?->actual_qty ?? 1]) }}</small>
                    </div>
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('general.pages.stock-transfers.cancel') }}</button>

                    @if ($currentItem)
                    <button type="button" class="btn btn-danger" wire:click="refundPurchaseItem">
                        <i class="fa fa-check"></i> {{ __('general.pages.stock-transfers.confirm_refund') }}
                    </button>
                    @endif
                </div>

            </div>
        </div>
    </div>

</div>
