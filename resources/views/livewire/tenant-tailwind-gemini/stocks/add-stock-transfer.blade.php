@php
    $statusValue = $data['status'] ?? null;
    $selectedStatus = collect($statuses)->firstWhere('value', $statusValue);
    $fromBranch = $branches->firstWhere('id', $data['from_branch_id'] ?? null);
    $toBranch = $branches->firstWhere('id', $data['to_branch_id'] ?? null);
    $totalItems = collect($items ?? [])->sum(fn ($item) => (float) ($item['qty'] ?? 0));
    $totalExpenses = collect($data['expenses'] ?? [])->sum(fn ($expense) => (float) ($expense['amount'] ?? 0));
@endphp

<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-brand-500/15 to-brand-500/5 p-5">
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.from_branch') }}</p>
                <div class="mt-3 text-xl font-bold text-slate-900 dark:text-white">{{ $fromBranch?->name ?? admin()?->branch?->name ?? __('general.pages.stock-transfers.select_branch') }}</div>
            </div>
        </div>
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-emerald-500/15 to-emerald-500/5 p-5">
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.to_branch') }}</p>
                <div class="mt-3 text-xl font-bold text-slate-900 dark:text-white">{{ $toBranch?->name ?? __('general.pages.stock-transfers.select_branch') }}</div>
            </div>
        </div>
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-amber-500/15 to-amber-500/5 p-5">
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.order_products') }}</p>
                <div class="mt-3 text-xl font-bold text-slate-900 dark:text-white">{{ number_format($totalItems, 2) }}</div>
            </div>
        </div>
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-rose-500/15 to-rose-500/5 p-5">
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.expenses') }}</p>
                <div class="mt-3 text-xl font-bold text-slate-900 dark:text-white">{{ currencyFormat($totalExpenses, true) }}</div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.65fr)_360px]">
        <div class="space-y-6">
            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.stock-transfers.stock_transfer_details')" :description="__('general.pages.stock-transfers.ref_no')">
                <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
                    <div class="space-y-2">
                        <label for="from_branch_id" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.from_branch') }}</label>
                        @if(admin()->branch_id === null)
                            <div class="flex gap-2">
                                <select id="from_branch_id" name="data.from_branch_id" class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                    <option value="">{{ __('general.pages.stock-transfers.select_branch') }}</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" @selected(($data['from_branch_id'] ?? '') == $branch->id)>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id: null})">+</button>
                            </div>
                        @else
                            <input type="text" class="block w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" value="{{ admin()->branch->name }}" disabled>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <label for="to_branch_id" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.to_branch') }}</label>
                        <div class="flex gap-2">
                            <select id="to_branch_id" name="data.to_branch_id" class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                <option value="">{{ __('general.pages.stock-transfers.select_branch') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected(($data['to_branch_id'] ?? '') == $branch->id)>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id: null})">+</button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="ref_no" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.ref_no') }}</label>
                        <input id="ref_no" type="text" wire:model="data.ref_no" placeholder="{{ __('general.pages.stock-transfers.ref_no') }}" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    </div>

                    <div class="space-y-2">
                        <label for="transfer_date" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.transfer_date') }}</label>
                        <input id="transfer_date" type="date" wire:model="data.transfer_date" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    </div>

                    <div class="space-y-2">
                        <label for="expense_paid_branch_id" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.expense_payer_question') }}</label>
                        <select id="expense_paid_branch_id" name="data.expense_paid_branch_id" class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            <option value="">{{ __('general.pages.stock-transfers.select_branch') }}</option>
                            @foreach ($selectedBranches as $branch)
                                <option value="{{ $branch->id }}" @selected(($data['expense_paid_branch_id'] ?? '') == $branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="status" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.status') }}</label>
                        <select id="status" name="data.status" class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            <option value="">{{ __('general.pages.stock-transfers.select_status') }}</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" @selected(($data['status'] ?? '') == $status->value)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </x-tenant-tailwind-gemini.table-card>

            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.stock-transfers.order_products')" :description="__('general.pages.stock-transfers.search_product_placeholder')">
                <div class="space-y-5 p-5">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,360px)_1fr]">
                        <div>
                            <label for="product_search" class="mb-2 block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.product') }}</label>
                            <input id="product_search" type="text" placeholder="{{ __('general.pages.stock-transfers.search_product_placeholder') }}" onkeydown="productSearchEvent(event)" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        </div>
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-400">
                            {{ __('general.pages.stock-transfers.order_products') }}: {{ count($items ?? []) }}
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                            <thead class="bg-slate-50 dark:bg-slate-950/60">
                                <tr>
                                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.product') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.unit') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.qty') }}</th>
                                    <th class="px-4 py-3 text-end text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.unit_price') }}</th>
                                    <th class="px-4 py-3 text-end text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.selling_price') }}</th>
                                    <th class="px-4 py-3 text-end text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($items ?? [] as $index => $product)
                                    <tr class="align-top">
                                        <td class="px-4 py-4 font-semibold text-slate-900 dark:text-white">{{ $product['name'] }}</td>
                                        <td class="px-4 py-4">
                                            <select wire:model.change="items.{{ $index }}.unit_id" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                                <option value="">{{ __('general.pages.stock-transfers.select_unit') }}</option>
                                                @foreach ($product['units'] as $unit)
                                                    <option value="{{ $unit['id'] }}" @selected(($items[$index]['unit_id'] ?? null) == $unit['id'])>{{ $unit['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="number" step="any" min="1" max="{{ $product['max_stock'] ?? 0 }}" wire:model.blur="items.{{ $index }}.qty" placeholder="0.00" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                            <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ __('general.pages.pos-page.max') }}: {{ $product['max_stock'] }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-end text-slate-600 dark:text-slate-300">{{ currencyFormat($product['unit_cost'], true) }}</td>
                                        <td class="px-4 py-4 text-end text-slate-600 dark:text-slate-300">{{ currencyFormat($product['sell_price'], true) }}</td>
                                        <td class="px-4 py-4 text-end">
                                            <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-600 text-white transition hover:bg-rose-700" wire:click="delete({{ $index }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.search_product_placeholder') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-tenant-tailwind-gemini.table-card>

            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.stock-transfers.expenses')" :description="__('general.pages.stock-transfers.add_new_expense')">
                <div class="space-y-4 p-5">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                            <thead class="bg-slate-50 dark:bg-slate-950/60">
                                <tr>
                                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.purchases.expense_category') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.description') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.amount') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.expense_date') }}</th>
                                    <th class="px-4 py-3 text-end text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($data['expenses'] ?? [] as $index => $expense)
                                    <tr>
                                        <td class="px-4 py-4">
                                            <select id="expense_category_{{ $index }}" name="data.expenses.{{ $index }}.expense_category_id" class="select2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                                <option value="">{{ __('general.pages.reports.purchases.select_expense_category') }}</option>
                                                @foreach ($expenseCategories as $category)
                                                    <option value="{{ $category->id }}" @selected(($expense['expense_category_id'] ?? null) == $category->id)>{{ $category->{app()->getLocale() == 'ar' ? 'ar_name' : 'name'} ?? $category->name }}</option>
                                                    @foreach ($category->children as $child)
                                                        <option value="{{ $child->id }}" @selected(($expense['expense_category_id'] ?? null) == $child->id)>&nbsp;&nbsp;-- {{ $child->{app()->getLocale() == 'ar' ? 'ar_name' : 'name'} ?? $child->name }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="text" wire:model="data.expenses.{{ $index }}.description" placeholder="{{ __('general.pages.stock-transfers.description') }}" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="number" step="any" min="0" wire:model.blur="data.expenses.{{ $index }}.amount" placeholder="0.00" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="date" wire:model="data.expenses.{{ $index }}.expense_date" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                        </td>
                                        <td class="px-4 py-4 text-end">
                                            <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-600 text-white transition hover:bg-rose-700" wire:click="removeExpense({{ $index }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.add_new_expense') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="inline-flex items-center justify-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700" wire:click="addExpense">
                        <i class="fa fa-plus me-2"></i>{{ __('general.pages.stock-transfers.add_new_expense') }}
                    </button>
                </div>
            </x-tenant-tailwind-gemini.table-card>
        </div>

        <div class="space-y-6">
            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.stock-transfers.status')">
                <div class="space-y-5 p-5">
                    <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-brand-700 p-5 text-white">
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-white/60">{{ __('general.pages.stock-transfers.status') }}</div>
                        <div class="mt-3 text-2xl font-bold">{{ $selectedStatus?->label() ?? __('general.pages.stock-transfers.select_status') }}</div>
                        <div class="mt-2 text-sm text-white/70">{{ $data['transfer_date'] ?? now()->toDateString() }}</div>
                    </div>

                    <dl class="space-y-3 text-sm">
                        <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.ref_no') }}</dt>
                            <dd class="font-semibold text-slate-900 dark:text-white">{{ $data['ref_no'] ?? '...' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.order_products') }}</dt>
                            <dd class="font-semibold text-slate-900 dark:text-white">{{ count($items ?? []) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('general.pages.stock-transfers.expenses') }}</dt>
                            <dd class="font-semibold text-slate-900 dark:text-white">{{ currencyFormat($totalExpenses, true) }}</dd>
                        </div>
                    </dl>

                    <div class="grid gap-3">
                        <button type="button" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60" wire:click="save" @disabled(count($items ?? []) === 0)>
                            <i class="fa fa-save me-2"></i>{{ __('general.pages.stock-transfers.do_transfer') }}
                        </button>
                        <a href="{{ panelAwareUrl(route('admin.stocks.transfers.list')) }}" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                            <i class="fa fa-times me-2"></i>{{ __('general.pages.stock-transfers.cancel') }}
                        </a>
                    </div>
                </div>
            </x-tenant-tailwind-gemini.table-card>
        </div>
    </div>
</div>

@push('scripts')
@livewire('admin.branches.branch-modal')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
<script>
    window.addEventListener('reset-search-input', () => {
        const input = document.getElementById('product_search');
        if (input) input.value = '';
    });

    function productSearchEvent(event) {
        if (event.key === "Enter") {
            @this.set('product_search', event.target.value);
            clearTimeout(window.productSearchTimeout);
        } else {
            clearTimeout(window.productSearchTimeout);
            window.productSearchTimeout = setTimeout(() => {
                @this.set('product_search', event.target.value);
            }, 2000);
        }
    }
</script>
@endpush
