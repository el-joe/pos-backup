<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.checks.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.checks.direction') }}
                </label>
                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model.live="filters.direction">
                    <option value="">{{ __('general.pages.checks.all') }}</option>
                    <option value="received">{{ __('general.pages.checks.received') }}</option>
                    <option value="issued">{{ __('general.pages.checks.issued') }}</option>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.checks.status') }}
                </label>
                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model.live="filters.status">
                    <option value="">{{ __('general.pages.checks.all') }}</option>
                    <option value="under_collection">{{ __('general.pages.checks.under_collection') }}</option>
                    <option value="collected">{{ __('general.pages.checks.collected') }}</option>
                    <option value="bounced">{{ __('general.pages.checks.bounced') }}</option>
                    <option value="issued">{{ __('general.pages.checks.issued') }}</option>
                    <option value="cleared">{{ __('general.pages.checks.cleared') }}</option>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.checks.branch') }}
                </label>
                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model.live="filters.branch_id">
                    <option value="">{{ __('general.pages.checks.all') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.checks.search') }}
                </label>
                <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" wire:model.live="filters.search" placeholder="{{ __('general.pages.checks.search_placeholder') }}">
            </div>

            <div class="col-span-1 flex items-end justify-end md:col-span-4 mt-2">
                <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" wire:click="resetFilters">
                    <i class="fa fa-undo"></i>
                    {{ __('general.pages.checks.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.pages.checks.checks')"
        icon="fa fa-money-check-alt"
    >
        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">#</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.checks.direction') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.checks.status') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.checks.check_number') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.checks.bank') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.checks.due_date') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.checks.amount') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.checks.reference') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.checks.customer_supplier') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.checks.branch') }}</th>
                    <th class="px-5 py-3 font-semibold w-56">{{ __('general.pages.checks.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse($checks as $check)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $check->id }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $check->direction === 'received' ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400' : 'bg-orange-50 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400' }}">
                                {{ __('general.pages.checks.'.$check->direction) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">{{ __('general.pages.checks.'.$check->status) }}</td>
                        <td class="px-5 py-4 text-slate-900 dark:text-white font-medium">{{ $check->check_number ?? '-' }}</td>
                        <td class="px-5 py-4">{{ $check->bank_name ?? '-' }}</td>
                        <td class="px-5 py-4">{{ $check->due_date?->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-5 py-4 font-semibold text-slate-900 dark:text-white">{{ currencyFormat($check->amount, true) }}</td>
                        <td class="px-5 py-4">
                            @php($payable = $check->payable)
                            @if($check->payable_type === \App\Models\Tenant\FixedAsset::class)
                                {{ __('general.pages.fixed_assets.fixed_asset') }}: {{ $payable?->code ?? ('#'.$check->payable_id) }}
                            @elseif($check->payable_type === \App\Models\Tenant\Sale::class)
                                {{ __('general.pages.sales.sale') }}: {{ $payable?->invoice_number ?? ('#'.$check->payable_id) }}
                            @elseif($check->payable_type === \App\Models\Tenant\Purchase::class)
                                {{ __('general.pages.purchases.purchase') }}: {{ $payable?->ref_no ?? ('#'.$check->payable_id) }}
                            @else
                                {{ class_basename((string)$check->payable_type) }} #{{ $check->payable_id }}
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($check->direction === 'received')
                                {{ $check->customer?->name ?? '-' }}
                            @elseif($check->direction === 'issued' && $check->supplier)
                                {{ $check->supplier?->name ?? '-' }}
                            @elseif($check->payable_type === \App\Models\Tenant\FixedAsset::class)
                                {{ $check->payable?->name ?? '-' }}
                            @else
                                {{ $check->supplier?->name ?? '-' }}
                            @endif
                        </td>
                        <td class="px-5 py-4">{{ $check->branch?->name ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                @if($check->direction === 'received' && $check->status === 'under_collection')
                                    <button class="rounded-lg bg-emerald-500 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" wire:click="collect({{ $check->id }})">
                                        {{ __('general.pages.checks.collect') }}
                                    </button>
                                    <button class="rounded-lg bg-rose-500 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" wire:click="bounce({{ $check->id }})">
                                        {{ __('general.pages.checks.bounce') }}
                                    </button>
                                @elseif($check->direction === 'issued' && $check->status === 'issued')
                                    <button class="rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" wire:click="clearIssued({{ $check->id }})">
                                        {{ __('general.pages.checks.clear') }}
                                    </button>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            {{ __('general.pages.checks.no_checks_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($checks->hasPages())
            <x-slot:footer>
                {{ $checks->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>
