<div class="space-y-6">
    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.refunds.add_new_refund_order')" icon="fa-rotate-left">
        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div>
                    <label for="branch_id" class="form-label">{{ __('general.pages.refunds.branch') }}</label>
                    @if(admin()->branch_id == null)
                    <div class="flex gap-2">
                        <select id="branch_id" name="data.branch_id" class="form-select select2">
                            <option value="">{{ __('general.pages.refunds.select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $branch->id == ($data['branch_id']??0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <button class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id : null})">+</button>
                    </div>
                    @else
                    <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                    @endif
            </div>

            <div>
                    <label for="order_type" class="form-label">{{ __('general.pages.refunds.order_type') }}</label>
                    <div>
                        <select id="order_type" name="order_type" class="form-select select2">
                            <option value="">{{ __('general.pages.refunds.select_type') }}</option>
                            <option value="sale" {{ $order_type == 'sale' ? 'selected' :'' }}>Sales</option>
                            <option value="purchase" {{ $order_type == 'purchase' ? 'selected' :'' }}>Purchases</option>
                        </select>
                    </div>
            </div>

            <div>
                    <label for="order_id" class="form-label">{{ __('general.pages.refunds.orders') }}</label>
                    <div>
                        <select id="order_id" name="order_id" class="form-select select2">
                            <option value="">{{ __('general.pages.refunds.select_order') }}</option>
                            @foreach ($orders as $_order)
                                <option value="{{ $_order->id }}" {{ $_order->id == $order_id ? 'selected' : '' }}>#{{ $_order->id }}</option>
                            @endforeach
                        </select>
                    </div>
            </div>

            <div class="md:col-span-2 xl:col-span-3">
                    <label for="reason" class="form-label">{{ __('general.pages.refunds.reason') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-info-circle"></i></span>
                        <input type="text" id="reason" class="form-control" placeholder="{{ __('general.pages.refunds.reason') }}" wire:model="data.reason">
                    </div>
            </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.refunds.order_products')" :description="__('general.pages.sales.sale_products')" icon="fa-cubes">
        <div class="p-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                                <tr>
                                    <th>{{ __('general.pages.sales.product') }}</th>
                                    <th>{{ __('general.pages.sales.quantity') }}</th>
                                    <th>{{ __('general.pages.sales.refund_qty') }}</th>
                                </tr>
                    </thead>
                    <tbody>
                        @forelse($order?->{$order_type == 'sale' ? 'saleItems' : 'purchaseItems'} ?? [] as $item)
                            <tr>
                                <td class="font-semibold">{{ $item->product?->name }} - {{ $item->unit?->name }}</td>
                                <td>{{ $item->actual_qty }}</td>
                                <td>
                                    <input type="number" class="form-control" min="0" max="{{ $item->actual_qty }}" wire:model="refundItems.{{ $item->id }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.select_order') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <div class="flex justify-end pb-2">
        <button class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700" wire:click="saveRefund"><i class="fa fa-save me-2"></i> {{ __('general.pages.refunds.save_refund') }}</button>
    </div>
</div>

@push('scripts')
@livewire('admin.branches.branch-modal')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
