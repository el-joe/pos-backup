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
        <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.refunds.product') }}</th>
                            <th>{{ __('general.pages.refunds.unit') }}</th>
                            <th>{{ __('general.pages.refunds.qty') }}</th>
                            <th>{{ __('general.pages.refunds.refundable') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refund->items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->product?->name ?? '—' }}</td>
                                <td>{{ $item->unit?->name ?? '—' }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>
                                    @if($item->refundable)
                                        {{ class_basename($item->refundable_type) }} #{{ $item->refundable_id }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('general.pages.refunds.no_items') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.refunds.transactions')" icon="fa-receipt" :render-table="false">
        <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.refunds.date') }}</th>
                            <th>{{ __('general.pages.refunds.type') }}</th>
                            <th>{{ __('general.pages.refunds.description') }}</th>
                            <th>{{ __('general.pages.refunds.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->date ?? $transaction->created_at?->format('Y-m-d') }}</td>
                                <td>{{ $transaction->type?->label() ?? (string)$transaction->type }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>{{ currencyFormat($transaction->amount ?? 0, true) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-0">
                                    <div class="p-3 bg-body-tertiary">
                                        <div class="fw-semibold mb-2">{{ __('general.pages.refunds.transaction_lines') }}</div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0 align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('general.pages.refunds.line_type') }}</th>
                                                        <th>{{ __('general.pages.refunds.account') }}</th>
                                                        <th class="text-end">{{ __('general.pages.refunds.amount') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($transaction->lines as $line)
                                                        <tr>
                                                            <td>{{ ucfirst($line->type) }}</td>
                                                            <td>{{ $line->account?->name ?? '—' }}</td>
                                                            <td class="text-end">{{ currencyFormat($line->amount ?? 0, true) }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted py-2">—</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('general.pages.refunds.no_transactions') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
