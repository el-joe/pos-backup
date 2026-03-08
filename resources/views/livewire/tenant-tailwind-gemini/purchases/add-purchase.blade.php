<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.items_count') }}</p>
            <div class="mt-3 flex items-center justify-between gap-3">
                <div>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ count($orderProducts ?? []) }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.order_products') }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300">
                    <i class="fa fa-cubes text-lg"></i>
                </span>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.total_quantity') }}</p>
            <div class="mt-3 flex items-center justify-between gap-3">
                <div>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($totalQuantity ?? 0, 2) }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.review_totals') }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                    <i class="fa fa-plus text-lg"></i>
                </span>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.subtotal_before_discount') }}</p>
            <div class="mt-3 flex items-center justify-between gap-3">
                <div>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($orderSubTotal ?? 0, true) }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.after_discount') }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300">
                    <i class="fa fa-calculator text-lg"></i>
                </span>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.grand_total') }}</p>
            <div class="mt-3 flex items-center justify-between gap-3">
                <div>
                    <p class="text-3xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($orderGrandTotal ?? 0, true) }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.purchase_summary') }}</p>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300">
                    <i class="fa fa-money text-lg"></i>
                </span>
            </div>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchases.purchase_details')" icon="fa fa-file-text">
        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div>
                <label for="branch_id" class="form-label">{{ __('general.pages.purchases.branch') }}</label>
                @if(admin()->branch_id == null)
                    <div class="flex gap-2">
                        <select id="branch_id" name="data.branch_id" class="form-select select2">
                            <option value="">{{ __('general.pages.purchases.select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $branch->id == ($data['branch_id'] ?? 0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <button class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id : null})">+</button>
                    </div>
                @else
                    <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                @endif
            </div>

            <div>
                <label for="supplier_id" class="form-label">{{ __('general.pages.purchases.supplier') }}</label>
                <div class="flex gap-2">
                    <select id="supplier_id" name="data.supplier_id" class="form-select select2">
                        <option value="">{{ __('general.pages.purchases.select_supplier') }}</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $supplier->id == ($data['supplier_id'] ?? 0) ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    <button class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : null,type: 'supplier'})">+</button>
                </div>
            </div>

            <div>
                <label for="ref_no" class="form-label">{{ __('general.pages.purchases.ref_no') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-product-hunt"></i></span>
                    <input type="text" id="ref_no" class="form-control" placeholder="{{ __('general.pages.purchases.ref_no') }}" wire:model="data.ref_no">
                </div>
            </div>

            <div>
                <label for="order_date" class="form-label">{{ __('general.pages.purchases.order_date') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    <input type="date" id="order_date" class="form-control" placeholder="{{ __('general.pages.purchases.order_date') }}" wire:model="data.order_date">
                </div>
            </div>

            <div class="md:col-span-2 xl:col-span-1">
                <label class="form-label">{{ __('general.pages.purchases.deferred_purchase') }}</label>
                <div class="flex h-11 items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 dark:border-slate-700 dark:bg-slate-800/60">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="purchaseDeferredSwitch" wire:model="data.is_deferred">
                        <label class="form-check-label fw-semibold" for="purchaseDeferredSwitch">{{ __('general.pages.purchases.deferred_purchase') }}</label>
                    </div>
                </div>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchases.order_products')" :description="__('general.pages.purchases.search_product_placeholder')" icon="fa fa-cubes">
        <x-slot:head>
            <div class="grid gap-4 md:grid-cols-[minmax(0,28rem)_auto] md:items-end">
                <div>
                    <label for="product_search" class="form-label">{{ __('general.pages.purchases.product') }}</label>
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

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-300">
                    {{ __('general.pages.purchases.items_count') }}: <span class="font-semibold text-slate-900 dark:text-white">{{ count($orderProducts ?? []) }}</span>
                </div>
            </div>
        </x-slot:head>

        <div class="p-5">
            <div class="table-responsive">
                <div class="responsive-table-wrapper">
                    <table class="table table-bordered align-middle order-products-table" style="min-width: 1200px;">
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
                            @forelse ($orderProducts ?? [] as $index => $product)
                                <tr>
                                    <td class="fw-semibold">{{ $product['name'] }}</td>
                                    <td>
                                        <select id="unit_id_{{ $index }}" name="orderProducts.{{ $index }}.unit_id" class="form-select select2">
                                            <option value="">{{ __('general.pages.purchases.unit') }}</option>
                                            @foreach ($product['units'] as $unit)
                                                <option value="{{ $unit['id'] }}">{{ $unit['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control" wire:model.blur="orderProducts.{{ $index }}.qty" min="1" placeholder="0.00"></td>
                                    <td><input type="number" class="form-control" wire:model.blur="orderProducts.{{ $index }}.purchase_price" step="0.01" min="0" placeholder="0.00"></td>
                                    <td><input type="number" class="form-control" wire:model.blur="orderProducts.{{ $index }}.discount_percentage" step="0.01" min="0" placeholder="0.00"></td>
                                    <td class="text-muted">{{ currencyFormat($product['unit_cost_after_discount'], true) }}</td>
                                    <td class="text-muted">{{ currencyFormat($product['unit_cost_after_discount'] * $product['qty'], true) }}</td>
                                    <td>
                                        <select id="tax_percentage_{{ $index }}" name="orderProducts.{{ $index }}.tax_percentage" class="form-select select2">
                                            <option value="">{{ __('general.pages.purchases.select_tax') }}</option>
                                            @foreach ($taxes as $tax)
                                                <option value="{{ $tax->rate }}" {{ $product['tax_percentage'] == $tax->rate ? 'selected' : '' }}>{{ $tax->name }} - {{ $tax->rate }}%</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-muted">{{ number_format($product['sub_total'] * $product['qty'], 2) }}</td>
                                    <td><input type="number" class="form-control" wire:model.blur="orderProducts.{{ $index }}.x_margin" step="0.01" min="0" placeholder="0.00"></td>
                                    <td class="fw-semibold">{{ currencyFormat($product['sell_price'], true) }}</td>
                                    <td class="fw-semibold">{{ currencyFormat($product['total'], true) }}</td>
                                    <td>
                                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20" wire:click="delete({{ $index }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.search_product_placeholder') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchases.order_expenses')" icon="fa fa-money-bill-wave">
        <div class="p-5">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('general.pages.purchases.expense_category') }}</th>
                            <th>{{ __('general.pages.purchases.description') }}</th>
                            <th>{{ __('general.pages.purchases.amount') }}</th>
                            <th>{{ __('general.pages.purchases.expense_date') }}</th>
                            <th class="text-center">{{ __('general.pages.purchases.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data['expenses'] ?? [] as $index => $expense)
                            <tr>
                                <td>
                                    <select id="expense_category_{{ $index }}" name="data.expenses.{{ $index }}.expense_category_id" class="form-select select2">
                                        <option value="">{{ __('general.pages.purchases.select_expense_category') }}</option>
                                        @foreach ($expenseCategories as $category)
                                            <option value="{{ $category->id }}" {{ ($expense['expense_category_id'] ?? null) == $category->id ? 'selected' : '' }}>{{ $category->{app()->getLocale() == 'ar' ? 'ar_name' : 'name'} ?? $category->name }}</option>
                                            @foreach ($category->children as $child)
                                                <option value="{{ $child->id }}" {{ ($expense['expense_category_id'] ?? null) == $child->id ? 'selected' : '' }}>&nbsp;&nbsp;-- {{ $child->{app()->getLocale() == 'ar' ? 'ar_name' : 'name'} ?? $child->name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" wire:model="data.expenses.{{ $index }}.description" placeholder="{{ __('general.pages.purchases.description') }}"></td>
                                <td><input type="number" class="form-control" wire:model.blur="data.expenses.{{ $index }}.amount" step="any" min="0" placeholder="0.00"></td>
                                <td><input type="date" class="form-control" wire:model="data.expenses.{{ $index }}.expense_date"></td>
                                <td class="text-center">
                                    <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20" wire:click="removeExpense({{ $index }})" title="{{ __('general.pages.purchases.remove') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.add_new_expense') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-slot:footer>
            <button class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700" wire:click="addExpense">
                <i class="fa fa-plus"></i> {{ __('general.pages.purchases.add_new_expense') }}
            </button>
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(22rem,0.9fr)]">
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchases.order_adjustments')" icon="fa fa-sliders">
            <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
                <div>
                    <label for="discount_type" class="form-label">{{ __('general.pages.purchases.discount_type') }}</label>
                    <select id="discount_type" name="data.discount_type" class="form-select select2">
                        <option value="" {{ ($data['discount_type'] ?? '') == '' ? 'selected' : '' }}>{{ __('general.pages.purchases.select_discount_type') }}</option>
                        <option value="fixed" {{ ($data['discount_type'] ?? '') == 'fixed' ? 'selected' : '' }}>{{ __('general.pages.purchases.fixed') }}</option>
                        <option value="percentage" {{ ($data['discount_type'] ?? '') == 'percentage' ? 'selected' : '' }}>{{ __('general.pages.purchases.percentage') }}</option>
                    </select>
                </div>
                @if($data['discount_type'] ?? false)
                    <div>
                        <label for="discount_value" class="form-label">{{ __('general.pages.purchases.discount_value') }}</label>
                        <div class="input-group">
                            @if (($data['discount_type'] ?? '') === 'percentage')
                                <span class="input-group-text"><i class="fa fa-percent"></i></span>
                            @elseif (($data['discount_type'] ?? '') === 'fixed')
                                <span class="input-group-text"><i class="fa fa-dollar"></i></span>
                            @endif
                            <input type="number" class="form-control" id="discount_value" placeholder="Discount Value" wire:model.blur="data.discount_value" step="any" min="0">
                        </div>
                    </div>
                @endif
                <div>
                    <label for="tax" class="form-label">{{ __('general.pages.purchases.tax') }}</label>
                    <select id="tax" name="data.tax_id" class="form-select select2">
                        <option value="">{{ __('general.pages.purchases.select_tax') }}</option>
                        @foreach ($taxes as $tax)
                            <option value="{{ $tax->id }}" {{ ($data['tax_id'] ?? '') == $tax->id ? 'selected' : '' }}>{{ $tax->name }} - {{ $tax->rate }}%</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-tenant-tailwind-gemini.table-card>

        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchases.purchase_summary')" :description="__('general.pages.purchases.review_totals')" icon="fa fa-receipt">
            <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-1">
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.discount_amount') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ currencyFormat($orderDiscountAmount ?? 0, true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.after_discount') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ currencyFormat($orderTotalAfterDiscount ?? 0, true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.tax_amount') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ currencyFormat($orderTaxAmount ?? 0, true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-brand-200 bg-brand-50 p-4 dark:border-brand-500/30 dark:bg-brand-500/10">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-brand-700 dark:text-brand-300">{{ __('general.pages.purchases.grand_total') }}</p>
                        <p class="mt-2 text-xl font-semibold text-brand-900 dark:text-white">{{ currencyFormat($orderGrandTotal ?? 0, true) }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="form-label">{{ __('general.pages.purchases.payment_status') }}</label>
                        <select class="form-select" wire:model.live="data.payment_status">
                            <option value="">{{ __('general.pages.purchases.choose_one') }}</option>
                            <option value="pending">{{ __('general.pages.purchases.pending') }}</option>
                            <option value="partial_paid">{{ __('general.pages.purchases.partial_payment') }}</option>
                            <option value="full_paid">{{ __('general.pages.purchases.fully_paid') }}</option>
                        </select>
                    </div>

                    @if(in_array($data['payment_status'] ?? false, ['partial_paid', 'full_paid']))
                        <div>
                            <label class="form-label">{{ __('general.pages.purchases.payment_account') }}</label>
                            <select class="form-select" wire:model="data.payment_account">
                                <option value="">{{ __('general.pages.purchases.select_payment_account') }}</option>
                                @foreach($paymentAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->paymentMethod->name }} - {{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if(($data['payment_status'] ?? '') === 'partial_paid')
                        <div>
                            <label class="form-label">{{ __('general.pages.purchases.paid_amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                <input type="number" class="form-control" wire:model="data.payment_amount" step="0.01" min="0" max="{{ $grandTotal ?? 0 }}" placeholder="0.00">
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="form-label">{{ __('general.pages.purchases.notes') }}</label>
                        <textarea class="form-control" wire:model="data.payment_note" rows="3" placeholder="{{ __('general.pages.purchases.add_additional_notes') }}"></textarea>
                    </div>
                </div>
            </div>
            <x-slot:footer>
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button type="button" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60" wire:click="savePurchase" {{ count($orderProducts ?? []) === 0 ? 'disabled' : '' }}>
                        <i class="fa fa-save me-2"></i> {{ __('general.pages.purchases.save_purchase_order') }}
                    </button>
                    <button type="button" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                        <i class="fa fa-times me-2"></i> {{ __('general.pages.purchases.cancel') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>

@push('scripts')
@livewire('admin.users.user-modal')
@livewire('admin.branches.branch-modal')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
    <script>
        window.addEventListener('reset-search-input', event => {
            const input = document.getElementById('product_search');
            if (input) {
                input.value = '';
            }
        });
    </script>
@endpush
