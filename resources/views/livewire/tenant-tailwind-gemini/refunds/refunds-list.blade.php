<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-3">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-brand-500/15 to-brand-500/5 p-5">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.titles.refunds') }}</div>
                <div class="mt-3 text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $refunds->total() }}</div>
            </div>
        </div>
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-sky-500/15 to-sky-500/5 p-5">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.created_at') }}</div>
                <div class="mt-3 text-lg font-bold tracking-tight text-slate-900 dark:text-white">{{ optional($refunds->first()?->created_at)->format('Y-m-d H:i') ?? '—' }}</div>
            </div>
        </div>
        <div class="overflow-hidden rounded-3xl border border-emerald-200 bg-white shadow-sm dark:border-emerald-500/20 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-emerald-500/15 to-emerald-500/5 p-5">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.items_count') }}</div>
                <div class="mt-3 text-3xl font-black tracking-tight text-emerald-700 dark:text-emerald-300">{{ collect($refunds->items())->sum(fn ($refund) => $refund->items?->count() ?? 0) }}</div>
            </div>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.refunds')" icon="fa-undo">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                <a class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" href="{{ route('admin.refunds.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.refunds.new_refund') }}
                </a>
                <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900" wire:click="$set('export','excel')">
                    <i class="fa fa-file-excel"></i> {{ __('general.pages.refunds.export') }}
                </button>
            </div>
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">#</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.refunds.order_type') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.refunds.order_id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.refunds.items_count') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.refunds.reason') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.refunds.created_at') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.refunds.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($refunds as $refund)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $refund->id }}</td>
                        <td class="px-5 py-4">{{ class_basename($refund->order_type) }}</td>
                        <td class="px-5 py-4">
                            @php($isSale = $refund->order_type === \App\Models\Tenant\Sale::class || class_basename($refund->order_type) === 'Sale')
                            <a target="_blank" class="font-medium text-brand-600 hover:text-brand-700 dark:text-brand-300 dark:hover:text-brand-200" href="{{ $isSale ? route('admin.sales.details',$refund->order_id) : route('admin.purchases.details',$refund->order_id) }}">#{{ $refund->order_id }}</a>
                        </td>
                        <td class="px-5 py-4">{{ $refund->items?->count() ?? 0 }}</td>
                        <td class="px-5 py-4">{{ $refund->reason }}</td>
                        <td class="px-5 py-4">{{ $refund->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="px-5 py-4 text-right">
                            <a class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10" href="{{ route('admin.refunds.details', $refund->id) }}" title="{{ __('general.pages.refunds.details') }}">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.no_refunds') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            {{ $refunds->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>
