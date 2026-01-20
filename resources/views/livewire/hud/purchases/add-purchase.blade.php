<div>
   <div class="col-12">
    <div class="card shadow-sm  mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('general.pages.purchases.purchase_details') }}</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="branch_id" class="form-label">{{ __('general.pages.purchases.branch') }}</label>
                    @if(admin()->branch_id == null)
                    <div class="d-flex">
                        <select id="branch_id" name="data.branch_id" class="form-select select2">
                            <option value="">{{ __('general.pages.purchases.select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $branch->id == ($data['branch_id']??0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-theme me-2" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id : null})">+</button>
                    </div>
                    @else
                    <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                    @endif
                </div>

                <div class="col-md-4">
                    <label for="supplier_id" class="form-label">{{ __('general.pages.purchases.supplier') }}</label>
                    <div class="d-flex">
                        <select id="supplier_id" name="data.supplier_id" class="form-select select2">
                            <option value="">{{ __('general.pages.purchases.select_supplier') }}</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $supplier->id == ($data['supplier_id']??0) ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-theme me-2" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : null,type: 'supplier'})">+</button>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="ref_no" class="form-label">{{ __('general.pages.purchases.ref_no') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-product-hunt"></i></span>
                        <input type="text" id="ref_no" class="form-control" placeholder="{{ __('general.pages.purchases.ref_no') }}" wire:model="data.ref_no">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="order_date" class="form-label">{{ __('general.pages.purchases.order_date') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                        <input type="date" id="order_date" class="form-control" placeholder="{{ __('general.pages.purchases.order_date') }}" wire:model="data.order_date">
                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="purchaseDeferredSwitch" wire:model="data.is_deferred">
                        <label class="form-check-label fw-semibold" for="purchaseDeferredSwitch">{{ __('general.pages.purchases.deferred_purchase') }}</label>
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
                <h5 class="mb-0">{{ __('general.pages.purchases.order_products') }}</h5>
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
                                placeholder="{{ __('general.pages.purchases.search_product_placeholder') }}"
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
                                    <th>{{ __('general.pages.purchases.grand_total_incl') }}</th>
                                    <th>{{ __('general.pages.purchases.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderProducts ?? [] as $index => $product)
                                    <tr>
                                        <td class="fw-semibold">{{ $product['name'] }}</td>
                                        <td>
                                            <select
                                                id="unit_id_{{ $index }}"
                                                name="orderProducts.{{ $index }}.unit_id"
                                                class="form-select select2">
                                                <option value="">{{ __('general.pages.purchases.unit') }}</option>
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
                                        <td class="text-muted">{{ currencyFormat($product['unit_cost_after_discount'], true) }}</td>
                                        <td class="text-muted">{{ currencyFormat($product['unit_cost_after_discount'] * $product['qty'], true) }}</td>
                                        <td>
                                            <select
                                                id="tax_percentage_{{ $index }}"
                                                name="orderProducts.{{ $index }}.tax_percentage"
                                                class="form-select select2">
                                                <option value="">{{ __('general.pages.purchases.select_tax') }}</option>
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
                                        <td class="fw-semibold">{{ currencyFormat($product['sell_price'], true) }}</td>
                                        <td class="fw-semibold">{{ currencyFormat($product['total'], true) }}</td>
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
            <h5 class="card-title mb-0">{{ __('general.pages.purchases.order_expenses') }}</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.purchases.expense_category') }}</th>
                            <th>{{ __('general.pages.purchases.description') }}</th>
                            <th>{{ __('general.pages.purchases.amount') }}</th>
                            <th>{{ __('general.pages.purchases.expense_date') }}</th>
                            <th class="text-center">{{ __('general.pages.purchases.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['expenses'] ?? [] as $index => $expense)
                            <tr>
                                <td>
                                    <select
                                        id="expense_category_{{ $index }}"
                                        name="data.expenses.{{ $index }}.expense_category_id"
                                        class="form-select select2">
                                        <option value="">{{ __('general.pages.purchases.select_expense_category') }}</option>
                                        @foreach ($expenseCategories as $category)
                                            <option value="{{ $category->id }}" {{ ($expense['expense_category_id']??null) == $category->id ? 'selected' : '' }}>
                                                {{ $category->{app()->getLocale() == 'ar' ? 'ar_name' : 'name'} ?? $category->name }}
                                            </option>
                                            @foreach ($category->children as $child)
                                                <option value="{{ $child->id }}" {{ ($expense['expense_category_id']??null) == $child->id ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;-- {{ $child->{app()->getLocale() == 'ar' ? 'ar_name' : 'name'} ?? $child->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                </td>
                                <td>
                                    <input type="text" class="form-control" wire:model="data.expenses.{{ $index }}.description" placeholder="{{ __('general.pages.purchases.description') }}">
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model.blur="data.expenses.{{ $index }}.amount" step="any" min="0" placeholder="0.00">
                                </td>
                                <td>
                                    <input type="date" class="form-control" wire:model="data.expenses.{{ $index }}.expense_date">
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-sm" wire:click="removeExpense({{ $index }})" title="{{ __('general.pages.purchases.remove') }}">
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
                    <i class="fa fa-plus"></i> {{ __('general.pages.purchases.add_new_expense') }}
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
            <h5 class="card-title mb-0">{{ __('general.pages.purchases.order_adjustments') }}</h5>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="discount_type" class="form-label">{{ __('general.pages.purchases.discount_type') }}</label>
                    <select id="discount_type" name="data.discount_type" class="form-select select2">
                        <option value="" {{ ($data['discount_type']??'') == '' ? 'selected' : '' }}>{{ __('general.pages.purchases.select_discount_type') }}</option>
                        <option value="fixed" {{ ($data['discount_type']??'') == 'fixed' ? 'selected' : '' }}>{{ __('general.pages.purchases.fixed') }}</option>
                        <option value="percentage" {{ ($data['discount_type']??'') == 'percentage' ? 'selected' : '' }}>{{ __('general.pages.purchases.percentage') }}</option>
                    </select>
                </div>

                @if($data['discount_type'] ?? false)
                    <div class="col-md-4">
                        <label for="discount_value" class="form-label">{{ __('general.pages.purchases.discount_value') }}</label>
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
                    <label for="tax" class="form-label">{{ __('general.pages.purchases.tax') }}</label>
                    <select id="tax" name="data.tax_id" class="form-select select2">
                        <option value="">{{ __('general.pages.purchases.select_tax') }}</option>
                        @foreach ($taxes as $tax)
                            <option value="{{ $tax->id }}" {{ ($data['tax_id']??'') == $tax->id ? 'selected' : '' }}>{{ $tax->name }} - {{ $tax->rate }}%</option>
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
            <h5 class="card-title mb-0">{{ __('general.pages.purchases.purchase_summary') }}</h5>
            <p class="text-muted small mb-0">{{ __('general.pages.purchases.review_totals') }}</p>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Left side - Calculation breakdown --}}
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">{{ __('general.pages.purchases.order_breakdown') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('general.pages.purchases.items_count') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-cube"></i></span>
                                        <input type="text" class="form-control" value="{{ count($orderProducts ?? []) }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('general.pages.purchases.total_quantity') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-plus"></i></span>
                                        <input type="text" class="form-control" value="{{ $totalQuantity ?? 0 }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('general.pages.purchases.subtotal_before_discount') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-calculator"></i></span>
                                        <input type="text" class="form-control" value="{{ number_format($orderSubTotal ?? 0, 2) }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('general.pages.purchases.discount_amount') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-minus"></i></span>
                                        <input type="text" class="form-control" value="{{ number_format($orderDiscountAmount ?? 0, 2) }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('general.pages.purchases.after_discount') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-minus-circle"></i></span>
                                        <input type="text" class="form-control" value="{{ number_format($orderTotalAfterDiscount ?? 0, 2) }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('general.pages.purchases.tax_amount') }}</label>
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
                            <h6 class="card-title mb-0">{{ __('general.pages.purchases.final_totals') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-primary fw-bold">{{ __('general.pages.purchases.grand_total') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white"><i class="fa fa-money"></i></span>
                                    <input type="text"
                                           class="form-control text-center fw-bold"
                                           value="{{ number_format($orderGrandTotal ?? 0, 2) }}"
                                           readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.pages.purchases.payment_status') }}</label>
                                <select class="form-select" wire:model.live="data.payment_status">
                                    <option value="">{{ __('general.pages.purchases.choose_one') }}</option>
                                    <option value="pending">{{ __('general.pages.purchases.pending') }}</option>
                                    <option value="partial_paid">{{ __('general.pages.purchases.partial_payment') }}</option>
                                    <option value="full_paid">{{ __('general.pages.purchases.fully_paid') }}</option>
                                </select>
                            </div>

                            @if(in_array($data['payment_status'] ?? false, ['partial_paid', 'full_paid']))
                                <div class="mb-3">
                                    <label class="form-label">{{ __('general.pages.purchases.payment_account') }}</label>
                                    <select class="form-select" wire:model="data.payment_account">
                                        <option value="">{{ __('general.pages.purchases.select_payment_account') }}</option>
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
                                    <label class="form-label">{{ __('general.pages.purchases.paid_amount') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                        <input type="number" class="form-control" wire:model="data.payment_amount"
                                               step="0.01" min="0" max="{{ $grandTotal ?? 0 }}" placeholder="0.00">
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.pages.purchases.notes') }}</label>
                                <textarea class="form-control" wire:model="data.payment_note" rows="3"
                                          placeholder="{{ __('general.pages.purchases.add_additional_notes') }}"></textarea>
                            </div>

                            <hr>

                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success btn-lg"
                                        wire:click="savePurchase"
                                        {{ count($orderProducts ?? []) === 0 ? 'disabled' : '' }}>
                                    <i class="fa fa-save"></i> {{ __('general.pages.purchases.save_purchase_order') }}
                                </button>
                                <button type="button" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> {{ __('general.pages.purchases.cancel') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="card border-info shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h6 class="card-title mb-0">{{ __('general.pages.purchases.quick_stats') }}</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-6">
                                    <h4 class="text-info mb-0">{{ count($orderProducts ?? []) }}</h4>
                                    <small class="text-muted">{{ __('general.pages.purchases.items') }}</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success mb-0">{{ currencyFormat($grandTotal ?? 0, true) }}</h4>
                                    <small class="text-muted">{{ __('general.pages.purchases.total') }}</small>
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
@livewire('admin.users.user-modal')
@livewire('admin.branches.branch-modal')
@include('layouts.hud.partials.select2-script')
    <script>
        window.addEventListener('reset-search-input', event => {
            const input = document.getElementById('product_search');
            if (input) {
                input.value = '';
            }
        });
    </script>

@endpush
