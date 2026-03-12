<div class="space-y-6">
    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.refunds.refund_details') . ' #' . $refund->id" :description="$refund->created_at?->format('Y-m-d H:i')" icon="fa-rotate-left">
        <x-slot:actions>
            <a class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" href="{{ route('admin.refunds.list', ['order_type' => $refund->order_type === \App\Models\Tenant\Sale::class ? 'sale' : 'purchase']) }}">
                <i class="fa fa-arrow-left"></i> {{ __('general.pages.refunds.back') }}
            </a>
            @if($order)
                <a class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700" target="_blank"
                   href="{{ $refund->order_type === \App\Models\Tenant\Sale::class ? route('admin.sales.details', $refund->order_id) : route('admin.purchases.details', $refund->order_id) }}">
                    <i class="fa fa-external-link"></i> {{ __('general.pages.refunds.view_order') }}
                </a>
            @endif
        </x-slot:actions>

        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.order_type') }}</div>
                <div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ class_basename($refund->order_type) }}</div>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.order_id') }}</div>
                <div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">#{{ $refund->order_id }}</div>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.items_count') }}</div>
                <div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ $refund->items?->count() ?? 0 }}</div>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.total') }}</div>
                <div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ isset($refund->total) ? currencyFormat($refund->total, true) : '—' }}</div>
            </div>
            <div class="md:col-span-2 xl:col-span-4 rounded-3xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.reason') }}</div>
                <div class="mt-3 text-sm font-medium text-slate-700 dark:text-slate-200">{{ $refund->reason ?: '—' }}</div>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.refunds.refund_items')" icon="fa-cubes" :render-table="false">
        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3 font-semibold">#</th>
                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.refunds.product') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.refunds.unit') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.refunds.qty') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.refunds.refundable') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($refund->items as $item)
                        <tr>
                            <td class="px-4 py-4">{{ $item->id }}</td>
                            <td class="px-4 py-4">{{ $item->product?->name ?? '—' }}</td>
                            <td class="px-4 py-4">{{ $item->unit?->name ?? '—' }}</td>
                            <td class="px-4 py-4">{{ $item->qty }}</td>
                            <td class="px-4 py-4">
                                @if($item->refundable)
                                    {{ class_basename($item->refundable_type) }} #{{ $item->refundable_id }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.no_items') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.refunds.transactions')" icon="fa-receipt" :render-table="false">
        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800">
                <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3 font-semibold">#</th>
                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.refunds.date') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.refunds.type') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('general.pages.refunds.description') }}</th>
                        <th class="px-4 py-3 text-right font-semibold">{{ __('general.pages.refunds.amount') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="px-4 py-4">{{ $transaction->id }}</td>
                            <td class="px-4 py-4">{{ $transaction->date ?? $transaction->created_at?->format('Y-m-d') }}</td>
                            <td class="px-4 py-4">{{ $transaction->type?->label() ?? (string)$transaction->type }}</td>
                            <td class="px-4 py-4">{{ $transaction->description }}</td>
                            <td class="px-4 py-4 text-right">{{ currencyFormat($transaction->amount ?? 0, true) }}</td>
                        </tr>
                        <tr class="bg-slate-50/80 dark:bg-slate-950/50">
                            <td colspan="5" class="px-4 py-4">
                                <div class="mb-3 text-sm font-semibold text-slate-900 dark:text-white">{{ __('general.pages.refunds.transaction_lines') }}</div>
                                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700">
                                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-700">
                                        <thead class="bg-white text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-900 dark:text-slate-400">
                                            <tr>
                                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.refunds.line_type') }}</th>
                                                <th class="px-4 py-3 font-semibold">{{ __('general.pages.refunds.account') }}</th>
                                                <th class="px-4 py-3 text-right font-semibold">{{ __('general.pages.refunds.amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                            @forelse($transaction->lines as $line)
                                                <tr>
                                                    <td class="px-4 py-3">{{ ucfirst($line->type) }}</td>
                                                    <td class="px-4 py-3">{{ $line->account?->name ?? '—' }}</td>
                                                    <td class="px-4 py-3 text-right">{{ currencyFormat($line->amount ?? 0, true) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">—</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.no_transactions') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
