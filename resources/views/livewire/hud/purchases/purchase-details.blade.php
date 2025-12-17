<div>
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('general.pages.purchases.purchase_order_details') }} #{{ $id }}</h5>
            </div>
            <div class="card-body">

                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'details' ? 'active' : '' }}"
                            wire:click="$set('activeTab', 'details')" data-bs-toggle="tab"
                            data-bs-target="#detailsTab" type="button" role="tab">
                            <i class="fa fa-info-circle me-1"></i> {{ __('general.pages.purchases.details_tab') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'products' ? 'active' : '' }}"
                            wire:click="$set('activeTab', 'products')" data-bs-toggle="tab"
                            data-bs-target="#productsTab" type="button" role="tab">
                            <i class="fa fa-cubes me-1"></i> {{ __('general.pages.purchases.products_tab') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'expenses' ? 'active' : '' }}"
                            wire:click="$set('activeTab', 'expenses')" data-bs-toggle="tab"
                            data-bs-target="#expensesTab" type="button" role="tab">
                            <i class="fa fa-credit-card me-1"></i> {{ __('general.pages.purchases.expenses_tab') }}
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-4">

                    {{-- Details Tab --}}
                    <div class="tab-pane fade {{ $activeTab === 'details' ? 'show active' : '' }}" id="detailsTab" role="tabpanel">
                        <h5 class="mb-3"><i class="fa fa-info-circle me-1"></i> {{ __('general.pages.purchases.purchase_details') }}</h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <strong><i class="fa fa-building me-1"></i> {{ __('general.pages.purchases.branch') }}:</strong>
                                    <div>{{ $purchase->branch?->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <strong><i class="fa fa-truck me-1"></i> {{ __('general.pages.purchases.supplier') }}:</strong>
                                    <div>{{ $purchase->supplier?->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <strong><i class="fa fa-hashtag me-1"></i> {{ __('general.pages.purchases.ref_no') }}:</strong>
                                    <div>{{ $purchase->ref_no ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <strong><i class="fa fa-calendar me-1"></i> {{ __('general.pages.purchases.order_date') }}:</strong>
                                    <div>{{ carbon($purchase->order_date)->format('F j, Y') ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3"><i class="fa fa-list-alt me-1"></i> {{ __('general.pages.purchases.purchase_summary') }}</h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="card text-center border-1 shadow-sm">
                                    <div class="card-body">
                                        <div class="fs-1 text-primary"><i class="fa fa-cube"></i></div>
                                        <h6>{{ __('general.pages.purchases.items_count') }}</h6>
                                        <h3>{{ $purchase->purchaseItems->count() }}</h3>
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center border-1 shadow-sm">
                                    <div class="card-body">
                                        <div class="fs-1 text-info"><i class="fa fa-plus"></i></div>
                                        <h6>{{ __('general.pages.purchases.total_quantity') }}</h6>
                                        <h3>{{ $actualQty }}</h3>
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center border-1 shadow-sm">
                                    <div class="card-body">
                                        <div class="fs-1 text-danger"><i class="fa fa-credit-card"></i></div>
                                        <h6>{{ __('general.pages.expenses.total_expense') ?? 'Expenses Total' }}</h6>
                                        <h3>{{ currency()->symbol }}{{ number_format($purchase->expenses->sum('amount') ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center border-1 shadow-sm">
                                    <div class="card-body">
                                        <div class="fs-1 text-warning"><i class="fa fa-calculator"></i></div>
                                        <h6>{{ __('general.pages.purchases.subtotal_before_discount') }}</h6>
                                        <h3>{{ currency()->symbol }}{{ number_format($orderSubTotal ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <div class="card text-center border-1 shadow-sm">
                                    <div class="card-body">
                                        <div class="fs-1 text-secondary"><i class="fa fa-minus-circle"></i></div>
                                        <h6>{{ __('general.pages.purchases.after_discount') }}</h6>
                                        <h3>{{ currency()->symbol }}{{ number_format($orderTotalAfterDiscount ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center border-1 shadow-sm">
                                    <div class="card-body">
                                        <div class="fs-1 text-success"><i class="fa fa-percent"></i></div>
                                        <h6>{{ __('general.pages.purchases.tax_amount') }}</h6>
                                        <h3>{{ currency()->symbol }}{{ number_format($orderTaxAmount ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center border-1 shadow-sm text-white">
                                    <div class="card-body">
                                        <div class="fs-1 text-info"><i class="fa fa-dollar-sign"></i></div>
                                        <h6>{{ __('general.pages.purchases.grand_total') }}</h6>
                                        <h3>{{ currency()->symbol }}{{ number_format($orderGrandTotal ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Products Tab --}}
                    <div class="tab-pane fade {{ $activeTab === 'products' ? 'show active' : '' }}" id="productsTab" role="tabpanel">
                        <h5 class="mb-3"><i class="fa fa-cubes me-1"></i> {{ __('general.pages.purchases.order_products') }}</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('general.pages.purchases.product') }}</th>
                                        <th>{{ __('general.pages.purchases.unit') }}</th>
                                        <th>{{ __('general.pages.purchases.qty') }}</th>
                                        <th>{{ __('general.pages.purchases.unit_price') }}</th>
                                        <th>{{ __('general.pages.purchases.discount_percentage') }}</th>
                                        <th>{{ __('general.pages.purchases.net_unit_cost') }}</th>
                                        <th>{{ __('general.pages.purchases.total_net_cost') }}</th>
                                        <th>{{ __('general.pages.purchases.tax_percentage') }}</th>
                                        <th>{{ __('general.pages.purchases.subtotal_incl_tax') }}</th>
                                        <th>{{ __('general.pages.purchases.extra_margin_percentage') }}</th>
                                        <th>{{ __('general.pages.purchases.selling_price_per_unit') }}</th>
                                        <th>{{ __('general.pages.purchases.grand_total') }}</th>
                                        <th>{{ __('general.pages.purchases.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase->purchaseItems as $item)
                                        <tr>
                                            <td><strong>{{ $item?->product?->name }}</strong></td>
                                            <td>{{ $item?->unit?->name ?? 'N/A' }}</td>
                                            <td>{{ $item->actual_qty }}</td>
                                            <td>{{ currency()->symbol }}{{ number_format($item->purchase_price, 2) }}</td>
                                            <td>{{ number_format($item->discount_percentage, 2) }}%</td>
                                            <td>{{ currency()->symbol }}{{ number_format($item->unit_cost_after_discount, 2) }}</td>
                                            <td>{{ currency()->symbol }}{{ number_format($item->total_after_discount, 2) }}</td>
                                            <td>{{ $item->tax_percentage ? number_format($item->tax_percentage, 2) : 'N/A' }}%</td>
                                            <td>{{ currency()->symbol }}{{ number_format($item->unit_amount_after_tax, 2) }}</td>
                                            <td>{{ number_format($item->x_margin, 2) }}%</td>
                                            <td>{{ currency()->symbol }}{{ number_format($item->total_after_x_margin, 2) }}</td>
                                            <td>{{ currency()->symbol }}{{ number_format($item->total_after_x_margin * $item->actual_qty, 2) }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" wire:click="setCurrentItem({{ $item->id }})" data-bs-toggle="modal" data-bs-target="#refundModal">
                                                    <i class="fa fa-undo"></i> {{ __('general.pages.purchases.refund') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Expenses Tab --}}
                    <div class="tab-pane fade {{ $activeTab === 'expenses' ? 'show active' : '' }}" id="expensesTab" role="tabpanel">
                        <h5 class="mb-3"><i class="fa fa-credit-card me-1"></i> {{ __('general.pages.purchases.order_expenses') }}</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('general.pages.purchases.description') }}</th>
                                        <th>{{ __('general.pages.purchases.amount') }}</th>
                                        <th>{{ __('general.pages.purchases.expense_date') }}</th>
                                        <th>{{ __('general.pages.purchases.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase->expenses ?? [] as $expense)
                                        <tr>
                                            <td>{{ $expense->description ?? 'N/A' }}</td>
                                            <td>{{ currency()->symbol }}{{ number_format($expense->amount ?? 0, 2) }}</td>
                                            <td>{{ $expense->expense_date ? carbon($expense->expense_date)->format('Y-m-d') : 'N/A' }}</td>
                                            <td>
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
                    <h5 class="modal-title mx-auto"><i class="fa fa-undo"></i> {{ __('general.pages.purchases.refund_item') }}</h5>
                    <button type="button" class="close refund-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="refund-warning">
                        <i class="fa fa-exclamation-circle"></i>
                        <div>
                            <p class="mb-1">{{ __('general.pages.purchases.about_to_refund') }}</p>
                            <strong class="refund-product-name">{{ $currentItem?->product?->name }}</strong>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="refundQty"><strong>{{ __('general.pages.purchases.quantity_to_refund') }}</strong></label>
                        <input type="number" class="form-control refund-input" id="refundQty" min="1" max="{{ $currentItem?->actual_qty ?? 1 }}" wire:model="refundedQty">
                        <small class="form-text text-muted">{{ __('general.pages.purchases.max_refundable') }} {{ $currentItem?->actual_qty ?? 1 }}</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('general.pages.purchases.cancel') }}</button>
                    @if ($currentItem)
                    <button type="button" class="btn btn-danger" wire:click="refundPurchaseItem">
                        <i class="fa fa-check"></i> {{ __('general.pages.purchases.confirm_refund') }}
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
