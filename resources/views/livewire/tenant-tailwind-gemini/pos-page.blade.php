<div class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 shadow-sm dark:border-slate-800 dark:bg-slate-950">
    @if($step == 1)
        <div wire:key="step-1-container" class="space-y-6 p-5 md:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('general.pages.pos-page.order_details') }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.pos-page.order_products') }}</p>
                </div>

                <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1 dark:border-slate-700 dark:bg-slate-900">
                    <button type="button" wire:click="$set('step', 1)" class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $step === 1 ? 'bg-brand-500 text-white' : 'text-slate-500 dark:text-slate-300' }}">1. {{ __('general.pages.pos-page.order_details') }}</button>
                    <button type="button" wire:click="$set('step', 2)" class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $step === 2 ? 'bg-brand-500 text-white' : 'text-slate-500 dark:text-slate-300' }}">2. {{ __('general.pages.pos-page.order_products') }}</button>
                </div>
            </div>

            <div>
                <div>
                    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.pos-page.order_details')" icon="fa fa-receipt" :render-table="false">
                        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
                            <div>
                                <label for="branchSelect" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.pos-page.branch') }}</label>
                                <div class="flex gap-2">
                                    @if(admin()->branch_id == null)
                                        <div wire:ignore class="w-full">
                                            <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" id="branchSelect" name="data.branch_id">
                                                <option value="">-- {{ __('general.pages.pos-page.branch') }} --</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" @selected(isset($data['branch_id']) && $data['branch_id'] == $branch->id)>{{ $branch->name }} @if($branch->phone) - {{ $branch->phone }} @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button class="inline-flex h-10 w-10 items-center justify-center shrink-0 rounded-xl bg-brand-500 text-white" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', null)">+</button>
                                    @else
                                        <input type="text" class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200" value="{{ admin()->branch?->name }}" disabled>
                                    @endif
                                </div>
                                @error('data.branch_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div>
                                <label for="customerSelect" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.pos-page.customer') }}</label>
                                <div class="flex gap-2">
                                    <div wire:ignore class="w-full">
                                        <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" id="customerSelect" name="selectedCustomerId">
                                            <option value="">-- {{ __('general.pages.pos-page.customer') }} --</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" @selected(isset($selectedCustomerId) && $selectedCustomerId == $customer->id)>{{ $customer->name }} @if($customer->phone) - {{ $customer->phone }} @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="inline-flex h-10 w-10 items-center justify-center shrink-0 rounded-xl bg-brand-500 text-white" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : null, type: 'customer'})">+</button>
                                </div>
                                @error('selectedCustomerId') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="hidden">
                                <label for="isDeferredSwitch" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.pos-page.deferred_revenue') }}</label>
                                <input type="checkbox" id="isDeferredSwitch" wire:model="data.is_deferred" @if(($deferredMode ?? false) === true) disabled @endif>
                            </div>

                            <div>
                                <label for="orderDate" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.pos-page.order_date') }}</label>
                                <input type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" id="orderDate" wire:model="data.order_date">
                                @error('data.order_date') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div>
                                <label for="dueDate" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.pos-page.due_date') }}</label>
                                <input type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" id="dueDate" wire:model="data.due_date">
                                @error('data.due_date') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div>
                                <label for="invoiceNumber" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.pos-page.invoice_number') }}</label>
                                <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" id="invoiceNumber" wire:model="data.invoice_number">
                                <small class="text-primary">{{ __('general.pages.pos-page.leave_blank_for_auto_generated') }}</small>
                            </div>

                            <div class="md:col-span-2 xl:col-span-3">
                                <label for="paymentNote" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.pos-page.payment_note') }}</label>
                                <textarea class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" id="paymentNote" rows="4" wire:model="data.payment_note"></textarea>
                                @error('data.payment_note') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <x-slot:footer>
                            <div class="flex flex-wrap items-center justify-end gap-3">
                                <a onclick="redirectTo('{{ panelAwareUrl(route('admin.sales.index')) }}')" href="javascript:" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                    <i class="bi bi-list-ul"></i>
                                    {{ __('general.pages.pos-page.orders_list') }}
                                </a>
                                <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-600" wire:click="$set('step', 2)">
                                    {{ __('general.pages.pos-page.next') }}
                                    <i class="bi bi-arrow-right-circle"></i>
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-tenant-tailwind-gemini.table-card>
                </div>
            </div>
        </div>
    @elseif($step == 2)
        <div wire:key="step-2-container" id="posStepContainer" class="relative flex h-[calc(100vh-11rem)] min-h-[720px] overflow-hidden bg-slate-50 dark:bg-slate-950">
            <aside class="hidden w-56 border-e border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900 lg:flex lg:flex-col">
                <div class="flex items-center h-14 px-4 border-b border-slate-200 dark:border-slate-800">
                    <span class="font-bold tracking-tight text-brand-600">POS</span>
                </div>
                <div class="flex-1 overflow-y-auto p-3 space-y-2">
                    <button type="button" data-filter="all" class="pos-category-filter w-full rounded-xl bg-brand-500 px-3 py-2 text-left text-sm font-medium text-white">
                        <i class="fa fa-fw fa-hamburger me-1"></i> {{ __('general.pages.pos-page.all') }}
                    </button>
                    @foreach ($categories as $category)
                        <button type="button" wire:key="category-{{ $category->id }}" data-filter="cat-{{ $category->id }}" class="pos-category-filter w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                            <i class="fa fa-fw {{ $category->icon }} me-1"></i> {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0">
                <header class="h-14 flex items-center justify-between px-4 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800 lg:hidden" data-pos-sidebar-open>
                            <i class="bi bi-bag"></i>
                        </button>
                        <div class="relative hidden md:block">
                            <input type="text" id="posProductSearch" placeholder="Search products..." class="w-72 rounded-full border border-slate-200 bg-slate-100 px-10 py-2 text-sm text-slate-700 outline-none focus:border-brand-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($deferredMode ?? false)
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">{{ __('general.pages.pos-page.deferred_order') }}</span>
                        @endif
                        <button type="button" class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800" wire:click="$set('step', 1)">
                            <i class="fa fa-arrow-left"></i>
                            <span class="hidden sm:inline">{{ __('general.pages.pos-page.previous') }}</span>
                        </button>
                    </div>
                </header>

                <main class="flex-1 flex overflow-hidden">
                    <section class="flex-1 overflow-y-auto p-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach ($products as $product)
                                <button type="button" wire:key="product-card-{{ $product->id }}" data-category="cat-{{ $product->category_id }}" data-search="{{ \Illuminate\Support\Str::lower(trim($product->name . ' ' . ($product->category?->name ?? '') . ' ' . ($product->sku ?? '') . ' ' . ($product->code ?? ''))) }}" class="pos-product-card group overflow-hidden rounded-2xl border border-slate-200 bg-white p-3 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900"
                                    @if($product->unit->children->count() > 0) data-bs-toggle="modal" data-bs-target="#modalPosItem" wire:click="setCurrentProduct({{ $product->id }})" @else wire:click="addToCart({{ $product->id }})" @endif>
                                    <div class="aspect-square overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">
                                        <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                                    </div>
                                    <h4 class="mt-3 truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $product->name }}</h4>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-sm font-bold text-brand-600 dark:text-brand-300">{{ currencyFormat($product->stockSellPrice($this->data['branch_id'] ?? null), true) }}</span>
                                        <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase text-slate-500 dark:bg-slate-800 dark:text-slate-400">{{ $product->category?->name }}</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </section>

                    <div class="absolute inset-0 z-20 hidden bg-slate-900/40 lg:hidden" data-pos-sidebar-overlay></div>

                    <section id="posCartSidebar" class="fixed inset-y-0 end-0 z-30 flex w-[min(92vw,22rem)] translate-x-full flex-col border-s border-slate-200 bg-white transition-transform duration-200 ease-out dark:border-slate-800 dark:bg-slate-900 lg:static lg:z-auto lg:w-full lg:max-w-sm lg:translate-x-0">
                        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                            <span class="font-semibold">{{ __('general.pages.pos-page.cart') }}</span>
                            <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800 lg:hidden" data-pos-sidebar-close>
                                <i class="bi bi-chevron-left"></i>
                            </button>
                        </div>

                        <div class="flex-1 overflow-y-auto p-4 space-y-3">
                            @forelse (($data['products'] ?? []) as $key => $dataProduct)
                                <div wire:key="cart-item-{{ $key }}" class="flex gap-3 rounded-xl border border-slate-200 p-3 dark:border-slate-800">
                                    <img src="{{ $dataProduct['image'] }}" alt="{{ $dataProduct['product_name'] }}" class="h-12 w-12 rounded-lg object-cover">
                                    <div class="flex-1 min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $dataProduct['product_name'] }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ currencyFormat($dataProduct['sell_price'], true) }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $dataProduct['unit_name'] }}</p>
                                        <div class="mt-2 flex items-center gap-2">
                                            <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-slate-300 dark:border-slate-600" wire:click="updateQty({{ $key }} , -1)">-</button>
                                            <span class="w-5 text-center text-sm font-semibold">{{ $dataProduct['quantity'] }}</span>
                                            <button type="button" class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-slate-300 dark:border-slate-600" wire:click="updateQty({{ $key }} , 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="text-right text-sm font-semibold text-slate-900 dark:text-white">{{ currencyFormat($dataProduct['subtotal'], true) }}</div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">{{ __('general.pages.pos-page.no_items_in_cart') }}</div>
                            @endforelse
                        </div>

                        <div class="p-4 border-t border-slate-200 dark:border-slate-800 space-y-3 bg-slate-50 dark:bg-slate-900/40">
                            @if(!$discount || $discount == 0)
                                <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                            <i class="fa fa-percent me-1 text-brand-500"></i> {{ __('general.pages.pos-page.discount') }}
                                        </label>
                                        <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" placeholder="{{ __('general.pages.pos-page.enter_code_or_amount') }}" wire:model="discountCode">
                                        <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500 text-sm font-medium text-white" wire:click="validateDiscountCode">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3 text-xs text-emerald-700 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <div class="font-semibold">
                                                <i class="fa fa-tag me-1"></i> {{ __('general.pages.pos-page.discount_applied') }}
                                            </div>
                                            <div class="mt-1 text-[11px] opacity-90">
                                                {{ __('general.pages.pos-page.code') }}: <strong>{{ $data['discount']['code'] ?? 'N/A' }}</strong>
                                                @if($data['discount']['value'] ?? false)
                                                    - <span>{{ $data['discount']['value'] }}% Off</span>
                                                @endif
                                                @if($data['discount']['max_discount_amount'] ?? 0)
                                                    <span>({{ __('general.pages.pos-page.max') }}: {{ currencyFormat($data['discount']['max_discount_amount'] ?? 0, true) }})</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="font-bold">{{ currencyFormat($discount, true) }}</div>
                                            <button class="mt-1 text-rose-500" wire:click="removeCoupon">{{ __('general.pages.pos-page.cancel') }}</button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($deferredMode ?? false)
                                <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300">
                                    {{ __('general.pages.pos-page.deferred_order_hint') }}
                                </div>
                            @endif

                            <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400"><span>{{ __('general.pages.pos-page.subtotal') }}</span><span>{{ currencyFormat($subTotal, true) }}</span></div>
                            <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400"><span>{{ __('general.pages.pos-page.taxes') }} ({{ $taxPercentage }}%)</span><span>{{ currencyFormat($tax, true) }}</span></div>
                            <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400"><span>{{ __('general.pages.pos-page.discount') }}</span><span>{{ currencyFormat($discount, true) }}</span></div>
                            <div class="flex justify-between border-t border-dashed border-slate-300 pt-2 text-lg font-bold text-slate-900 dark:border-slate-700 dark:text-white"><span>{{ __('general.pages.pos-page.total') }}</span><span class="text-brand-600 dark:text-brand-300">{{ currencyFormat($total, true) }}</span></div>

                            <div class="grid grid-cols-[96px_minmax(0,1fr)] gap-2">
                                <button type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800" wire:click="$set('step', 1)">
                                    <i class="bi bi-arrow-left-circle me-1"></i>
                                    {{ __('general.pages.pos-page.previous') }}
                                </button>
                                <button type="button" class="rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-600/30 transition hover:bg-emerald-700" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                    <i class="bi bi-send-check me-1"></i>
                                    {{ __('general.pages.pos-page.submit_order') }}
                                </button>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    @endif

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 overflow-hidden rounded-3xl shadow-2xl dark:bg-slate-900">
                <div class="px-5 py-4 bg-brand-500 text-white">
                    <h5 class="mb-0 font-semibold"><i class="fa fa-credit-card me-2"></i>{{ __('general.pages.pos-page.complete_payment') }}</h5>
                </div>
                <div class="p-5">
                    <div class="mb-4 rounded-xl bg-slate-100 px-4 py-3 text-sm dark:bg-slate-800">
                        <strong>{{ __('general.pages.pos-page.order_total') }}:</strong> {{ currencyFormat($total, true) }}
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-slate-700 dark:text-slate-200">
                            <thead>
                                <tr>
                                    <th class="py-2 text-left">{{ __('general.pages.pos-page.payment_method') }}</th>
                                    <th class="py-2 text-left">{{ __('general.pages.pos-page.amount') }}</th>
                                    <th class="py-2 text-left">{{ __('general.pages.pos-page.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $index => $payment)
                                    <tr wire:key="payment-row-{{ $index }}">
                                        <td class="py-2 pe-2">
                                            <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model="payments.{{ $index }}.account_id">
                                                <option value="">-- {{ __('general.pages.pos-page.payment_method') }} --</option>
                                                @foreach ($paymentAccounts ?? [] as $account)
                                                    <option value="{{ data_get($account, 'id') }}">{{ data_get($account, 'paymentMethod.name') }} - {{ data_get($account, 'name') }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-2 pe-2"><input type="number" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model="payments.{{ $index }}.amount" step="any" min="0" max="{{ $total }}"></td>
                                        <td class="py-2"><button type="button" wire:click="removePayment({{ $index }})" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 text-center text-slate-500 dark:text-slate-400">{!! __('general.pages.pos-page.click_add_payment_to_get_started') !!}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="mt-3 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white dark:bg-white dark:text-slate-900" wire:click="addPayment">{{ __('general.pages.pos-page.add_payment') }}</button>
                </div>
                <div class="flex justify-end gap-2 border-t border-slate-200 p-4 dark:border-slate-800">
                    <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 dark:border-slate-700 dark:text-slate-200" data-bs-dismiss="modal">{{ __('general.pages.pos-page.cancel') }}</button>
                    <button type="button" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white" wire:click="confirmPayment">{{ __('general.pages.pos-page.confirm_payment') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-pos fade" id="modalPosItem" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 bg-transparent">
                @if($currentProduct)
                    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="grid gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
                            <div class="overflow-hidden rounded-2xl bg-slate-100 dark:bg-slate-800">
                                <img src="{{ $currentProduct->image_path }}" alt="{{ $currentProduct->name }}" class="h-full w-full object-cover">
                            </div>
                            <div>
                                <div class="mb-2 flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $currentProduct->name }}</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $currentProduct->description }}</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="mb-4">
                                    <div class="mb-2 text-sm font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">{{ __('general.pages.pos-page.unit') }}</div>
                                    <div class="grid gap-2 sm:grid-cols-2">
                                        @foreach($currentProduct->units() as $unit)
                                            @php $sellPrice = number_format($unit->stock($currentProduct->id,$this->data['branch_id']??null)?->sell_price ?? 0, 3); @endphp
                                            <label wire:key="product-unit-{{ $unit->id }}" class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 px-3 py-2 text-sm transition has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 dark:border-slate-700 dark:has-[:checked]:bg-brand-500/10">
                                                <span>
                                                    <input type="radio" class="me-2" wire:model.live="selectedUnitId" value="{{ $unit->id }}">
                                                    {{ $unit->name }}
                                                </span>
                                                <span class="font-semibold">{{ currencyFormat($sellPrice, true) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="mb-2 text-sm font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">{{ __('general.pages.pos-page.quantity') }}</div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 dark:border-slate-700" @if($selectedQuantity > 1) wire:click="$set('selectedQuantity', {{ $selectedQuantity }} - 1)" @endif><i class="fa fa-minus"></i></button>
                                        <input type="text" class="w-20 rounded-xl border border-slate-200 bg-white px-3 py-2 text-center text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="selectedQuantity" max="{{ $maxQuantity }}" readonly>
                                        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 dark:border-slate-700" @if($selectedQuantity < $maxQuantity && $selectedQuantity != 0) wire:click="$set('selectedQuantity', {{ $selectedQuantity }} + 1)" @endif><i class="fa fa-plus"></i></button>
                                    </div>
                                    <small class="text-danger">Max: {{ $maxQuantity ?? 0 }}</small>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <button type="button" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-dismiss="modal">{{ __('general.pages.pos-page.cancel') }}</button>
                                    <button type="button" class="rounded-xl bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600" wire:click="addToCart">{{ __('general.pages.pos-page.add_to_cart') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
@livewire('admin.branches.branch-modal')
@livewire('admin.users.user-modal')

@include('layouts.tenant-tailwind-gemini.partials.select2-script')

<script>
    function redirectTo(url){
        if(confirm(@json(__('general.pages.pos-page.confirm_leave')))){
            window.location.href = url;
        }
    }

    function initGeminiPosFilters() {
        const categoryButtons = Array.from(document.querySelectorAll('.pos-category-filter'));
        const productCards = Array.from(document.querySelectorAll('.pos-product-card'));
        const searchInput = document.getElementById('posProductSearch');

        if (!categoryButtons.length || !productCards.length) {
            return;
        }

        const activeClasses = ['bg-brand-500', 'text-white'];
        const inactiveClasses = ['border', 'border-slate-200', 'bg-white', 'text-slate-700', 'dark:border-slate-700', 'dark:bg-slate-900', 'dark:text-slate-200'];

        const setActiveFilter = (activeButton) => {
            categoryButtons.forEach((button) => {
                if (button === activeButton) {
                    button.classList.add(...activeClasses);
                    button.classList.remove(...inactiveClasses);
                } else {
                    button.classList.remove(...activeClasses);
                    button.classList.add(...inactiveClasses);
                }
            });
        };

        const applyFilters = () => {
            const activeButton = categoryButtons.find((button) => button.dataset.active === 'true') ?? categoryButtons[0];
            const categoryFilter = activeButton?.dataset.filter ?? 'all';
            const searchTerm = (searchInput?.value ?? '').trim().toLowerCase();

            productCards.forEach((card) => {
                const matchesCategory = categoryFilter === 'all' || card.dataset.category === categoryFilter;
                const searchableText = card.dataset.search ?? '';
                const matchesSearch = !searchTerm || searchableText.includes(searchTerm);
                card.classList.toggle('hidden', !(matchesCategory && matchesSearch));
            });
        };

        categoryButtons.forEach((button, index) => {
            if (!button.dataset.boundFilter) {
                button.addEventListener('click', () => {
                    categoryButtons.forEach((item) => delete item.dataset.active);
                    button.dataset.active = 'true';
                    setActiveFilter(button);
                    applyFilters();
                });
                button.dataset.boundFilter = 'true';
            }

            if (index === 0 && !categoryButtons.some((item) => item.dataset.active === 'true')) {
                button.dataset.active = 'true';
            }
        });

        if (searchInput && !searchInput.dataset.boundSearch) {
            searchInput.addEventListener('input', applyFilters);
            searchInput.dataset.boundSearch = 'true';
        }

        const activeButton = categoryButtons.find((button) => button.dataset.active === 'true') ?? categoryButtons[0];
        if (activeButton) {
            setActiveFilter(activeButton);
        }

        applyFilters();
    }

    function initGeminiPosSidebarToggle() {
        const container = document.getElementById('posStepContainer');
        const sidebar = document.getElementById('posCartSidebar');
        const overlay = container?.querySelector('[data-pos-sidebar-overlay]');

        if (!container || !sidebar || !overlay) {
            return;
        }

        const openSidebar = () => {
            sidebar.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
        };

        const closeSidebar = () => {
            sidebar.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        };

        container.querySelectorAll('[data-pos-sidebar-open]').forEach((button) => {
            if (!button.dataset.boundOpen) {
                button.addEventListener('click', openSidebar);
                button.dataset.boundOpen = 'true';
            }
        });

        container.querySelectorAll('[data-pos-sidebar-close]').forEach((button) => {
            if (!button.dataset.boundClose) {
                button.addEventListener('click', closeSidebar);
                button.dataset.boundClose = 'true';
            }
        });

        if (!overlay.dataset.boundOverlay) {
            overlay.addEventListener('click', closeSidebar);
            overlay.dataset.boundOverlay = 'true';
        }

        if (window.matchMedia('(min-width: 1024px)').matches) {
            closeSidebar();
        }
    }

    // Bridge Select2 changes to Livewire 3
    document.addEventListener('livewire:initialized', () => {
        $('#branchSelect').on('change', function (e) {
            @this.set('data.branch_id', $(this).val());
        });

        $('#customerSelect').on('change', function (e) {
            @this.set('selectedCustomerId', $(this).val());
        });

        initGeminiPosFilters();
        initGeminiPosSidebarToggle();
    });

    document.addEventListener('livewire:navigated', () => {
        initGeminiPosFilters();
        initGeminiPosSidebarToggle();
    });

    if (window.Livewire) {
        window.Livewire.hook('morph.updated', () => {
            initGeminiPosFilters();
            initGeminiPosSidebarToggle();
        });
    }
</script>
@endpush
