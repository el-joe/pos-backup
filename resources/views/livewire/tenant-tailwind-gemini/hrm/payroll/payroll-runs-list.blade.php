<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.hrm.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.hrm.status') }}
                </label>
                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        wire:model.blur="filters.status">
                    <option value="">{{ __('general.pages.hrm.all') }}</option>
                    @foreach(App\Enums\PayrollRunStatusEnum::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end justify-start md:justify-end mt-2">
                <button type="button"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 md:w-auto dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900"
                        wire:click="$set('filters', [])">
                    <i class="fa fa-undo"></i> {{ __('general.pages.hrm.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.titles.hrm_payroll_runs')"
        icon="fa fa-money"
    >
        <x-slot:actions>
            @adminCan('hrm_payroll.create')
                <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900"
                        data-bs-toggle="modal"
                        data-bs-target="#editHrmPayrollRunModal"
                        wire:click="$dispatch('hrm-payroll-run-set-current', null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.hrm.new') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.month') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.year') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.status') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.total_payout') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.hrm.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse($runs as $r)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $r->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $r->month }}</td>
                        <td class="px-5 py-4">{{ $r->year }}</td>
                        <td class="px-5 py-4">
                            @php
                                $statusVal = $r->status?->value ?? $r->status;
                                $statusColor = match($statusVal) {
                                    App\Enums\PayrollRunStatusEnum::DRAFT->value => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                    App\Enums\PayrollRunStatusEnum::APPROVED->value => 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-400',
                                    App\Enums\PayrollRunStatusEnum::PAID->value => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                    default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $statusColor }}">
                                {{ $r->status?->label() ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-900 dark:text-white">
                            {{ numFormat($r->total_payout) }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @adminCan('hrm_payroll.approve')
                                    @if(($r->status?->value ?? $r->status) === App\Enums\PayrollRunStatusEnum::DRAFT->value)
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 dark:text-emerald-400 dark:hover:bg-emerald-500/10 dark:focus:ring-emerald-400/50"
                                                wire:click="approveAlert({{ $r->id }})"
                                                title="{{ __('general.pages.hrm.approve_action') }}">
                                            <i class="fa fa-check text-lg"></i>
                                        </button>
                                    @endif
                                @endadminCan

                                @adminCan('hrm_payroll.pay')
                                    @if(($r->status?->value ?? $r->status) === App\Enums\PayrollRunStatusEnum::APPROVED->value)
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-brand-600 transition-colors hover:bg-brand-50 focus:outline-none focus:ring-2 focus:ring-brand-500/50 dark:text-brand-400 dark:hover:bg-brand-500/10 dark:focus:ring-brand-400/50"
                                                wire:click="payAlert({{ $r->id }})"
                                                title="{{ __('general.pages.hrm.mark_paid_action') }}">
                                            <i class="fa fa-money-bill text-lg"></i>
                                        </button>
                                    @endif
                                @endadminCan

                                @adminCan('hrm_payroll.update')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:text-blue-400 dark:hover:bg-blue-500/10 dark:focus:ring-blue-400/50"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editHrmPayrollRunModal"
                                            wire:click="$dispatch('hrm-payroll-run-set-current', { id: {{ $r->id }} })"
                                            title="{{ __('general.pages.hrm.edit') ?? 'Edit' }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                @endadminCan

                                @adminCan('hrm_payroll.delete')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500/50 dark:text-rose-400 dark:hover:bg-rose-500/10 dark:focus:ring-rose-400/50"
                                            wire:click="deleteAlert({{ $r->id }})"
                                            title="{{ __('general.pages.hrm.delete') ?? 'Delete' }}">
                                        <i class="fa fa-times text-lg"></i>
                                    </button>
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            {{ __('general.pages.hrm.no_data_found') ?? 'No payroll runs found.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($runs, 'hasPages') && $runs->hasPages())
            <x-slot:footer>
                {{ $runs->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
    @livewire('admin.hrm.payroll.payroll-run-modal')
@endpush
