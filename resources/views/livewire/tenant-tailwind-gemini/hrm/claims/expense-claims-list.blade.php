<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.hrm.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.hrm.status') }}
                </label>
                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        wire:model.blur="filters.status">
                    <option value="all">{{ __('general.pages.hrm.all') }}</option>
                    @foreach ([
                        App\Enums\ExpenseClaimStatusEnum::PENDING,
                        App\Enums\ExpenseClaimStatusEnum::SUBMITTED,
                        App\Enums\ExpenseClaimStatusEnum::APPROVED,
                        App\Enums\ExpenseClaimStatusEnum::REJECTED,
                        App\Enums\ExpenseClaimStatusEnum::PAID
                    ] as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.hrm.employee') }}
                </label>
                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        wire:model.blur="filters.employee_id">
                    <option value="">{{ __('general.pages.hrm.all') }}</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-1 lg:justify-end mt-2">
                <button type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.hrm.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.titles.hrm_expense_claims')"
        icon="fa fa-receipt"
    >
        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.employee') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.date') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.total') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.status') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.hrm.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse($claims as $c)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $c->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">
                            {{ $c->employee?->name ?? $c->employee_id }}
                        </td>
                        <td class="px-5 py-4">{{ optional($c->claim_date)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-5 py-4 font-semibold text-slate-900 dark:text-white">
                            {{ numFormat($c->total_amount) }}
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $statusVal = $c->status?->value ?? $c->status;
                                $statusColor = match($statusVal) {
                                    App\Enums\ExpenseClaimStatusEnum::APPROVED->value => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                    App\Enums\ExpenseClaimStatusEnum::REJECTED->value => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
                                    App\Enums\ExpenseClaimStatusEnum::PENDING->value => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                    App\Enums\ExpenseClaimStatusEnum::SUBMITTED->value => 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-400',
                                    App\Enums\ExpenseClaimStatusEnum::PAID->value => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400',
                                    default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $statusColor }}">
                                {{ $c->status?->label() ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(in_array(($c->status?->value ?? $c->status), [App\Enums\ExpenseClaimStatusEnum::PENDING->value, App\Enums\ExpenseClaimStatusEnum::SUBMITTED->value], true))
                                    @adminCan('hrm_claims.approve')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 dark:text-emerald-400 dark:hover:bg-emerald-500/10 dark:focus:ring-emerald-400/50"
                                                wire:click="approveAlert({{ $c->id }})"
                                                title="{{ __('general.pages.hrm.approve') ?? 'Approve' }}">
                                            <i class="fa fa-check text-lg"></i>
                                        </button>
                                    @endadminCan

                                    @adminCan('hrm_claims.reject')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500/50 dark:text-rose-400 dark:hover:bg-rose-500/10 dark:focus:ring-rose-400/50"
                                                wire:click="rejectAlert({{ $c->id }})"
                                                title="{{ __('general.pages.hrm.reject') ?? 'Reject' }}">
                                            <i class="fa fa-times text-lg"></i>
                                        </button>
                                    @endadminCan
                                @else
                                    <span class="inline-flex h-8 items-center justify-center px-2 text-slate-400 dark:text-slate-500">
                                        -
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            {{ __('general.pages.hrm.no_data_found') ?? 'No expense claims found.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($claims, 'hasPages') && $claims->hasPages())
            <x-slot:footer>
                {{ $claims->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>
