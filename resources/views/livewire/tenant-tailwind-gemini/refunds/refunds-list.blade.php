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
            <a class="btn btn-primary" href="{{ route('admin.refunds.create') }}">
                <i class="fa fa-plus"></i> {{ __('general.pages.refunds.new_refund') }}
            </a>
            <button type="button" class="btn btn-outline-success" wire:click="$set('export','excel')">
                <i class="fa fa-file-excel"></i> {{ __('general.pages.refunds.export') }}
            </button>
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>#</th>
                <th>{{ __('general.pages.refunds.order_type') }}</th>
                <th>{{ __('general.pages.refunds.order_id') }}</th>
                <th>{{ __('general.pages.refunds.items_count') }}</th>
                <th>{{ __('general.pages.refunds.reason') }}</th>
                <th>{{ __('general.pages.refunds.created_at') }}</th>
                <th>{{ __('general.pages.refunds.actions') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($refunds as $refund)
            <tr>
                <td>{{ $refund->id }}</td>
                <td>{{ class_basename($refund->order_type) }}</td>
                <td>
                    @php($isSale = $refund->order_type === \App\Models\Tenant\Sale::class || class_basename($refund->order_type) === 'Sale')
                    <a target="_blank" href="{{ $isSale ? route('admin.sales.details',$refund->order_id) : route('admin.purchases.details',$refund->order_id) }}">#{{ $refund->order_id }}</a>
                </td>
                <td>{{ $refund->items?->count() ?? 0 }}</td>
                <td>{{ $refund->reason }}</td>
                <td>{{ $refund->created_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.refunds.details', $refund->id) }}">
                        <i class="fa fa-eye"></i> {{ __('general.pages.refunds.details') }}
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.refunds.no_refunds') }}</td>
            </tr>
        @endforelse

        <x-slot:footer>
            {{ $refunds->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>
