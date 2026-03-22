<div class="space-y-6">
    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.refunds.add_new_refund_order')" icon="fa-rotate-left" :render-table="false">
        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div class="space-y-2">
                <label for="branch_id" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.branch') }}</label>
                @if(admin()->branch_id == null)
                    <div class="flex gap-2">
                        <select id="branch_id" name="data.branch_id" class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            <option value="">{{ __('general.pages.refunds.select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected($branch->id == ($data['branch_id'] ?? 0))>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id : null})">+</button>
                    </div>
                @else
                    <input type="text" class="block w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" value="{{ admin()->branch?->name }}" disabled>
                @endif
            </div>

            <div class="space-y-2">
                <label for="order_type" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.order_type') }}</label>
                <select id="order_type" name="order_type" class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <option value="">{{ __('general.pages.refunds.select_type') }}</option>
                    <option value="sale" @selected($order_type == 'sale')>Sales</option>
                    <option value="purchase" @selected($order_type == 'purchase')>Purchases</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="order_id" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.orders') }}</label>
                <select id="order_id" name="order_id" class="select2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                    <option value="">{{ __('general.pages.refunds.select_order') }}</option>
                    @foreach ($orders as $_order)
                        <option value="{{ $_order->id }}" @selected($_order->id == $order_id)>#{{ $_order->id }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2 md:col-span-2 xl:col-span-3">
                <label for="reason" class="block text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.reason') }}</label>
                <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <span class="flex items-center border-r border-slate-200 px-3 text-slate-500 dark:border-slate-700 dark:text-slate-400"><i class="fa fa-info-circle"></i></span>
                    <input type="text" id="reason" class="min-w-0 flex-1 bg-transparent px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none dark:text-white dark:placeholder:text-slate-500" placeholder="{{ __('general.pages.refunds.reason') }}" wire:model="data.reason">
                </div>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.refunds.order_products')" :description="__('general.pages.sales.sale_products')" icon="fa-cubes" :render-table="false">
        <div class="p-5">
            <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                        <tr>
                            <th class="px-4 py-3 font-semibold">{{ __('general.pages.sales.product') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('general.pages.sales.quantity') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('general.pages.sales.refund_qty') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($order?->{$order_type == 'sale' ? 'saleItems' : 'purchaseItems'} ?? [] as $item)
                            <tr>
                                <td class="px-4 py-4 font-semibold text-slate-900 dark:text-white">{{ $item->product?->name }} - {{ $item->unit?->name }}</td>
                                <td class="px-4 py-4">{{ $item->actual_qty }}</td>
                                <td class="px-4 py-4">
                                    <input type="number" class="block w-28 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" min="0" max="{{ $item->actual_qty }}" wire:model="refundItems.{{ $item->id }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.select_order') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <x-slot:footer>
            <div class="flex justify-end">
                <button class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700" wire:click="saveRefund"><i class="fa fa-save"></i> {{ __('general.pages.refunds.save_refund') }}</button>
            </div>
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
@livewire('admin.branches.branch-modal')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
