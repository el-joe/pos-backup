<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.fixed_asset') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $asset->code }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.cost') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($asset->cost ?? 0, true) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.monthly_depreciation') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($asset->monthly_depreciation ?? 0, true) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.net_book_value') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($asset->net_book_value ?? 0, true) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.status') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">
                @if($asset->status === 'active')
                    {{ __('general.pages.fixed_assets.status_active') }}
                @elseif($asset->status === 'under_construction')
                    {{ __('general.pages.fixed_assets.status_under_construction') }}
                @elseif($asset->status === 'sold')
                    {{ __('general.pages.fixed_assets.status_sold') }}
                @else
                    {{ __('general.pages.fixed_assets.status_disposed') }}
                @endif
            </p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.fixed_assets.fixed_asset') . ': ' . $asset->code" icon="fa fa-building">
        <x-slot:actions>
            <a class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" href="{{ route('admin.depreciation-expenses.create', ['fixed_asset_id' => $asset->id]) }}">
                <i class="fa fa-plus"></i> {{ __('general.pages.fixed_assets.add_asset_entry') }}
            </a>
        </x-slot:actions>

        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.name') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $asset->name }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.branch') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $asset->branch?->name ?? '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.purchase_date') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $asset->purchase_date ? dateTimeFormat($asset->purchase_date, true, false) : '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.salvage_value') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ currencyFormat($asset->salvage_value ?? 0, true) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.useful_life_months') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $asset->useful_life_months ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.depreciation_rate') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $asset->depreciation_rate ? number_format((float)$asset->depreciation_rate, 4) . '%' : '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.depreciation_method') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">
                    @if($asset->depreciation_method === 'declining_balance')
                        {{ __('general.pages.fixed_assets.method_declining_balance') }}
                    @elseif($asset->depreciation_method === 'double_declining_balance')
                        {{ __('general.pages.fixed_assets.method_double_declining_balance') }}
                    @else
                        {{ __('general.pages.fixed_assets.method_straight_line') }}
                    @endif
                </p>
            </div>

            @if($asset->note)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 md:col-span-2 xl:col-span-3 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.note') }}</p>
                    <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-300">{{ $asset->note }}</p>
                </div>
            @endif
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.fixed_assets.depreciation_history')" icon="fa fa-line-chart">
        <x-slot:actions>
            <a class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700" href="{{ route('admin.depreciation-expenses.create', ['fixed_asset_id' => $asset->id]) }}">
                <i class="fa fa-plus"></i> {{ __('general.pages.fixed_assets.new_depreciation_expense') }}
            </a>
        </x-slot:actions>

        <div class="p-5">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.depreciation_expenses.category') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.amount') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.date') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.note') }}</th>
                            <th class="text-center">{{ __('general.pages.depreciation_expenses.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($depreciationExpenses as $expense)
                            <tr>
                                <td>{{ $expense->id }}</td>
                                <td>{{ $expense->category?->display_name ?? '—' }}</td>
                                <td>{{ currencyFormat($expense->amount ?? 0, true) }}</td>
                                <td>{{ $expense->expense_date }}</td>
                                <td>{{ $expense->note ?? '—' }}</td>
                                <td class="text-center">
                                    <a class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" href="{{ route('admin.depreciation-expenses.details', $expense->id) }}">
                                        {{ __('general.pages.depreciation_expenses.details') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.no_records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $depreciationExpenses->links('pagination::default5') }}
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.fixed_assets.payments_history')" icon="fa fa-credit-card">
        <x-slot:actions>
            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $asset->orderPayments->count() }} {{ __('general.pages.fixed_assets.payments_count') }}</span>
        </x-slot:actions>
        <div class="p-5">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('general.pages.fixed_assets.date') }}</th>
                            <th>{{ __('general.pages.fixed_assets.amount') }}</th>
                            <th>{{ __('general.pages.fixed_assets.method') }}</th>
                            <th>{{ __('general.pages.fixed_assets.note') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asset->orderPayments->sortByDesc('id') as $payment)
                            <tr>
                                <td>{{ dateTimeFormat($payment->created_at, true, false) }}</td>
                                <td>{{ currencyFormat($payment->amount ?? 0, true) }}</td>
                                <td>{{ $payment->account ? (($payment->account->paymentMethod?->name ? $payment->account->paymentMethod?->name.' - ' : '').$payment->account->name) : __('general.messages.n_a') }}</td>
                                <td>{{ $payment->note ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.no_payments') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.fixed_assets.lifespan_extensions')" icon="fa fa-calendar-plus-o">
        <div class="p-5">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.depreciation_expenses.amount') }}</th>
                            <th>{{ __('general.pages.fixed_assets.added_useful_life_months') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.date') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.note') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lifespanExtensions as $extension)
                            <tr>
                                <td>{{ $extension->id }}</td>
                                <td>{{ currencyFormat($extension->amount ?? 0, true) }}</td>
                                <td>{{ $extension->added_useful_life_months ?? 0 }}</td>
                                <td>{{ $extension->extension_date ? dateTimeFormat($extension->extension_date, true, false) : '—' }}</td>
                                <td>{{ $extension->note ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.no_extensions') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
