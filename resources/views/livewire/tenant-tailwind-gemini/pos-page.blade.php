<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('general.pages.pos-page.order_details') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.pos-page.order_products') }}</p>
        </div>

        <div class="inline-flex rounded-2xl border border-slate-200 bg-white p-1 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <button type="button" wire:click="$set('step', 1)" class="rounded-xl px-4 py-2 text-sm font-semibold transition {{ $step === 1 ? 'bg-brand-600 text-white' : 'text-slate-500 dark:text-slate-300' }}">1. {{ __('general.pages.pos-page.order_details') }}</button>
            <button type="button" wire:click="$set('step', 2)" class="rounded-xl px-4 py-2 text-sm font-semibold transition {{ $step === 2 ? 'bg-brand-600 text-white' : 'text-slate-500 dark:text-slate-300' }}">2. {{ __('general.pages.pos-page.order_products') }}</button>
        </div>
    </div>

    @if($step == 1)
        <div class="grid gap-6 xl:grid-cols-3">
            <div class="xl:col-span-2">
                <x-tenant-tailwind-gemini.table-card :title="__('general.pages.pos-page.order_details')" :description="__('general.pages.pos-page.order_products')">
                    <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
                        <div>
                            <label for="branchSelect" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('general.pages.pos-page.branch') }}</label>
                            <div class="flex gap-2">
                                @if(admin()->branch_id == null)
                                    <select class="form-select select2" id="branchSelect" name="data.branch_id">
                                        <option value="">-- {{ __('general.pages.pos-page.branch') }} --</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" @selected(isset($data['branch_id']) && $data['branch_id'] == $branch->id)>{{ $branch->name }} @if($branch->phone) - {{ $branch->phone }} @endif</option>
                                        @endforeach
                                    </select>
                                    <button class="inline-flex h-[42px] w-[42px] items-center justify-center rounded-xl bg-brand-600 text-white" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', null)">+</button>
                                @else
                                    <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                                @endif
                            </div>
                            @error('data.branch_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div>
                            <label for="customerSelect" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('general.pages.pos-page.customer') }}</label>
                            <div class="flex gap-2">
                                <select class="form-select select2" id="customerSelect" name="selectedCustomerId">
                                    <option value="">-- {{ __('general.pages.pos-page.customer') }} --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected(isset($selectedCustomerId) && $selectedCustomerId == $customer->id)>{{ $customer->name }} @if($customer->phone) - {{ $customer->phone }} @endif</option>
                                    @endforeach
                                </select>
                                <button class="inline-flex h-[42px] w-[42px] items-center justify-center rounded-xl bg-brand-600 text-white" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : null, type: 'customer'})">+</button>
                            </div>
                            @error('selectedCustomerId') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div>
                            <label for="invoiceNumber" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('general.pages.pos-page.invoice_number') }}</label>
                            <input type="text" class="form-control" id="invoiceNumber" wire:model="data.invoice_number">
                            <small class="text-primary">{{ __('general.pages.pos-page.leave_blank_for_auto_generated') }}</small>
                        </div>

                        <div>
                            <label for="orderDate" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('general.pages.pos-page.order_date') }}</label>
                            <input type="date" class="form-control" id="orderDate" wire:model="data.order_date">
                            @error('data.order_date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div>
                            <label for="dueDate" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('general.pages.pos-page.due_date') }}</label>
                            <input type="date" class="form-control" id="dueDate" wire:model="data.due_date">
                            @error('data.due_date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="md:col-span-2 xl:col-span-3">
                            <label for="paymentNote" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('general.pages.pos-page.payment_note') }}</label>
                            <textarea class="form-control" id="paymentNote" rows="4" wire:model="data.payment_note"></textarea>
                            @error('data.payment_note') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <x-slot:footer>
                        <div class="flex flex-wrap items-center justify-end gap-3">
                            <a onclick="redirectTo('{{ panelAwareUrl(route('admin.sales.index')) }}')" href="javascript:" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                <i class="bi bi-list-ul"></i>
                                {{ __('general.pages.pos-page.orders_list') }}
                            </a>
                            <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-700" wire:click="$set('step', 2)">
                                {{ __('general.pages.pos-page.next') }}
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                        </div>
                    </x-slot:footer>
                </x-tenant-tailwind-gemini.table-card>
            </div>

            <div class="space-y-4">
                <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-brand-600 to-brand-700 p-6 text-white shadow-xl shadow-brand-600/20">
                    <p class="text-sm text-white/80">{{ __('general.pages.pos-page.total') }}</p>
                    <div class="mt-3 text-3xl font-bold">{{ currencyFormat($total, true) }}</div>
                    <div class="mt-4 grid gap-3 text-sm text-white/80">
                        <div class="flex justify-between"><span>{{ __('general.pages.pos-page.subtotal') }}</span><span>{{ currencyFormat($subTotal, true) }}</span></div>
                        <div class="flex justify-between"><span>{{ __('general.pages.pos-page.taxes') }} ({{ $taxPercentage }}%)</span><span>{{ currencyFormat($tax, true) }}</span></div>
                        <div class="flex justify-between"><span>{{ __('general.pages.pos-page.discount') }}</span><span>{{ currencyFormat($discount, true) }}</span></div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('general.pages.pos-page.customer') }}</h3>
                    <div class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $selectedCustomer?->name ?? __('general.pages.pos-page.customer') }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ $selectedCustomer?->phone }}</div>
                </div>
            </div>
        </div>
    @elseif($step == 2)
        <div class="grid gap-6 xl:grid-cols-[1.8fr_minmax(340px,0.9fr)]">
            <div class="space-y-6">
                <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.pos-page.order_products')" :expanded="true" icon="fa fa-store">
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white">{{ __('general.pages.pos-page.all') }}</button>
                        @foreach ($categories as $category)
                            <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-brand-500 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300">
                                <i class="fa {{ $category->icon }} me-1"></i>
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </x-tenant-tailwind-gemini.filter-card>

                <div class="grid gap-4 md:grid-cols-2 2xl:grid-cols-3">
                    @foreach ($products as $product)
                        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
                            <button type="button" class="block w-full text-start" @if($product->unit->children->count() > 0) data-bs-toggle="modal" data-bs-target="#modalPosItem" wire:click="setCurrentProduct({{ $product->id }})" @else wire:click="addToCart({{ $product->id }})" @endif>
                                <div class="aspect-[4/3] w-full bg-slate-100 dark:bg-slate-800">
                                    <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                </div>
                                <div class="space-y-2 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="font-semibold text-slate-900 dark:text-white">{{ $product->name }}</h3>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $product->category?->name }}</p>
                                        </div>
                                        <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-600 dark:bg-brand-500/10 dark:text-brand-300">{{ currencyFormat($product->stockSellPrice($this->data['branch_id'] ?? null), true) }}</span>
                                    </div>
                                </div>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-4">
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.pos-page.cart') }}</h3>
                    </div>
                    <div class="max-h-[460px] space-y-3 overflow-y-auto px-5 py-4">
                        @forelse (($data['products'] ?? []) as $key=>$dataProduct)
                            <div class="flex gap-3 rounded-2xl border border-slate-200 p-3 dark:border-slate-800">
                                <img src="{{ $dataProduct['image'] }}" alt="{{ $dataProduct['product_name'] }}" class="h-16 w-16 rounded-2xl object-cover">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="font-semibold text-slate-900 dark:text-white">{{ $dataProduct['product_name'] }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $dataProduct['unit_name'] }}</div>
                                        </div>
                                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ currencyFormat($dataProduct['subtotal'], true) }}</div>
                                    </div>
                                    <div class="mt-3 flex items-center gap-2">
                                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 dark:border-slate-700 dark:text-slate-300" wire:click="updateQty({{ $key }} , -1)"><i class="fa fa-minus"></i></button>
                                        <input type="text" class="form-control max-w-[70px] text-center" wire:model="data.products.{{ $key }}.quantity">
                                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 dark:border-slate-700 dark:text-slate-300" wire:click="updateQty({{ $key }} , 1)"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">{{ __('general.pages.pos-page.no_items_in_cart') }}</div>
                        @endforelse
                    </div>
                    <div class="space-y-4 border-t border-slate-100 px-5 py-4 dark:border-slate-800">
                        @if(!$discount || $discount == 0)
                            <div class="flex gap-2">
                                <input type="text" class="form-control" placeholder="{{ __('general.pages.pos-page.enter_code_or_amount') }}" wire:model="discountCode">
                                <button class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white" wire:click="validateDiscountCode">{{ __('general.pages.pos-page.discount') }}</button>
                            </div>
                        @else
                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <div class="font-semibold">{{ __('general.pages.pos-page.discount_applied') }}</div>
                                        <div>{{ __('general.pages.pos-page.code') }}: {{ $data['discount']['code'] ?? 'N/A' }}</div>
                                    </div>
                                    <button class="rounded-xl bg-rose-500 px-3 py-1.5 text-xs font-semibold text-white" wire:click="removeCoupon">{{ __('general.pages.pos-page.cancel') }}</button>
                                </div>
                            </div>
                        @endif

                        @if($deferredMode ?? false)
                            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300">
                                {{ __('general.pages.pos-page.deferred_order_hint') }}
                            </div>
                        @endif

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">{{ __('general.pages.pos-page.subtotal') }}</span><span class="font-semibold">{{ currencyFormat($subTotal, true) }}</span></div>
                            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">{{ __('general.pages.pos-page.taxes') }} ({{ $taxPercentage }}%)</span><span class="font-semibold">{{ currencyFormat($tax, true) }}</span></div>
                            <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">{{ __('general.pages.pos-page.discount') }}</span><span class="font-semibold">{{ currencyFormat($discount, true) }}</span></div>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-4 text-lg font-bold dark:border-slate-800">
                            <span>{{ __('general.pages.pos-page.total') }}</span>
                            <span>{{ currencyFormat($total, true) }}</span>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <button type="button" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800" wire:click="$set('step', 1)">{{ __('general.pages.pos-page.previous') }}</button>
                            <button type="button" class="rounded-xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#checkoutModal">{{ __('general.pages.pos-page.submit_order') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="bg-brand-600 px-4 py-3 text-white">
                    <h5 class="mb-0 fw-bold"><i class="fa fa-credit-card me-2"></i>{{ __('general.pages.pos-page.complete_payment') }}</h5>
                </div>
                <div class="p-4">
                    <div class="mb-4 rounded-3 bg-slate-100 px-4 py-3 text-sm dark:bg-slate-800">
                        <strong>{{ __('general.pages.pos-page.order_total') }}:</strong> {{ currencyFormat($total, true) }}
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>{{ __('general.pages.pos-page.payment_method') }}</th>
                                    <th>{{ __('general.pages.pos-page.amount') }}</th>
                                    <th>{{ __('general.pages.pos-page.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $index => $payment)
                                    <tr>
                                        <td>
                                            <select class="form-select" wire:model="payments.{{ $index }}.account_id">
                                                <option value="">-- {{ __('general.pages.pos-page.payment_method') }} --</option>
                                                @foreach ($paymentAccounts ?? [] as $account)
                                                    <option value="{{ data_get($account, 'id') }}">{{ data_get($account, 'paymentMethod.name') }} - {{ data_get($account, 'name') }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control" wire:model="payments.{{ $index }}.amount" step="any" min="0" max="{{ $total }}"></td>
                                        <td><button type="button" wire:click="removePayment({{ $index }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">{!! __('general.pages.pos-page.click_add_payment_to_get_started') !!}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white dark:bg-white dark:text-slate-900" wire:click="addPayment">{{ __('general.pages.pos-page.add_payment') }}</button>
                </div>
                <div class="d-flex justify-content-end gap-2 border-top p-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('general.pages.pos-page.cancel') }}</button>
                    <button type="button" class="btn btn-success" wire:click="confirmPayment">{{ __('general.pages.pos-page.confirm_payment') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-pos fade" id="modalPosItem" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0">
                @if($currentProduct)
                    <div class="rounded-4 border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="grid gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
                            <div class="overflow-hidden rounded-3xl bg-slate-100 dark:bg-slate-800">
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
                                    <div class="mb-2 text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('general.pages.pos-page.unit') }}</div>
                                    <div class="grid gap-2 sm:grid-cols-2">
                                        @foreach($currentProduct->units() as $unit)
                                            @php $sellPrice = number_format($unit->stock($currentProduct->id,$this->data['branch_id']??null)?->sell_price ?? 0, 3); @endphp
                                            <label class="flex cursor-pointer items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50 dark:border-slate-700 dark:has-[:checked]:bg-brand-500/10">
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
                                    <div class="mb-2 text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('general.pages.pos-page.quantity') }}</div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 dark:border-slate-700" @if($selectedQuantity > 1) wire:click="$set('selectedQuantity', {{ $selectedQuantity }} - 1)" @endif><i class="fa fa-minus"></i></button>
                                        <input type="text" class="form-control max-w-[80px] text-center" wire:model="selectedQuantity" max="{{ $maxQuantity }}" readonly>
                                        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 dark:border-slate-700" @if($selectedQuantity < $maxQuantity && $selectedQuantity != 0) wire:click="$set('selectedQuantity', {{ $selectedQuantity }} + 1)" @endif><i class="fa fa-plus"></i></button>
                                    </div>
                                    <small class="text-danger">Max: {{ $maxQuantity ?? 0 }}</small>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <button type="button" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-dismiss="modal">{{ __('general.pages.pos-page.cancel') }}</button>
                                    <button type="button" class="rounded-xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700" wire:click="addToCart">{{ __('general.pages.pos-page.add_to_cart') }}</button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.hljs) {
            window.hljs.highlightAll();
        }
    });

    document.addEventListener('livewire:navigated', () => {
        if (window.hljs) {
            window.hljs.highlightAll();
        }
    });
</script>
@include('layouts.tenant-tailwind-gemini.partials.select2-script')

<script>
    function redirectTo(url){
        if(confirm(@json(__('general.pages.pos-page.confirm_leave')))){
            window.location.href = url;
        }
    }
</script>
@endpush
